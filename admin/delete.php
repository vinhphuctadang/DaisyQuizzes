<?php

	$str = $_SERVER['DOCUMENT_ROOT'].'/DaisyQuizzes/middleware/auth_admin.php';
	include $str;
	
	if (!checkLoggedIn ())	{	
		header('Location: ./login.php');
		exit ();	
	}
	
	$id = $_SESSION['userid'];
	$username = $_SESSION['username'];
		
	include $_SERVER['DOCUMENT_ROOT'].'/DaisyQuizzes/database.php'; // parent directory
	
	function delete_collection ($conn, $userid, $collection) {
		$sql = "DELETE FROM daisy_collection WHERE id = $collection";
		$conn->query($sql);
	}
	
	$conn = db_connect ();
	$collection = $_GET ['k'];
	delete_collection ($conn, $id, $collection);
	$conn->close ();
	header("Location: ./dashboard.php");
?>
	
	