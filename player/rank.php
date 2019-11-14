<?php
	$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
	if (substr_count($_SERVER['DOCUMENT_ROOT'], 'DaisyQuizzes') == 0) {
		$DOCUMENT_ROOT = $DOCUMENT_ROOT . '/DaisyQuizzes';
	}
	include $DOCUMENT_ROOT . '/database.php';
	include serverpath('middleware/auth.php');

	function findLoggedPlayer($conn, $round)
	{

		$sql = "SELECT name, created_time, score FROM daisy_player_round WHERE round='$round' ORDER BY score DESC LIMIT 100";
		$result = $conn->query($sql);
		$list = [];
		while ($row = $result->fetch_assoc()) {
			$list[] = $row;
		}
		return $list;
	}


	function getScore ($conn, $round, $token) {
		$sql = "SELECT score FROM daisy_player_round WHERE round='$round' and token='$token'";
		$result = $conn->query($sql);
		$row = $result->fetch_assoc ();
		return $row['score'];
	}

	function findRank ($conn, $round, $score) {
		$sql = "SELECT COUNT(*) as cnt FROM daisy_player_round WHERE round='$round' and score>$score";
		$result = $conn->query($sql);
		$row = $result->fetch_assoc ();
		return $row['cnt']+1;
	}


	if (!isset($_SESSION['round']))
		die("Không tìm thấy vòng chơi yêu cầu, có thể bạn cần đăng nhập lại");

	$conn = db_connect();
	$round = $_SESSION['round'];
	$token = $_SESSION['token'];

	$result = findLoggedPlayer ($conn, $round);
	$myscore = getScore($conn, $round, $token);
	$myrank = findRank ($conn, $round, $myscore);

	$conn->close();

?>

<html>
<head>
	<title>Daisy Quizzes</title>
	<meta charset="utf-8">
	<link href="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.css" rel="stylesheet">
	<script src="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.js"></script>
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<link href="./styles.css" rel="stylesheet" type="text/css">
</head>

<body>
	<p> Your score: <?php echo $myscore; ?> </p>
	<p> Your rank: <?php echo $myrank;?> </p>

	<div class="mdc-data-table" data-mdc-auto-init="MDCDataTable">
		<table class="mdc-data-table__table" aria-label="Dessert calories">
			<thead>
				<tr class="mdc-data-table__header-row">
					<th class="mdc-data-table__header-cell text-center" role="columnheader" scope="col">STT</th>
					<th class="mdc-data-table__header-cell" role="columnheader" scope="col">Tên người chơi<i></i></th>
					<th class="mdc-data-table__header-cell" role="columnheader" scope="col">Dấu thời gian</th>
					<th class="mdc-data-table__header-cell text-center" role="columnheader" scope="col">Điểm</th>
				</tr>
			</thead>
			<tbody class="mdc-data-table__content">
				<?php
				$cnt = 0;
				foreach ($result as $each) {
					++$cnt;
					?>
					<tr class="mdc-data-table__row">
						<td class="mdc-data-table__cell text-center"><?php echo $cnt; ?></td>
						<td class="mdc-data-table__cell"><?php echo $each['name']; ?></td>
						<td class="mdc-data-table__cell"><?php echo $each['created_time']; ?></td>
						<td class="mdc-data-table__cell text-center">
							<?php echo $each['score']; ?>
						</td>
					</tr>
				<?php
				}
				?>
			</tbody>
		</table>
	</div>
</body>

</html>