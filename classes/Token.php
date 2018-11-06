<?php 

class Token{

	public static function generate(){
		$token = md5(uniqid());

		Session::put(Config::get('session/token_name'), $token);

		return $token;
	}

	public static function check($token){
		$tokenName = Config::get('session/token_name');
		
		if(Session::exists($tokenName) && $token === Session::get($tokenName)){
			Session::delete($tokenName);
			return true;
		}
		else
			return false;

	}
}

?>