<?php
		require_once('core/init.php');		
		Session::delete('cart');
		Redirect::to('index.php');
?>