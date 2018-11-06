<?php
	include('includes/header.php');
	
	Session::put('redirect_after_login', 'create_new_user.php');

	if(Session::exists(Config::get('session/session_name'))){	
		Redirect::to('index.php');
	}
	
	$message_html = file_get_contents('templates/message_template.html');

	echo Template::replaceKeys($message_html, array(
			'---$message---' => "Skapa ny användare."
			));
	
	include('includes/add_user.php');
	include('includes/footer.html');	
?>