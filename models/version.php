<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "feedback".
 *
 * @property string $id
 * @property integer $uid
 * @property integer $type
 * @property string $content
 */
class Version extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'version';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'vid'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'vid' => 'Vid',
        ];
    }
}
