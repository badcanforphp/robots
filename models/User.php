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

    //检测是否是新的wxid
    public static function checkUser($arr)
    {
        $check = User::find()->where(['wxid'=>$arr['wxid']])->asArray()->one();
        if($check){
            $model = User::findOne(['wxid'=>$arr['wxid']]);
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
}
