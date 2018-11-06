<?php	
	include('includes/header.php');

	if(Session::get('admin')){		
		
		$html = file_get_contents('templates/view_orders_template.html');
		$html_pieces = explode('<!--==xxx==-->', $html);		

		//Get and print orders which are not sent
		$orders = Order::getAll();
		$sent = '';
		$not_sent = '';

		foreach ($orders as $order) {
			$status = $order->sent ? 'Ja' : 'Nej';
			$num = $order->sent ? 3 : 1;

			$modified_template = Template::replaceKeys($html_pieces[$num], array(
					'---$order_id---' => $order->id,
					'---$user_id---' => $order->user_id,
					'---$order_date---' => $order->order_date,
					'---$status---' => $status
					));

			if($order->sent){
				$sent .= $modified_template;
			}
			else{
				$not_sent .= $modified_template;
			}
		}

		$html_pieces[1] = $not_sent;
		$html_pieces[3] = $sent;

		echo $html_pieces[0] . $html_pieces[1] . $html_pieces[2] . $html_pieces[3] . $html_pieces[4];

		include('includes/footer.html');
	}
	else
		Redirect::to('index.php');		
?>