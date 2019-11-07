<?php
$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
if (substr_count($_SERVER['DOCUMENT_ROOT'], 'DaisyQuizzes') == 0) {
	$DOCUMENT_ROOT = $DOCUMENT_ROOT . '/DaisyQuizzes';
}
include($DOCUMENT_ROOT . '/middleware/auth_admin.php');
if (!checkLoggedIn()) {
	header('Location: ../login');
	exit();
}
include($DOCUMENT_ROOT . '/database.php');
function db_fetch_questions($conn, $collection)
{
	$sql = "SELECT id, body, choice_a, choice_b, choice_c, choice_d FROM daisy_question WHERE collection_id = $collection";
	$result = [];
	$q = $conn->query($sql);
	while ($row = $q->fetch_assoc()) {
		$result[] = $row;
	}
	return $result;
}

function set_collection($conn, $userid, $collection, $collection_name)
{
	$sql = "UPDATE daisy_collection SET name='$collection_name' WHERE id=$collection and admin_id=$userid";
	$conn->query($sql);
}
$id = $_SESSION['userid'];

// nên viết 1 hàm main () cho đơn giản
$userid = $_SESSION['userid'];
$collection = $_GET['k'];
$conn = db_connect();
$collection_name = db_authen($conn, $userid, $collection);

if ($collection_name == false) {
	$conn->close();
	die("Không tìm thấy bộ câu hỏi được yêu cầu");
}

if (isset($_POST['collection_name'])) {
	$collection_name = $_POST['collection_name'];
	set_collection($conn, $id, $collection, $collection_name);
}

$data = db_fetch_questions($conn, $collection);
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
	<title><?php echo $collection_name; ?></title>
	<meta charset="utf-8">
	<link href="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.css" rel="stylesheet">
	<script src="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.js"></script>
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<link href="./styles.css" rel="stylesheet" type="text/css">
	<!-- <link href="../index.css" rel="stylesheet" type="text/css"> -->
</head>

<body>
	<header class="mdc-top-app-bar" data-mdc-auto-init="MDCTopAppBar">
		<div class="mdc-top-app-bar__row">
			<section class="mdc-top-app-bar__section mdc-top-app-bar__section--align-start">
				<i class="material-icons">check_circle</i><span class="mdc-top-app-bar__title">Xin chào, <?php echo $_SESSION['username']; ?>!</span> </section>
			<section class="mdc-top-app-bar__section mdc-top-app-bar__section--align-end">
				<button class="mdc-fab mdc-fab--extended" onclick="linkTo('../logout.php')">
					<div class="mdc-fab__ripple"></div>
					<span class="material-icons mdc-fab__icon">exit_to_app</span>
					<span class="mdc-fab__label">Đăng xuất</span>
				</button>
			</section>
		</div>
	</header>

	<div id="wrapper">
		<form action="modify.php?k=<?php echo $collection ?>" method="post" id="fCName">
			<div class="mdc-text-field" data-mdc-auto-init="MDCTextField" id="collection_name">
				<input class="mdc-text-field__input" name="collection_name" value="<?php echo $collection_name ?>" required>
				<div class="mdc-line-ripple"></div>
				<label for="collection_name" class="mdc-floating-label">Tên bộ câu hỏi</label>
			</div>
			<button class="mdc-fab mdc-fab--extended" type="submit">
				<div class="mdc-fab__ripple"></div>
				<span class="material-icons mdc-fab__icon">send</span>
				<span class="mdc-fab__label">Đổi</span>
			</button>
		</form>
		<?php
		$cnt = 0;

		foreach ($data as $each) {
			$cnt++;
			?>
			<div class="mdc-card demo-card">
				<div class="mdc-card__content">
					<div class="demo-card__primary">
						<h2 class="demo-card__title mdc-typography mdc-typography--headline6"><?php echo "$cnt."
																										. $each['body'] ?></h2>
					</div>
					<div class="demo-card__secondary mdc-typography mdc-typography--body2">
						<?php
							echo 'A (đáp án): ' . $each['choice_a'] . "<br>";
							echo 'B: ' . $each['choice_b'] . "<br>";
							echo 'C: ' . $each['choice_c'] . "<br>";
							echo 'D: ' . $each['choice_d'] . "<br>";
							?>
					</div>
				</div>
				<div class="mdc-card__actions">
					<div class="mdc-card__action-icons">
						<button class="mdc-fab mdc-fab--extended mdc-fab-edit" onclick="linkTo('modify_question.php?k=<?php echo $collection; ?>&question=<?php echo $each['id']; ?>')">
							<div class="mdc-fab__ripple"></div>
							<span class="material-icons mdc-fab__icon">edit</span>
							<span class="mdc-fab__label">Chỉnh sửa</span>
						</button>
						<button class="mdc-fab mdc-fab--extended mdc-fab-delete" onclick="open_confirm('delete', 'Xoá câu hỏi', 'Bạn có chắc muốn xoá câu hỏi?', 'delete_question.php?k=<?php echo $collection; ?>&question=<?php echo $each['id']; ?>')">
							<div class="mdc-fab__ripple"></div>
							<span class="material-icons mdc-fab__icon">delete</span>
							<span class="mdc-fab__label">Xoá</span>
						</button>
					</div>
				</div>
			</div>
		<?php
		}
		$conn->close();
		?>
		<button class="mdc-fab app-fab--absolute" aria-label="Add" title=" Thêm câu hỏi" onclick="linkTo('add_question.php?k=<?php echo $collection; ?>')">
			<div class="mdc-fab__ripple"></div>
			<span class="mdc-fab__icon material-icons">add</span>
		</button>
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
						<span class="mdc-button__label">Huỷ</span>
					</button>
					<button id="btnOK" type="button" class="mdc-button mdc-dialog__button mdc-button--raised" data-mdc-auto-init="MDCRipple" data-mdc-dialog-action="accept">
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
	disp_alert($_SESSION['flash_alert']);
	?>
</body>

</html>