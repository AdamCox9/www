<?php

// base class with member properties and methods
class DatabaseConnection {

   var $conn;

	function __construct() 
	{
		
		$this->conn = mysqli_connect($GLOBALS['db_host'], $GLOBALS['db_user'], $GLOBALS['db_pass'],$GLOBALS['db_name']);

		if (!$this->conn) {
			error_log('Could not connect: ' . mysql_error(), 0 );
		}

	}

	function __destruct()
	{
		//Close $this->conn
		mysqli_close($this->conn);
	}

	function getConn()
	{
		return $this->conn;
	}

}
 
?>