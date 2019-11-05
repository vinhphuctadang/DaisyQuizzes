<html>

<head>
	<title>Register</title>
	<meta charset="utf-8">
	<link href="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.css" rel="stylesheet">
	<script src="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.js"></script>
	<script src="register.js"></script>
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<link href="../general.css" rel="stylesheet">
	<link href="./styles.css" rel="stylesheet">
</head>

<body>
	<div id="wrapper">
		<form name="fRegister" class="center-card" action="middle_register.php" method="post">
			<div class="header">
				<button class="mdc-icon-button material-icons" disabled>lock_open</button>
				<div>Đăng ký</div>
			</div>
			<div class="mdc-text-field mdc-text-field--outlined" data-mdc-auto-init="MDCTextField">
				<input class="mdc-text-field__input" id="username" name="username" required onkeyup="handle_username(event)" />
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
				<input class="mdc-text-field__input" id="password" name="password" type="password" required onkeyup="handle_password(event)">
				<div class="mdc-notched-outline">
					<div class="mdc-notched-outline__leading"></div>
					<div class="mdc-notched-outline__notch">
						<label for="password" class="mdc-floating-label">Mật khẩu</label>
					</div>
					<div class="mdc-notched-outline__trailing"></div>
				</div>
			</div>
			<div id="error_password" class="error">helper_text</div>
			<div class="mdc-text-field mdc-text-field--outlined" data-mdc-auto-init="MDCTextField">
				<input class="mdc-text-field__input" id="repassword" name="repassword" type="password" required onkeyup="handle_repassword()">
				<div class="mdc-notched-outline">
					<div class="mdc-notched-outline__leading"></div>
					<div class="mdc-notched-outline__notch">
						<label for="repassword" class="mdc-floating-label"> Nhập lại mật khẩu</label>
					</div>
					<div class="mdc-notched-outline__trailing"></div>
				</div>
			</div>
			<div id="error_repassword" class="error">helper_text</div>
			<button id="btnRegister" class="btn mdc-button mdc-button--raised" type="submit" disabled>TIẾP TỤC
			</button>

		</form>

	</div>
	<script>
		window.mdc.autoInit();
	</script>
</body>

</html>