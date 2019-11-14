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
	?>

</body>
	<p id="timing">10</p>
	<form id="question" method='post'></form>
	</body>
	<script>
		window.mdc.autoInit();
	</script>
	<script src="<?php echo path ("socket.io.js");?>"></script>
	<script>
		timeLeft = 10;
		intervalHandler = null;

		function renderTimer () {
			document.getElementById ("timing").innerText = timeLeft;
		}

		function setEllapsedTime (time) {
			if (intervalHandler != null)
				clearInterval (intervalHandler);
			intervalHandler = setInterval ("onTimingInterval ()", 1000);
			timeLeft = time;
			renderTimer ();
		}

		function onTimingInterval () {
			if (timeLeft > 0) {
				renderTimer ();
				timeLeft-=1;
			} else {
				timeLeft = 0;
				renderTimer ();
			}
		}

		var socket = io.connect('http://localhost:8080');

		function render (question) {
			var question_pane = document.getElementById ("question");
			question_pane.innerHTML = question;
			// TODO: Invoke onInterval after a desired time 
		}

		function requestNext () {
			request = new XMLHttpRequest ();
			
			request.onreadystatechange = function () {	
				if (this.readyState == 4 && this.status == 200) {			
					render (this.responseText);					
				}
			};
			request.open ("GET", "/api.php?method=get_question_body", true)
			request.send ();
		}

		socket.on('<?php echo "onChange".$round?>', function(time){
			// alert ("update needed: " + time);
			setEllapsedTime (time);
        	requestNext ();
        });

        function onChoiceClick (choice) {
        	request = new XMLHttpRequest ();
        	request.onreadystatechange = function () {	
				if (this.readyState == 4 && this.status == 200) {			
					var question_pane = document.getElementById ("question");
					question_pane.innerHTML = this.responseText;
				}		
			};

			var params = "choice="+choice;
			request.open ("POST", "check.php", true);
			request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			request.send (params);
        }

        requestNext ();
	</script>
</html>