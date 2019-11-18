<?php
$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
if (substr_count($_SERVER['DOCUMENT_ROOT'], 'DaisyQuizzes') == 0) {
	$DOCUMENT_ROOT = $DOCUMENT_ROOT . '/DaisyQuizzes';
}
include $DOCUMENT_ROOT . '/database.php';
include serverpath('middleware/auth.php');

$GLOBALS['apikey'] = 'daisy2610';
function checkExists($conn, $player, $round)
{
	$sql = "SELECT status FROM daisy_round WHERE round='$round'";
	$result = $conn->query($sql);
	if ($result->num_rows == 0) die("* Không tìm thấy vòng chơi yêu cầu");
	// die("Không tìm thấy vòng chơi yêu cầu, có thể nó đã bị xóa khỏi CSDL");
	$result = $result->fetch_assoc();
	if ($result['status'] == 0)
		die("Không thể đăng nhập, vòng chơi đã kết thúc");

	$sql = "SELECT score FROM daisy_player_round where round='$round' and name='$player'";
	$result = $conn->query($sql);
	if ($result->num_rows > 0)
		return true;
	return false;
}

function addPlayer($conn, $player, $round)
{
	$sql = "INSERT INTO daisy_player_round (name, round, score, token) VALUES ('$player', '$round', 0, '" . md5($player . $GLOBALS['apikey']) . "')";
	$result = $conn->query($sql);
}

if (!isset($_POST['round']))
	die("Không tìm thấy vòng chơi");
if (!isset($_POST['player']))
	die("Không tìm thấy người chơi");
#echo json_encode ($_POST);
$conn = db_connect();
$round = $_POST['round'];
$player = $_POST['player'];
if (checkExists($conn, $player, $round)) {
	// echo "Tên tài khoản \"$player\" với vòng chơi này đã tồn tại, hãy đổi tên<br>";
	// echo "Trở lại <a href='../'>trang đầu</a>";
	$_SESSION['flash_alert'] = "Tên tài khoản '" . $player . "' với vòng chơi này đã tồn tại, hãy đổi tên";
	// echo $_SESSION['flash_alert'];
	header("Location: ../index.php");
} else {
	addPlayer($conn, $player, $round);
	$_SESSION['round'] = $round;
	$token = md5($player . $GLOBALS['apikey']);
	$_SESSION['token'] = $token;

	$NODEJS_HOST_SERVER = $GLOBALS["NODEJS_HOST_SERVER"]; // Thông báo ai đó đã đăng nhập vào vòng chơi
	file_get_contents($NODEJS_HOST_SERVER . '/player/' . $round . "/" . $token);
	header('Location: ./main.php');
}

$conn->close();
?>

<!-- #<script> 
#    var url= "./main.php";
#    window.location = url; 
#</script>  -->