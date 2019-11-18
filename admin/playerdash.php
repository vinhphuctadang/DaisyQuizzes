<?php
$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
if (substr_count($_SERVER['DOCUMENT_ROOT'], 'DaisyQuizzes') == 0) {
	$DOCUMENT_ROOT = $DOCUMENT_ROOT . '/DaisyQuizzes';
}
include $DOCUMENT_ROOT . '/database.php';
include serverpath('middleware/auth.php');

function findLoggedPlayer($conn, $admin, $token)
{

	$sql = "SELECT name, created_time, score FROM daisy_player_round, daisy_round WHERE daisy_player_round.round=daisy_round.round and daisy_round.access_token = '$token' ORDER BY score DESC";
	$result = $conn->query($sql);
	$list = [];
	while ($row = $result->fetch_assoc()) {
		$list[] = $row;
	}
	return $list;
}


$conn = db_connect();
$token = $_GET['token'];
$result = findLoggedPlayer($conn, $token);
$conn->close();

?>

<html>

	<head>
		<title>Daisy Quizzes</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.css" rel="stylesheet">
		<script src="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.js"></script>
		<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
		<link href="./index.css" rel="stylesheet" type="text/css">
	</head>

	<body>
		<div id="wrapper">
			<div class="mdc-card wrapper-card">
				<p class="finish">🎉 Chúc mừng 🎉</p>
				<p class="finish">Bạn đã hoàn thành vòng chơi</p>
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
				<div class="btn-exit"><button class="mdc-button mdc-button--raised" onclick="window.location='../'">THOÁT
					</button></div>

			</div>
		</div>
	</body>
	<script>
		function updatePlayerScore(player) {

			httprq = new XMLHttpRequest();

			httprq.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					var txt = httprq.responseText;
					console.log(txt);
					var jsn = JSON.parse(txt);
					var arr = jsn.result;
					var id = arr[0].name;
					var score = arr[0].score;
					document.getElementById(id).innerText = score;
				}
			}

			httprq.open("GET", '<?php echo path("api.php?method=get_player&name="); ?>' + player + "&token=<?php echo $token; ?>", true);
			httprq.send();
		}

		var socket = io.connect('<?php echo $GLOBALS["NODEJS_HOST_SERVER"]; ?>');
		socket.on('<?php echo "onPlayer" . $round ?>', function(player) {
			// alert ("update needed: " + time);
			updatePlayerScore(player);
		});
	</script>
</html>