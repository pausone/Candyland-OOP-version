<?php 

class Cart{

	public static function add($itemid){
		$itemquantity = 1;
		$cart = Session::get('cart');
		
		if($cart){
			if(isset($cart[$itemid])){					
				$cart[$itemid]++;
				Session::put('cart', $cart);
			}
			else{
				$cart[$itemid] = $itemquantity;
			}
		}
		else{
			$cart = array();			
			$cart[$itemid] = $itemquantity;			
		}

		Session::put('cart', $cart);

		return true;					
	}

	public static function subtract($itemid){
		$cart = Session::get('cart');

		//Remove item if exists. The product id is kept even if items are zero in case customer wants to add to it again in register		
		if(isset($cart[$itemid]) && $cart[$itemid] > 0){
			$cart[$itemid]--;
			Session::put('cart', $cart);
			return true;	
		}
		else
			return false;
		
					
	}

	public static function delete($itemid){
		$cart = Session::get('cart');
		$success = false;

		if(isset($cart[$itemid])){
			unset($cart[$itemid]);	
			$success = true;	
		}

		//Delete cart if empty
		if(count($cart) == 0)
			Session::delete('cart');
		else
			Session::put('cart', $cart);

		return $success;	
	}

	public static function cartHasItems(){		
		$items_in_cart = false;		
		
		if(Session::exists('cart')){
			if(count(Session::get('cart')) > 0)
				$items_in_cart = true;
			else	
				Session::delete('cart');
		}
		
		return $items_in_cart;	
	}

	//Get number of items of product in cart
	public static function productItemsCount($id){
		$productItems = 0;
		$cart = Session::get('cart');
		
		if(isset($cart[$id])){
			$productItems = $cart[$id];
		}
		
		return $productItems;	
	}
}

?>