<?php
		require_once('core/init.php');
		
		User::logout();
		Session::delete('redirect_after_login');
		Redirect::to('index.php');
?>
	