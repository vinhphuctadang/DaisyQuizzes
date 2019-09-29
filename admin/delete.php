<?php

	session_start (); // middleware 
	if (!isset ($_SESSION['userid']))	{	
		header('Location: ./login.php');
		exit ();	
	}
	$id = $_SESSION['userid'];
	$username = $_SESSION['username'];
		
	include '../database.php'; // parent directory
	
	function delete_collection ($conn, $userid, $collection) {
		$sql = "DELETE FROM daisy_admin_collection WHERE admin_id=$userid and collection_id = $collection";
		
		$conn->query($sql);
		$sql = "DELETE FROM daisy_collection WHERE id = $collection";
		$conn->query($sql);
	}
	
	$conn = db_connect ();
	$collection = $_GET ['k'];
	delete_collection ($conn, $id, $collection);
	$conn->close ();
	header("Location: ./dashboard.php");
?>
	
	