<?php 
	$str = $_SERVER['DOCUMENT_ROOT'].'/middleware/auth_admin.php';
	include $str;
	
	if (checkLoggedIn ()) {
		header('Location: ./dashboard.php');
		exit ();
	}
?>
<html>	
	<head>
		<title>Login</title>
		<meta charset="utf-8">
		

		<!-- <link href="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.css" rel="stylesheet">
		<script src="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.js"></script>
		 -->
		<link href="./general.css" rel="stylesheet">
		<!-- <style>
			@import "@material/notched-outline/mdc-notched-outline";
		</style>
		 -->
	</head>
	
	<body>
		<div id="wrapper">
			<form class="center-card" action="middle_login.php" method="post">				

				<input type="text" placeholder="Tên đăng nhập" name="username" required><br>
				<input type="password" placeholder="Mật khẩu" name="password" required><br>
				<button class="btn" type="submit">TIẾP TỤC</button>  
				<p> Chưa có tài khoản? <a href="./register.html">Đăng ký </a> </p>

			</form>
			
		</div>
		
		
		<script async="" src="https://www.google-analytics.com/analytics.js"></script>
		<script>
			import {MDCNotchedOutline} from '@material/notched-outline';
			const notchedOutline = new MDCNotchedOutline(document.querySelector('.mdc-text-field mdc-text-field--outlined'));
			
		</script>
	</body>
	
</html>