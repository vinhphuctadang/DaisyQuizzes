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
			<form action="middle.php" method="post">
			  <div class="imgcontainer">
				<img src="<?php echo assets ('img_avatar2.png')?>" alt="Avatar" class="avatar">
			  </div>

			  <div class="container">
			
				<input type="text" placeholder="Mã vòng chơi" name="round" required>
				<?php
					$player = $_POST['player'];
					echo "<input type='hidden' name='player' value='$player'>";
				?>
				<button type="submit">TIẾP TỤC</button>    
			  </div>

			  </div>
			</form>
		</div>
	</body>
</html>