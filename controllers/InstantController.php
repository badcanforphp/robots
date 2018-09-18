<?php
/**
 * Created by PhpStorm.
 * User: zjj
 * Date: 2018/8/3
 * Time: 16:22
 */
namespace app\controllers;


class InstantController extends BaseController
{
    public function actionIndex()
    {

        return $this->render('index');
    }
}