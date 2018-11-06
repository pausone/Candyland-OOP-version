<?php
	include('includes/header.php');
	
	$message_html = file_get_contents('templates/message_template.html');	
	echo Template::replaceKeys($message_html, array('---$message---' => 'Order skapad!'));				
	
	include('includes/footer.html');
?>