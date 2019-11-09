<?php
$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
if (substr_count($_SERVER['DOCUMENT_ROOT'], 'DaisyQuizzes') == 0) {
	$DOCUMENT_ROOT = $DOCUMENT_ROOT . '/DaisyQuizzes';
}
include $DOCUMENT_ROOT . '/database.php'; // parent directory
$str = $DOCUMENT_ROOT . '/middleware/auth_admin.php';
include $str;

$userid = $_SESSION['userid'];
if (!isset($_GET['k']))
	exit('NOT FOUND');

$collection = $_GET['k'];
$question_id = $_GET['question'];

if (!checkLoggedIn()) {
	header('Location: ../login');
	exit();
}

function db_fetch_question($conn, $collection, $question)
{
	$sql = "SELECT id, body, choice_a, choice_b, choice_c, choice_d FROM daisy_question WHERE collection_id = $collection and id=$question";
	$q = $conn->query($sql);
	$row = $q->fetch_assoc();
	return $row;
}

function modifyQuestion($conn, $question_id, $question)
{
	$sql = "UPDATE daisy_question SET " .
		"body='" . $question['body'] . "', " .
		"choice_a='" . $question['choice_a'] . "', " .
		"choice_b='" . $question['choice_b'] . "', " .
		"choice_c='" . $question['choice_c'] . "', " .
		"choice_d='" . $question['choice_d'] . "', " .
		"explaination='" . $question['explain'] . "' WHERE id=" . $question_id;
	$result = $conn->query($sql);
}

$conn = db_connect();
if (!db_authen($conn, $userid, $collection)) {
	$conn->close();
	die("Không tìm thấy bộ câu hỏi được yêu cầu"); // hãy thay thế bằng một gateway nào đó
}

if (isset($_POST['body'])) {
	modifyQuestion($conn, $question_id, $_POST);
	$_SESSION['flash_alert'] = "Chỉnh sửa câu hỏi thành công";
	header("Location: ./index.php?k=$collection");
}
