<?php
	require_once(dirname(__FILE__)."/../configs/config.inc.php");

	function get_db() {
		$db = new mysqli(DB_HOST, DB_USER, DB_PASSWD, DB_NAME);
		if (mysqli_connect_errno()) { 
			printf("Connect failed: %s\n", mysqli_connect_error()); 
			exit(); 
		} 
		return $db;	
	}
?>
