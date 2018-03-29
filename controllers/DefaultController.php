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
    /*private  $apiKey = [
        'f186b8ff46534282975c3225acb17f14',
        '275723f579e1451b959052655dd8dcd7',
        'eecd4c3d85384f6a9b2ae899ff78f844',
        '837542d3fd134238aad278dc4d267a18',
        '6b0fb4565d854def8d5509006fa217f5',
        'a05c3a073f784c4e8e66617a0b6c2d19',
        'b1a69315979c45e49598cccb57909010',
        '66f944e67e0d427b95736241a98389ac',
        'e76ca2e836234915a19133366defd425',
        'df707c0e4fd446a28db1e0768abc80b6'
    ];*/
    public function actionIndex()
    {
        if(isset($_REQUEST['chat'])){
            $text = $_REQUEST['chat'];
        }else{
            $text = '';
        }

        $selfInfo = [
            'location' => [
                'city' => '长沙'
            ]
        ];

        $this->apiKey = 'eecd4c3d85384f6a9b2ae899ff78f844';
        $this->secret = '';

        $this->userId = md5(1);

        $this->selfInfo = $selfInfo;
        $this->timestamp = time();

        $iv = '';
        $iv = str_repeat(chr(0),16);

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

        $cipher = base64_encode(openssl_encrypt(json_encode($param), 'aes-128-cbc', hash('MD5', $aesKey, true), OPENSSL_RAW_DATA, $iv));

        $postData = [
            'key' => $this->apiKey,
            'timestamp' => $this->timestamp,
//            'data' => $param
            'data' => $cipher
        ];
//        var_dump($postData);die;
        $result = json_decode('['.$this->post('http://openapi.tuling123.com/openapi/api/v2',json_encode($param)).']',true);
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
}