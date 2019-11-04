<?php
	include $_SERVER['DOCUMENT_ROOT'].'/database.php';

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
	function findLoggedPlayer ($conn, $token) {		
		$sql = "SELECT name, score FROM daisy_player_round, daisy_round WHERE daisy_round.round=daisy_player_round.round and access_token='$token' ORDER BY score DESC";
		$result = $conn->query ($sql);
		$list = [];
		while ($row = $result->fetch_assoc ()){
			$list[] = $row;
		}
		return $list;
	}
	
	function changeQuestion ($conn, $token, $increment, $time) {
		// TODO: Fix injection error here (IMPORTANT)
		$sql = "UPDATE daisy_round SET question_no=question_no+$increment, next_timestamp=TIMESTAMP (CURRENT_TIMESTAMP()+10) WHERE access_token='$token'";
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
	
	function getStatus ($conn, $token) {
		$sql = "SELECT status FROM daisy_round WHERE access_token='$token'";
		$result = $conn->query ($sql);
		if ($result->num_rows == 0)
			return -1;
		$result = $result->fetch_assoc ();
		return $result['status'];
	}
	
	function getQuestionNumber ($conn, $token) {
		$sql = "SELECT question_no FROM daisy_round WHERE access_token='$token'";
		$result = $conn->query ($sql);
		if ($result->num_rows == 0)
			return -1;
		$result = $result->fetch_assoc ();
		return $result['question_no'];
	}

	function getQuestionBody ($conn, $token) {
		$sql = "SELECT status, question_no FROM daisy_round where access_token='$token'";
		$result = $conn->query($sql);
		$assoc = $result->fetch_assoc();
		$status = $assoc['status'];

		if ($status == 0)
			return "ERR_ROUND_CLOSED";
		if ($status == 1) {
			return "ERR_ROUND_IS_WAITING";
		}

		$question_no =  $assoc['question_no'];
		$sql = "SELECT id, body, choice_a, choice_b, choice_c, choice_d, next_timestamp FROM daisy_shuffle_content, daisy_question, daisy_round WHERE question_id = id AND question_no=" . $question_no;
		$result = $conn->query ($sql);
		if ($result->num_rows == 0)
			return -1;
		$result = $result->fetch_assoc ();
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
				break;
			case "get_status":
				$err = checkRequiredParam ($request, ['token']);				
				if ($err === "")
					$result = getStatus ($conn, $request['token']);				
				else 
					$success = false;
				break;
			
			case "get_question_no":
				$err = checkRequiredParam ($request, ['token']);		
				if ($err === "")
					$result = getQuestionNumber ($conn, $request['token']);				
				else 
					$success = false;
				break;
			case "get_question_body":
				$err = checkRequiredParam ($request, ['token']);		
				if ($err === "")
					$result = getQuestionBody ($conn, $request['token']);				
				else 
					$success = false;
				break;
						
			case "change_question": 
				$err = checkRequiredParam ($request, ['token', 'change', 'nextupdate']);
				if ($err === "")
					$result = changeQuestion ($conn, $request['token'], $request['change'], $request['nextupdate']);
				else 
					$success = false;				
				break;
			
			default:
				$err = "ERR_UNSUPPORTED_METHOD";
				$success = false;
				break;
		}		
		$conn->close ();
		return formResp ($success, $result, $err);
	}	
	
	$result = control ($_GET);
	echo json_encode ($result);
?>