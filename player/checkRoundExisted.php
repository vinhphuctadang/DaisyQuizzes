<?php
$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
if (substr_count($_SERVER['DOCUMENT_ROOT'], 'DaisyQuizzes') == 0) {
    $DOCUMENT_ROOT = $DOCUMENT_ROOT . '/DaisyQuizzes';
}
include $DOCUMENT_ROOT . '/database.php';
include serverpath('middleware/auth.php');

$conn = db_connect();
$round = $_POST['round'];
$player = $_POST['player'];

$sql = "SELECT status FROM daisy_round WHERE round='$round'";
$result = $conn->query($sql);
if ($result->num_rows == 0) die("Không tìm thấy vòng chơi yêu cầu");

$conn->close();
