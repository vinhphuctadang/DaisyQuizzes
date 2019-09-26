<?php
	
	function connect () {
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
	
	function db_fetch_question ($conn, $round) {
		$sql = "SELECT status, question_no FROM daisy_round_collection where round='$round'";
		$result = $conn->query ($sql);			
		$assoc = $result->fetch_assoc ();
		$status = $assoc ['status'];
		
		if ($status == 0)
			die ("This round is now closed or not exists");
		$question_no =  $assoc ['question_no'];	
		$sql = "SELECT choice_a FROM daisy_shuffle_content, daisy_question WHERE question_id = id AND question_no=".$question_no;
		$result = $conn->query ($sql);
		$question = $result->fetch_assoc ();
		return $question;
	}
	
	function respondCorrect () {
		
	}
	
	
	$conn = connect ();
	$round = $_POST ['round'];
	$answer = db_fetch_question ($conn, $round);
	
	if ($answer ['choice_a'] == $_POST['choice']) 
		echo "Câu trả lời đúng";
	else 
		echo "Câu trả lời sai";
	$conn->close ();
?>