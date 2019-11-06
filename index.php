<?php
$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
if (substr_count($_SERVER['DOCUMENT_ROOT'], 'DaisyQuizzes') == 0) {
	$DOCUMENT_ROOT = $DOCUMENT_ROOT . '/DaisyQuizzes';
}
include $DOCUMENT_ROOT . '/database.php';
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
		<form action="player/entry.php" method="post">

			<div class="imgcontainer">
				<!-- <img src="<?php echo assets('img_avatar2.png') ?>" alt="Avatar" class="avatar"> -->
				<img src="assets/img_avatar2.png" alt="Avatar" class="avatar">
			</div>

			<div class="container">
				<div class="mdc-text-field" data-mdc-auto-init="MDCTextField">
					<input class="mdc-text-field__input" id="player" name="player" required>
					<div class="mdc-line-ripple"></div>
					<label for="player" class="mdc-floating-label">Tên bạn muốn hiển thị</label>
				</div>
				<!-- <input type="text" placeholder="Tên bạn muốn hiển thị" name="player" required> -->
				<button id="btnLogin" class="btn mdc-button mdc-button--raised" type="submit">TIẾP TỤC
				</button>
			</div>
			<div class='admin'>
				<a href='./admin/login'> Tôi là người kiến tạo? </a>
			</div>

	</div>
	</form>
	</div>
	<script>
		window.mdc.autoInit();
	</script>
</body>

</html>