<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "black_list".
 *
 * @property integer $id
 * @property string $character_name
 * @property string $reason
 * @property integer $server
 * @property integer $parent_id
 * @property string $youtube
 * @property string $facebook
 * @property integer $status
 * @property integer $bad_point
 * @property integer $good_point
 * @property integer $created_by
 * @property integer $created_at
 * @property integer $updated_by
 * @property integer $updated_at
 */
class BlackList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'black_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['character_name', 'reason', 'server'], 'required'],
            [['server', 'parent_id', 'status', 'bad_point', 'good_point', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['character_name', 'reason', 'youtube', 'facebook'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'character_name' => Yii::t('app', 'Character Name'),
            'reason' => Yii::t('app', 'Reason'),
            'server' => Yii::t('app', 'Server'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'youtube' => Yii::t('app', 'Youtube'),
            'facebook' => Yii::t('app', 'Facebook'),
            'status' => Yii::t('app', 'Status'),
            'bad_point' => Yii::t('app', 'Bad Point'),
            'good_point' => Yii::t('app', 'Good Point'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
