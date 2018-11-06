<?php	
	if(Session::get('admin'))
	{
		$name = '';
		$sales_price = ''; 
		$category = '';
		$original_price = '';
		$image_filename = '';
		$in_stock = '';

		$product_created = false;

		//If product form is filled and validated, create new product
		if(Input::exists() && Token::check(strip_tags(Input::get('token')))){

			$name = strip_tags(Input::get('product_name'));
			$sales_price = strip_tags(Input::get('sales_price')); 
			$category = strip_tags(Input::get('category'));
			$original_price = strip_tags(Input::get('original_price'));
			$image_filename = strip_tags(Input::get('image_filename'));
			$in_stock = strip_tags(Input::get('in_stock'));

			$validate = new Validate();

			$validation = $validate->check($_POST, array(
				'product_name' => array(
					'required' => true,
					'min' => 3,
					'max' => 16,
				),
				'sales_price' => array(
					'required' => true,
					'max' => 8,
					'digits' => true
				),
				'category' => array(
					'required' => true,
					'min' => 3,
					'max' => 16,
					'lettersOnly' => true
				),
				'original_price' => array(
					'required' => true,
					'max' => 8,
					'digits' => true
				),
				'image_filename' => array(
					'required' => true,
					'min' => 3,
					'max' => 30,
					'filename' => true,
				),
				'in_stock' => array(
					'required' => true,
					'max' => 16,
					'digits' => true
				)
			));	

			if($validate->passed()){
				$product_created = Product::create(array(
					'name' => $name,
					'sales_price' => $sales_price,
					'category' => $category,
					'original_price' => $original_price,
					'image_filename' => $image_filename,
					'in_stock' => $in_stock
					));

				if($product_created){
					echo 'Produkten är tillagd!';

					//Reset form data after product created
					$name = '';
					$sales_price = ''; 
					$category = '';
					$original_price = '';
					$image_filename = '';
					$in_stock = '';
				}
				else
					echo 'Det gick inte att lägga till en vara.';
			}
			else{
				foreach ($validate->errors() as $error)
					echo $error . '<br>';
			}			
		}

		$html = file_get_contents('templates/products_admin_template.html');
		$html_pieces = explode('<!--==xxx==-->', $html);

		//Show form for new product
		echo Template::replaceKeys($html_pieces[0], array(
			'---$name---' => escape($name),
			'---$sales_price---' => escape($sales_price),
			'---$category---' => escape($category),
			'---$original_price---' => escape($original_price),
			'---$image_filename---' => escape($image_filename),
			'---$in_stock---' => escape($in_stock),
			'---$token---' => Token::generate()
			));

		//Get products info from database
		$products = Product::getAll();

		//Show products
		if ($products) {
			foreach($products as $product){
				$tmp_html_piece = $html_pieces[1];

				echo Template::replaceKeys($tmp_html_piece, array(
						'---$id---' => $product->id,
						'---$name---' => $product->name,
						'---$sales_price---' => $product->sales_price,
						'---$category---' => $product->category,
						'---$image---' => $product->image_filename,
						'---$orig_price---' => $product->original_price,
						'---$filename---' => $product->image_filename,
						'---$in_stock---' => $product->in_stock						
						));			
			}
		} 
		else
			echo "0 results";
	}

?>