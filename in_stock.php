<?php	
	include('includes/header.php');

	if(Session::get('admin')){		
		if(Input::exists() && Token::check(strip_tags(Input::get('token')))){
			$id = Input::get('id');
			$add_quantity = strip_tags(Input::get('add_quantity'));
			$product = Product::get($id);
			
			if($id && $product && $add_quantity > 0){			
				$new_number = $product->in_stock + $add_quantity;

				//Update number of items in stock for product
				if(!DB::getInstance()->update('products', $id, array('in_stock' => $new_number))->count())
					echo "Det gick inte att ändra lagersaldo.";
			}
		}

		$html = file_get_contents('templates/in_stock_template.html');
		$html_pieces = explode('<!--==xxx==-->', $html);				

		echo $html_pieces[0];

		$products = Product::getAll();
		$token = Token::generate();			

		//Show products
		if ($products) {
			foreach($products as $product) {
				$tmp_html_piece = $html_pieces[1];

				echo Template::replaceKeys($tmp_html_piece, array(
					'---$id---' => $product->id,
					'---$token---' => $token,
					'---$name---' => $product->name,
					'---$in_stock---' => $product->in_stock
					));
			}
		} 
		else 
			echo "0 results";
		
		echo $html_pieces[2];

		include('includes/footer.html');
	}
	else
		Redirect::to('index.php');		
?>