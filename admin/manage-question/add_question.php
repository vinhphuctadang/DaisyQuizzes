<?php
$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
if (substr_count($_SERVER['DOCUMENT_ROOT'], 'DaisyQuizzes') == 0) {
	$DOCUMENT_ROOT = $DOCUMENT_ROOT . '/DaisyQuizzes';
}
$str = $DOCUMENT_ROOT . '/middleware/auth_admin.php';
include $str;

if (!checkLoggedIn()) {
	header('Location: ../login');
	exit();
}

include $DOCUMENT_ROOT . '/database.php'; // parent directory

function addQuestion($conn, $collection, $question)
{
	$explain = $question['explain'];
	$sql = "INSERT INTO daisy_question (body, choice_a, choice_b, choice_c, choice_d, collection_id, explaination) " .
		"VALUES ('" . $question['body'] . "', '" . $question['choice_a'] . "', '" . $question['choice_b'] . "', '" . $question['choice_c'] . "', '" . $question['choice_d'] . "', $collection, '$explain')";
	$result = $conn->query($sql);
}

$userid = $_SESSION['userid'];
if (!isset($_GET['k']))
	exit('Không tìm thấy bộ câu hỏi');

$collection = $_GET['k'];

$conn = db_connect();
if (!db_authen($conn, $userid, $collection)) {
	$conn->close();
	die("Không tìm thấy bộ câu hỏi được yêu cầu"); // hãy thay thế bằng một gateway nào đó
}

if (isset($_POST['body'])) {
	addQuestion($conn, $collection, $_POST);
	$_SESSION['message'] = "Thêm câu hỏi thành công";
	$_SESSION['flash_alert'] = "Thêm câu hỏi thành công";
	header("Location: ./index.php?k=$collection");
}

$conn->close();
