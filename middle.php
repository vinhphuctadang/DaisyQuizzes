<?php
	include 'database.php';
	$GLOBALS['apikey'] = 'daisy2610';
	function checkExists ($conn, $player, $round) {		
		$sql = "SELECT status FROM daisy_round_collection WHERE round='$round'";
		$result = $conn->query ($sql);
		if ($result->num_rows == 0)
			die ("Không tìm thấy vòng chơi yêu cầu, có thể nó đã bị xóa khỏi CSDL");
		$result = $result->fetch_assoc ();
		if ($result ['status'] == 0)
			die ("Không thể đăng nhập, vòng chơi đã kết thúc");
		
		$sql = "SELECT score FROM daisy_player_round where round='$round' and name='$player'";
		$result = $conn->query ($sql);
		if ($result->num_rows > 0)
			return true;
		return false;
	}
	
	function addPlayer ($conn, $player, $round) {	
		$sql = "INSERT INTO daisy_player_round (name, round, score, token) VALUES ('$player', '$round', 0, '".md5 ($player.$GLOBALS['apikey'])."')";
		$result = $conn->query ($sql);
	}
	
	if (!isset ($_POST['round'])) 
		die ("Không tìm thấy vòng chơi");
	if (!isset ($_POST['player'])) 
		die ("Không tìm thấy người chơi");
	#echo json_encode ($_POST);
	$conn = db_connect ();
	$round = $_POST ['round'];
	$player = $_POST ['player'];
	if (checkExists ($conn, $player, $round)) {
		echo "Tên tài khoản \"$player\" với vòng chơi này đã tồn tại, hãy đổi tên<br>";
		echo "Trở lại <a href='./index.html'>trang đầu</a>";
		exit ();
	} else {
		addPlayer ($conn, $player, $round);
		include './session_start.php';
		$_SESSION['round'] = $round;
		$_SESSION['token'] = md5($player.$GLOBALS['apikey']);
		header('Location: ./main.php');
	}
	
	$conn->close ();
?>

<!-- #<script> 
#    var url= "./main.php";
#    window.location = url; 
#</script>  -->