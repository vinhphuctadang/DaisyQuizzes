<?php
	include $_SERVER['DOCUMENT_ROOT'] . '/database.php';
	include serverpath('middleware/auth.php');
?>
<html>

<head>
	<title>Daisy Quizzes</title>
	<meta charset="utf-8">
</head>

<body>
	<?php

	function onWaiting()
	{
		echo "<p> Vòng chơi đang chờ đợi để bắt đầu, có thể bạn cần <a href='main.php'>tải lại trang</a></p>";
	}

	// $conn->close ();
	/*if (!isset ($_POST ['player'])) {
				die ("Không tồn tại người chơi, xóa nối kết");
			}*/
	if (!isset($_SESSION['round']))
		die("Không tìm thấy vòng chơi yêu cầu, có thể bạn cần đăng nhập lại");
	if (!isset($_SESSION['token']))
		die("Không tìm thấy người chơi");

	$token = $_SESSION['token'];
	$round = $_SESSION['round'];
	#function checkPlayer ($conn, $round, $player) {
	#	if (!in_array ('player', $_POST)) {
	#		die ("Không tồn tại player, hãy về trang đăng nhập tại ./entry.php");
	#	} else {
	#		$p = $_POST ['player'];
	#		$sql = "
	#	}
	#}

	function db_fetch_question($conn, $round)
	{
		$sql = "SELECT status, question_no FROM daisy_round where round='$round'";
		$result = $conn->query($sql);
		$assoc = $result->fetch_assoc();
		$status = $assoc['status'];

		if ($status == 0)
			die("Vòng chơi này đang đóng hoặc không tồn tại");
		if ($status == 1) {
			die(onWaiting());
		}

		$question_no =  $assoc['question_no'];
		// TODO: Here comes a bug
		$sql = "SELECT id, body, choice_a, choice_b, choice_c, choice_d FROM daisy_shuffle_content, daisy_question WHERE question_id = id AND question_no=" . $question_no;
		//echo $sql;
		$result = $conn->query($sql);
		$question = $result->fetch_assoc();
		return $question;
	}
	function display($question, $round, $token)
	{
		echo "<form action='check.php' method='post'>";
		echo "<h1>Câu hỏi</h1>";
		echo "<p>" . $question['body'] . "</p>";
		$val = ['a', 'b', 'c', 'd'];
		
		shuffle($val);
		foreach ($val as $c) {
			echo "<input type='submit' name='choice' value='" . $question['choice_' . $c] . "'>" . "</input> <br>";
		}
		echo "<input type='hidden' name='question' value=" . $question['id'] . ">";
		echo "<input type='hidden' name='round' value='" . $round . "'>" . "</input> <br>";
		echo "<input type='hidden' name='token' value='" . $token . "'>" . "</input> <br>"; # cái này chưa có bảo mật, mặc định là daisy, 1-10-2019: đã fix bảo mật

		echo "</form>";
	}

	$conn = db_connect();
	$question = db_fetch_question($conn, $round);
	//echo $round;
	display($question, $round, $token);
	$conn->close();
	?>
	</body>
	
</html>