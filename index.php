<?php
	include $_SERVER['DOCUMENT_ROOT'].'/database.php';
?>

<html>	
	<head>
		<title>Login</title>
		<meta charset="utf-8">
		<link href="./index.css" rel="stylesheet" type="text/css">
	</head>
	
	<body>
		<div id="wrapper">
			<form action="player/entry.php" method="post">
				<div class='admin'>
					<a href='./admin/login.php'> Tôi là người kiến tạo </a>			
				</div>
			  <div class="imgcontainer">
				<img src="<?php echo assets ('img_avatar2.png')?>" alt="Avatar" class="avatar">
			  </div>

			  <div class="container">
				<input type="text" placeholder="Tên bạn muốn hiển thị" name="player" required>
				<button type="submit">TIẾP TỤC</button>    
			  </div>

			  </div>
			</form>
		</div>
	</body>
</html>