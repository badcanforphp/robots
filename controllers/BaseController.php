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
    private $_salt = '$%fan.xin@&hm';
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

    //检验sgin
    public function __checkSign($params)
    {//return true;
        //$params = ['sign'=>'b77192590a8bc212398776c105119e09','version'=>'1.0.0','token'=>'hgf8MU16sq3mLMYgWpQHK+HiYkA94kPv','sendtime'=>'1528123622'];//,'chat'=>'涂','type'=>'33'
        $sign = isset($params['sign']) ? trim($params['sign']) : '';
        if (!$sign) {
            //'调用API的sign参数不能为空';
            echo json_encode(['code' => 4001]);
            return false;
        }
        unset($params['sign']);
        ksort($params);
        $raw_sign = '';
        foreach ($params as $key => $val) {
            if ($val) {
                $raw_sign .= trim($key) . trim($val);
            }
        }

        $raw_sign .= $this->_salt;
        if (md5($raw_sign) != $sign) {
            //'调用API的sign参数错误';
            echo json_encode(['code' => 4002]);
            return false;
        }

        return true;
    }
}