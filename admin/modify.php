<?php

	session_start (); // middleware 
	if (!isset ($_SESSION['userid']))	{	
		header('Location: ./login.php');
		exit ();	
	}
	
	
?>
