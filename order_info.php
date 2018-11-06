<?php	
	include('includes/header.php');
	if(Session::get('admin') && Input::exists('get')){		
		$html = file_get_contents('templates/order_info_template.html');
		$html_pieces = explode('<!--==xxx==-->', $html);

		$order = Order::get(Input::get('order'));
		
		if($order){
			echo Template::replaceKeys($html_pieces[0], array(
					'---$order_id---' => $order->id,
					'---$user_id---' => $order->user_id,
					'---$order_date---' => $order->order_date,
					'---$sent---' => $order->sent ? 'Ja' : 'Nej'
					));				
				
				$products = unserialize($order->products); //For getting products information
				$sum = 0;
				
				//Get products data from database		
				foreach($products as $id => $quantity){
					$tmp_html_piece = $html_pieces[1];								
					
					//Get product info from database
					$product = Product::get($id);			
					
					if ($product) {
						echo Template::replaceKeys($tmp_html_piece, array(
								'---$quantity---' => $quantity,
								'---$name---' => $product->name,
								'---$sales_price---' => $product->sales_price,
								'---$sum_post---' => $product->sales_price*$quantity
								));

						$sum += $product->sales_price*$quantity;
					}
				}
				
				//Add sum to next piece
				echo Template::replaceKeys($html_pieces[2], array('---$sum---' => $sum));
				
				//Get user information from database
				$user = User::getById($order->user_id);
				
				if($user){
					echo Template::replaceKeys($html_pieces[3], array(
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
			echo "Order ej funnen";	
		
		include('includes/footer.html');
	}
	else
		Redirect::to('index.php');		
?>