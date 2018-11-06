<?php
	include('includes/header.php');
	echo Session::flashGet('success'); //Writes empty string if empty
	
	if(!Session::exists(Config::get('session/session_name'))){
		$username = "";
		$password = "";
		$remember = "";

		if(Input::exists() && Token::check(strip_tags(Input::get('token')))){
			$username = strip_tags(Input::get('username'));
			$password = strip_tags(Input::get('password'));
			$remember = Input::get('remember');

			$validate = new Validate();
			$validation = $validate->check($_POST, array(
				'username' => array(
					'required' => true
				),
				'password' => array(
					'required' => true
				))
			);

			if($validate->passed()){
				if (User::login($username, $password, $remember)) {
					if(Input::exists('get')){
						$redirect = Input::get('redirect_after_login');

						Session::delete('redirect_after_login');						
						Redirect::to($redirect);	
					}
					else if(Session::exists('redirect_after_login')){
						$redirect = Session::get('redirect_after_login');
						
						Session::delete('redirect_after_login');
						Redirect::to($redirect);				
					}
					else
						Redirect::to('index.php');
				}
				else{
					if($remember)
						$remember = 'checked';
				}				
			}
			else{
				foreach ($validate->errors() as $error) 
					echo $error . '<br>';
			}
		}
				
		$html = file_get_contents('templates/login_form_template.html');
		
		echo Template::replaceKeys($html, array(
				'---$username---' => $username,
				'---$password---' => $password,
				'---$remember---' => $remember,
				'---$token---' => Token::generate()
				));		
		
		include('includes/footer.html');
	}
	else
		Redirect::to('index.php');
?>
