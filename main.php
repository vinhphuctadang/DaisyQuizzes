<html>
	<head>
		<title>Daisy Quizzes</title>
		<meta charset="utf-8">		
	</head>
	
	<body>
		
		<?php						
			/*if (!isset ($_POST ['player'])) {
				die ("Không tồn tại người chơi, xóa nối kết");
			}*/
			
			$player = 'daisy';//$_POST ['player'];
			$round_code = "love"; // should retrieve from 
			
			function db_connect () {
				$servername = "localhost";
				$username = "root";
				$password = "";
				$database = "daisybeaver";
				
				// Create connection
				$conn = new mysqli($servername, $username, $password, $database);
				// Check connection
				if ($conn->connect_error) {					
					exit(json_encode (formResp (false, "Connection failed: " . $conn->connect_error)));
				}				
				
				$conn->set_charset ("utf8");
				
				return $conn;
			}
			
			#function checkPlayer ($conn, $round, $player) {
			#	if (!in_array ('player', $_POST)) {
			#		die ("Không tồn tại player, hãy về trang đăng nhập tại ./entry.php");
			#	} else {
			#		$p = $_POST ['player'];
			#		$sql = "
			#	}
			#}
			
			function db_fetch_question ($conn, $round) {
				$sql = "SELECT status, question_no FROM daisy_round_collection where round='$round'";
				$result = $conn->query ($sql);			
				$assoc = $result->fetch_assoc ();
				$status = $assoc ['status'];
				
				if ($status == 0)
					die ("This round is now closed or not exists");
				
				$question_no =  $assoc ['question_no'];
				
				
				$sql = "SELECT id, body, choice_a, choice_b, choice_c, choice_d FROM daisy_shuffle_content, daisy_question WHERE question_id = id AND question_no=".$question_no;
				$result = $conn->query ($sql);
				$question = $result->fetch_assoc ();
				return $question;
			}
			
			function display ($question, $round, $player) {		
				echo "<form action='check.php' method='post'>";
				echo "<p>".$question['body']."</p>";
					$val = ['a', 'b', 'c', 'd'];
					shuffle ($val);					
					foreach ($val as $c) {
						echo "<input type='submit' name='choice' value='".$question['choice_'.$c]."'>"."</input> <br>";
					}
					
					echo "<input type='hidden' name='round' value='".$round."'>"."</input> <br>"; 
					echo "<input type='hidden' name='player' value='".$player."'>"."</input> <br>"; # cái này chưa có bảo mật, mặc định là daisy
				echo "</form>";
			}
			
			$conn = db_connect ();
			$question = db_fetch_question ($conn, $round_code);
			display ($question, $round_code, $player);
			$conn->close ();
		?>
	</body>
</html>