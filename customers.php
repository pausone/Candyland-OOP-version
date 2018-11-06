<?php
	include('includes/header.php');

	if(Session::get('admin')){		
		$html = file_get_contents('templates/customers_template.html');
		$html_pieces = explode('<!--==xxx==-->', $html);
		
		echo $html_pieces[0];
				
		$users = User::getAll();
		
		if ($users) {
			foreach($users as $user) {
				//Show users which are not admin	
				if($user->admin == 0){
					$tmp_html_piece = $html_pieces[1];

					echo Template::replaceKeys($tmp_html_piece, array(
							'---$username---' => $user->username,
							'---$first_name---' => $user->first_name,
							'---$last_name---' => $user->last_name,
							'---$adress---' => $user->adress,
							'---$postal---' => $user->postal,
							'---$city---' => $user->city
							));
				}				
			}
		}
		else
			echo "Kunder ej funna";
		
		echo $html_pieces[2];
		
		include('includes/footer.html');
	}
	else
		Redirect::to('index.php');

?>