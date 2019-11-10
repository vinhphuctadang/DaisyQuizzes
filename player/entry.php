<?php
$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
if (substr_count($_SERVER['DOCUMENT_ROOT'], 'DaisyQuizzes') == 0) {
	$DOCUMENT_ROOT = $DOCUMENT_ROOT . '/DaisyQuizzes';
}
include $DOCUMENT_ROOT . '/database.php';
include $DOCUMENT_ROOT . '/session_start.php';

function disp_alert($alertText)
{
	if (isset($alertText)) {
		?>
		<script type="text/javascript">
			open_alert("<?php echo $alertText; ?>");
		</script>
<?php
		unset($_SESSION['flash_username']);
		unset($_SESSION['flash_alert']);
	}
}
?>

<html>

<head>
	<title>Login</title>
	<meta charset="utf-8">
	<link href="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.css" rel="stylesheet">
	<script src="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.js"></script>
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<link href="./index.css" rel="stylesheet" type="text/css">
</head>

<body>
	<div id="wrapper">
		<form action="middle.php" method="post">
			<div class="imgcontainer">
				<img src="../assets/img_avatar.png" alt="Avatar" class="avatar">
			</div>

			<div class="container">
				<div id="input_round" class="mdc-text-field" data-mdc-auto-init="MDCTextField">
					<input class="mdc-text-field__input " id="round" name="round" required onblur="loadXMLDoc(this.value, '<?php echo $player; ?>')" onfocus="focus_input()">
					<div class="mdc-line-ripple"></div>
					<label for="player" class="mdc-floating-label">Mã vòng chơi</label>
				</div>
				<div id="error"></div>
				<!-- <input type="text" placeholder="Tên bạn muốn hiển thị" name="player" required> -->
				<button id="btnRound" class="btn mdc-button mdc-button--raised" type="submit" disabled>TIẾP TỤC
				</button>

				<!-- <input type="text" placeholder="Mã vòng chơi" name="round" required> -->
				<?php
				$player = $_POST['player'];
				echo "<input type='hidden' name='player' value='$player'>";
				?>
			</div>
			<div style="visibility: hidden">hidden
			</div>
	</div>
	</form>
	</div>
	<div id="mdc-snackbar" class="mdc-snackbar mdc-snackbar--leading" data-mdc-auto-init="MDCSnackbar">
		<div class="mdc-snackbar__surface">
			<div id="mdc-snackbar-label" class="mdc-snackbar__label" role="status" aria-live="polite"></div>
			<div class="mdc-snackbar__actions">
				<button class="mdc-icon-button mdc-snackbar__dismiss material-icons" title="Dismiss">close</button>
			</div>
		</div>
	</div>
	<script>
		window.mdc.autoInit();

		function loadXMLDoc(round, player) {
			var xmlhttp;
			if (window.XMLHttpRequest) xmlhttp = new XMLHttpRequest();
			else xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					var responseText = xmlhttp.responseText;
					document.getElementById('error').innerHTML = responseText;
					if (responseText) {
						document.getElementById('btnRound').disabled = true;
						document.getElementById('input_round').classList.add("mdc-text-field--invalid");
					} else {
						document.getElementById('btnRound').disabled = false;
						document.getElementById('input_round').classList.remove("mdc-text-field--invalid");
					}
				}
			}
			xmlhttp.open("POST", "checkRoundExisted.php", true);
			xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xmlhttp.send("round=" + round + "&player=" + player);
		}

		function focus_input() {
			document.getElementById('error').innerHTML = "";
			document.getElementById('input_round').classList.remove("mdc-text-field--invalid");
		}

	</script>
	<?php
	disp_alert($_SESSION['flash_alert']);
	?>
</body>

</html>