<?php
$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
if (substr_count($_SERVER['DOCUMENT_ROOT'], 'DaisyQuizzes') == 0) {
	$DOCUMENT_ROOT = $DOCUMENT_ROOT . '/DaisyQuizzes';
}
$str = $DOCUMENT_ROOT . '/middleware/auth_admin.php';
include $str;

if (checkLoggedIn()) {
	header('Location: ../dashboard.php');
	exit();
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
	<link href="../general.css" rel="stylesheet">
	<link href="./styles.css" rel="stylesheet">
</head>

<body>
	<div id="wrapper">
		<div class="mdc-card wrapper-card">
			<form name="fLogin" class="center-card" action="middle_login.php" method="post">
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
	<script>
		window.mdc.autoInit();

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
	</script>
</body>

</html>