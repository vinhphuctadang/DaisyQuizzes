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
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="<?php echo path("/assets/material-components-web.min.css") ?> " rel="stylesheet">
	<script src="<?php echo path("/assets/material-components-web.min.js") ?> "></script>
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
		$sql = "SELECT id, body, choice_a, choice_b, choice_c, choice_d FROM daisy_shuffle_content, daisy_question WHERE question_id = id AND question_no=$question_no AND daisy_shuffle_content.round='$round'";
		//echo $sql;
		$result = $conn->query($sql);
		$question = $result->fetch_assoc();
		return $question;
	}
	?>

	<form id="question">
		<p class="timing" id="timing"></p>
		<div id="question-body">

		</div>
	</form>

	<script>
		window.mdc.autoInit();
	</script>
	<script src="<?php echo path("socket.io.js"); ?>"></script>
	<script>
		timeLeft = 10;
		intervalHandler = null;
		chosen = null;

		function renderTimer() {
			var timingView = document.getElementById("timing");
			if (timingView != null)
				timingView.innerText = timeLeft;
		}

		function setEllapsedTime(time) {
			if (intervalHandler != null)
				clearInterval(intervalHandler);
			intervalHandler = setInterval("onTimingInterval ()", 1000);
			timeLeft = time;
			renderTimer();
		}

		function onTimingInterval() {
			if (timeLeft > 0) {
				renderTimer();
				timeLeft -= 1;
			} else {
				timeLeft = 0;
				renderTimer();
			}
		}



		function render(question) {

			var question_pane = document.getElementById("question-body");
			question_pane.innerHTML = question;
			//document.getElementById ("explanation-pane").style.display = "none";
			// TODO: Invoke onInterval after a desired time 
		}

		function requestNext() {
			request = new XMLHttpRequest();

			request.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					render(this.responseText);
				}
			};


			request.open("GET", "<?php echo path('api.php?method=get_question_body'); ?>", true);
			request.send();
		}

		function onChoiceClick(choice) {

			if (chosen != null)
				return;

			chosen = choice;

			request = new XMLHttpRequest();
			request.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					var status = document.getElementById('status');
					if (status != null)
						status.innerHTML = this.responseText;
				}
			};

			var params = "choice=" + choice.getAttribute('value');
			choice.style.backgroundColor = '#FDC228';
			choice.style.color = 'white';
			choice.style.borderRadius = '4px';
			document.getElementById('icon-' + choice.id).innerHTML = 'cached';
			request.open("POST", "check.php", true);
			request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			request.send(params);
		}

		// var socket = io.connect('http://localhost:8080');

		var socket = io.connect('<?php echo $GLOBALS["NODEJS_HOST_SERVER"]; ?>');

		socket.on('<?php echo "onChange" . $round; ?>', function(time) {
			// alert ("update needed: " + time);
			chosen = null; // set lại cho nó chưa chọn
			setEllapsedTime(time);
			requestNext();
		});

		socket.on('<?php echo "onExplain" . $round; ?>', function(explain, time, answer) {
			// alert ("update needed: " + time);
			// do approriate stuff for expressing explanation
			console.log(explain + ",'" + answer + "'");
			if (explain != "") {
				document.getElementById("explanation-pane").style.display = "block";
				document.getElementById("explanation-pane").classList.add('animated');
				document.getElementById("explanation-pane").classList.add('fadeIn');
				var status = document.getElementById('explanation');
				if (status != null)
					status.innerHTML = explain;
			}


			if (chosen != null) {
				chosen.style.backgroundColor = "#f44336";
				chosen.style.color = 'white';
				chosen.style.borderRadius = '4px';
				document.getElementById('icon-' + chosen.id).innerHTML = 'highlight_off';
			}


			var answers = document.getElementsByClassName('mdc-text-field__input');
			console.log(answers.length);
			for (i = 0; i < answers.length; ++i) {
				var btn = answers[i];

				if (btn.getAttribute('value') === answer) {
					console.log(btn.getAttribute('value'));
					if (chosen == null) btn.style.backgroundColor = "#f44336";
					else btn.style.backgroundColor = "#4caf50";
					btn.style.color = 'white';
					btn.style.borderRadius = '4px';
					document.getElementById('mdc-text-field-' + i).classList.add('animated');
					document.getElementById('mdc-text-field-' + i).classList.add('fadeIn');
					document.getElementById('icon-' + i).innerHTML = 'check_circle_outline';
				}
			}
			setEllapsedTime(time);
		});


		socket.on('<?php echo "onFinished" . $round ?>', function(message) {
			alert("Màn chơi đã kết thúc ...");
			clearInterval(intervalHandler);
			window.location.href = "rank.php";
		});


		requestNext();
	</script>
</body>

</html>