<?php
$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
if (substr_count($_SERVER['DOCUMENT_ROOT'], 'DaisyQuizzes') == 0) {
	$DOCUMENT_ROOT = $DOCUMENT_ROOT . '/DaisyQuizzes';
}
include($DOCUMENT_ROOT . '/middleware/auth_admin.php');
if (!checkLoggedIn()) {
	header('Location: ../login');
	exit();
}
include($DOCUMENT_ROOT . '/database.php');
function db_fetch_questions($conn, $collection)
{
	$sql = "SELECT id, body, choice_a, choice_b, choice_c, choice_d FROM daisy_question WHERE collection_id = $collection";
	$result = [];
	$q = $conn->query($sql);
	while ($row = $q->fetch_assoc()) {
		$result[] = $row;
	}
	return $result;
}

function set_collection($conn, $userid, $collection, $collection_name)
{
	$sql = "UPDATE daisy_collection SET name='$collection_name' WHERE id=$collection and admin_id=$userid";
	$conn->query($sql);
}
$id = $_SESSION['userid'];

// nên viết 1 hàm main () cho đơn giản
$userid = $_SESSION['userid'];
$collection = $_GET['k'];
$conn = db_connect();
$collection_name = db_authen($conn, $userid, $collection);

if ($collection_name == false) {
	$conn->close();
	die("Không tìm thấy bộ câu hỏi được yêu cầu");
}

if (isset($_POST['collection_name'])) {
	$collection_name = $_POST['collection_name'];
	set_collection($conn, $id, $collection, $collection_name);
	$_SESSION['flash_alert'] = " Cập nhật tên bộ câu hỏi thành công!";
	header('Location: index.php?k=' . $collection);
	$conn->close();
}
