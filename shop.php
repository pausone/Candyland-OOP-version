<?php
	include('includes/header.php');

	if(Cart::cartHasItems() && Validate::isCustomer())
	{
		$message_for_id = '';
		$msg = '';
		$success = '';		

		if(Input::exists('get')){
			$success = Input::get('success');

			if(!$success && $success != ''){
				$msg = Session::get('msg');
				if(Session::exists('item_id'))
					$message_for_id = Session::get('item_id');
			} 
		}

		echo $msg;		
		
		//Prepare template for table		
		$html = file_get_contents('templates/cart_table_template.html');
		$html_pieces = explode('<!--==xxx==-->', $html);
		
		echo $html_pieces[0];
		$sum = 0;

		foreach(Session::get('cart') as $id => $quantity){
			//Reset variables
			$visibility_add = '';
			$visibility_subtract = '';
			$visibility_low_stock = ' hidden';
			$tmp_html_piece = $html_pieces[1];								
			
			//Check if product is available in stock 
			$inStock = Product::getInstock($id);

			if($inStock){					
				if($inStock <= $quantity){					
					$visibility_add = ' hidden';
					$visibility_low_stock = '';
				}
			} 
			else 
				echo "Lagersaldo ej funnet."; 	

			if ($quantity <= 0) {
				$visibility_subtract = ' hidden';
			}		
			
 			$product = Product::get($id);

			if ($product){
					$sum_post = $product->sales_price*$quantity;		

					echo Template::replaceKeys($tmp_html_piece, array(
							'---$name---' => $product->name,
							'---$quantity---' => $quantity,
							'---$visibility_add---' => $visibility_add,
							'---$visibility_low_stock---' => $visibility_low_stock,
							'---$visibility_subtract---' => $visibility_subtract,
							'---$id---' => $id,
							'---$sales_price---' => $product->sales_price,
							'---$sum_post---' => $sum_post
							));

					$sum += $sum_post;					
			}			
		}
		echo Template::replaceKeys($html_pieces[2], array('---$sum---' => $sum));				
		
		//If user is logged in, show user information, otherwise show new user form
		if(Validate::isCustomerLoggedIn())
		{
			include('includes/user_information.php');
		}
		else{			
			Session::put('redirect_after_login','shop.php');
			
			include('includes/customer_message.html');			
			include('includes/add_user.php');
		}		
	}	
	else
		Redirect::to('index.php');
	
	include('includes/footer.html');	
	
?>