<?php

class DB{	

	private static $instance = null; 
	private $pdo,			//store PDO object
			$query, 		//store PDOStatement object from $this->pdo->prepare($sql) (http://php.net/manual/en/class.pdostatement.php)
			$error = false, //indicates that query was executed no matter the result
			$results, 		//store our result set using $this->query->fetchAll(PDO::FETCH_OBJ)
			$count = 0;		//number of rows affected by DELETE, INSERT or UPDATE. Number of result rows from SELECT.

	
	protected function __construct(){
		try {
			$this->pdo = new PDO(
								'mysql:host='. Config::get('mysql/host') .';dbname='. Config::get('mysql/db'), 
								Config::get('mysql/username'), 
								Config::get('mysql/password')
							);
		}catch (PDOException $e) {
			die($e->getMessage());			
		}
	}

	public static function getInstance(){
		if (!isset(self::$instance)) { //If used twice on a page it will not reconnect to DB because connection is stored in $instance
			self::$instance = new DB();
		}
		return self::$instance;
	}

	public function query($sql, $params = array()){
		$this->error = false;
		if($this->query = $this->pdo->prepare($sql)){
			$x = 1;
			if(count($params)){
				foreach($params as $param){
					$this->query->bindValue($x, $param); //If x = 1, assign $param to first "?"	etc			
					$x++;
				}
			}
		}

		if($this->query->execute()){
			$this->results = $this->query->fetchAll(PDO::FETCH_OBJ);

			$this->count = $this->query->rowCount();
		
			//In case rowCount() does not work for SELECT in current implementation
			if($this->results){
				$this->count = count($this->results);
			}

		}
		else
			$this->error = true;

		return $this;
	}



	public function action($action, $table, $where = array()){
		if(count($where) === 3){
			$operators = array('<', '>', '>=', '<=', '=');

			$field = $where[0];
			$operator = $where[1];
			$value = $where[2];

			if(in_array($operator, $operators)){
				$sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";

				return $this->query($sql, array($value));
			}
			else
				$this->error = true;

			return $this;
		}

		return $this->query($sql, array($value));
	}

	public function get($table, $where){
		return $this->action('SELECT *', $table, $where);
	}

	public function delete($table, $where){
		return $this->action('DELETE', $table, $where);
	}

	public function insert($table, $fields = array()){
		$keys = array_keys($fields);

		$values = '';
		$x = 1;

		foreach($fields as $field){
			$values .= '?' ;
			if($x < count($fields)){
				$values .= ', ' ;
			}
			$x++;
		}

		$sql = "INSERT into {$table} (" . implode(', ', $keys) . ") values ({$values})";

		return $this->query($sql, $fields);
	}	

	public function update($table, $id, $fields = array()){
		$set = '';
		$x = 1;

		foreach($fields as $key => $value){
			$set .= "{$key} = ?";

			if($x < count($fields)){
				$set .= ', ' ;
			}
			$x++;
		}

		$sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";
		return $this->query($sql, $fields);
	}	

	public function results(){
		return $this->results;
	}

	public function first(){
		return $this->results()[0];
	}
	
	public function count(){
		return $this->count;
	}

	public function error(){
		return $this->error;
	}
}

?>