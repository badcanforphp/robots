<?php
/**
 * Created by PhpStorm.
 * User: zjj
 * Date: 2018/3/28
 * Time: 18:24
 */
namespace app\controllers;

use app\models\Test;
use Yii;
use app\models\Version;

class InterController extends BaseController
{
    //检测是否需要更新
    public function actionCheckVer()
    {
        //var_dump(Version::find()->asArray()->one());die;
        $request = Yii::$app->request;
        if($request->isPost){
            $ver = $request->post('version');
            $post = $request->post();
            $model = new Test();
            $model->data = serialize($post);
            $model->save();
            if(!$ver){
                echo json_encode([200 => '无需更新']);
            }else{
                echo json_encode([200 => '请更新最新版本']);
            }
        }else{
            echo json_encode([502 => '传值方式错误，请重试']);
        }
    }
}