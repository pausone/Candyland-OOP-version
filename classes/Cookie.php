<?php

class Cookie{	

	public static function put($name, $value, $expiry)
	{
		setcookie($name, $value, time() + $expiry); 
		return true;
	}

	public static function get($name)
	{
		return $_COOKIE[$name];
	}

	public static function delete($name)
	{
		if(self::exists($name)){
			$reset = self::put($name, '', - 1); // empty value and expire one hour before now
			return true;
		}
		return false;			
	}

	public static function exists($name)
	{
		if(isset($_COOKIE[$name]))
			return true;
		else
			return false;
	}
}

?>