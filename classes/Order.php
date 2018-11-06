<?php 

class Order{

	public static function create($fields = array()){
		if(DB::getInstance()->insert('orders', $fields)->count())
			return true;
		else 
			return false;
	}

	public static function getAll(){
		$orders = DB::getInstance()->query('SELECT * FROM orders');

		if($orders->count())
			return $orders->results();	
		else
			return false;
	}	

	public static function get($id){
		$order = DB::getInstance()->get('orders', array('id', '=', $id));

		if($order->count())
			return $order->first();	
		else
			return false;
	}

	public static function changeSentStatus($id){
		$order = self::get($id);

		if($order && DB::getInstance()->update('orders', $id, array('sent' => !$order->sent))->count())
			return true;
		else 
			return false;			
	}				
}

?>