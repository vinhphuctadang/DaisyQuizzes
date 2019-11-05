<?php
$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
if (substr_count($_SERVER['DOCUMENT_ROOT'], 'DaisyQuizzes') == 0) {
	$DOCUMENT_ROOT = $DOCUMENT_ROOT . '/DaisyQuizzes';
}
$str = $DOCUMENT_ROOT . '/middleware/auth_admin.php';
include $str;

if (!checkLoggedIn()) {
	header('Location: ./login');
	exit();
}

$id = $_SESSION['userid'];
$username = $_SESSION['username'];

include $DOCUMENT_ROOT . '/database.php'; // parent directory

function delete_round($conn, $userid, $round)
{
	$sql = "DELETE FROM daisy_round WHERE round = '$round' and admin_id=$userid";
	$result = $conn->query($sql);
	if ($result == FALSE)
		return FALSE;
	return TRUE;
}

// TODO: (Nguy hiểm): Hãy kiểm tra round đó có phải do đúng admin này tạo hay không (bảo mật)

$conn = db_connect();
$round = $_GET['k'];
delete_round($conn, $id, $round);
$conn->close();
header("Location: ./dashboard.php");
