<?php

try {
	$strJsonFileContents = file_get_contents(serverpath("config.json"));
	$array = json_decode($strJsonFileContents, true);
	$NODEJS_HOST_SERVER = 'http://' . $array['HOST'] . ':8080';
} catch (Exception $e) {
	$NODEJS_HOST_SERVER = 'http://localhost:8080';
}

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

function serverpath($dir) // generate path for serverside
{
	$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
	if (substr_count($_SERVER['DOCUMENT_ROOT'], 'DaisyQuizzes') == 0) {
		$DOCUMENT_ROOT = $DOCUMENT_ROOT . '/DaisyQuizzes';
	}
	return $DOCUMENT_ROOT . '/' . $dir;
}

function path($dir) // path for client side that connects to server
{
	$str = '/DaisyQuizzes/' . $dir;
	return $str;
}

function assets($dir) // path for client side connect to server, retrieving assets
{
	return path('assets/' . $dir);
}
