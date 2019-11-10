<?php
function db_connect()
{
	$servername = "localhost";
	$username = "root";
	$password = "";
	$database = "daisybeaver";
	// Create connection
	$conn = new mysqli($servername, $username, $password, $database);

	// Check connection
	if ($conn->connect_error) {
		exit(json_encode(formResp(false, "Connection failed: " . $conn->connect_error)));
	}

	$conn->set_charset("utf8");
	return $conn;
}

function db_token($string, $method)
{ // method sẽ được sử dụng sau
	$apikey = 'daisy2610';
	return md5($string . $apikey);
}

function serverpath($dir)
{
	$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
	if (substr_count($_SERVER['DOCUMENT_ROOT'], 'DaisyQuizzes') == 0) {
		$DOCUMENT_ROOT = $DOCUMENT_ROOT . '/DaisyQuizzes';
	}
	return $DOCUMENT_ROOT . '/' . $dir;
}

function path($dir)
{
	$str = '/' . $dir;
	return $str;
}

function assets($dir)
{
	return path('assets/' . $dir);
}
