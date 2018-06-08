<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/6/8
 * Time: 17:53
 */

class CronController extends \yii\console\Controller
{

    //每日零点清除用户使用次数
    public function actionClearTime()
    {
        $model = \app\models\User::updateAll(['time'=>0]);
    }
}


