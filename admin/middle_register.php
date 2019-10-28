<?php

	include($_SERVER['DOCUMENT_ROOT'].'/database.php');
	include($_SERVER['DOCUMENT_ROOT'].'/middleware/auth_admin.php');
	
	function checkUserExists ($conn, $username) {
		$sql = "SELECT * FROM daisy_admin where username='$username' LIMIT 1";
		$result = $conn->query ($sql);
		if ($result->num_rows == 0) {
			return false;
		}
		return true;
	}
	
	function addUser ($conn, $username, $password) {
		$sql = "INSERT INTO daisy_admin (username, password) VALUES ('$username', '".md5 ($password)."')";
		$result = $conn->query ($sql);
		return $result;
	}
	
	function findID ($conn, $username){
		$sql = "SELECT id FROM daisy_admin WHERE username='$username'";
		$result = $conn->query ($sql);
		$result = $result->fetch_assoc ();
		return $result ['id'];
	}
	
	
	$conn = db_connect ();
	$username = $_POST ['username'];
	$password = $_POST ['password'];
	$repassword = $_POST ['repassword'];
	if ($password != $repassword) 
		die ("Password nhập lại không khớp");
	if (checkUserExists ($conn, $username)) 
		die ("Người dùng đã tồn tại");
	if (addUser ($conn, $username, $password) != TRUE) {
		die ("Đăng ký không thành công, có thể tên đăng nhập đã tồn tại, <a href='./register.html'>Thử lại</a>");
	}
	
	$_SESSION["username"] = $username;
	$_SESSION["userid"] = findID ($conn, $username);
	
	echo "<p> Đăng ký thành công </p>";
	echo "<a href='./dashboard.php'>Đi đến các bộ câu hỏi ngay</a>";	
	$conn->close ();
	
?>