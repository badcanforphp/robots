<?php
/**
 * Created by PhpStorm.
 * User: zjj
 * Date: 2018/5/31
 * Time: 11:06
 */
namespace app\models;

use Yii;


class Record extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'record';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','time','uid','type'], 'integer'],
            [['to_text','back_text'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'Uid',
            'time' => 'Time',
            'type' => 'Type',
            'to_text' => 'ToText',
            'back_text' => 'BackText',
        ];
    }

    //ÁÄÌì¼ÇÂ¼
    public static function setRecord($wxid,$type,$to_text,$back_text)
    {
        $uid = User::find()->where(['wxid'=>$wxid])->asArray()->one();
        $model = new Record();
        $model->uid = $uid['id'];
        $model->time = time();
        $model->type = $type;
        $model->to_text = $to_text;
        $model->back_text = $back_text;
        $model->save();
    }
}
