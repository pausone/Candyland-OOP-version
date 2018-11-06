<?php
	require_once('core/init.php');

	$items_in_cart = false;
	$admin = false;
	$customer_loggedin = false;
	$success = 1;

	//Check if cart content exists
	if(Session::exists('cart') && count(Session::get('cart')) > 0){
		foreach(Session::get('cart') as $id => $quantity){
			if($quantity > 0)
				$items_in_cart = true;
		}
	}		
	
	if(Session::exists('admin')){
		if(Session::get('admin'))
			$admin = true;
		else
			$customer_loggedin = true;
	}
	
	//Proceed to create order if customer is logged in and there is something in the cart
	if(!$admin && $items_in_cart && $customer_loggedin)
	{	
		$all_in_stock = true;

		//Check that there is enough i stock
		foreach(Session::get('cart') as $id => $quantity){
			
			//Get current number of items in stock for product
			$product = Product::get($id);		
			
			if ($product) {
				if($product->in_stock < $quantity){
					$all_in_stock = false;
					Session::put('msg',"Det finns inte tillräcklig på lager av $product->name. På lager: $product->in_stock");
					Redirect::to('shop.php?success=0');					
				}
			} else {
				echo "Lagersaldo ej funnet.";
			}
		}
		
		if($all_in_stock){
			//Create order if all ordered items are in stock
			$order_created = Order::create(array(
				'user_id' => Session::get(Config::get('session/session_name')),
				'order_date' => date("Y-m-d"),
				'products' => serialize(Session::get('cart')),
				'sent' => 0
			));

			if (!$order_created){
				Session::put('msg',"Order skapades inte.");
				Redirect::to('shop.php?success=0');			
			}
				
			//Update items in stock for each purchased product
			foreach(Session::get('cart') as $id => $quantity){
				$product = Product::get($id);
				
				if ($product) {
					//Calculate new number of items in stock
					$new_number = $product->in_stock - $quantity;

					//Update number of items in stock for product
					if(!DB::getInstance()->update('products', $id, array('in_stock' => $new_number))->count()){
						Session::put('msg',"Order gjord. Det gick inte att ändra lagersaldo.");
						Redirect::to('shop.php?success=0');	
					}
				}			
			}
			//Delete cart after created order
			Session::delete('cart');
			
			//Show success message
			Redirect::to('order_success.php');	
		}
		
	}
	else{		
		if($admin){
			Session::put('msg','Du är admin och kan inte skapa ordrar.');
			$success = 0;
		}
		else if($items_in_cart == false){
			Session::put('msg','Din varukorg är tom.');
			$success = 0;
		}
		else if($customer_loggedin == false){
			Session::put('msg','Du behöver logga in för att kunna skapa ordrar.');
			$success = 0;
		}
		
		Redirect::to("shop.php?success=$success");		
	}	
?>