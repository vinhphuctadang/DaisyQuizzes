<html>	
	<head>
		<title>dashboard</title>
		<meta charset="utf-8">
		<link href="./index.css" rel="stylesheet" type="text/css">
	</head>
	
	<body>
		
		<?php 
			session_start ();
			if (!isset ($_SESSION['userid']))	{	
				header('Location: ./login.php');
				exit ();	
			}
			$id = $_SESSION['userid'];
			$username = $_SESSION['username'];
		?>
		
		<div class="pane-log">
			<a href="logout.php"> <?php echo $username;?> Logout </a> 
		</div>
		<?php
			include '../database.php'; // parent directory
			$conn = db_connect ();
			// TODO: đang thiếu lấy ra từ database
			
			function get_collections ($conn, $userid) {
				$sql = "SELECT name FROM daisy_admin_collection, daisy_collection where collection_id = id and admin_id = $userid";
				//echo $sql;
				$result = $conn->query ($sql);
				
				echo "Các bộ bạn đã tạo (".$result->num_rows." bộ): <br>";
				while ($row = $result->fetch_assoc ()) {
					echo json_encode ($row)."<br>";
				}
			}				
			get_collections ($conn, $id);
			$conn->close ();
		?>
	</body>
</html>