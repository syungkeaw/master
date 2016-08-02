<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%item_jobs}}".
 *
 * @property integer $item_id
 * @property integer $job_id
 */
class ItemJob extends \yii\db\ActiveRecord
{
     
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%item_jobs}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id', 'job_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'item_id' => 'Item ID',
            'job_id' => 'Job ID',
        ];
    }
}
