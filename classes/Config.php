<?php

class Config{

	public static function get($path = null){
		if($path){
			$config = $GLOBALS['config'];
			$path = explode('/', $path);
			
			foreach ($path as $bit) { 			//For each bit/key in path
				if(isset($config[$bit])){
					$config = $config[$bit]; 	//Set to next bit/keys value to get to to lowest level in array
				}
			}

			return $config;
		}
	}
}

?>
