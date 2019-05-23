<?php
if (file_exists($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php")) {
	$path = $_SERVER['DOCUMENT_ROOT'];
	require_once $_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php";
}else{
	$path = substr(__DIR__, 0, -7);
    require_once $path."/vendor/autoload.php";
}

$dotenv = new Dotenv\Dotenv($path);
$dotenv->load();

$servername = $_ENV['DB_HOST'];
$databasename = $_ENV['DB_DATABASE'];

$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASSWORD'];

$datetime = date('Y-m-d H:i:s');
$date = date('Y-m-d');

// db connection
$connection = new PDO("mysql:host=$servername;dbname=$databasename",$username,$password); 

$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if (!$connection) {
	echo "No connection esterblished";
}

?>