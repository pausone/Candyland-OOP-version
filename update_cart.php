<?php
	require_once('core/init.php');

	$success = 0;

	Session::delete('item_id');
	Session::delete('msg');
	
	if(Input::exists('get') && Validate::isCustomer()){
		$id = Input::get('id');

		switch (Input::get('action')){
			case 'add':
				//Check if enough in stock
				if(Product::getInStock($id) > Cart::productItemsCount($id)){
					if(Cart::add($id)){
						$success = 1;
						Session::put('msg','Ökning lyckades');
					}
					else{
						Session::put('msg','Ökning misslyckades');
					}
				}
				else{
					Session::put('msg','Ej tillräcklig i lagersaldot.');
				}
				break;
			case 'subtract':
				if(Cart::subtract($id)){
					$success = 1;
					Session::put('msg','Minskning lyckades');
				}
				else{
					Session::put('msg','Minskning misslyckades');
				}
				break;
			case 'delete':
				if(Cart::delete($id)){
					$success = 1;
					Session::put('msg','Borttagning lyckades');
				}
				else{
					Session::put('msg','Borttagning misslyckades');
				}
				break;		
			default:
				Session::put('msg', 'Uppdatering misslyckades.');
				break;
		}

		Session::put('item_id', $id);

		if(Input::get('redirect'))
			Redirect::to(Input::get('redirect') . '?success=' . $success);
		else
			Redirect::to('index.php');	
	}
	else{
		echo 'Admin kan inte lägga till vara i varukorgen';
	}
		


?>