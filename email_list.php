<?php
	include('includes/header.php');
	
	if(Session::get('admin')){				
		$html = file_get_contents('templates/email_list_template.html');
		$html_pieces = explode('<!--==xxx==-->', $html);
		
		echo $html_pieces[0];				
		
		//Get emails from database
		$emails = EmailList::getAll();
		
		if ($emails) {
			foreach ($emails as $email) {
				$tmp_html_piece = $html_pieces[1];

				echo Template::replaceKeys($tmp_html_piece, array('---$email---' => $email->email));				
			}
		}
		else
			echo "Email ej funna";
		
		echo $html_pieces[2];
		
		include('includes/footer.html');
	}
	else
		Redirect::to('index.php');

?>