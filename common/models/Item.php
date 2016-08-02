<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%item}}".
 *
 * @property integer $id
 * @property string $source_id
 * @property string $item_name
 * @property integer $item_slot
 * @property integer $item_slot_spare
 * @property string $item_num_hand
 * @property integer $item_type_id
 * @property string $item_type
 * @property string $item_class
 * @property integer $item_attack
 * @property integer $item_defense
 * @property integer $item_required_lvl
 * @property integer $item_weapon_lvl
 * @property string $item_description
 */
class Item extends \yii\db\ActiveRecord
{
    private $_job_ids;

    private static $_element = [
        '',
        'Fire',
        'Water',
        'Wind',
        'Earth',
    ];

    private static $_very = [
        '',
        1 => 'very strong',
        2 => 'very very strong',
    ];

    private static $_ehancement = [
        '+0',
        '+1',
        '+2',
        '+3',
        '+4',
        '+5',
        '+6',
        '+7',
        '+8',
        '+9',
        '+10',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%item}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_name'], 'required'],
            [['source_id', 'item_slot', 'item_slot_spare', 'item_type_id', 'item_defense', 'item_required_lvl', 'item_weapon_lvl', 'item_num_hand'], 'integer'],
            [['item_name', 'item_description', 'item_prefix_suffix', 'item_attack'], 'string'],
            [['item_type', 'item_class'], 'string'],
            [['source_id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'source_id' => Yii::t('app', 'Source ID'),
            'item_name' => Yii::t('app', 'Item Name'),
            'item_slot' => Yii::t('app', 'Item Slot'),
            'item_slot_spare' => Yii::t('app', 'Item Slot Spare'),
            'item_num_hand' => Yii::t('app', 'Item Num Hand'),
            'item_type_id' => Yii::t('app', 'Item Type ID'),
            'item_type' => Yii::t('app', 'Item Type'),
            'item_class' => Yii::t('app', 'Item Class'),
            'item_attack' => Yii::t('app', 'Item Attack'),
            'item_defense' => Yii::t('app', 'Item Defense'),
            'item_required_lvl' => Yii::t('app', 'Item Required Lvl'),
            'item_weapon_lvl' => Yii::t('app', 'Item Weapon Lvl'),
            'item_description' => Yii::t('app', 'Item Description'),
            'item_prefix_suffix' => Yii::t('app', 'Item Prefix Suffix'),
        ];
    }

    public static function getEnhancements()
    {
        return self::$_ehancement;
    }

    public static function getElements()
    {
        return self::$_element;
    }

    public static function getVeries()
    {
        return self::$_very;
    }

    public function getJobIds()
    {
        return $this->_job_ids;
    }

    public function setJobIds($id)
    {
        $this->_job_ids = $id;
    }

    public function getNameSlot()
    {
        return in_array($this->item_type_id, [4, 5]) && $this->item_slot > 0 ? $this->item_name. ' ['. $this->item_slot .']' : $this->item_name;
    }

    public function getItemJobs()
    {
        return $this->hasMany(ItemJob::className(), [
            'item_id' => 'source_id'
        ]);
    }

    public function getJobs()
    {
        return $this->hasMany(Job::className(), [
            'id' => 'job_id'
        ])->via('itemJobs');
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        
        $this->unlinkAll('jobs', true);
        if (isset($this->_job_ids) && is_array($this->_job_ids)) {
            $jobs = Job::findAll([
                'id' => $this->_job_ids
            ]);
            foreach ($jobs as $job) {
                $this->link('jobs', $job);
            }
        }
    }

}
