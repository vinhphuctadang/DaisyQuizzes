<?php
	$str = $_SERVER['DOCUMENT_ROOT'].'/DaisyQuizzes/middleware/auth_admin.php';
	include $str;
	
	
	if (!checkLoggedIn ())	{	
		header('Location: ./login.php');
		exit ();	
	}
	
	function addQuestion ($conn, $collection, $question) {
		$sql = "INSERT INTO daisy_question (body, choice_a, choice_b, choice_c, choice_d) VALUES (".$question['body'].", ".$question['choice_a'].", ".$question['choice_b'].", ".$question['choice_c'].", ".$question['choice_d'].")";
		$result = $conn->query ($sql);
	}
	
	$userid = $_SESSION ['userid'];
	if (!isset ($_GET['k'])) 
		exit ('Không tìm thấy bộ câu hỏi');
	
	$collection = $_GET ['k'];
?>

<html>	
	<head>
		<title>dashboard</title>
		<meta charset="utf-8">
		<link href="./index.css" rel="stylesheet" type="text/css">
	</head>
	
	<body>
		<?php
			include $_SERVER['DOCUMENT_ROOT'].'/DaisyQuizzes/database.php'; // parent directory
			
			$conn = db_connect ();
			if (!db_authen ($conn, $userid, $collection)) {
				$conn->close ();
				die ("Không tìm thấy bộ câu hỏi được yêu cầu"); // hãy thay thế bằng một gateway nào đó
			}			
			
			if (isset ($_POST['body'])) {
				addQuestion ($conn, $collection, $_POST);
				header("Location: ./add_question.php?k=$collection");
			}
			
			$conn->close ();
		?>
		
		<form action="add_question.php?k=<?php echo $collection?>" method="post">				
			  <div class="container">
				<input type="text" placeholder="Nội dung" name="body" required><br>
				<input type="text" placeholder="A (đáp án):" name="choice_a" required><br>
				<input type="text" placeholder="B" name="choice_b" required><br>
				<input type="text" placeholder="C" name="choice_c" required><br>
				<input type="text" placeholder="D" name="choice_d" required><br>
				<button type="submit">Thêm</button>    
			  </div>
		</form>
	</body>
</html>