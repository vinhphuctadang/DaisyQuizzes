<?php
	include $_SERVER['DOCUMENT_ROOT'].'/DaisyQuizzes/database.php';

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
	
	function checkRequiredParam ($request, $params) {
		foreach ($params as $param) {
			if (!array_key_exists ($param, $request)) 
				return "ERR_".$param."_REQUIRED";
		}
		return "";
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
			default:
				$err = "ERR_UNSUPPORTED_METHOD";
				$success = false;
				break;
		}		
		$conn->close ();
		return formResp ($success, $result, $err);
	}	
	
	$result = control ($_POST);
	echo json_encode ($result);
?>