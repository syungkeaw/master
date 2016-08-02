<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%sell_history}}".
 *
 * @property integer $id
 * @property integer $item_id
 * @property integer $created_by
 * @property integer $created_at
 * @property integer $updated_by
 * @property integer $updated_at
 */
class SellHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sell_history}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'item_id' => 'Item ID',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }
}
