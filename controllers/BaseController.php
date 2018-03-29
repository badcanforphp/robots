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
    public $apiKey;
    public $secret;
    public $userId = 1;
    public $selfInfo = '';

    private $error = [
        5000 => '无解析结果',
        6000 => '暂不支持该功能',
        4000 => '请求参数格式错误',
        4001 => '加密方式错误',
        4002 => '无功能权限',
        4003 => '该apikey没有可用请求次数',
        4005 => '无功能权限',
        4007 => 'apikey不合法',
        4100 => 'userid获取失败',
        4200 => '上传格式错误',
        4300 => '批量操作超过限制',
        4400 => '没有上传合法userid',
        4500 => 'userid申请个数超过限制',
        4600 => '输入内容为空',
        4602 => '输入文本内容超长(上限150)',
        7002 => '上传信息失败',
        8008 => '服务器错误'];

    public $type = ['text','url','image'];

    public $timestamp;

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