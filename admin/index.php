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
		
	</head>
	<body>
		<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
		<script>
						
			function onTimeout (){
				window.location.href="./dashboard.php";			
			}
			loadInterval = setTimeout ("onTimeout ()",800)
		</script>
	</body>
</html>
