<?php
$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
if (substr_count($_SERVER['DOCUMENT_ROOT'], 'DaisyQuizzes') == 0) {
	$DOCUMENT_ROOT = $DOCUMENT_ROOT . '/DaisyQuizzes';
}
include $DOCUMENT_ROOT . '/database.php'; // parent directory
$str = $DOCUMENT_ROOT . '/middleware/auth_admin.php';
include $str;

if (!checkLoggedIn()) {
	header('Location: ./login');
	exit();
}

$id = $_SESSION['userid'];
$username = $_SESSION['username'];



function delete_collection($conn, $userid, $collection)
{
	$sql = "DELETE FROM daisy_collection WHERE id = $collection";
	$conn->query($sql);
}

$conn = db_connect();
$collection = $_GET['k'];
delete_collection($conn, $id, $collection);
$conn->close();
$_SESSION['flash_alert'] = "Xoá bộ câu hỏi thành công!";
header("Location: ./dashboard.php");
