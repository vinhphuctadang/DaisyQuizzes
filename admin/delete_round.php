<?php

	$str = $_SERVER['DOCUMENT_ROOT'].'/middleware/auth_admin.php';
	include $str;
	
	if (!checkLoggedIn ())	{	
		header('Location: ./login.php');
		exit ();	
	}
	
	$id = $_SESSION['userid'];
	$username = $_SESSION['username'];
		
	include $_SERVER['DOCUMENT_ROOT'].'/database.php'; // parent directory
	
	function delete_round ($conn, $userid, $round) {
		$sql = "DELETE FROM daisy_round WHERE round = '$round' and admin_id=$userid";
		$result = $conn->query($sql);
		if ($result == FALSE)
			return FALSE;
		return TRUE;
	}
	
	// TODO: (Nguy hiểm): Hãy kiểm tra round đó có phải do đúng admin này tạo hay không (bảo mật)
	
	$conn = db_connect ();
	$round = $_GET ['k'];
	delete_round ($conn, $id, $round);
	$conn->close ();
	header("Location: ./dashboard.php");
?>