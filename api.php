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

	function __render($question){

		// echo json_encode ($question);
		if ($question == "ERR_NOT_LOGGED_IN"){
			echo "<p> Vui lòng đăng nhập vào vòng chơi </p>";
			return;
		}
		if ($question == "ERR_ROUND_CLOSED"){
			echo "<p> Vòng chơi đã kết thúc, có thể bạn cần quay về <a href='/'>trang chủ </a></p>";
			return;
		}

		if ($question == "ERR_ROUND_IS_WAITING") {
			echo "<p>Vòng chơi đang chờ đợi để bắt đầu</p>";
			return;
		}

		$round = $_SESSION['round'];
		$token = $_SESSION['token'];		
		?>
		<div id="wrapper" class="main-container">
			<form action='check.php' method='post'>
				<h1>Câu hỏi</h1>
				<p><?php echo $question['body'] ?></p>
				<div class="group-answer">
					<?php
						$val = ['a', 'b', 'c', 'd'];
						shuffle($val);
						foreach ($val as $c) {
							?>
						<div class="mdc-text-field mdc-text-field--outlined" data-mdc-auto-init="MDCTextField">
							<input readonly class="mdc-text-field__input" id="text-field-hero-input" onClick="onChoiceClick (this.value)" type='button' name='choice' value="<?php echo $question['choice_' . $c] ?>">
							<div class="mdc-notched-outline mdc-notched-outline--no-label">
								<div class="mdc-notched-outline__leading"></div>
								<div class="mdc-notched-outline__notch">
									<label for="text-field-hero-input" class="mdc-floating-label"></label>
								</div>
								<div class="mdc-notched-outline__trailing"></div>
							</div>
						</div>
					<?php
							// echo "<input type='submit' name='choice' value='" . $question['choice_' . $c] . "'>" . "</input> <br>";
						}
						?>
				</div>
				<input type='hidden' name='question' value='<?php echo $question['id'] ?>' />
				<input type='hidden' name='round' value='<?php echo $round ?>'> <br>
				<input type='hidden' name='token' value='<?php echo $token ?>'> <br>				
			</form>
		</div>
	<?php
	}

	function renderQuestion ($question)
	{
		// should render NULL (ERROR) QUESTION

		if ($question == "ERR_NOT_LOGGED_IN"){
			echo $question;
			return;
		}

		// echo json_encode($question);
		echo "<h1>Câu hỏi</h1>";
		echo "<p>" . $question['body'] . "</p>";
		$val = ['a', 'b', 'c', 'd'];
		
		shuffle($val);
		foreach ($val as $c) {
			echo "<input type='button' name='choice' value='" . $question['choice_' . $c] . "'>" . "</input> <br>";
		}
		echo "<input type='hidden' name='question' value=" . $question['id'] . ">";
		
		
	}

	/*
		Tất cả các hàm sau đều là các hàm chức năng
	*/	

	// Trả về (danh sách) người chơi trong vòng đó ($name là tùy chọn, nếu $name = "", hàm trả về danh sách tất cả người chơi tương ứng vòng đó)
	// Lưu ý: name là token của người chơi
	// Lỗi: Chưa sửa injection

	function findLoggedPlayer ($conn, $token, $name) {

		$addition = "";
		if ($name != "")
			$addition = " AND daisy_player_round.token='$name'";
		$sql = "SELECT name, score FROM daisy_player_round, daisy_round WHERE daisy_round.round=daisy_player_round.round and access_token='$token' $addition ORDER BY score DESC";
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
	
	function getQuestionNumber ($conn, $token) {
		$sql = "SELECT question_no FROM daisy_round WHERE access_token='$token'";
		$result = $conn->query ($sql);
		if ($result->num_rows == 0)
			return -1;
		$result = $result->fetch_assoc ();
		return $result['question_no'];
	}

	function changeQuestion ($conn, $token, $increment, $time) { 
		// TODO: Fix injection error here (IMPORTANT)
		if (getStatus ($conn, $token) == '0'){
			return "ERR_ROUND_STILL_CLOSE ".getStatus ($conn, $token);
		}


		$sql = "SELECT MAX(question_no) AS mx FROM daisy_shuffle_content";
		$result = $conn->query ($sql);
		$maxNumber = $result->fetch_assoc ()['mx'];
		$questionNumber = getQuestionNumber ($conn, $token);

		if ($questionNumber + $increment > $maxNumber) 
			return "ERR_EXCEED";
		

		$sql = "UPDATE daisy_round SET question_no=question_no+$increment WHERE access_token='$token'";
		$conn->query ($sql);

		$sql = "SELECT round FROM daisy_round WHERE access_token='$token'";
		$result = $conn->query ($sql);
		$value = $result->fetch_assoc ();
		$round = $value['round'];
		// TODO: Send NodeJS request to notify all clients about that
		$NODEJS_HOST_SERVER = $GLOBALS["NODEJS_HOST_SERVER"];
		// TODO: Security measure: AUTHORIZATION PROCESS NEEDED
		file_get_contents ($NODEJS_HOST_SERVER.'/notify/'.$round."/".$time); 
		return "success";
	}

	function notifyRoundFinish ($conn, $token) {
		$sql = "SELECT round FROM daisy_round WHERE access_token='$token'";
		$result = $conn->query ($sql);
		$row = $result->fetch_assoc();
		$round= $row['round'];
		$NODEJS_HOST_SERVER = $GLOBALS["NODEJS_HOST_SERVER"];
		// TODO: Security measure: AUTHORIZATION PROCESS NEEDED
		file_get_contents ($NODEJS_HOST_SERVER.'/finish/'.$round); 
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
		$sql = "UPDATE daisy_round SET status=$status, question_no=0 WHERE round='$round'";
		$conn->query ($sql);	
	}
	
	

	function getQuestionBody ($conn, $token) { //để javascript truy cập từ bên ngoài

		if ($token === "") 
			if (!isset($_SESSION['round']))
				return "ERR_NOT_LOGGED_IN";

		if ($token === "") {
			$round = $_SESSION['round'];
			$sql = "SELECT status, question_no, round FROM daisy_round where round='$round'";
		} else {
			$sql = "SELECT status, question_no, round FROM daisy_round where access_token='$token'";
		}
		
		$result = $conn->query($sql);
		$assoc = $result->fetch_assoc();
		$status = $assoc['status'];
		$round = $assoc['round'];
		

		if ($status == 0)
			return "ERR_ROUND_CLOSED";
		if ($status == 1) {
			return "ERR_ROUND_IS_WAITING";
		}

		$question_no =  $assoc['question_no'];
		$sql = "SELECT id, body, choice_a, choice_b, choice_c, choice_d FROM daisy_shuffle_content, daisy_question WHERE daisy_shuffle_content.question_id = daisy_question.id AND daisy_shuffle_content.question_no=$question_no
			AND daisy_shuffle_content.round = '$round'";
		// echo $sql;
		// echo $sql;
		$result = $conn->query ($sql);
		if ($result->num_rows == 0)
			return -1;
		$result = $result->fetch_assoc ();
		$result["question_no"] = $question_no;
		
		// echo json_encode ($result);
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
				$name = "";
				if (isset($request['name']))
					$name = $request['name'];
				if ($err === "")
					$result = findLoggedPlayer ($conn, $request['token'], $name);				
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
			case "notify_round_finish":
				$err = checkRequiredParam ($request, ['token']);
				if ($err === "")
					$result = notifyRoundFinish ($conn, $request['token']);				
				else 
					$success = false;
				return formResp ($success, $result, $err);				
			case "get_question_body":
				$err = checkRequiredParam ($request, []);
				$token = "";	
				if (array_key_exists("token", $request)) $token = $request["token"];
				if ($err === "") {
					$result = getQuestionBody ($conn, $token);
					__render($result);
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