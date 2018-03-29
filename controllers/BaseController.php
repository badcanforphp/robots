<?php
/**
 * Created by PhpStorm.
 * User: zjj
 * Date: 2018/3/29
 * Time: 9:43
 */
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;

class BaseController extends Controller
{
    public function init()
    {
        parent::init();
    }

    public function afterAction($action, $result)
    {
        $request = Yii::$app->request;
        $response = Yii::$app->response;
        $return = parent::afterAction($action, $result);
        if ($callback = $request->get('callback')) {
            $return['data'] = $result;
            $return['callback'] = $callback;
            $response->format = Response::FORMAT_JSONP;
        } else if($request->isAjax){
            $response->format = Response::FORMAT_JSON;
        }
        return $return;
    }

    public function post($url,$data){

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_URL, $url);
        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }
}