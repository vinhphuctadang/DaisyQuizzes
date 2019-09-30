<?php
	
	
	$str = $_SERVER['DOCUMENT_ROOT'].'/DaisyQuizzes/middleware/auth_admin.php';
	include $str;
	$str = $_SERVER['DOCUMENT_ROOT'].'/DaisyQuizzes/database.php';
	include $str;
	
	function add_collection ($conn, $userid, $collection){
		$sql = "INSERT INTO daisy_collection (status, name, admin_id) VALUES (0, '$collection', $userid)";
		$conn->query ($sql);
	}
	$id = $_SESSION['userid'];
	
	if (isset ($_POST ['collection'])) {
		$collection = $_POST['collection'];
		$conn = db_connect ();
		add_collection ($conn, $id, $collection);
		header('Location: ./dashboard.php');
		$conn->close ();
		exit ();
	}
	
	?>
<html>	
	<head>
		<title>dashboard</title>
		<meta charset="utf-8">
		<link href="./index.css" rel="stylesheet" type="text/css">
	</head>
	<body> 
	
		<form action="add.php" method="post">				
			  <div class="container">
				<input type="text" placeholder="Nhập tên bộ câu hỏi" name="collection" required><br>
				<button type="submit">TIẾP TỤC</button>    
			  </div>
		</form>
	</body>
</html>