<?php 

class Session{

	public static function exists($name)
	{
		if(isset($_SESSION[$name]))
			return true;
		else
			return false;
	}

	public static function put($name, $value)
	{
		$_SESSION[$name] = $value;

		return true;
	}

	public static function get($name)
	{
		if(isset($_SESSION[$name]))
			return $_SESSION[$name];
		else
			return false;
	}

	public static function delete($name)
	{
		if(self::exists($name))
			unset($_SESSION[$name]);

		return true;
	}

	public static function flashPut($name, $string = '')
	{
		self::put($name, $string);

		return true;			
	}

	public static function flashGet($name)
	{
		if(self::exists($name)){
			$session = self::get($name);
			self::delete($name);
			return $session;
		}
		else
			return '';
	}
}

?>