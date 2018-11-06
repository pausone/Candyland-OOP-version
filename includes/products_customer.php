<?php	
	if(!Session::exists('admin') || !Session::get('admin'))
	{	
		$message_for_id = '';
		$msg = '';
		$success = '';	

		if(Input::exists('get')){
			$success = Input::get('success');
			if($success != ''){
				$msg = Session::get('msg');
				$message_for_id = Session::get('item_id');
			}
		}
		
		$products = Product::getAll();
		
		$html = file_get_contents('templates/products_customer_template.html');
		
		//Show products
		if($products){
			foreach($products as $product){
				$html_pieces = explode('<!--==xxx==-->', $html);

				$html_pieces[0] = Template::replaceKeys($html_pieces[0], array(
						'---$image---' => $product->image_filename,
						'---$id---' => $product->id,
						'---$name---' => $product->name,
						'---$sales_price---' => $product->sales_price,
						'---$category---' => $product->category,
						'---$in_stock---' => $product->in_stock
						));

				//Check stock
				if($product->in_stock > 0){
					//Check wanted items compared to in stock					
					if(Cart::productItemsCount($product->id) < $product->in_stock){
						$item_msg = '';

						if($message_for_id)
							if($product->id == $message_for_id)
								$item_msg = $msg;							

						$html_pieces[1] = Template::replaceKeys($html_pieces[1], array(
							'---$id---' => $product->id,
							'---$message---' => $item_msg,
							));
					}
					else
						$html_pieces[1] = "Du kan tyvärr inte handla fler pga lagersaldot.";
				}
				else
					$html_pieces[1] = "Slut i lager";
				
				//Show products
				echo $html_pieces[0] . $html_pieces[1] . $html_pieces[2];
			}
		} 
		else 
			echo "0 results";
	}
?>