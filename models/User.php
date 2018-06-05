<?php
/**
 * Created by PhpStorm.
 * User: zjj
 * Date: 2018/5/31
 * Time: 11:06
 */
namespace app\models;

use Yii;


class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','time','first_time','last_time'], 'integer'],
            [['wxid'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'wxid' => 'Wxid',
            'time' => 'Time',
            'first_time' => 'FirstTime',
            'last_time' => 'LastTime',
        ];
    }

    //����Ƿ����µ�wxid
    public static function checkUser($arr)
    {
        $check = User::find()->where(['wxid'=>$arr['token']])->asArray()->one();
        if($check){
            $model = User::findOne(['wxid'=>$arr['token']]);
            $model->last_time = time();
            $model->save();
        }else{
            $model = new User();
            $model->wxid = $arr['token'];
            $model->time = 0;
            $model->first_time = time();
            $model->last_time = time();
            $model->save();
        }
    }

    //��¼ʹ�ô���
    public static function Time($wxid)
    {
        $model = User::findOne(['wxid'=>$wxid]);
        $model->time += 1;
        $model->save();
    }

    //��ⵥһ�û�ÿ�մ����Ƿ�����
    public static function CheckTime($wxid)
    {
        $model = User::find()->where(['wxid'=>$wxid])->asArray()->one();
        $time = $model['time'];
        if($time > 300){
            return false;
        }else if($time == 300){
            User::Time($wxid);
            return 4;
        }
        return true;
    }
}
