<html>	
	<head>
		<title>Login</title>
		<meta charset="utf-8">
		<link href="./index.css" rel="stylesheet" type="text/css">
	</head>
	
	<body>
		<?php 
			session_start ();
			if (isset ($_SESSION['userid'])) {
				header('Location: ./dashboard.php');
				exit ();
			}
		?>
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