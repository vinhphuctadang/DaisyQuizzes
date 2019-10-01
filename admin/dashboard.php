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
				$sql = "SELECT name, daisy_collection.id, COUNT(daisy_question.id) as num_questions FROM daisy_collection ".
				"LEFT JOIN daisy_question ON daisy_question.collection_id=daisy_collection.id ".
				"WHERE admin_id = $userid GROUP BY daisy_collection.name, daisy_collection.id";
				
				//echo $sql;
				$result = $conn->query ($sql);
				return $result;
			}
			
			function get_rounds ($conn, $userid){
				$sql = "SELECT round, name FROM daisy_round_collection, daisy_collection WHERE collection = id and daisy_round_collection.admin_id = $userid";
				$result = $conn->query ($sql);
				return $result;
			}

			function display ($result) { // vẽ kết qủa lên màn hình
				echo "Các bộ bạn đã tạo (".$result->num_rows." bộ): <br>";
				$i = 0;
				if ($result->num_rows > 0) {
					echo "<table>";
					echo "<tr>"
						."<th> STT </th>"
						."<th> Bộ câu hỏi </th>"
						."<th> Số câu </th>"
						."<th> Xóa </th>"
						."<th> Tạo vòng chơi </th>"
						."</tr>";
						
					while ($row = $result->fetch_assoc ()) {
						$id = $row['id'];
						$num = $row['num_questions'];
						$i++;
						echo "<tr>";
						echo "<td>$i</td>"
							."<td><a href='./modify.php?k=$id'>".$row['name']."</a>"
							."<td>$num</td>"
							."<td><a href='./delete.php?k=$id'> Xóa </a> </td>"
							."<td><a href='./create_round.php?k=$id'>Tạo vòng chơi</a> </td>";	
						echo "</tr>";
					}
					
					echo "</table>";
				}
			}
			
			function display_round ($result) {
				
				echo "<p>Các vòng chơi đã tạo (".$result->num_rows." vòng) </p>";
				$i = 0;
				
					while ($row = $result->fetch_assoc ()) {
						$id   = $row ['round'];
						$name = $row ['name'];
						$i++;
						
						echo "$i. $id: $name <a href='delete_round.php?k=$id'>Xóa</a>, <a href='modify_round.php?k=$id'>Trạng thái</a></p>";
					}
					
			}
			
			$result = get_collections ($conn, $id);
			display ($result);
			echo "<a href='./add.php'> Thêm bộ câu hỏi mới </a><br>";
			$rounds = get_rounds ($conn, $id);
			display_round ($rounds);
			$conn->close ();
		?>
	</body>
</html>