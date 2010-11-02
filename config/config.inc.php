<?php
	defined( 'uservice' ) or die( 'You should not see this.' );

	define('DB_HOST', 'localhost');
	define('DB_USER', 'root');
	define('DB_PASSWD', 'jack');
	define('DB_NAME', 'uservice');

	function get_salt() {
		$length = 10;
		$characters = ’0123456789abcdefghijklmnopqrstuvwxyz’;
		$salt = ”;    
		for ($p = 0; $p < $length; $p++) {
			$salt .= $characters[mt_rand(0, strlen($characters))];
		}
		return $salt;
	}
?>
