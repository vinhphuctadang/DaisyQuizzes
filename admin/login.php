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
		<link href="./index.css" rel="stylesheet" type="text/css">
	</head>
	
	<body>
		<div id="wrapper">
			
			<form action="middle_login.php" method="post">				
			  <div class="container">
				<input type="text" placeholder="Tên đăng nhập" name="username" required><br>
				<input type="password" placeholder="Mật khẩu" name="password" required><br>
				<button type="submit">TIẾP TỤC</button>    
			  </div>
			</form>
		</div>
		
		<div id="register">
			<p> Chưa có tài khoản? <a href="./register.html">Đăng ký </a> </p>
		</div>
	</body>
</html>