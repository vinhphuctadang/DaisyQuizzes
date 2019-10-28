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
		$sql = "SELECT name, score FROM daisy_player_round, daisy_round_collection WHERE daisy_round_collection.round=daisy_player_round.round and access_token='$token' ORDER BY score DESC";
		$result = $conn->query ($sql);
		$list = [];
		while ($row = $result->fetch_assoc ()){
			$list[] = $row;
		}
		return $list;
	}
	
	function changeQuestion ($conn, $token, $increment) {
		// TODO: Fix injection error here (IMPORTANT)
		$sql = "UPDATE daisy_round_collection SET question_no=question_no+$increment WHERE access_token='$token'";
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
		$sql = "UPDATE daisy_round_collection SET status=$status, question_no=1 WHERE round='$round'";
		$conn->query ($sql);	
	}
	
	function getStatus ($conn, $token) {
		$sql = "SELECT status FROM daisy_round_collection WHERE access_token='$token'";
		$result = $conn->query ($sql);
		if ($result->num_rows == 0)
			return -1;
		$result = $result->fetch_assoc ();
		return $result['status'];
	}
	
	function getQuestionNumber ($conn, $token) {
		$sql = "SELECT question_no FROM daisy_round_collection WHERE access_token='$token'";
		$result = $conn->query ($sql);
		if ($result->num_rows == 0)
			return -1;
		$result = $result->fetch_assoc ();
		return $result['question_no'];
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
						
			case "change_question": 
				$err = checkRequiredParam ($request, ['token', 'change']);
				if ($err === "")
					$result = changeQuestion ($conn, $request['token'], $request['change']);
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