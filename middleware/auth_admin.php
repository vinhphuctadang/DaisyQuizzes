<?php	
	include 'auth.php';

	function checkLoggedIn () {
		if (isset ($_SESSION['userid']))	{	
			return true;
		}
		return false;
	}
	
	function db_authen ($conn, $userid, $collection) {
		$sql = "SELECT admin_id, name FROM daisy_collection WHERE id = $collection";
		$result = $conn->query ($sql);
		if ($result->num_rows == 0) 
			return false;
		$result = $result->fetch_assoc ();
		if ($result['admin_id'] != $userid) 
			return false;
		return $result['name'];		
	}
?>