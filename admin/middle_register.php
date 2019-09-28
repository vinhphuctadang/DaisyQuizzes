<?php
	include '../database.php';
	
	function checkUserExists ($conn, $username) {
		$sql = "SELECT * FROM daisy_admin_login where username='$username' LIMIT 1";
		$result = $conn->query ($sql);
		if ($result->num_rows == 0) {
			return false;
		}
		return true;
	}
	
	function addUser ($conn, $username, $password) {
		$sql = "INSERT INTO daisy_admin_login (username, password) VALUES ('$username', '".md5 ($password)."')";
		echo $sql."<br>";
		$result = $conn->query ($sql);
	}
	
	
	$conn = db_connect ();
	$username = $_POST ['username'];
	$password = $_POST ['password'];
	$repassword = $_POST ['repassword'];
	if ($password != $repassword) 
		die ("Password nhập lại không khớp");
	if (checkUserExists ($conn, $username)) 
		die ("Người dùng đã tồn tại");
	addUser ($conn, $username, $password);
	echo "Đăng ký thành công";
?>
	