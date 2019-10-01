<?php
		include($_SERVER['DOCUMENT_ROOT'].'/DaisyQuizzes/middleware/auth_admin.php');
		if (!checkLoggedIn ()){
			header('Location: ./login.php');
			exit ();	
		}	
		include($_SERVER['DOCUMENT_ROOT'].'/DaisyQuizzes/database.php');
		function db_fetch_questions ($conn, $collection) {
			$sql = "SELECT id, body, choice_a, choice_b, choice_c, choice_d FROM daisy_question WHERE collection_id = $collection";
			$result = [];
			$q = $conn->query ($sql);
			while ($row = $q->fetch_assoc ()) {
				$result[] = $row;
			}
			return $result;
		}
		
		function set_collection ($conn, $userid, $collection, $collection_name){
			$sql = "UPDATE daisy_collection SET name='$collection_name' WHERE id=$collection and admin_id=$userid";
			$conn->query ($sql);
		}
		$id = $_SESSION['userid'];
		
		// nên viết 1 hàm main () cho đơn giản
		$userid = $_SESSION ['userid'];
		$collection = $_GET ['k'];
		$conn = db_connect ();	
		$collection_name = db_authen ($conn, $userid, $collection);
		
		if ($collection_name == false) {
			$conn->close ();
			die ("Không tìm thấy bộ câu hỏi được yêu cầu");
		}
		
		if (isset ($_POST ['collection_name'])) {
			$collection_name = $_POST['collection_name'];
			set_collection ($conn, $id, $collection, $collection_name);
		}

		$data = db_fetch_questions ($conn, $collection);
		?>
<html>	
	<head>
		<title><?php echo $collection_name;?></title>
		<meta charset="utf-8">
		<link href="./index.css" rel="stylesheet" type="text/css">
	</head>
	<body>
	
		<form action="modify.php?k=<?php echo $collection?>" method="post">
			<input type="text" name="collection_name" value="<?php echo $collection_name?>">
			<input type="submit" value="Đổi">
		</form>
		
		<?php
		echo "<div class='heading-pane'>";
		echo '<a href="./dashboard.php">Trở lại trang chính</a>';
		echo '<a href="./add_question.php?k='.$collection.'">Thêm</a>';
		echo "</div>";
		$cnt = 0;
		
		foreach ($data as $each) {
			$cnt++;
			echo "<div class='question-pane'>";
			echo "<div class='question-action-pane'>";
			echo "<a href='delete_question.php?k=$collection&question=".$each['id']."'>Xóa</a>";
			echo "<a href='./modify_question.php?k=$collection&question=".$each['id']."'>Chỉnh sửa</a>";
			echo "</div>";
			echo "<div class='body-pane'>";
			
			echo "$cnt."
				.$each['body']."<br>";
			echo 'A (đáp án): '.$each['choice_a']."<br>";
			echo 'B: '.$each['choice_b']."<br>";
			echo 'C: '.$each['choice_c']."<br>";
			echo 'D: '.$each['choice_d']."<br>";
			echo "</div>";
			echo "</div>";
		}
		echo '<a href="./add_question.php?k='.$collection.'">Thêm</a><br>';
		?>
		
	<?php
		$conn->close ();	
	?>
	</body>
</html>
