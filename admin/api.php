<?php
	include $_SERVER['DOCUMENT_ROOT'].'/database.php';
	include serverpath ('middleware/auth_admin.php');
	// Dành cho đội ngũ phát triển sử dụng
	// sử dụng api để lấy dữ liệu từ vòng chơi (admin)

	function formResp ($success, $result, $error) {
		$json = [];
		$json['success'] = $success;
		if ($result != null)
			$json['result'] = $result;
		if ($error!=null)
			$json['error'] = $error;
		return $json;
	}

	function renderQuestion ($question)
	{
		// should render NULL (ERROR) QUESTION

		echo "<h1>Câu hỏi</h1>";
		echo "<p>" . $question['body'] . "</p>";
		$val = ['a', 'b', 'c', 'd'];
		
		shuffle($val);
		foreach ($val as $c) {
			echo "<input type='submit' name='choice' value='" . $question['choice_' . $c] . "'>" . "</input> <br>";
		}
		echo "<input type='hidden' name='question' value=" . $question['id'] . ">";
		echo "<input type='hidden' name='next_timestamp' value='" . $question['next_timestamp'] . "'>" . "</input> <br>";
	}

	/*
		Tất cả các hàm sau đều là các hàm chức năng
	*/	

	function findLoggedPlayer ($conn, $token) {		
		$sql = "SELECT name, score FROM daisy_player_round, daisy_round WHERE daisy_round.round=daisy_player_round.round and access_token='$token' ORDER BY score DESC";
		$result = $conn->query ($sql);
		$list = [];
		while ($row = $result->fetch_assoc ()){
			$list[] = $row;
		}
		return $list;
	}

	function getStatus ($conn, $token) {
		$sql = "SELECT status FROM daisy_round WHERE access_token='$token'";
		$result = $conn->query ($sql);
		if ($result->num_rows == 0)
			return -1;
		$result = $result->fetch_assoc ();
		return $result['status'];
	}
	

	function changeQuestion ($conn, $token, $increment, $time) { 
		// TODO: Fix injection error here (IMPORTANT)
		if (getStatus ($conn, $token) == '0'){
			return "ERR_ROUND_STILL_CLOSE ".getStatus ($conn, $token);
		}

		$sql = "UPDATE daisy_round SET question_no=question_no+$increment, next_timestamp=TIMESTAMP (CURRENT_TIMESTAMP()+$time) WHERE access_token='$token'";
		$conn->query ($sql);
		return "success";
	}
	
	function checkRequiredParam ($request, $params) {
		foreach ($params as $param) {
			if (!array_key_exists ($param, $request)) 
				return "ERR_".$param."_REQUIRED";
		}
		return "";
	}
	
	function setStatus ($conn, $round, $status) {
		$sql = "UPDATE daisy_round SET status=$status, question_no=1 WHERE round='$round'";
		$conn->query ($sql);	
	}
	
	function getQuestionNumber ($conn, $token) {
		$sql = "SELECT question_no FROM daisy_round WHERE access_token='$token'";
		$result = $conn->query ($sql);
		if ($result->num_rows == 0)
			return -1;
		$result = $result->fetch_assoc ();
		return $result['question_no'];
	}

	function getQuestionBody ($conn, $token) { //để javascript truy cập từ bên ngoài

		if ($token === "") 
			if (!isset($_SESSION['round']))
				return "ERR_NOT_LOGGED_IN";
		if ($token === "") {
			$round = $_SESSION['round'];
			$sql = "SELECT status, question_no, next_timestamp FROM daisy_round where round='$round'";
		} else {
			$sql = "SELECT status, question_no, next_timestamp FROM daisy_round where access_token='$token'";
		}
		
		$result = $conn->query($sql);
		$assoc = $result->fetch_assoc();
		$status = $assoc['status'];
		$next_timestamp = $assoc['next_timestamp'];

		if ($status == 0)
			return "ERR_ROUND_CLOSED";
		// if ($status == 1) {
		// 	return "ERR_ROUND_IS_WAITING";
		// }

		$question_no =  $assoc['question_no'];
		$sql = "SELECT id, body, choice_a, choice_b, choice_c, choice_d FROM daisy_shuffle_content, daisy_question WHERE daisy_shuffle_content.question_id = daisy_question.id AND daisy_shuffle_content.question_no=$question_no";
		// echo $sql;
		// echo $sql;
		$result = $conn->query ($sql);
		if ($result->num_rows == 0)
			return -1;
		$result = $result->fetch_assoc ();
		$result["question_no"] = $question_no;
		$result["next_timestamp"] = $next_timestamp;
		return $result;
	}

	function control ($request) {
		$result = [];
		if (!array_key_exists ('method', $request)) {
			return formResp (false, null, 'ERR_NO_METHOD');
		}
		$conn = db_connect ();
		$success = true;
		$result = null;
		$err = null;
		switch ($request['method']) {
			case "get_player":
				$err = checkRequiredParam ($request, ['token']);
				if ($err === "")
					$result = findLoggedPlayer ($conn, $request['token']);				
				else 
					$success = false;
				return formResp ($success, $result, $err);
				break;
			case "get_status":
				$err = checkRequiredParam ($request, ['token']);				
				if ($err === "")
					$result = getStatus ($conn, $request['token']);				
				else 
					$success = false;
				return formResp ($success, $result, $err);
				break;
			
			case "get_question_no":
				$err = checkRequiredParam ($request, ['token']);		
				if ($err === "")
					$result = getQuestionNumber ($conn, $request['token']);				
				else 
					$success = false;
				return formResp ($success, $result, $err);
				break;
			case "get_question_body":
				$err = checkRequiredParam ($request, []);
				$token = "";	
				if (array_key_exists("token", $request)) $token = $request["token"];
				if ($err === "") {
					$result = getQuestionBody ($conn, $token);				
					renderQuestion($result);
				}
				else 
					$success = false;
				return "";
				break;
						
			case "change_question": 
				$err = checkRequiredParam ($request, ['token', 'change', 'nextupdate']);
				if ($err === "")
					$result = changeQuestion ($conn, $request['token'], $request['change'], $request['nextupdate']);
				else 
					$success = false;				
				return formResp ($success, $result, $err);
				break;
			
			default:
				$err = "ERR_UNSUPPORTED_METHOD";
				$success = false;
				return formResp ($success, $result, $err);
				break;
		}				
		$conn->close ();
	}	
	
	$result = control ($_GET);
	if ($result != "")
		echo json_encode ($result);
?>