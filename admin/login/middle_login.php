<?php
$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
if (substr_count($_SERVER['DOCUMENT_ROOT'], 'DaisyQuizzes') == 0) {
	$DOCUMENT_ROOT = $DOCUMENT_ROOT . '/DaisyQuizzes';
}
$str = $DOCUMENT_ROOT .	'/database.php';

include $str;

function checkUserExists($conn, $username, $password)
{
	$sql = "SELECT * FROM daisy_admin where username='$username' and password='" . md5($password) . "'";
	//echo $sql;
	$result = $conn->query($sql);
	if (!$result) {
		return false;
	}

	if ($result->num_rows == 0) {
		return false;
	}

	$result = $result->fetch_assoc();
	return $result['id'];
}

$conn = db_connect();
$username = $_POST['username'];
$password = $_POST['password'];
$id = checkUserExists($conn, $username, $password);
if ($id == false)
	echo "<p> Tên tài khoản hoặc mật khẩu không tồn tại, đăng nhập thất bại </p>";
else {
	session_start();
	$_SESSION['userid'] = $id;
	$_SESSION['username'] = $username;
	$_SESSION['flash_username'] = "Xin chào, " . $username . "!";
	header('Location: ../dashboard.php');
}
?>
<a href='./index.php'>Thử lại</a>
<?php
$conn->close();
?>