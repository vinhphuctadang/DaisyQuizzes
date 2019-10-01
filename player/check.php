<?php
	include $_SERVER['DOCUMENT_ROOT'].'/DaisyQuizzes/database.php';
	include serverpath ('middleware/auth.php');
	
	function db_fetch_question ($conn, $round) {
		$sql = "SELECT status, question_no FROM daisy_round_collection where round='$round'";
		$result = $conn->query ($sql);			
		$assoc = $result->fetch_assoc ();
		$status = $assoc ['status'];
		if ($status == 0)
			die ("Vòng này đã đóng hoặc không tồn tại!");
		$question_no =  $assoc ['question_no'];	
		$sql = "SELECT choice_a FROM daisy_shuffle_content, daisy_question WHERE question_id = id AND question_no=".$question_no;
		$result = $conn->query ($sql);
		$question = $result->fetch_assoc ();
		return $question;
	}
	
	function getScore ($conn, $round, $token) {
		$sql = "SELECT score FROM daisy_player_round where round='$round' and token='$token'";
		$result = $conn->query ($sql);
		if ($result->num_rows == 0)
			throw new Exception ("Failed:NoPlayer");
		$result = $result->fetch_assoc ();
		return $result['score'];
	}	
	
	function setScore ($conn, $round, $token, $score) {
		$sql = "UPDATE daisy_player_round SET score = $score WHERE round='$round' and token='$token'";
		$result = $conn->query ($sql);
	}
	
	function respondCorrect ($conn, $round, $token) {	
		try {
			$score = getScore ($conn, $round, $token);
			setScore ($conn, $round, $token, $score+1);		
		} catch (exception $e) {
			echo "Lỗi giao dịch: Không cập nhật được (lỗi đường truyền)<br>";
		} finally {
			echo "Câu trả lời đúng";
		}
	}
	
	function respondIncorrect ($conn, $round, $token) {
		echo "Câu trả lời sai";
	}
	
	if (!isset ($_SESSION['round']))
		die ("Không tìm thấy vòng chơi");
	if (!isset ($_SESSION['token'])) 
		die ("Không tìm thấy người chơi");
	
	$conn = db_connect ();
	$round = $_SESSION['round'];
	$token = $_SESSION['token'];
	//echo $player;
	$answer = db_fetch_question ($conn, $round);
	
	if ($answer ['choice_a'] == $_POST['choice']) 
		respondCorrect ($conn, $round, $token);
	else 
		respondIncorrect ($conn, $round, $token);
	
	$conn->close ();
?>