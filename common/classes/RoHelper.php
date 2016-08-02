<?php

namespace common\classes;

use yii;

class RoHelper{

	static public function getActiveServerName(){
		$server_name = [
			1 => 'Thor',
			2 => 'Loki',
		];

        if (!Yii::$app->session->isActive){
            Yii::$app->session->open();
        }

        $active = Yii::$app->session->get('server') ? Yii::$app->session->get('server') : 1;

		return $server_name[$active];
	}

	static public function getActiveServerId(){
        if (!Yii::$app->session->isActive){
            Yii::$app->session->open();
        }
        
		return Yii::$app->session->get('server');
	}

}