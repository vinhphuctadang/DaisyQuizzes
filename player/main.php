<?php
$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
if (substr_count($_SERVER['DOCUMENT_ROOT'], 'DaisyQuizzes') == 0) {
	$DOCUMENT_ROOT = $DOCUMENT_ROOT . '/DaisyQuizzes';
}
include $DOCUMENT_ROOT . '/database.php';
include serverpath('middleware/auth.php');
?>
<html>

<head>
	<title>Daisy Quizzes</title>
	<meta charset="utf-8">
	<link href="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.css" rel="stylesheet">
	<script src="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.js"></script>
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<link href="./index.css" rel="stylesheet" type="text/css">
</head>

<body>
	<?php

	function onWaiting()
	{
		?>
		<div id="wrapper" class="main-container">
			<form style="padding-bottom: 20px">
				<p> Vòng chơi đang chờ đợi để bắt đầu, có thể bạn cần <a href='main.php'>tải lại trang</a></p>
			</form>
		</div>
		<?php
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

			if ($status == 0) {
				?>
			<div id="wrapper" class="main-container">
				<form style="padding-bottom: 20px">
					<p> Vòng chơi này đang đóng hoặc không tồn tại!</p>
				</form>
			</div>
		<?php
				exit();
			}
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
							<input readonly class="mdc-text-field__input" id="text-field-hero-input" type='submit' name='choice' value="<?php echo $question['choice_' . $c] ?>">
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
				<!--cái này chưa có bảo mật, mặc định là daisy, 1-10-2019: đã fix bảo mật -->
			</form>
		</div>
	<?php

	}

	// $conn = db_connect();
	// $question = db_fetch_question($conn, $round);
	// display($question, $round, $token);
	// $conn->close();
	?>
	<script>
		window.mdc.autoInit();
	</script>
</body>

	<form id="question" action='check.php' method='post'>	
	</form>

	</body>

	<script>

		// viết tất cả các hàm này để thể hiện câu hỏi theo thời gian
		function render (question) {
			var question_pane = document.getElementById ("question");
			question_pane.innerHTML = question;

			var nxtTime = document.getElementById ("next_timestamp").value;
			if (next_timestamp != null) {
				
			}
			// TODO: Invoke onInterval after a desired time 
		}

		function requestNext () {
			request = new XMLHttpRequest ();
			request.open ("GET", "/api.php?method=get_question_body", true)
			request.onreadystatechange = function () {
				if (this.readyState == 4 && this.status == 200) {			
					render (this.responseText);					
				}
			};
			request.send ();
		}

		function onInterval () {
			requestNext ();
		}

		requestNext ();

	</script>
	
</html>