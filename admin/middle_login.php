<?php
$str = $_SERVER['DOCUMENT_ROOT'] . '/DaisyQuizzes/database.php';

include $str;

function checkUserExists($conn, $username, $password)
{
	$sql = "SELECT * FROM daisy_admin where username='$username' and password='" . md5($password) . "'";
	$result = $conn->query($sql);
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
	echo "<p> Đăng nhập thất bại </p>";
else {
	session_start();
	$_SESSION['userid'] = $id;
	$_SESSION['username'] = $username;
	header('Location: ./dashboard.php');
}
?>
<a href='./login.php'>Thử lại</a>
<?php
$conn->close();
?>