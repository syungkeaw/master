<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class RoActiveRecord extends ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    public static function getDb() {
        if (!Yii::$app->session->isActive){
            Yii::$app->session->open();
        }

        switch (Yii::$app->session->get('server')) {
            case 'thor':
                $db = Yii::$app->thordb;
                break;            
            case 'loki':
                $db = Yii::$app->lokidb;
                break;
            
            default:
                $db = Yii::$app->thordb;
                break;
        }
        return $db;
    }
}