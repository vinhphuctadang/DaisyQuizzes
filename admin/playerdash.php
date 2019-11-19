<?php
$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
if (substr_count($_SERVER['DOCUMENT_ROOT'], 'DaisyQuizzes') == 0) {
	$DOCUMENT_ROOT = $DOCUMENT_ROOT . '/DaisyQuizzes';
}
include $DOCUMENT_ROOT . '/database.php';
include $DOCUMENT_ROOT . '/middleware/auth_admin.php';

if (!checkLoggedIn()) {
	header('Location: ./login');
	exit();
}

function findLoggedPlayer($conn, $token)
{

	$sql = "SELECT name, created_time, score FROM daisy_player_round, daisy_round WHERE daisy_player_round.round=daisy_round.round and daisy_round.access_token = '$token' ORDER BY score DESC";
	$result = $conn->query($sql);
	$list = [];
	while ($row = $result->fetch_assoc()) {
		$list[] = $row;
	}
	return $list;
}

function findTokenByRound($conn, $round)
{
	$sql = "SELECT access_token FROM daisy_round WHERE round = '$round'";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	return $row['access_token'];
}

$conn = db_connect();
$round = $_GET['round'];
$token = findTokenByRound($conn, $round);
$result = findLoggedPlayer($conn, $token);
$conn->close();
?>

<html>

<head>
	<title>Daisy Quizzes</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="<?php echo path("/assets/material-components-web.min.css"); ?> " rel="stylesheet">
	<script src="<?php echo path("/assets/material-components-web.min.js"); ?> "></script>
	<link href="<?php echo path("/player/index.css"); ?>" rel="stylesheet" type="text/css">
</head>

<body>
	<div class='waiting'>
		<div id="wrapper">
			<div class="mdc-card wrapper-card card-rank">
				<p class="finish ranking">🎉 Bảng xếp hạng 🎉</p>
				<div class="mdc-data-table" data-mdc-auto-init="MDCDataTable">
					<table class="mdc-data-table__table" aria-label="Dessert calories">
						<thead>
							<tr class="mdc-data-table__header-row">
								<th class="mdc-data-table__header-cell" role="columnheader" scope="col">Tên người chơi<i></i></th>
								<th class="mdc-data-table__header-cell" role="columnheader" scope="col">Dấu thời gian</th>
								<th class="mdc-data-table__header-cell text-center" role="columnheader" scope="col">Điểm</th>
							</tr>
						</thead>
						<tbody id="players" class="mdc-data-table__content">
							<?php
							$cnt = 0;
							foreach ($result as $each) {
								++$cnt;
								?>
								<tr class="mdc-data-table__row">
									<td class="mdc-data-table__cell"><?php echo $each['name']; ?></td>
									<td class="mdc-data-table__cell"><?php echo $each['created_time']; ?></td>
									<td class="mdc-data-table__cell text-center" id="<?php echo $each['name']; ?>">
										<?php echo $each['score']; ?>
									</td>
								</tr>
							<?php
							}
							?>
						</tbody>
					</table>
				</div>
				<!-- <div class="btn-exit"><button class="mdc-button mdc-button--raised" onclick="window.location='../'">THOÁT
					</button></div> -->

			</div>
		</div>
		<ul class='bg-bubbles'>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
		</ul>
	</div>
</body>
<script src="<?php echo path("socket.io.js"); ?>"></script>
<script>
	pendingPlayerInfos = [];

	function updatePlayerScore(player) {
		httprq = new XMLHttpRequest();
		httprq.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				var txt = httprq.responseText;
				console.log(txt);
				var jsn = JSON.parse(txt);
				var arr = jsn.result[0];
				pendingPlayerInfos.push(arr);
			}
		}
		httprq.open("GET", '<?php echo path("api.php?method=get_player&name="); ?>' + player + "&token=<?php echo $token; ?>", true);
		httprq.send();
	}

	function addPlayer(player) {

		anElement = '<tr class="mdc-data-table__row">' +
			'<td class="mdc-data-table__cell">' + player.name + '</td>' +
			'<td class="mdc-data-table__cell">' + player.created_time + '</td>' +
			'<td class="mdc-data-table__cell text-center" id="' + player.name + '">' + player.score + '</td>' +
			'</tr>';
		document.getElementById("players").innerHTML += anElement;
	}

	function updatePendingPlayerInfos() {
		for (i = 0; i < pendingPlayerInfos.length; ++i) {
			var id = pendingPlayerInfos[i].name;
			var score = pendingPlayerInfos[i].score;
			var view = document.getElementById(id);
			if (view == null)
				addPlayer(pendingPlayerInfos[i]);
			else
				view.innerText = score;
		}
		pendingPlayerInfos = []
	}
	var socket = io.connect('<?php echo $GLOBALS["NODEJS_HOST_SERVER"]; ?>');
	socket.on('<?php echo "onPlayer" . $round ?>', function(player) {
		updatePlayerScore(player);
	});
	socket.on('<?php echo "onChange" . $round ?>', function(player) {
		updatePendingPlayerInfos();
	});
	socket.on('<?php echo "onFinished" . $round ?>', function(player) {
		updatePendingPlayerInfos();
	});
</script>

</html>