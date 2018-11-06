<?php 

class User{

	public static function create($fields = array()){
		$db = DB::getInstance();

		if($db->insert('users', $fields)->count())
			return true;
		else 
			return false;
	}

	public static function getAll(){
		$users = DB::getInstance()->query('SELECT * FROM users');

		if($users->count())
			return $users->results();	
		else
			return false;
	}	

	public static function getByName($username = null){
		$user = DB::getInstance()->get('users', array('username', '=', $username));

		if($user->count()){
			return $user->first();
		}

		return false;
	}

	public static function getById($userId = null){
		$user = DB::getInstance()->get('users', array('id', '=', $userId));

		if($user->count()){
			return $user->first();
		}

		return false;
	}

	public static function login($username = null, $password = null, $remember = null){
		$user = User::getByName($username);
		$session_name = Config::get('session/session_name');

		//If user exists and the password is a match, log in user
		if ($user){
			if ($user->password === Hash::make($password, $user->salt)){
				Session::put($session_name, $user->id);
				Session::put('admin', $user->admin);

				//If user wants autologin, add/update Cookie. 
				if($remember){
					User::remember($user->id);
				}

				return true;		
			}
			else 
				echo "Lösenord felaktigt.";
		}
		else
			echo "Användarnamn finns ej.";

		return false;
	}

	public static function logout(){
		$session = Config::get('session/session_name');
		$delete = DB::getInstance()->delete('sessions', array('user_id', '=', Session::get($session)));

		Session::delete($session);
		Session::delete('admin');
		Cookie::delete(Config::get('remember/cookie_name'));
	}


	private static function remember($userId){
		$hash = Hash::unique();
		$db = DB::getInstance();

		//Check if user has session stored
		$session = $db->get('sessions', array('user_id', '=', $userId));

		//If user does not have session stored, create new entry
		if ($session->results()) {	
			$session = $db->query('UPDATE sessions SET hash = ? WHERE user_id = ?', array($hash, $userId));
			Cookie::put(Config::get('remember/cookie_name'), $hash, Config::get('remember/cookie_expiry'));				
		}
		else{	
			$session = $db->insert('sessions', array(
				'user_id' => $userId,
				'hash' => $hash
				));
				Cookie::put(Config::get('remember/cookie_name'), $hash, Config::get('remember/cookie_expiry'));
		}			
	}
}

?>