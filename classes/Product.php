<?php 

class Product{

	public static function create($fields = array()){
		if(DB::getInstance()->insert('products', $fields)->count())
			return true;
		else 
			return false;
	}

	public static function getAll(){
		$products = DB::getInstance()->query('SELECT * FROM products');

		if($products->count())
			return $products->results();	
		else
			return false;
	}	

	public static function get($id){
		$product = DB::getInstance()->get('products', array('id', '=', $id));

		if($product->count())
			return $product->first();	
		else
			return false;
	}	

	public static function getInStock($id){
		$in_stock = 0;
		$product = DB::getInstance()->get('products', array('id', '=', $id));

		if($product->count())
			return $product->first()->in_stock;	
		else
			return false;
	}
}

?>