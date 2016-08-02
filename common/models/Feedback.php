<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "feedback".
 *
 * @property integer $id
 * @property integer $shop_item_id
 * @property integer $feedback_id
 * @property integer $feedback_by
 */
class Feedback extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'feedback';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_item_id', 'feedback_id', 'feedback_by'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shop_item_id' => 'Shop Item ID',
            'feedback_id' => 'Feedback ID',
            'feedback_by' => 'Feedback By',
        ];
    }
}
