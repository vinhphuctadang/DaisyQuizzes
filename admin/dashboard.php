<?php
	$str = $_SERVER['DOCUMENT_ROOT'].'/DaisyQuizzes/middleware/auth_admin.php';
	include $str;
	
	if (!checkLoggedIn ())	{	
		header('Location: ./login.php');
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
		
		<?php
			$id = $_SESSION['userid'];
			$username = $_SESSION['username'];
		?>
		
		<div class="pane-log">
			<a href="logout.php"> <?php echo $username;?> Logout </a> 
		</div>
		<?php
			include $_SERVER['DOCUMENT_ROOT'].'/DaisyQuizzes/database.php'; // parent directory
			$conn = db_connect ();
			// TODO: đang thiếu lấy ra từ database
			
			function get_collections ($conn, $userid) {
				$sql = "SELECT name, id FROM daisy_collection WHERE admin_id = $userid";
				//echo $sql;
				$result = $conn->query ($sql);
				return $result;
			}

			function display ($result) { // vẽ kết qủa lên màn hình
				echo "Các bộ bạn đã tạo (".$result->num_rows." bộ): <br>";
				$i = 0;
				while ($row = $result->fetch_assoc ()) {
					$id = $row['id'];
					$i++;
					echo $i.". <a href='./modify.php?k=$id'>".$row['name']."</a> <a href='./delete.php?k=$id'> Xóa </a> <br>";
				}
			}
			
			$result = get_collections ($conn, $id);
			display ($result);
			echo "<a href='./add.php'> Thêm bộ câu hỏi mới </a>";
			$conn->close ();
		?>
	</body>
</html>