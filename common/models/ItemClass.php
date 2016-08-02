<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%item_class}}".
 *
 * @property integer $id
 * @property string $class_name
 */
class ItemClass extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%item_class}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['class_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'class_name' => 'Class Name',
            'class_type_id' => 'Class Type Id',
        ];
    }
}
