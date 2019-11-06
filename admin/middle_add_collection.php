<?php
$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
if (substr_count($_SERVER['DOCUMENT_ROOT'], 'DaisyQuizzes') == 0) {
	$DOCUMENT_ROOT = $DOCUMENT_ROOT . '/DaisyQuizzes';
}
$str = $DOCUMENT_ROOT . '/middleware/auth_admin.php';
include $str;
$str = $DOCUMENT_ROOT . '/database.php';
include $str;

function add_collection($conn, $userid, $collection)
{
	$sql = "INSERT INTO daisy_collection (name, admin_id) VALUES ('$collection', $userid)";
	$conn->query($sql);
}
$id = $_SESSION['userid'];

if (isset($_GET['collection'])) {
	$collection = $_GET['collection'];
	$conn = db_connect();
	add_collection($conn, $id, $collection);
	$_SESSION['flash_alert'] = "Thêm bộ câu hỏi thành công!";
	header('Location: ./dashboard.php');
	$conn->close();
	exit();
}
