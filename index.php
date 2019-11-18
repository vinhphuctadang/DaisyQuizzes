<?php
$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
if (substr_count($_SERVER['DOCUMENT_ROOT'], 'DaisyQuizzes') == 0) {
	$DOCUMENT_ROOT = $DOCUMENT_ROOT . '/DaisyQuizzes';
}
include $DOCUMENT_ROOT . '/database.php';
include "session_start.php";

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
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.css" rel="stylesheet">
	<script src="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.js"></script>
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<link href="./index.css" rel="stylesheet" type="text/css">
</head>

<body>
	<div class='waiting'>
		<div id="wrapper">
			<div class="mdc-card wrapper-card card">
				<form action="player/entry.php" method="post">
					<div class="imgcontainer">
						<img src="assets/img_avatar.png" alt="Avatar" class="avatar">
					</div>
					<div class="container">
						<div class="mdc-text-field" data-mdc-auto-init="MDCTextField">
							<input class="mdc-text-field__input" id="player" name="player" onkeyup="handle_change(this.value)">
							<div class="mdc-line-ripple"></div>
							<label for="player" class="mdc-floating-label">Tên bạn muốn hiển thị *</label>
						</div>
						<!-- <input type="text" placeholder="Tên bạn muốn hiển thị" name="player" required> -->
						<button id="btnLogin" class="btn mdc-button mdc-button--raised" type="submit" disabled>TIẾP TỤC
						</button>
					</div>
					<div class='admin'>
						<a href='./admin/login'> Tôi là người kiến tạo? </a>
					</div>
				</form>
			</div>
		</div>
		<ul class='bg-bubbles'>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
		</ul>
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
		var MDCSnackbar = mdc.snackbar.MDCSnackbar;
		const snackbar = new MDCSnackbar(document.querySelector('.mdc-snackbar'));

		function open_alert(text) {
			document.getElementById('mdc-snackbar-label').innerHTML = text;
			snackbar.open();
		}

		function close_alert() {
			snackbar.close();
		}

		function handle_change(value) {
			document.getElementById('btnLogin').disabled = value.length == 0;
		}
		document.addEventListener("click", function() {
			close_alert();
		});
	</script>
	<?php
	disp_alert($_SESSION['flash_alert']);
	?>
</body>

</html>