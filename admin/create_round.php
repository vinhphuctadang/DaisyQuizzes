<?php
$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
if (substr_count($_SERVER['DOCUMENT_ROOT'], 'DaisyQuizzes') == 0) {
	$DOCUMENT_ROOT = $DOCUMENT_ROOT . '/DaisyQuizzes';
}
$str = $DOCUMENT_ROOT . '/middleware/auth_admin.php';
include $str;
$str = $DOCUMENT_ROOT . '/database.php';
include $str;

if (!checkLoggedIn()) {
	header('Location: ./login');
	exit();
}

function db_fetch_question_ids($conn, $collection)
{
	$sql = "SELECT id from daisy_question WHERE collection_id = $collection";
	$result = $conn->query($sql);
	if ($result->num_rows == 0)
		return [];
	$outp =  [];
	while ($row = $result->fetch_assoc()) {
		$id = $row['id'];
		$outp[] = $id;
	}
	return $outp;
}

function generate($conn, $userid, $round, $collection)
{

	$token = db_token($round, "");
	$sql = "INSERT INTO daisy_round (collection, status, round, admin_id, question_no, access_token) VALUES ($collection, 0, '$round', $userid, 0, '$token')";
	// echo $sql."<br>";
	$result = $conn->query($sql);
	$sql = "DELETE FROM daisy_shuffle_content WHERE round='$round'";
	// echo $sql."<br>";
	$result = $conn->query($sql);
	$outp = db_fetch_question_ids($conn, $collection);
	shuffle($outp);
	$cnt = 0;
	foreach ($outp as $value) {
		$cnt++;
		$sql = "INSERT INTO daisy_shuffle_content (round, question_no, question_id) VALUES ('$round', $cnt, $value)";
		$conn->query($sql);
	}
}

$collection = $_GET['k'];
$userid = $_SESSION['userid'];
$conn = db_connect();
$collection_name = db_authen($conn, $userid, $collection);

if ($collection_name == false) {
	$conn->close();
	die("Không tìm thấy bộ câu hỏi được yêu cầu");
}

if (isset($_GET['round'])) {
	$round = $_GET['round'];
	generate($conn, $userid, $round, $collection);
	$conn->close();
	$_SESSION['flash_alert'] = "Tạo vòng chơi thành công!";
	header("Location: ./dashboard.php");
	exit();
}
$conn->close();
