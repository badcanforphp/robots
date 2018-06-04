<?php
/**
 * Created by PhpStorm.
 * User: zjj
 * Date: 2018/3/28
 * Time: 18:24
 */
namespace app\controllers;

use Yii;

class DefaultController extends BaseController
{
    private $apiKey;
    private $secret;
    private $userId = 1;
    private $selfInfo = '';

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

    private $type = ['text','url','image'];

    private $timestamp;

    public function actionIndex()
    {
        if(isset($_REQUEST)){
            $check = $this->__checkSign($_REQUEST);
        }else{
            echo json_encode(4000);
            exit();
        }
        if(isset($_REQUEST['chat'])){
            $text = $_REQUEST['chat'];
        }else{
            $text = '';
        }
        $data = $_REQUEST;

        if(isset($data['type'])){
            switch($data['type']){
                case 33://聊天
                    $type_arr = [
                        '20624b9154d24758acc1810281a7a45a',
                        '37139791e5654555aca11ec4e474c448',
                        '3a6e70130d8e41f29d44994c173c7d2c',
                        '39ad0c1e2a1e4dabb026ec8c81566b15'
                    ];
                    break;
                case 44://情感
                    $type_arr = [
                        '0a0eada1e1a248f5aea569ead03209ce',
                        '9eeb188d09c54bd698185844e7cf0257',
                        'c469a6a7546d4bbb90d3e908f1a1165c'
                    ];
                    break;
                case 55://客服
                    $type_arr = [
                        '636e3b13f9314bde83f1a84c5cfaa330',
                        '2bbf5933692048029045f47ea26cc7c8',
                        '56d8d939f5274baf897ec3297a6ad453'
                    ];
                    break;
            }
        }else{
            echo json_encode('');
            exit();
        }
        for($i=0;$i<count($type_arr);$i++){
            $result = $this->robot($text,$data,$type_arr[$i]);
            if(!isset($result[0]['results'])){
                if($this->error[$result[0]['intent']['code']] != '该apikey没有可用请求次数'){
                    break;
                }
            }
        }

        $ar = [];

        $num = [];
        if(isset($result[0]['results'])){
            foreach($result[0]['results'] as $key=>$val){
                //$ar['type'][$key] = $val['resultType'];
                if(count($result[0]['results']) == 1 && in_array($val['resultType'],$this->type)){
                    $ar['type'] = $val['resultType'];
                }else if(count($result[0]['results']) != 1){
                    if($val['resultType'] != 'text'){
                        $ar['type'] = $val['resultType'];
                    }
                }else{
                    echo json_encode(123);
                    exit();
                }
                foreach($val['values'] as $as=>$qs){
                    if(in_array($as,$this->type)){
                        $num[] = $qs;
                    }
                }
                $ar['message'] = implode("---",$num);
                //."\n" .
            }
            echo json_encode($ar);
            exit();
        }else{
            if($result[0]['intent']['code'] == 4602){
                echo json_encode('');
                exit();
            }else{
                echo json_encode($this->error[$result[0]['intent']['code']]);
                exit();
            }
        }
    }

    private function robot($text,$data,$key)
    {
        $selfInfo = [
            'location' => [
                'city' => '长沙'
            ]
        ];

        $this->secret = '';

        $this->userId = md5(1);

        $this->selfInfo = $selfInfo;
        $this->timestamp = time();

        $iv = '';
        $iv = str_repeat(chr(0),16);

        $this->apiKey = $key;
        $aesKey = md5($this->secret.$this->timestamp.$this->apiKey);

        $param = [
            'perception' => [
                'inputText' => [
                    'text' => $text,
                ],
                'selfInfo' => $this->selfInfo
            ],
            'userInfo' => [
                'apiKey' => $this->apiKey,
                'userId' => $this->userId,
            ]
        ];

        $iv = '';
        $iv = str_repeat(chr(0),16);

        $aesKey = md5($this->secret.$this->timestamp.$this->apiKey);

        $cipher = base64_encode(openssl_encrypt(json_encode($param), 'aes-128-cbc', hash('MD5', $aesKey, true), OPENSSL_RAW_DATA, $iv));

        $postData = [
            'key' => $this->apiKey,
            'timestamp' => $this->timestamp,
//            'data' => $param
            'data' => $cipher
        ];
//        var_dump($postData);die;
        $result = json_decode('['.$this->post('http://openapi.tuling123.com/openapi/api/v2',json_encode($param)).']',true);
        return $result;
    }
}