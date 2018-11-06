<?php
	include('includes/header.php');	
	
	if(Session::get('admin'))
	{		
		include('includes/products_admin.php');			
	}	
	else
		include('includes/products_customer.php');

	include('includes/footer.html');
?>

