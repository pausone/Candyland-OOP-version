<?php

	if(Session::exists(Config::get('session/session_name'))){	
		Redirect::to('index.php');
	}
		
	//Prepare message templates
	$message_html = file_get_contents('templates/message_template.html');
	$failed_message_html = file_get_contents('templates/failed_message_template.html');

	//To be set from formdata
	$username = '';
	$password = ''; 
	$first_name = ''; 
	$last_name = '';
	$adress = '';
	$postal = '';
	$city = '';	
	
	$user_created = false;

	if(Input::exists() && Token::check(strip_tags(Input::get('token')))){
		//Userdata
		$username = strip_tags(Input::get('username'));
		$password = strip_tags(Input::get('password')); 
		$first_name = strip_tags(Input::get('first_name')); 
		$last_name = strip_tags(Input::get('last_name'));
		$adress = strip_tags(Input::get('adress'));
		$postal = strip_tags(Input::get('postal'));
		$city = strip_tags(Input::get('city'));

		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'username' => array(
				'required' => true,
				'min' => 3,
				'max' => 16,
				'unique' => 'users'
			),
			'password' => array(
				'required' => true,
				'min' => 8,
				'max' => 20,
				'pwdCheck' => true
			),
			'first_name' => array(
				'required' => true,
				'max' => 30,
				'lettersOnly' => true
			),
			'last_name' => array(
				'required' => true,
				'max' => 30,
				'lettersOnly' => true
			),
			'adress' => array(
				'required' => true,
				'min' => 3,
				'max' => 16,
			),
			'postal' => array(
				'required' => true,
				'min' => 6,
				'max' => 6,
				'post_number' => true
			),
			'city' => array(
				'required' => true,
				'max' => 30,
				'lettersOnly' => true
			))
		);		

		if($validate->passed()){
			$salt = Hash::salt(32);

			$user_created = User::create(array(
				'username' => $username,
				'password' => Hash::make($password, $salt),
				'salt' => $salt,
				'first_name' => $first_name,
				'last_name' => $last_name,
				'adress' => $adress,
				'postal' => $postal,
				'city' => $city,
				'admin' => 0	//No admin right as default. Can only be changed directly in db.
			));

			if ($user_created){
				Session::flashPut('success', 'Du är nu registrerad. Logga in!');
				Redirect::to('login.php');		
			}
			else
				echo "Det gick inte att lägga till en ny användare.";
		}
		else{
			foreach ($validate->errors() as $error) {
				echo $error . '<br>';
			}
		}
	}
	
	$html = file_get_contents('templates/add_user_form_template.html');

	echo Template::replaceKeys($html, array(
			'---$username---' => escape($username),
			'---$password---' => escape($password),
			'---$first_name---' => escape($first_name),
			'---$last_name---' => escape($last_name),
			'---$adress---' => escape($adress),
			'---$postal---' => escape($postal),
			'---$city---' => escape($city),
			'---$token---' => Token::generate()
			));
?>