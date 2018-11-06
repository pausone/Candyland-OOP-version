<?php 

class Hash{	

	public static function make($string, $salt = ''){
		return hash('sha256', $string . $salt);
	}

	// Add salt (string) to password to create different hash for passwords that are the same
	public static function salt($length){
		return mcrypt_create_iv($length);
	}

	public static function unique(){
		return self::make(uniqid());
	}
}

?>