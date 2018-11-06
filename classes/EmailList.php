<?php 

class EmailList{

	public static function create($fields = array()){
		$db = DB::getInstance();

		if($db->insert('email_list', $fields)->count())
			return true;
		else 
			return false;
	}

	public static function getAll(){
		$emails = DB::getInstance()->query('SELECT * FROM email_list');

		if($emails->count())
			return $emails->results();	
		else
			return false;
	}	
}

?>