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
	//header("Location: ./add_question.php?k=$collection");
}

$conn->close();
?>

<html>

<head>
	<title>Thêm câu hỏi</title>
	<meta charset="utf-8">
	<link href="<?php echo assets('css/admin/formstyle.css'); ?>" rel="stylesheet" type="text/css">
</head>

<body>
	<?php
	$conn = db_connect();
	if (!db_authen($conn, $userid, $collection)) {
		$conn->close();
		die("Không tìm thấy bộ câu hỏi được yêu cầu"); // hãy thay thế bằng một gateway nào đó
	}

	if (isset($_SESSION['message'])) {
		?>
		<p class="message"><?php echo $_SESSION['message'];
								$_SESSION['message'] = null; ?></p>
	<?php
	}
	?>

	<form class="form-style-5" action="add_question.php?k=<?php echo $collection ?>" method="post">
		<div class="container">
			<textarea placeholder="Nội dung" name="body" required></textarea><br>
			<textarea type="text" placeholder="A (đáp án):" name="choice_a" required></textarea><br>
			<textarea type="text" placeholder="B" name="choice_b" required></textarea><br>
			<textarea type="text" placeholder="C" name="choice_c" required></textarea><br>
			<textarea type="text" placeholder="D" name="choice_d" required></textarea><br>
			<textarea type="text" placeholder="Giải thích cho câu trả lời" name="explain"></textarea><br>
			<button type="submit">Thêm</button>
		</div>

	</form>

	<div class="redirect">
		<a href="./modify.php?k=<?php echo $collection ?>"> Quay lại chỉnh sửa </a>
	</div>
</body>

</html>