<?php
	include('includes/header.php');
	
	if(Session::get('admin')){		
		$sales_sum = 0; 		//Sum of sales price of all sold items
		$bought_sum = 0;		//Sum of original price for all bought items (items sold + items in stock)
		$profit = 1;			//Sum of profit from sold items minus sum of price for all bought items
		$profit_sold_items = 0; //Profit from sold items(sales price minus original price)
		$in_stock_price = 0; 	//Sum original price for all items in stock		

		//Get orders info from database
		$orders = Order::getAll();
		
		if ($orders) {
			foreach($orders as $order) {
				$products = unserialize($order->products);

				//Get product info from database		
				foreach($products as $id => $quantity){

					$product = Product::get($id);					
					
					if ($product) {
						$sales_sum += $product->sales_price * $quantity;
						$bought_sum += $product->original_price * $quantity;
					}
					else
						echo "Produkt ej funnen";
				}
			}
		}
		else
			echo "Orders ej funna";	

		$profit_sold_items = $sales_sum - $bought_sum;
		
		//Get products info from database		
		$products = Product::getAll();					
		
		if ($products) {
			foreach($products as $product){
				$in_stock_price += $product->original_price * $product->in_stock;	
			}
		}
		else
			echo "Produkter ej funna";
			
		$bought_sum = $in_stock_price + $bought_sum; //Sum bying price of products in stock		
		$profit = $profit_sold_items - $bought_sum;

		$html = file_get_contents('templates/finance_template.html');

		//Print finance information
		echo Template::replaceKeys($html, array(
				'---$sales_sum---' => $sales_sum,	
				'---$bought_sum---' => $bought_sum,
				'---$profit---' => $profit,
				'---$in_stock_price---' => $in_stock_price,
				'---$profit_sold_items---' => $profit_sold_items
				));	
	
		include('includes/footer.html');	
	}
?>