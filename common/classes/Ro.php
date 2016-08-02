<?php 
namespace common\classes;

class Ro {

	public function getInfo($url = "http://ratemyserver.net/index.php?page=item_db", $item_type = 4, $page_num = 1){
		
		$html = $this->geturl($url. '&item_type='. $item_type .'&page_num='. $page_num);

		$all_item = $this->match_all('/<table class = \'content_box_item\'(.*?)<\/td><\/tr><\/table><br>/ms', $html, 1);

		foreach ($all_item as $html) {
			$items[] = $this->scrape_item($html, $item_type);
		}

		if(!isset($items)){
			echo 'Undefined variable: items:::: '. $url. '<br>';
			$items = [];
		}

		return $items;
	}

	private function scrape_item($html, $item_type){
		$attr['item_id'] = $this->match('/ Item ID# (.*?) /ms', $html, 1);
		$attr['item_name'] = $this->match('/<b> (.*?) <\/b>/ms', $html, 1);
		$attr['item_slot'] = $this->match('/<\/b><b>\[<\/b>(.*?)<b>\]<\/b>/ms', $html, 1);
		$attr['item_slot_spare'] = $this->match('/Slot<\/th>[\n\s]+<td class = \'bb\' align=\'right\'>(.*?)<\/td>/ms', $html, 1);
		$attr['item_num_hand'] = $this->match('/&nbsp; <b>\[<\/b>(.*?)<b>\]<\/b>&nbsp;/ms', $html, 1);
		$attr['item_type_id'] = $item_type;
		$attr['item_type'] = $this->match('/Type<\/th>[\n\s]*<td class=\'bb\' align=\'right\'>(.*?)<\/td>/ms', $html, 1);
		$attr['item_class'] = $this->match('/Class<\/th>[\n\s]+<td class = \'bb\' align=\'right\'>(.*?)<\/td>/ms', $html, 1);
		$attr['item_attack'] = $this->match('/Attack<\/th>[\n\s]+<td class = \'bb\' align=\'right\'>(.*?)<\/td>/ms', $html, 1);
		$attr['item_defense'] = $this->match('/Defense<\/th>[\n\s]+<td class = \'bb\' align=\'right\'>(.*?)<\/td>/ms', $html, 1);
		$attr['item_required_lvl'] = $this->match('/Required Lvl<\/th>[\n\s]+<td class = \'bb\' align=\'right\'>(.*?)<\/td>/ms', $html, 1);
		$attr['item_weapon_lvl'] = $this->match('/Weapon Lvl<\/th>[\n\s]+<td class = \'bb\' align=\'right\'>(.*?)<\/td>/ms', $html, 1);
		$attr['item_jobs'] = $this->match_all('/<img src=\'images\/bu2\.gif\'><\/td><td width=100>(.*?) <\/td>/ms', $html, 1);
		$attr['item_description'] = strip_tags($this->match('/<td colspan=9 class=\'bb\'  valign=\'top\'>(.*?)<\/td><\/tr>/ms', $html, 1));
		$attr['item_prefix_suffix'] = strip_tags($this->match('/Pre\/Suffix<\/th>[\n\s]+<td class = \'bb\' align=\'right\'>(.*?)<\/td>/ms', $html, 1));
		return $attr;
	}

	private function geturl($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		$ip=rand(0,255).'.'.rand(0,255).'.'.rand(0,255).'.'.rand(0,255);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("REMOTE_ADDR: $ip", "HTTP_X_FORWARDED_FOR: $ip"));
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/".rand(3,5).".".rand(0,3)." (Windows NT ".rand(3,5).".".rand(0,2)."; rv:2.0.1) Gecko/20100101 Firefox/".rand(3,5).".0.1");
		$html = curl_exec($ch);
		curl_close($ch);
		return $html;
	}

	private function match_all($regex, $str, $i = 0){
		if(preg_match_all($regex, $str, $matches) === false)
			return false;
		else
			return $matches[$i];
	}

	private function match_all_arr($regex, $str){
		if(preg_match_all($regex, $str, $matches) === false)
			return false;
		else
			return $matches;
	}


	private function match($regex, $str, $i = 0){
		if(preg_match($regex, $str, $match) == 1)
			return $match[$i];
		else
			return false;
	}
}