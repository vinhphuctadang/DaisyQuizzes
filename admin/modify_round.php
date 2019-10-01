<?php
	$str = $_SERVER['DOCUMENT_ROOT'].'/DaisyQuizzes/middleware/auth_admin.php';
	include $str;
	$str = $_SERVER['DOCUMENT_ROOT'].'/DaisyQuizzes/database.php';
	include $str;
	
	if (!checkLoggedIn ())	{	
		header('Location: ./login.php');
		exit ();	
	}
	
	function getStatus ($conn, $round) {
		$sql = "SELECT status FROM daisy_round_collection WHERE round='$round'";
		$result = $conn->query ($sql);
		if ($result->num_rows == 0)
			return -1;
		$result = $result->fetch_assoc ();
		return $result['status'];
	}
	
	function setStatus ($conn, $round, $status) {
		$sql = "UPDATE daisy_round_collection SET status=$status WHERE round='$round'";
		$conn->query ($sql);
	}
	
	function findLoggedPlayer ($conn, $round) {
		
		$sql = "SELECT name, created_time, score FROM daisy_player_round WHERE round='$round'";
		$result = $conn->query ($sql);
		$list = [];
		while ($row = $result->fetch_assoc ()){
			$list[] = $row;
		}
		return $list;
	}
	
	$round = $_GET['k'];
	$conn = db_connect ();
	$result = findLoggedPlayer ($conn, $round);
	$status = getStatus ($conn, $round);
	if (isset ($_POST['change'])) {
		if ($status == 0) $status = 1; else $status = 0;
		setStatus ($conn, $round, $status);
	}
	$conn->close ();
?>
<html>	
	<head>
		<title>dashboard</title>
		<meta charset="utf-8">
		<link href="./index.css" rel="stylesheet" type="text/css">
	</head>
	
	<body>
		<p>Trạng thái: <?php echo $status;?></p>
		<form action="modify_round.php?k=<?php echo $round;?>" method="post">
			<input type="hidden" name="change">
			<input type="submit" value="Thay đổi trạng thái">
		</form>
		<div class="container">
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
						$cnt ++;
						echo "<tr>";
							echo "<td>".$cnt."</td>";
							echo "<td>".$each['name']."</td>";
							echo "<td>".$each['created_time']."</td>";
							echo "<td>".$each['score']."</td>";	 
						echo "</tr>";
					}
				?>
			</table>
		</div>
	</body>
</html>
	