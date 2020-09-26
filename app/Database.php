<?php

Class Database
{ 
	private $_connection;
	private static $_instance;
	private $_host = DB_HOST;
	private $_username = DB_USER;
	private $_password = DB_PASS;
	private $_database = DB_NAME; 

	private function __construct() 
	{
		$this->_connection = new mysqli($this->_host, $this->_username,$this->_password, $this->_database);
		if(mysqli_connect_error()) trigger_error("Failed to conencto to MySQL: " . mysql_connect_error(),E_USER_ERROR); 
	}
 
	public static function getInstance() 
	{
		if(!self::$_instance)  self::$_instance = new self(); 
		return self::$_instance;
	}
   
	private function __clone() { } 

	public function getConnection() 
	{
		return $this->_connection;
	}
	  
}
?>