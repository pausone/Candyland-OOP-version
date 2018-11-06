<?php 

class Redirect{
	
	public static function to($location = null){
		if($location){
			if(is_numeric($location)){
				switch ($location) {
					case 404:	//Page can not be found
						header('HTTP/1.0 404 Not Found');
						include 'includes/errors/404.php';
						exit();
						break;					
					default:
						header('HTTP/1.0 404 Not Found');
						include 'includes/errors/404.php';
						exit();
						break;
				}
			}

			header('location: '. $location);
			exit();
		}
	}	
}

?>