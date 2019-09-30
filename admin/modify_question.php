<?php
	$str = $_SERVER['DOCUMENT_ROOT'].'/DaisyQuizzes/middleware/auth_admin.php';
	include $str;
	
	
	if (!checkLoggedIn ())	{	
		header('Location: ./login.php');
		exit ();	
	}
	
	function db_fetch_question ($conn, $collection, $question) {
		$sql = "SELECT id, body, choice_a, choice_b, choice_c, choice_d FROM daisy_question WHERE collection_id = $collection and id=$question";
		$q = $conn->query ($sql);
		$row = $q->fetch_assoc ();
		return $row;
	}
	
	function modifyQuestion ($conn, $collection, $question) {
		$sql = "UPDATE daisy_question SET ".
			"body='".$question['body']."', ".
			"choice_a='".$question['choice_a']."', ".
			"choice_b='".$question['choice_b']."', ".
			"choice_c='".$question['choice_c']."', ".
			"choice_d='".$question['choice_d']."' WHERE id=".$question['id'];
		$result = $conn->query ($sql);
	}
	
	$userid = $_SESSION ['userid'];
	if (!isset ($_GET['k'])) 
		exit ('Không tìm thấy bộ câu hỏi');
	
	$collection = $_GET ['k'];
	$question_id = $_GET['question'];
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
				modifyQuestion ($conn, $collection, $_POST);
				header("Location: ./modify.php?k=$collection");
			} 
			
			$question = db_fetch_question ($conn, $collection, $question_id);
			$conn->close ();
		?>
		
		<form action="modify_question.php?k=<?php echo $collection?>&question=<?php echo $question_id?>" method="post">				
			  <div class="container">
				
				<input type="text" placeholder="Nội dung" name="body" value="<?php echo $question['body']?>" required><br>
				<input type="text" placeholder="A (đáp án):" name="choice_a" value="<?php echo $question['choice_a']?>" required><br>
				<input type="text" placeholder="B:" name="choice_b" value="<?php echo $question['choice_b']?>" required><br>
				<input type="text" placeholder="C:" name="choice_c" value="<?php echo $question['choice_c']?>" required><br>
				<input type="text" placeholder="D:" name="choice_d" value="<?php echo $question['choice_d']?>" required><br>
				<input type="hidden" name="id" value=<?php echo $question['id']?>>
				<button type="submit">Cập nhật</button>    
			  </div>			  
		</form>
		
		<div class="redirect">
			<a href="./modify.php?k=<?php echo $collection?>"> Quay lại xem các câu hỏi </a>
		</div>
	</body>
</html>