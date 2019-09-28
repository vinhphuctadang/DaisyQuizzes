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
	
?>