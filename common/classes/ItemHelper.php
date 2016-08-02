<?php

namespace common\classes;

class ItemHelper{

	static public function getImgFileName ($item){
		$imgFileName = $item['source_id'];

		if (strpos(strtolower($item['item_name']), 'card') !== false) {
		    $imgFileName = 'card';
		}

		return $imgFileName. '.gif';
	}



}