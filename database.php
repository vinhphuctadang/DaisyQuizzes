<?php
	function db_connect () {
		$servername = "localhost";
		$username = "root";
		$password = "";
		$database = "daisybeaver";
		// Create connection
		$conn = new mysqli($servername, $username, $password, $database);

		// Check connection
		if ($conn->connect_error) {
			exit(json_encode (formResp (false, "Connection failed: " . $conn->connect_error)));
		}				
		
		$conn->set_charset ("utf8");
		return $conn;	
	}
	
	function db_token ($string, $method) { // method sẽ được sử dụng sau
		$apikey = 'daisy2610';
		return md5 ($string.$apikey);
	}
	
	function serverpath ($dir){
		return $_SERVER['DOCUMENT_ROOT'].'/DaisyQuizzes/'.$dir;
	}
	
	function path ($dir){
		$str = '/DaisyQuizzes/'.$dir;
		return $str;
	}
	
	function assets ($dir) {
		return path ('assets/'.$dir);
	}
?>