<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/6/8
 * Time: 17:53
 */
namespace app\commands;

use app\models\User;
use yii\console\Controller;

class CronController extends Controller
{

    //ÿ���������û�ʹ�ô���
    public function actionClearTime()
    {
        $model = User::updateAll(['time'=>0]);
    }
}


