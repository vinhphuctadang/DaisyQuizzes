<?php
	include $_SERVER['DOCUMENT_ROOT'].'/database.php'; // parent directory
	$str = $_SERVER['DOCUMENT_ROOT'].'/middleware/auth_admin.php';
	include $str;
	
	if (!checkLoggedIn ())	{	
		header('Location: ./login.php');
		exit ();	
	}
	
	
?>
<html>	
	<head>
		<title>Quản lí câu hỏi</title>
		<meta charset="utf-8">
		<link href="<?php echo assets ('css/general/table_classic.css');?>" rel="stylesheet" type="text/css">
		<link href="<?php echo assets ('css/admin/dashboard.css');?>" rel="stylesheet" type="text/css">
	</head>
	
	<body>
		<div id="wrapper">
		<?php
			$id = $_SESSION['userid'];
			$username = $_SESSION['username'];
		?>
		
		<div class="pane-log">
			<a href="logout.php"> <?php echo $username;?> Đăng xuất </a> 
		</div>
		<?php
			
			//Các trạng thái của một vòng chơi
			$const_status = [
				0=> 'Đóng',
				1=> 'Mở và chờ đợi',
				2=> 'Đang diễn ra'];
				
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
				$sql = "SELECT round, name, daisy_round_collection.status FROM daisy_round_collection, daisy_collection WHERE collection = id and daisy_round_collection.admin_id = $userid";
				$result = $conn->query ($sql);
				return $result;
			}

			function display ($result) { // vẽ kết qủa lên màn hình
				echo "<p class='heading'>Các bộ bạn đã tạo (".$result->num_rows." bộ):</p>";
				$i = 0;
				
				echo "<table>";
				echo "<thead>";
				echo "<tr>"
					."<th> STT </th>"
					."<th> Bộ câu hỏi </th>"
					."<th> Số câu </th>"
					."<th> Xóa </th>"
					."<th> Tạo vòng chơi </th>"
					."</tr>";
				echo "</thead>"."<tbody>";	
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
				echo "</tbody>";
				
				echo "<td colspan=5><a class='action' href='./add.php'> Thêm bộ câu hỏi mới </a></td>";
				echo "</table>";
			}
			
			function display_round ($result, $const_status) {
				
				echo "<p class='heading'>Các vòng chơi đã tạo (".$result->num_rows." vòng) </p>";
				$i = 0;
				if ($result->num_rows > 0) {
					echo "<table>";
					echo "<thead>";
					echo "<tr>"
						."<th> STT </th>"
						."<th> Mã vòng chơi </th>"
						."<th> Bộ câu hỏi </th>"
						."<th> Trạng thái </th>"
						."<th> Xóa </th>"
						."</tr>";
					echo "</thead>"."<tbody>";	
					while ($row = $result->fetch_assoc ()) {
						$id   = $row ['round'];
						$name = $row ['name'];
						$status = $row['status'];
						$i++;
						
						echo "<tr>";
						echo "<td>$i</td>"
							."<td>$id</td>"
							."<td><a href='modify_round.php?k=$id'>$name</a></td>"
							."<td>".$const_status[$status]."</td>"
							."<td><a href='delete_round.php?k=$id'>Xóa</a></td>";	
						echo "</tr>";
					}
					echo "</tbody>";
					
					echo "</table>";
				}					
			}
			
			$result = get_collections ($conn, $id);
			display ($result);
			
			$rounds = get_rounds ($conn, $id);
			display_round ($rounds, $const_status);
			$conn->close ();
		?>
		</div>
	</body>
</html>