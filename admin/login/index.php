<?php
$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
if (substr_count($_SERVER['DOCUMENT_ROOT'], 'DaisyQuizzes') == 0) {
	$DOCUMENT_ROOT = $DOCUMENT_ROOT . '/DaisyQuizzes';
}
include $DOCUMENT_ROOT . '/database.php';
include $DOCUMENT_ROOT . '/session_start.php';
include $DOCUMENT_ROOT . '/middleware/auth_admin.php';

if (checkLoggedIn()) {
	header('Location: ../dashboard.php');
	exit();
}
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
	<link href="<?php echo path("/assets/material-components-web.min.css") ?> " rel="stylesheet">
	<script src="<?php echo path("/assets/material-components-web.min.js") ?> "></script>
	<link href="../general.css" rel="stylesheet">
	<link href="./styles.css" rel="stylesheet">
</head>

<body>
	<div id="wrapper">
		<div class="mdc-card wrapper-card">
			<form name="fLogin" action="middle_login.php" method="post">
				<div class="imgcontainer">
					<img src="../../assets/img_avatar.png" alt="Avatar" class="avatar">
					<div>Đăng nhập</div>
				</div>
				<div class="mdc-text-field mdc-text-field--outlined" data-mdc-auto-init="MDCTextField">
					<input class="mdc-text-field__input" id="username" name="username" required onkeyup="validateForm(event)" />
					<div class="mdc-notched-outline">
						<div class="mdc-notched-outline__leading"></div>
						<div class="mdc-notched-outline__notch">
							<label for="username" class="mdc-floating-label">Tên đăng nhập</label>
						</div>
						<div class="mdc-notched-outline__trailing"></div>
					</div>
				</div>
				<div id="error_username" class="error">helper_text</div>
				<div class="mdc-text-field mdc-text-field--outlined" data-mdc-auto-init="MDCTextField">
					<input class="mdc-text-field__input" id="password" name="password" type="password" required onkeyup="validateForm(event)">
					<div class="mdc-notched-outline">
						<div class="mdc-notched-outline__leading"></div>
						<div class="mdc-notched-outline__notch">
							<label for="password" class="mdc-floating-label">Mật khẩu</label>
						</div>
						<div class="mdc-notched-outline__trailing"></div>
					</div>
				</div>
				<div id="error_password" class="error">helper_text</div>
				<button id="btnLogin" class="btn mdc-button mdc-button--raised" type="submit" disabled>TIẾP TỤC
				</button>
				<p class="footer"><a href="../../">Tham gia </a><br>Chưa có tài khoản? <a href="../register">Đăng ký </a> </p>

			</form>
		</div>
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

		function linkTo(link) {
			window.location = link;
		}

		function validateForm(event) {
			var form = document.forms["fLogin"];
			var username = form["username"].value;
			var password = form["password"].value;
			if (!username || !password) {
				document.getElementById('btnLogin').disabled = true;
			} else {
				document.getElementById('btnLogin').disabled = false;
				if (event.key == 'Enter') {
					document.getElementById('btnLogin').click();
				}
			}
		}

		function open_alert(text) {
			document.getElementById('mdc-snackbar-label').innerHTML = text;
			snackbar.open();
		}

		function close_alert() {
			snackbar.close();
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