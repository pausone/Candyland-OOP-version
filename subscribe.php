<?php

	include('includes/header.php');

	if(Input::exists() && Token::check(strip_tags(Input::get('token')))){
		$validate = new Validate();

		$validation = $validate->check($_POST, array(
			'email' => array(
				'required' => true,
				'max' => 40,
				'email' => true
			)
		));		

		if($validate->passed()){
			if(EmailList::create(array('email' => Input::get('email'))))			
				echo "Epost tillagd.";
			else
				echo "Det gick inte att lägga till en ny epost.";
		}
		else{
			foreach ($validate->errors() as $error)
				echo $error . '<br>';
		}	
	}

	$html = file_get_contents('templates/subscribe_form.html');

	//Show form for new product
	echo Template::replaceKeys($html, array(
		'---$token---' => Token::generate()
		));

	include('includes/footer.html');
?>