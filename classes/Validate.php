<?php 

//Tutorial: https://www.youtube.com/watch?v=rWon2iC-cQ0&index=11&list=PLfdtiltiRHWF5Rhuk7k4UAU1_yLAZzhWc

/**
* Validate 
*/
class Validate
{
	private $_passed = false,
			$_errors = array(),
			$_db = null,
			$_reg_exp = array(
				'pwdCheck' => array('/[A-Z]+/', '/[a-z]+/', '/[\d\W]+/', '/\S{7,}/'),
				'lettersOnly' => '/[a-zA-ZäöüßÄÖÜ]/',
				'post_number' => '/^\d{3} \d{2}$/',
				'filename' => '/^[\w,\s-]+\.(png|jpg|gif|bmp|jpeg|PNG|JPG|GIF|BMP)$/',
				'email' => '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,10})$/'
			);

	public function __construct(){
		$this->_db = DB::getInstance();
	}

	public function check($source, $items = array()){
		foreach ($items as $item => $rules) {
			foreach ($rules as $rule => $rule_value) {
				
				$value = trim($source[$item]);

				if($rule === 'required' && $rule_value && empty($value))
					$this->addError("{$item}: Fyll i fältet");
				else if($rule === 'min' && strlen($value) < $rule_value)
					$this->addError("{$item}: Minst {$rule_value} tecken i fältet ");
				else if($rule === 'max' && strlen($value) > $rule_value)
					$this->addError("{$item}: Max {$rule_value} tecken i fältet");
				else if($rule === 'pwdCheck'){
					foreach ($this->_reg_exp['pwdCheck'] as $regExp) {
						if(!preg_match($regExp, $value))
							$this->addError("{$regExp}: {$value} måste innehålla minst en stor och liten bokstav samt en siffra.");
					}						
				}
				else if($rule === 'unique'){
					$check = $this->_db->get($rule_value, array($item, '=', $value));	
					
					if ($check->count())
						$this->addError("{$value} är ej unikt.");												
				}
				else if($rule === 'lettersOnly'){
					if(!preg_match($this->_reg_exp['lettersOnly'], $value))
						$this->addError("{$value} får endast innehålla bokstäver.");						
				}
				else if($rule === 'post_number'){
					if(!preg_match($this->_reg_exp['post_number'], $value))
						$this->addError("{$value} är inte ett giligt postnummer.");					
				}
				else if($rule === 'filename'){
					if(!preg_match($this->_reg_exp['filename'], $value))
						$this->addError("{$value} är inte ett giligt filnamn.");						
				}
				else if($rule === 'digits'){
					if(!is_numeric($value))
						$this->addError("{$value} är inte en siffra.");						
				}
				else if($rule === 'email'){
					if(!preg_match($this->_reg_exp['email'], $value))
						$this->addError("{$value} är inte ett giltigt email.");						
				}
			}
		}

		if (empty($this->_errors)) {
			$this->_passed = true;
		}
		
		return $this;
	}

	public function passed(){
		return $this->_passed;
	}

	public function errors(){
		return $this->_errors;
	}

	public function addError($error){
		$this->_errors[] = $error;
	}

	public function isCustomer(){		
		$admin = false;	
		$customer_not_loggedin = false;
		$customer_loggedin = false;
		$customer = false;
		
		if(Session::exists('admin')){
			if(Session::get('admin'))
				$admin = true;
			else
				$customer_loggedin = true;
		}
		else
			$customer_not_loggedin = true;
		
		if(!$admin && ($customer_loggedin || $customer_not_loggedin))
		{
			$customer =  true;			
		}	
		
		return $customer;	
	}

	public static function isAdmin(){		
		if(Session::exists('admin')){
			if(Session::get('admin'))
				return true;				
		}
		
		return false;	
	}

	public static function isCustomerLoggedIn(){		
		$customer_loggedin = false;
		
		if(Session::exists(Config::get('session/session_name'))){
			if(!Session::get('admin'))
				$customer_loggedin = true;				
		}
		
		return $customer_loggedin;	
	}

	public static function isNotLoggedIn(){		
		$loggedIn = false;	
		
		if(Session::exists(Config::get('session/session_name'))){
			$loggedIn = true;	
		}
		
		return $loggedIn;	
	}
}

?>