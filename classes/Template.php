<?php 

class Template{	

	public static function replaceKeys($template, $keys = array()){
		foreach ($keys as $key => $value) {
			$template = str_replace($key , $value, $template);
		}

		return $template;
	}
}

?>