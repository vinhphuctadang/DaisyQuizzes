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
	header('Location: ../login');
	exit();
}

function getStatus($conn, $round)
{
	$sql = "SELECT status, access_token FROM daisy_round WHERE round='$round'";
	$result = $conn->query($sql);
	if ($result->num_rows == 0)
		return -1;
	$result = $result->fetch_assoc();
	return $result;
}

function setStatus($conn, $round, $status)
{
	$sql = "UPDATE daisy_round SET status=$status, question_no=1 WHERE round='$round'";
	$conn->query($sql);
}

function findLoggedPlayer($conn, $round)
{

	$sql = "SELECT name, created_time, score FROM daisy_player_round WHERE round='$round' ORDER BY score DESC";
	$result = $conn->query($sql);
	$list = [];
	while ($row = $result->fetch_assoc()) {
		$list[] = $row;
	}
	return $list;
}


// TODO: Kiểm tra quyền admin cho phương thức này
$round = $_GET['k'];
$conn = db_connect();
$result = findLoggedPlayer($conn, $round);
$statusTuple = getStatus($conn, $round);

$status = $statusTuple['status'];
$token = $statusTuple['access_token'];

if (isset($_POST['change'])) {
	$status = $_POST['change'];
	setStatus($conn, $round, $status);
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
	<p> <a href="dashboard.php">Quay lại trang chính </a></p>
	<p>Trạng thái: <p id="status"><?php echo $status; ?></p>
	</p>
	<p>Mã truy cập cho nhà phát triển: <i> <?php echo $token; ?> </i></p>
	<form action="modify_round.php?k=<?php echo $round; ?>" method="post">
		<input type="radio" name="change" value="0" <?php echo ($status == 0 ? 'checked' : ''); ?>> Đóng <br>
		<input type="radio" name="change" value="1" <?php echo ($status == 1 ? 'checked' : ''); ?>> Mở và chờ đợi <br>
		<input type="radio" name="change" value="2" <?php echo ($status == 2 ? 'checked' : ''); ?>> Diễn ra <br>
		<input type="submit" value="Thay đổi trạng thái">
	</form>
	<div class="container">
		<p> Câu hỏi hiện tại: <p id="number">1</p>
		</p>
		<p id="time">10</p>

		<table border="1">
			<tr>
				<th>Số thứ tự</th>
				<th>Tên người chơi</th>
				<th>Dấu thời gian</th>
				<th>Điểm </th>
			</tr>
			<?php
			$cnt = 0;
			foreach ($result as $each) {
				$cnt++;
				echo "<tr>";
				echo "<td>" . $cnt . "</td>";
				echo "<td>" . $each['name'] . "</td>";
				echo "<td>" . $each['created_time'] . "</td>";
				echo "<td>" . $each['score'] . "</td>";
				echo "</tr>";
			}
			?>
		</table>
	</div>
	<script>
		var timing = 10;
		var x = null;

		function startInterval() {
			render();
			x = setInterval("onInterval()", 1000);
		}

		function render() {
			document.getElementById("time").innerText = timing;
		}

		function onInterval() {
			timing -= 1;
			render();
			if (timing == 0) {

				timing = 10;
				increaseQuestionNumber(updateQuestionNumber);
			}
		}

		function increaseQuestionNumber(onDoneResp) {
			httprqIQ = new XMLHttpRequest();

			httprqIQ.onreadystatechange = function() {
				if (httprq.readyState == 4 && httprq.status == 200) {
					onDoneResp(httprqIQ.responseText);
				}
			}
			httprqIQ.open("GET", "api.php?method=change_question&token=<?php echo $token ?>&change=1&nextupdate=10", true);
			httprqIQ.send();
			//alert ("New question updated");
		}

		function updateQuestionNumber(msg) {

			httprq = new XMLHttpRequest();
			httprq.onreadystatechange = function() {
				if (httprq.readyState == 4 && httprq.status == 200) {
					var txt = httprq.responseText;
					var jsn = JSON.parse(txt);
					document.getElementById("number").innerText = jsn.result;
				}
			}

			httprq.open("GET", "api.php?method=get_question_no&token=<?php echo $token ?>", true);
			httprq.send();
		}

		function stopInterval() {
			clearInterval(x);
		}

		var status = document.getElementById("status").innerText;
		if (status == '2') {
			startInterval();
			updateQuestionNumber();
		}
	</script>
</body>

</html>