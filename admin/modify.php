<?php
	include($_SERVER['DOCUMENT_ROOT'].'/DaisyQuizzes/middleware/auth_admin.php');
	if (!checkLoggedIn ()){
		header('Location: ./login.php');
		exit ();	
	}
	
	$userid = $_SESSION ['userid'];
	$collection = $_GET ['k'];
	
	include($_SERVER['DOCUMENT_ROOT'].'/DaisyQuizzes/database.php');
	

	function db_fetch_questions ($conn, $collection) {
		$sql = "SELECT id, body, choice_a, choice_b, choice_c, choice_d FROM daisy_question WHERE collection_id = $collection";
		$result = [];
		$q = $conn->query ($sql);
		while ($row = $q->fetch_assoc ()) {
			$result[] = $row;
		}
		return $result;
	}
	
	$conn = db_connect ();
	if (!db_authen ($conn, $userid, $collection)) {
		$conn->close ();
		die ("Không tìm thấy bộ câu hỏi được yêu cầu");
	}

	$data = db_fetch_questions ($conn, $collection);
	echo '<a href="./add_question.php?k='.$collection.'">Thêm</a><br>';
	$cnt = 0;
	
	foreach ($data as $each) {
		$cnt++;
		echo "<div class='question-pane'>";
		echo $cnt.". ".$each['body']."<br>";
		echo 'A (đáp án): '.$each['choice_a']."<br>";
		echo 'B: '.$each['choice_b']."<br>";
		echo 'C: '.$each['choice_c']."<br>";
		echo 'D: '.$each['choice_d']."<br>";
		echo "</div>";
	}
	echo '<a href="./add_question.php?k="'.$collection.'">Thêm</a><br>';
	?>
	
<?php
	$conn->close ();
	
?>
