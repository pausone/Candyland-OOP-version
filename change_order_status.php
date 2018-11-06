<?php
	require_once('core/init.php');

	if(Session::get('admin') && Input::exists('get')){
		$id = Input::get('order');

		//Change order status
		if($id && Order::changeSentStatus($id))
			Redirect::to("order_info.php?order=$id");	
		else
			echo 'Kunde inte ändra status på order';	
	}
	else
		Redirect::to('index.php');		
?>