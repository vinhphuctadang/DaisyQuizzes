<?php
$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
if (substr_count($_SERVER['DOCUMENT_ROOT'], 'DaisyQuizzes') == 0) {
	$DOCUMENT_ROOT = $DOCUMENT_ROOT . '/DaisyQuizzes';
}
include $DOCUMENT_ROOT . '/database.php'; // parent directory
$str = $DOCUMENT_ROOT . '/middleware/auth_admin.php';
include $str;

if (!checkLoggedIn()) {
	header('Location: ./login');
	exit();
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
?>

<html>

<head>
	<title>Quản lí câu hỏi</title>
	<meta charset="utf-8">
	<link href="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.css" rel="stylesheet">
	<script src="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.js"></script>
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<link href="./dashboard.css" rel="stylesheet" type="text/css">
	<!-- <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500" rel="stylesheet"> -->
</head>

<body>
	<header class="mdc-top-app-bar" data-mdc-auto-init="MDCTopAppBar">
		<div class="mdc-top-app-bar__row">
			<section class="mdc-top-app-bar__section mdc-top-app-bar__section--align-start">
				<i class="material-icons">check_circle</i><span class="mdc-top-app-bar__title">Xin chào, <?php echo $_SESSION['username']; ?>!</span> </section>
			<section class="mdc-top-app-bar__section mdc-top-app-bar__section--align-end">
				<button class="mdc-fab mdc-fab--extended" onclick="linkTo('./logout.php')">
					<!-- <div class="mdc-fab__ripple"></div> -->
					<span class="material-icons mdc-fab__icon">exit_to_app</span>
					<span class="mdc-fab__label">Đăng xuất</span>
				</button>
			</section>
		</div>
	</header>

	<div id="wrapper">
		<?php
		$id = $_SESSION['userid'];
		//Các trạng thái của một vòng chơi
		$const_status = [
			0 => 'Đóng',
			1 => 'Mở và chờ đợi',
			2 => 'Đang diễn ra'
		];

		$conn = db_connect();
		// TODO: đang thiếu lấy ra từ database

		function get_collections($conn, $userid)
		{
			$sql = "SELECT name, daisy_collection.id, COUNT(daisy_question.id) as num_questions FROM daisy_collection " .
				"LEFT JOIN daisy_question ON daisy_question.collection_id=daisy_collection.id " .
				"WHERE admin_id = $userid GROUP BY daisy_collection.name, daisy_collection.id";

			//echo $sql;
			$result = $conn->query($sql);
			return $result;
		}

		function get_rounds($conn, $userid)
		{
			$sql = "SELECT round, name, daisy_round.status FROM daisy_round, daisy_collection WHERE collection = id and daisy_round.admin_id = $userid";
			$result = $conn->query($sql);
			return $result;
		}


		function display($result)
		{ // vẽ kết qủa lên màn hình
			echo "<h2 class='heading'>Các bộ đã tạo (" . $result->num_rows . " bộ):</h2>";
			$i = 0;
			?>
			<div class="mdc-data-table" data-mdc-auto-init="MDCDataTable">
				<table class="mdc-data-table__table" aria-label="Dessert calories">
					<thead>
						<tr class="mdc-data-table__header-row">
							<th class="mdc-data-table__header-cell text-center" role="columnheader" scope="col">STT</th>
							<th class="mdc-data-table__header-cell" role="columnheader" scope="col">Bộ câu hỏi<i></i></th>
							<th class="mdc-data-table__header-cell text-center" role="columnheader" scope="col">Số câu</th>
							<th class="mdc-data-table__header-cell" role="columnheader" scope="col"></th>
						</tr>
					</thead>
					<tbody class="mdc-data-table__content">
						<?php
							while ($row = $result->fetch_assoc()) {
								$id = $row['id'];
								$num = $row['num_questions'];
								$i++;
								?>
							<tr class="mdc-data-table__row">
								<td class="mdc-data-table__cell text-center"><?php echo $i; ?></td>
								<td class="mdc-data-table__cell"><a href='./manage-question?k=<?php echo $id; ?>' style="max-width: 500px;"> <?php echo  $row['name']; ?></a></td>
								<td class="mdc-data-table__cell text-center"><?php echo $num; ?></td>
								<td class="mdc-data-table__cell text-right">
									<button class="mdc-icon-button mdc-icon-edit material-icons" aria-label="CreateRound" onclick="open_confirm('add_circle', 'Tạo vòng chơi', '', './create_round.php?k=<?php echo $id; ?>', 'round')" title="Tạo vòng chơi"> play_circle_filled </button>
									<button class="mdc-icon-button mdc-icon-delete material-icons" aria-label="DeleteCollection" onclick="open_confirm('delete', 'Xoá bộ câu hỏi','Bạn có chắc muốn xoá bộ câu hỏi?', './middle_delete_collection.php?k=<?php echo $id; ?>')" title="Xoá bộ câu hỏi"> delete </button>
								</td>
							</tr>
						<?php
							}
							?>

					</tbody>
				</table>
			</div>
			<?php
			}

			function display_round($result, $const_status)
			{

				echo "<h2 class='heading'>Các vòng chơi đã tạo (" . $result->num_rows . " vòng) </h2>";
				$i = 0;
				if ($result->num_rows > 0) {
					?>
				<div class="mdc-data-table" data-mdc-auto-init="MDCDataTable">
					<table class="mdc-data-table__table" aria-label="Dessert calories">
						<thead>
							<tr class="mdc-data-table__header-row">
								<th class="mdc-data-table__header-cell text-center" role="columnheader" scope="col">STT</th>
								<th class="mdc-data-table__header-cell" role="columnheader" scope="col">Mã vòng chơi</th>
								<th class="mdc-data-table__header-cell" role="columnheader" scope="col">Bộ câu hỏi</th>
								<th class="mdc-data-table__header-cell" role="columnheader" scope="col">Trạng thái</th>
								<th class="mdc-data-table__header-cell" role="columnheader" scope="col"></th>
							</tr>
						</thead>
						<tbody class="mdc-data-table__content">
							<?php
									while ($row = $result->fetch_assoc()) {
										$id   = $row['round'];
										$name = $row['name'];
										$status = $row['status'];
										$i++;
										?>
								<tr class="mdc-data-table__row">
									<td class="mdc-data-table__cell text-center"><?php echo $i; ?></td>
									<td class="mdc-data-table__cell"><?php echo $id; ?></td>
									<td class="mdc-data-table__cell"><a href='manage-question/modify_round.php?k=<?php echo $id; ?>'><?php echo $name; ?></a></td>
									<td class="mdc-data-table__cell"><?php echo $const_status[$status]; ?></td>
									<td class="mdc-data-table__cell text-right">
										<button class="mdc-icon-button mdc-icon-delete material-icons" aria-label="Delete" onclick="open_confirm('delete', 'Xoá vòng chơi', 'Bạn có chắc muốn xoá vòng chơi?', './delete_round.php?k=<?php echo $id; ?>')" title="Xoá vòng chơi"> delete </button>
									</td>
								</tr>
							<?php
									}

									?>

						</tbody>
					</table>
				</div>
		<?php
			}
		}

		$result = get_collections($conn, $id);
		display($result);

		$rounds = get_rounds($conn, $id);
		display_round($rounds, $const_status);
		$conn->close();
		?>
		<div class="tooltip">
			<button class="mdc-fab app-fab--absolute" aria-label="Add" onclick="open_confirm('add_circle', 'Tạo bộ câu hỏi', '', './middle_add_collection.php', 'collection')">
				<div class="mdc-fab__ripple"></div>
				<span class="mdc-fab__icon material-icons">add</span>
				<span class="tooltiptext" id="myTooltip">Tạo bộ câu hỏi</span>
			</button>
		</div>

	</div>
	<div id="mdc-snackbar" class="mdc-snackbar mdc-snackbar--leading" data-mdc-auto-init="MDCSnackbar">
		<div class="mdc-snackbar__surface">
			<div id="mdc-snackbar-label" class="mdc-snackbar__label" role="status" aria-live="polite"></div>
			<div class="mdc-snackbar__actions">
				<button class="mdc-icon-button mdc-snackbar__dismiss material-icons" title="Dismiss">close</button>
			</div>
		</div>
	</div>
	<div id="mdc-dialog" class="mdc-dialog" role="alertdialog" aria-modal="true" aria-labelledby="my-dialog-title" aria-describedby="my-dialog-content" data-mdc-auto-init="MDCDialog">
		<div class="mdc-dialog__container">
			<div class="mdc-dialog__surface">
				<!-- Title cannot contain leading whitespace due to mdc-typography-baseline-top() -->
				<h2 class="mdc-dialog__title" id="my-dialog-title">
					<i id="mdc-dialog-icon" class="material-icons">
						3d_rotation
					</i>
					<span id="mdc-dialog-title">This is title</span>

				</h2>
				<div class="mdc-dialog__content" id="my-dialog-content">
					Bạn có chắc muốn xoá ... ?
				</div>
				<footer class="mdc-dialog__actions">
					<button id="btnCancel" type="button" class="mdc-button mdc-dialog__button" data-mdc-auto-init="MDCRipple" data-mdc-dialog-action="close">
						<div class="mdc-button__ripple"></div>
						<span class="mdc-button__label">Huỷ</span>
					</button>
					<button id="btnOK" type="button" class="mdc-button mdc-dialog__button mdc-button--raised" data-mdc-auto-init="MDCRipple" data-mdc-dialog-action="accept">
						<div class="mdc-button__ripple"></div>
						<span class="mdc-button__label">Đồng ý</span>
					</button>
				</footer>
			</div>
		</div>
		<div class="mdc-dialog__scrim"></div>
	</div>
	<script type="text/javascript">
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

		function open_confirm(icon, title, content, link, input) {
			document.getElementById('mdc-dialog-icon').innerHTML = icon;
			document.getElementById('mdc-dialog-title').innerHTML = title;
			document.getElementById('my-dialog-content').innerHTML = content;
			dialog.open()
			if (input == 'collection') {
				document.getElementById('my-dialog-content').innerHTML = `<div class="mdc-text-field" data-mdc-auto-init="MDCTextField">
					<input class="mdc-text-field__input" id="collection" name="collection" required onfocus="status_OK('collection')" onkeyup="status_OK('collection', event)">
					<div class="mdc-line-ripple"></div>
					<label for="collection" class="mdc-floating-label">Nhập tên bộ câu hỏi</label>
				</div>`;
				window.mdc.autoInit();
				let myFunc = function() {
					let value = document.getElementById('collection').value;
					linkTo(link + "?collection=" + value);
					document.getElementById('btnOK').removeEventListener("click", myFunc);
				}
				document.getElementById('btnOK').addEventListener("click", myFunc);
				return;
			}
			if (input == 'round') {
				document.getElementById('my-dialog-content').innerHTML = `<div class="mdc-text-field" data-mdc-auto-init="MDCTextField">
					<input class="mdc-text-field__input" id="round" name="round" required onfocus="status_OK('round')" onkeyup="status_OK('round', event)">
					<div class="mdc-line-ripple"></div>
					<label for="round" class="mdc-floating-label">Mã vòng (là duy nhất trong hệ thống)</label>
				</div>`;
				window.mdc.autoInit();
				let myFunc = function() {
					let value = document.getElementById('round').value;
					linkTo(link + "&round=" + value);
					document.getElementById('btnOK').removeEventListener("click", myFunc);
				}
				document.getElementById('btnOK').addEventListener("click", myFunc);
				return;
			}
			document.getElementById('btnOK').disabled = false;
			document.getElementById('btnOK').addEventListener("click", function() {
				linkTo(link)
			});
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
	</script>
	<?php
	if (isset($_SESSION['flash_username']))
		disp_alert($_SESSION['flash_username']);
	if (isset($_SESSION['flash_alert']))
		disp_alert($_SESSION['flash_alert']);
	?>
</body>

</html>