<?php
$str = $_SERVER['DOCUMENT_ROOT'] . '/DaisyQuizzes/middleware/auth_admin.php';
include $str;
$str = $_SERVER['DOCUMENT_ROOT'] . '/DaisyQuizzes/database.php';
include $str;

if (!checkLoggedIn()) {
	header('Location: ./login.php');
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
	$sql = "INSERT INTO daisy_round_collection (collection, status, round, admin_id, question_no, access_token) VALUES ($collection, 0, '$round', $userid, 0, '$token')";
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

if (isset($_POST['round'])) {
	$round = $_POST['round'];
	generate($conn, $userid, $round, $collection);
	$conn->close();
	header("Location: ./dashboard.php");
	exit();
}
$conn->close();
?>
<html>

<head>
	<title>dashboard</title>
	<meta charset="utf-8">
	<link href="./index.css" rel="stylesheet" type="text/css">
</head>

<body>

	<form action="create_round.php?k=<?php echo $collection ?>" method="post">
		<div class="container">
			<input type="text" placeholder="Mã vòng (là duy nhất trong hệ thống)" name="round" required><br>
			<button type="submit">TIẾP TỤC</button>
		</div>
	</form>
</body>

</html>