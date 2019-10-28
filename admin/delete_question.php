<?php
	$str = $_SERVER['DOCUMENT_ROOT'].'/middleware/auth_admin.php';
	include $str;
	include $_SERVER['DOCUMENT_ROOT'].'/database.php';
	
	
	if (!checkLoggedIn ())	{	
		header('Location: ./login.php');
		exit ();	
	}
	
	function deleteQuestion ($conn, $collection, $question) {
		$sql = "DELETE FROM daisy_question WHERE collection_id=$collection and id = $question";
		$result = $conn->query ($sql);
	}
	
	$userid = $_SESSION ['userid'];
	if (!isset ($_GET['k'])) 
		exit ('Không tìm thấy bộ câu hỏi');
	
	$collection = $_GET ['k'];
	$question_id = $_GET['question'];
	
	$conn = db_connect ();
	deleteQuestion ($conn, $collection, $question_id);
	$conn->close ();
	
	header("Location: ./modify.php?k=$collection");
?>