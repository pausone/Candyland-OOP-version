<?php

	$session_name = Config::get('session/session_name');

	if(Session::exists($session_name))
	{
		$user_template = file_get_contents('templates/user_information_template.html');
		
		$user = User::getById(Session::get($session_name));

		if($user){
			echo Template::replaceKeys($user_template, array(
					'---$username---' => $user->username,
					'---$first_name---' => $user->first_name,
					'---$last_name---' => $user->last_name,
					'---$adress---' => $user->adress,
					'---$postal---' => $user->postal,
					'---$city---' => $user->city
					));
		}
		else
			echo "Kund ej funnen";	
	}	
	else
		Redirect::to('index.php');
?>