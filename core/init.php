<?php 

session_start();

//Make config array into a global
$GLOBALS['config'] = array(
	'mysql' => array(
		'host' => 'TBD',
		'username' => 'TBD',
		'password' => 'TBD',
		'db' => 'candyland'
	),
	'remember' => array(
		'cookie_name' => 'hash',
		'cookie_expiry' => 86400 * 30
	),
	'session' => array(
		'session_name' => 'user',
		'token_name' => 'token'
	),
);

//Autoload classes on demand with specified function
spl_autoload_register(function($class){
	require_once 'classes/' . $class . '.php';
});

require_once 'functions/sanitize.php';

?>