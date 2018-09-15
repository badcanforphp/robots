<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "wechat_user".
 *
 * @property integer $id
 * @property string $openid
 * @property string $nickname
 * @property integer $sex
 * @property string $headimgurl
 * @property string $country
 * @property string $province
 * @property string $city
 * @property string $access_token
 * @property string $refresh_token
 * @property string $created_at
 */
class WechatUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wechat_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'openid', 'nickname', 'sex', 'headimgurl', 'country', 'province', 'city', 'access_token', 'refresh_token'], 'required'],
            [['id', 'sex'], 'integer'],
            [['created_at'], 'safe'],
            [['openid', 'headimgurl', 'access_token', 'refresh_token'], 'string', 'max' => 255],
            [['nickname', 'country', 'province', 'city'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'openid' => 'Openid',
            'nickname' => 'Nickname',
            'sex' => 'Sex',
            'headimgurl' => 'Headimgurl',
            'country' => 'Country',
            'province' => 'Province',
            'city' => 'City',
            'access_token' => 'Access Token',
            'refresh_token' => 'Refresh Token',
            'created_at' => 'Created At',
        ];
    }

    public function UserInfo()
    {
        if(isset($_REQUEST["openid"])){
            $openid = $_REQUEST["openid"];
            $user = WechatUser::find()->where(['openid'=>$openid])->one();
            if ($user) {
                $result['error'] = 0;
                $result['msg'] = '获取成功';
                $result['user'] = $user;
            } else {
                $result['error'] = 1;
                $result['msg'] = '没有该用户';
            }
        } else {
            $result['error'] = 1;
            $result['msg'] = 'openid为空';
        }
        return $result;
    }
}
