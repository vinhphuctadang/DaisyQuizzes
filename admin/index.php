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
?>

<html id="fakeload">
	<head>
		<title>Dashboard cho <?php echo $_SESSION['username']?></title>
		<meta charset="utf-8">		
		<link href="loading.css" type="text/css" rel="stylesheet">
		<link href="<?php echo path("index.css");?>" type="text/css" rel="stylesheet">
		<link href="<?php echo path("/assets/material-components-web.min.css") ?> " rel="stylesheet">
		<script src="<?php echo path("/assets/material-components-web.min.js") ?> "></script>
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
		<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
		<script>
			loadInterval = null;		
			respText = null;
			function onTimeout (){
				loadInterval = null;
				if (respText != null)
					document.getElementById ("fakeload").innerHTML=respText;
			}			
			function toDashboard (){
				httprq = new XMLHttpRequest();
				httprq.onreadystatechange = function() {
					var resp = this.responseText;
					if (loadInterval == null)
						document.getElementById ("fakeload").innerHTML=resp;
					else 
						respText = resp;
				}
				
				httprq.open("GET", 'dashboard.php', true);
				httprq.send();
			}
			loadInterval = setTimeout ("onTimeout ()",800)
			toDashboard ();
		</script>
	</body>
</html>
