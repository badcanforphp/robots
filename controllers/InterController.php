<?php
/**
 * Created by PhpStorm.
 * User: zjj
 * Date: 2018/3/28
 * Time: 18:24
 */
namespace app\controllers;

use app\models\Test;
use app\models\User;
use Yii;
use app\models\Version;

class InterController extends BaseController
{
    //检测是否需要更新
    public function actionCheckVer()
    {
        //$post = ['sign'=>'5cd7c5cab5672a136885bb47688f18ee','version'=>'1.1.0','wxid'=>'wxid_hmsog98q6fth22','sendtime'=>'1527775308'];
        //$params = ['sign'=>'b77192590a8bc212398776c105119e09','version'=>'1.0.0','token'=>'hgf8MU16sq3mLMYgWpQHK+HiYkA94kPv','sendtime'=>'1528123622'];
        //var_dump($this->__checkSign($params));die;
        //echo 123;die;
        $ver = Version::find()->asArray()->one();
        $request = Yii::$app->request;
        if($request->isPost){//isset($post)
//            $ver = $request->post('version');
            $post = $request->post();
            $check = $this->__checkSign($post);
            if($check){
                $model = new Test();
                $model->data = serialize($post);
                $model->save();
                User::checkUser($post);
                if($ver['vid'] != $post['version']){
                    echo json_encode(['code' => 6001]);
                }else{
                    echo json_encode(['code' => 6100]);
                }
            }
        }else{
            echo json_encode(['code' => 6010]);
        }
    }
}