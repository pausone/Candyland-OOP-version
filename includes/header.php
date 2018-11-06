<?php
	require_once('core/init.php');

	$session_name = Config::get('session/session_name');
	$remember_name = Config::get('remember/cookie_name');

	$loggedIn = Session::exists($session_name);
	$remember = Cookie::exists($remember_name);

	if(!$loggedIn && $remember){

		//Check sessions-id in database
		$db = DB::getInstance();

		$result = $db->get('sessions', array('hash', '=', Cookie::get($remember_name)));

		if($result->count()){
			$user = User::getById($result->first()->user_id);
			$loggedIn = User::login($user->username, $user->password, true);
		}
	}

	$username = 'Username';
	$admin = Session::get('admin');
	$shop = Session::get('cart');
	$login_out = "login.php";
	$login_out_option = "Logga in";	


	//Get username if logged in and not admin
	if(!$admin && $loggedIn){
		$user = User::getById(Session::get($session_name));
		$username = $user->username;
	}

	if(!$admin){
		$html_nav = file_get_contents('templates/nav_customer.html');

		$html_pieces_nav = explode('<!--==xxx==-->', $html_nav);	

		//If items in cart, add them to dropdown area in menu
		if($shop && count($shop) > 0)
		{
			$cart = '';
			
			foreach($shop as $id => $quantity){
				$tmp_html_piece = $html_pieces_nav[1];							
				$tmp_html_piece = Template::replaceKeys($tmp_html_piece, array(
					'---$quantity---' => $quantity));
				
				$product  = Product::get($id);
				if($product){
					$tmp_html_piece = Template::replaceKeys($tmp_html_piece, array(
					'---$item---' => $product->name));
					
				}
				else
					$tmp_html_piece = 'Product id does not exist<br>';

				$cart = $cart . $tmp_html_piece;
			}

			$html_pieces_nav[1] = $cart;
			$shop = "";	
		}
		else{
			$html_pieces_nav[1] = "Varukorgen är tom";
			$shop = " hide";
		}

		//If user is logged in, show user dropdown, otherwise just login link
		if($loggedIn)
		{	
			$logged_in = ""; 
			$logged_out = " hide";
		}
		else{
			$logged_in = " hide"; 
			$logged_out = "";
		}

		$html_pieces_nav[2] = Template::replaceKeys($html_pieces_nav[2], array(
				'---$logged_in---' => $logged_in,
				'---$logged_out---' => $logged_out,
				'---$username---' => $username,
				'---$shop---' => $shop
				));

		//Put pieces back together
		$html_nav = $html_pieces_nav[0] . $html_pieces_nav[1] . $html_pieces_nav[2];

	}	
	else
		$html_nav = file_get_contents('templates/nav_admin.html');


	$html_header = file_get_contents('templates/header_template.html');

	//Navigation
	echo Template::replaceKeys($html_header, array('---$custom_nav---' => $html_nav));

?>