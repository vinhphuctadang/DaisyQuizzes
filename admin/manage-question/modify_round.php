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
	$sql = "UPDATE daisy_round SET status=$status, question_no=0 WHERE round='$round'";
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
	$_SESSION['flash_alert'] = "Cập nhật trạng thái vòng chơi thành công!";
}
function disp_alert($alertText)
{
	if (isset($alertText)) {
		?>
		<script type="text/javascript">
			open_alert("<?php echo $alertText; ?>");
		</script>
<?php
		unset($_SESSION['flash_username']);
		unset($_SESSION['flash_alert']);
	}
}
$conn->close();
?>
<html>

<head>
	<title>Vòng <?php echo $round; ?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.css" rel="stylesheet">
	<script src="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.js"></script>
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<link href="./styles.css" rel="stylesheet" type="text/css">
</head>

<body>
	<header class="mdc-top-app-bar" data-mdc-auto-init="MDCTopAppBar">
		<div class="mdc-top-app-bar__row">
			<section class="mdc-top-app-bar__section mdc-top-app-bar__section--align-start">
				<button class="mdc-fab mdc-fab--extended" onclick="linkTo('../dashboard.php')">
					<span class="material-icons mdc-fab__icon">arrow_back</span>
					<span class="mdc-fab__label">Trang chủ</span>
				</button>
			</section>
			<section class="mdc-top-app-bar__section mdc-top-app-bar__section--align-end">
				<button class="mdc-fab mdc-fab--extended" onclick="linkTo('../logout.php')">
					<!-- <div class="mdc-fab__ripple"></div> -->
					<span class="material-icons mdc-fab__icon">exit_to_app</span>
					<span class="mdc-fab__label">Đăng xuất</span>
				</button>
			</section>
		</div>
		<?php
		$status_name = array("Đóng", "Mở và chời đợi", "Đang diễn ra");
		$status_color = array("#ED1C24", "#1976d2", "#4CAF50");
		$status_icon = array("cancel", "cached", "check_circle");
		?>
	</header>

	<div id="wrapper">
		<!-- <a href="../dashboard.php">Quay lại trang chính </a> -->
		<h3 style="display: none">Trạng thái: <span id="status"><?php echo $status; ?></span>
		</h3>

		<div id="status-container">
			<button id="btnStatus" class="mdc-button mdc-button--raised" style="background-color: <?php echo $status_color[$status] ?>" onclick="open_confirm()">
				<div class="mdc-button__ripple"></div>
				<i class="material-icons mdc-button__icon" aria-hidden="true"><?php echo $status_icon[$status] ?></i>
				<span class="mdc-button__label"><?php echo $status_name[$status] ?></span>
			</button>
		</div>
		<div id="token">
			<fieldset>
				<legend>Mã truy cập cho nhà phát triển</legend>
				<div style="display: flex">
					<input type="text" value="<?php echo $token; ?>" id="myInput" readonly>
					<div class="tooltip">
						<span class="tooltiptext" id="tooltip-token">Copy</span>
						<i class="material-icons btn-copy" aria-hidden="true" onclick="myFunction()" onmouseout="outFunc()">file_copy</i>
					</div>
				</div>

			</fieldset>
		</div>
		<div class="container">
			<h3> Câu hỏi hiện tại: <span id="number">0</span>
			</h3>
			<p id="time">10</p>
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
		</div>
	</div>
	<div id="mdc-dialog" class="mdc-dialog" role="alertdialog" aria-modal="true" aria-labelledby="my-dialog-title" aria-describedby="my-dialog-content" data-mdc-auto-init="MDCDialog">
		<div class="mdc-dialog__container">
			<div class="mdc-dialog__surface">
				<!-- Title cannot contain leading whitespace due to mdc-typography-baseline-top() -->
				<h2 class="mdc-dialog__title" id="my-dialog-title">
					<i id="mdc-dialog-icon" class="material-icons">
						edit
					</i>
					<span id="mdc-dialog-title">Cập nhật trạng thái</span>

				</h2>
				<div class="mdc-dialog__content" id="my-dialog-content">
					<form id="fStatus" action="modify_round.php?k=<?php echo $round; ?>" method="post">
						<div id="radio-status">
							<div class="mdc-form-field">
								<div class="mdc-radio">
									<input class="mdc-radio__native-control" type="radio" id="radio-1" name="change" <?php echo ($status == 0 ? 'checked ' : ''); ?> value="0">
									<div class="mdc-radio__background">
										<div class="mdc-radio__outer-circle"></div>
										<div class="mdc-radio__inner-circle"></div>
									</div>
									<div class="mdc-radio__ripple"></div>
								</div>
								<label for="radio-1">Đóng</label>
							</div>
							<div class="mdc-form-field">
								<div class="mdc-radio">
									<input class="mdc-radio__native-control" type="radio" id="radio-2" name="change" <?php echo ($status == 1 ? 'checked ' : ''); ?> value="1">
									<div class="mdc-radio__background">
										<div class="mdc-radio__outer-circle"></div>
										<div class="mdc-radio__inner-circle"></div>
									</div>
									<div class="mdc-radio__ripple"></div>
								</div>
								<label for="radio-2">Mở và chờ đợi</label>
							</div>
							<div class="mdc-form-field">
								<div class="mdc-radio">
									<input class="mdc-radio__native-control" type="radio" id="radio-3" name="change" <?php echo ($status == 2 ? 'checked ' : ''); ?>value="2">
									<div class="mdc-radio__background">
										<div class="mdc-radio__outer-circle"></div>
										<div class="mdc-radio__inner-circle"></div>
									</div>
									<div class="mdc-radio__ripple"></div>
								</div>
								<label for="radio-3">Đang diễn ra</label>
							</div>
						</div>
						<footer class="mdc-dialog__actions">
							<button id="btnCancel" type="button" class="mdc-button mdc-dialog__button" data-mdc-auto-init="MDCRipple" data-mdc-dialog-action="close">
								<div class="mdc-button__ripple"></div>
								<span class="mdc-button__label">Huỷ</span>
							</button>
							<button id="btnOK" type="submit" class="mdc-button mdc-dialog__button mdc-button--raised" data-mdc-auto-init="MDCRipple" data-mdc-dialog-action="accept">
								<div class="mdc-button__ripple"></div>
								<span class="mdc-button__label">Đồng ý</span>
							</button>
						</footer>
						<!-- <button class="mdc-button mdc-button--raised" type="submit">Thay đổi trạng thái</button> -->
					</form>
				</div>

			</div>
		</div>
		<div class="mdc-dialog__scrim"></div>
	</div>
	<div id="mdc-snackbar" class="mdc-snackbar mdc-snackbar--leading" data-mdc-auto-init="MDCSnackbar">
		<div class="mdc-snackbar__surface">
			<div id="mdc-snackbar-label" class="mdc-snackbar__label" role="status" aria-live="polite"></div>
			<div class="mdc-snackbar__actions">
				<button class="mdc-icon-button mdc-snackbar__dismiss material-icons" title="Dismiss">close</button>
			</div>
		</div>
	</div>
	<script>
		var totalTime = 10;
		var totalTimeForExplaination = 1;
		var timing = totalTime;

		var state = 0; // 0: question state, 1: explaination state
		var x = null;

		function startInterval() {
			increaseQuestionNumber(updateQuestionNumber);
			x = setInterval("onInterval ()", 1000);
		}

		function render() {
			document.getElementById("time").innerText = timing;
		}

		function checkTimeline() {

			if (state == 0) {
				timing = totalTimeForExplaination;
				state = 1;
				notifyQuestionExplaination();
			} else {
				timing = totalTime;
				state = 0;
				increaseQuestionNumber(updateQuestionNumber);
			}
		}

		function onInterval() {

			if (timing == 0) {
				checkTimeline();
			} else
				timing -= 1;

			render();
		}

		function increaseQuestionNumber(onDoneResp) {
			httprqIQ = new XMLHttpRequest();

			httprqIQ.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					onDoneResp(this.responseText);
					console.log(this.responseText);
				}
			}
			httprqIQ.open("GET", '<?php echo path("api.php?method=change_question&token=$token&change=1&nextupdate=10"); ?>', true);
			httprqIQ.send();
		}

		function notifyQuestionExplaination() {
			httprqIQ = new XMLHttpRequest();

			httprqIQ.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					console.log(this.responseText);
				}
			}
			httprqIQ.open("GET", '<?php echo path("api.php?method=notify_explaination&token=$token&nextupdate="); ?>'+totalTimeForExplaination, true);
			httprqIQ.send();				
		}

		function notifyRoundFinish() {
			var request = new XMLHttpRequest();
			request.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					console.log(this.responseText);
				}
			}
			request.open("GET", '<?php echo path("api.php?method=notify_round_finish&token=$token"); ?>', true);
			request.send();
		}

		function updateQuestionNumber(msg) {

			jsn = JSON.parse(msg);
			if (jsn.result === "ERR_EXCEED") {
				clearInterval(x);
				notifyRoundFinish();
				alert("Vòng chơi đã kết thúc");
				return;
			}

			httprq = new XMLHttpRequest();
			httprq.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					var txt = httprq.responseText;
					var jsn = JSON.parse(txt);
					document.getElementById("number").innerText = jsn.result;
				}
			}

			httprq.open("GET", '<?php echo path("api.php?method=get_question_no&token=$token"); ?>', true);
			httprq.send();
		}

		function stopInterval() {
			clearInterval(x);
		}

		var status = document.getElementById("status").innerText;
		if (status == '2') {
			startInterval();
		}

		window.mdc.autoInit();
		var MDCDialog = mdc.dialog.MDCDialog;
		const dialog = new MDCDialog(document.querySelector('.mdc-dialog'));

		var MDCSnackbar = mdc.snackbar.MDCSnackbar;
		const snackbar = new MDCSnackbar(document.querySelector('.mdc-snackbar'));

		function linkTo(link) {
			window.location = link;
		}

		function disp_confirm(text, link) {
			var delete_confirm = confirm(text);
			if (delete_confirm == true) {
				linkTo(link)
			}
		}

		function status_OK(type, event) {
			let value = document.getElementById(type).value;
			if (!value) {
				document.getElementById('btnOK').disabled = true;
			} else {
				document.getElementById('btnOK').disabled = false;
				if (event.key == 'Enter') {
					document.getElementById('btnOK').click();
				}
			}
		}

		function open_confirm() {
			dialog.open()
		}

		function open_alert(text) {
			document.getElementById('mdc-snackbar-label').innerHTML = text;
			snackbar.open();
		}

		function close_alert() {
			snackbar.close();
		}
		document.addEventListener("click", function() {
			close_alert();
		});

		function myFunction() {
			var copyText = document.getElementById("myInput");
			copyText.select();
			copyText.setSelectionRange(0, 99999);
			document.execCommand("copy");

			var tooltip = document.getElementById("tooltip-token");
			tooltip.innerHTML = "Copied!!!";
		}

		function outFunc() {
			var tooltip = document.getElementById("tooltip-token");
			tooltip.innerHTML = "Copy";
		}

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
	</script>
	<?php
	if (isset($_SESSION['flash_alert']))
		disp_alert($_SESSION['flash_alert']);
	?>

	<script src="<?php echo path("socket.io.js"); ?>"></script>
	<script>
		var socket = io.connect('<?php echo $GLOBALS["NODEJS_HOST_SERVER"]; ?>');
		socket.on('<?php echo "onPlayer" . $round ?>', function(player) {
			// alert ("update needed: " + time);
			updatePlayerScore(player);
		});
	</script>
</body>

</html>