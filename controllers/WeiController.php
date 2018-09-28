<?php
/**
 * Created by PhpStorm.
 * User: zjj
 * Date: 2018/8/11
 * Time: 10:44
 */
namespace app\controllers;


use app\models\WechatUser;

class WeiController extends BaseController
{
    public $enableCsrfValidation = false;

    const tid = 'g4IuqwOutyYesShfTreOXm5j4YXrSewTd_9mUoLbO50';
    const url = 'https://baidu.com';
    const appid = 'wxd902eece8491d8a8';
    const appsc = '9a173c60cbce672602ab1af5a5a8933a';
    const button = '{
                 "button":[
                 {
                       "name":"云医链",
                       "sub_button":[
                        {
                           "type":"click",
                           "name":"公司简介",
                           "key":"jianjie"
                        },
                        {
                           "type":"view",
                           "name":"商城",
                           "url":"https://o2o.zhcxkeji.com/Wechat/#/"
                        },
                        {
                           "type":"click",
                           "name":"成为合伙人",
                           "key":"hezuo"
                        },
                        {
                           "type":"click",
                           "name":"联系我们",
                           "key":"lianxi"
                        }]
                  },
                  {
                       "type":"view",
                       "name":"医生端",
                       "url":"http://dxc.yunyilian.com.cn/my/passport/login-p"
                   },
                   {
                       "type":"click",
                       "name":"用户端",
                       "key":"cus"
                   }]
                }';

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

        $postStr = $GLOBALS['HTTP_RAW_POST_DATA'];//$request->post();

        if (!empty($postStr)) {
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = trim($postObj->MsgType);//用户发送的消息类型判断
            $EVENT = $postObj->Event;//获取事件类型
            //$keyword = trim($postObj->Content);//获取内容
            /*$fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $time = time();

            $textTpl = "<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[%s]]></MsgType>
                <Content><![CDATA[%s]]></Content>
                </xml>";*/
            //标准xml

            if($RX_TYPE=='event'){
                if($EVENT=="subscribe"){//首次关注
                   /* $msgType = "text";
                    $content = "欢迎您关注"."<a href='http://www.baidu.com'>云医链共享服务平台</a>"."，更多功能正在开发中！";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $content);*/
                    $result  = $this->receiveFirst($postObj);
                    return  $result;
                }elseif ($EVENT=='CLICK'){  //点击菜单
                    //点击事件
                    $EventKey = $postObj->EventKey;//菜单的自定义的key值，可以根据此值判断用户点击了什么内容，从而推送不同信息
                    switch($EventKey){
                        case 'jianjie':
                            $content = '湖南云医链网络科技有限公司是一家以人工智能、大数据、云计算、物联网、区块链技术应用、研发、投资为一体的大型综合性科技企业，公司总部坐落于湖南省长沙市天心区广告产业园。

    “云医链”是公司重点打造的全国首家以区块链技术作为底层架构，综合运用云计算、人工智能、大数据等多项前沿技术作为支撑，用去中心化、大数据思维重塑大健康管理的共享服务平台。通过云计算建立全球数据资源釆集、汇聚、共享和应用机制，为大健康产业的发展提供完全去中心化的智能合约、溯源等增值服务，实现全民数据共享、资源共享。';
                            break;
                        case 'lianxi':
                            $content = '请拨打联系电话：0731--85519535，或直接前往湖南省长沙市天心区创谷产业园A2十层面议';
                            break;
                        case 'hezuo':
                            $content = '请拨打联系电话：0731--85519535，或直接前往湖南省长沙市天心区创谷产业园A2十层面议';
                            break;
                        case 'cus':
                            $content = "请用户大人"."<a href='http://u.yunyilian.com.cn/my/passport/set-info'>"."完善个人资料/进入个人中心"."</a>"."";
                            break;
                        default:
                            $content = '欢迎使用云医链！';
                            break;
                    }
                    $result = $this->transmitText($postObj, $content);
                    return $result;
                }
            }

            switch ($RX_TYPE)
            {
                case "text":    //文本消息
                    $result = $this->receiveText($postObj);
                    break;
                case "image":   //图片消息
                    $result = $this->receiveImage($postObj);
                    break;
                case "voice":   //语音消息
                    $result = $this->receiveVoice($postObj);
                    break;
                case "video":   //视频消息
                    $result = $this->receiveVideo($postObj);
                    break;
                case "location"://位置消息
                    $result = $this->receiveLocation($postObj);
                    break;
                case "link":    //链接消息
                    $result = $this->receiveLink($postObj);
                    break;
                default:
                    $result = "unknow msg type: ".$RX_TYPE;
                    break;
            }
            return $result;
        }


        if($this->checkSignature($sign,$time,$nonce)){
            return $echostr;
        }else{
            return false;
        }
        //return $this->render('index');
    }

    private function checkSignature($sign,$time,$nonce)
    {
        $token = \Yii::$app->params['weChat']['token'];//微信公众平台里面填写的token
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

    /**
     * 设定自定义菜单
     */
    public function actionMenu()
    {
        //获取access_token
        $json_token = $this->curlPost("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".self::appid."&secret=".self::appsc);

        $result = json_decode($json_token,true);

        $ACC_TOKEN = $result['access_token'];
        //$ACC_TOKEN = '14_C2ECcqewtzClxQp4bisSquOu7RLnIlXhir0wKZv5Z9jwd1b9CU_R-ZgqPEp9Aiduf3pzwMM6UpR2Y8eFGPyvlKPg3A-9bTquXFxT1gSxV14M1htXyMzJg__cPM45tbNNm3EsiOpTK21nUiQKACRfAFAMOD';
        $MENU_URL = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$ACC_TOKEN;
        $info = $this->curlPost($MENU_URL,self::button);
        var_dump($info);
    }

    /**
     * 发送模板消息
     */
    public function send_notice(){
        //获取access_token
        $json_token=$this->curlPost("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".self::appid."&secret=".self::appsc);
        //var_dump($json_token);die;
        if ($_COOKIE['access_token']){
            $access_token2=$_COOKIE['access_token'];
        }else{
            $json_token=$this->curlPost("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".self::appid."&secret=".self::appsc);
            //var_dump($json_token);die;
            $access_token2=$json_token['access_token'];
            setcookie('access_token',$access_token2,7200);
        }
        //模板消息
        $json_template = $this->json_tempalte();
        //echo($json_template);die;
        $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token2;
        $res=$this->curlPost($url,urldecode($json_template));
        if ($res['errcode']==0){
            return '发送成功';
        }else{
            return '发送失败';
        }
    }

    /**
     * 将模板消息json格式化
     */
    public function json_tempalte(){
        //模板消息
        $template=array(
            'touser'=>'.$openid.',  //用户openid
            'template_id'=>self::tid, //在公众号下配置的模板id
            'url'=>self::url, //点击模板消息会跳转的链接
            'topcolor'=>"#7B68EE",
            'data'=>array(
                'first'=>array('value'=>urlencode("收货地址(测试)"),'color'=>"#FF0000"),
                'keyword1'=>array('value'=>urlencode('桐梓坡霸王'),'color'=>'#FF0000'),  //keyword需要与配置的模板消息对应
                'keyword2'=>array('value'=>urlencode(15211111111),'color'=>'#FF0000'),
                'keyword3'=>array('value'=>urlencode('测试发布人'),'color'=>'#FF0000'),
                //'keyword4'=>array('value'=>urlencode('测试状态'),'color'=>'#FF0000'),
                'remark' =>array('value'=>urlencode('备注：这是测试'),'color'=>'#FF0000'), )
        );
        $json_template=json_encode($template);
        return $json_template;
    }

    //基础获取授权接口
    public function actionAccessToken()
    {
        $code = $_GET["code"];
        $state = $_GET["state"];
        $appid = self::appid;
        $appsecret = self::appsc;
        $request_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$appsecret.'&code='.$code.'&grant_type=authorization_code';

        $result = $this->curlPost($request_url);

        //获取token和openid成功，数据解析
        $access_token = $result['access_token'];//授权id
        $refresh_token = $result['refresh_token'];//刷新id
        $openid = $result['openid'];//用户open_id

        //请求微信接口，获取用户信息
        $userInfo = $this->getUserInfo($access_token,$openid);
        $user_check = WechatUser::find()->where(['openid'=>$openid])->one();
        if ($user_check) {
            //更新用户资料

        } else {
            //保存用户资料
            $model = new WechatUser();
            $model->id = 1;
            $model->openid = $userInfo['openid'];
            $model->nickname = $userInfo['nickname'];
            $model->sex = $userInfo['sex'];
            $model->headimgurl = $userInfo['headimgurl'];
            $model->access_token = $access_token;
            $model->refresh_token = $refresh_token;
            $model->save();
            if($model->save()){
                //前端网页的重定向
                if ($openid) {
                    return $this->redirect($state);
                } else {
                    return $this->redirect($state);
                }
            }else{
                var_dump($model->firstErrors);die;
            }
        }
    }

    public function getUserInfo($access_token,$openid)
    {
        $request_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
        $result = $this->curlPost($request_url);
        return $result;
    }

    //post
    function curlPost($uri, $data = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $uri);//地址
        curl_setopt($ch, CURLOPT_POST, 1);//请求方式为post
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//取消ssl验证
        curl_setopt($ch, CURLOPT_HEADER, 0);//不打印header信息
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//返回结果转成字符串
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);//post传输的数据。
        /*curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: ' . strlen($data)
            )
        );*/
        $result = curl_exec($ch);//curl_error($ch);
        curl_close($ch);
        return json_decode($result, true);
    }

    /*
    * 首次关注推送
    */
    private function receiveFirst($object)
    {
        $content = "欢迎您关注"."<a href='https://open.weixin.qq.com/connect/oauth2/authorize?appid=".self::appid."&redirect_uri=".urlencode('http://xin.fantasticskybaby.cn/wei/access-token')."&response_type=code&scope=snsapi_userinfo&state=".urlencode('http://xin.fantasticskybaby.cn/code.txt')."#wechat_redirect'>云医链共享服务平台</a>"."，更多功能正在开发中！";
        $result = $this->transmitText($object, $content);
        return $result;
    }

    /*
    * 接收文本消息
    */
    private function receiveText($object)
    {
        $content = "你发送的是文本，内容为：".$object->Content;
        $result = $this->transmitText($object, $content);
        return $result;
    }

    /*
     * 接收图片消息
     */
    private function receiveImage($object)
    {
        $content = "你发送的是图片，地址为：".$object->PicUrl;
        $result = $this->transmitText($object, $content);
        return $result;
    }

    /*
     * 接收语音消息
     */
    private function receiveVoice($object)
    {
        $content = "你发送的是语音，媒体ID为：".$object->MediaId;
        $result = $this->transmitText($object, $content);
        return $result;
    }

    /*
     * 接收视频消息
     */
    private function receiveVideo($object)
    {
        $content = "你发送的是视频，媒体ID为：".$object->MediaId;
        $result = $this->transmitText($object, $content);
        return $result;
    }

    /*
     * 接收位置消息
     */
    private function receiveLocation($object)
    {
        $content = "你发送的是位置，纬度为：".$object->Location_X."；经度为：".$object->Location_Y."；缩放级别为：".$object->Scale."；位置为：".$object->Label;
        $result = $this->transmitText($object, $content);
        return $result;
    }

    /*
     * 接收链接消息
     */
    private function receiveLink($object)
    {
        $content = "你发送的是链接，标题为：".$object->Title."；内容为：".$object->Description."；链接地址为：".$object->Url;
        $result = $this->transmitText($object, $content);
        return $result;
    }

    /*
     * 回复文本消息
     */
    private function transmitText($object, $content)
    {
        $textTpl = "<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[text]]></MsgType>
            <Content><![CDATA[%s]]></Content>
            </xml>";
        $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content);
        return $result;
    }
}