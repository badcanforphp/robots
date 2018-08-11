<?php
/**
 * Created by PhpStorm.
 * User: zjj
 * Date: 2018/8/11
 * Time: 10:44
 */
namespace app\controllers;


class WeiController extends BaseController
{
    public function actionIndex()
    {
        $request = \Yii::$app->request;

        if($request->isGet){
            $sign = $request->getQueryParam('signature');
            $time = $request->getQueryParam('timestamp');
            $nonce = $request->getQueryParam('nonce');
            $echostr = $request->getQueryParam('echostr');
        }else{
            $sign = $request->post('signature');
            $time = $request->post('timestamp');
            $nonce = $request->post('nonce');
            $echostr = $request->post('echostr');
        }


        if($this->checkSignature($sign,$time,$nonce)){
            echo $echostr;die;
        }else{
            echo false;
        }
        return $this->render('index');
    }

    private function checkSignature($sign,$time,$nonce)
    {
        $token = "zjjtest1";//微信公众平台里面填写的token
        $tmpArr = [$token,$time, $nonce];
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        if($tmpStr == $sign){
            return true;
        }else{
            return false;
        }
    }
}