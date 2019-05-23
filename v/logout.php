<?php
session_name("performance-org");
session_start();

if ($_SESSION['role'] == "app_admin") {
	// hyper link
	$link = "setup";
} else { 
	$link = "";
}

$expire = time()-60;
//setcookie('chowhub', $_SESSION['name'], $expire);
session_destroy();
header("Refresh:0; url=../$link");
// echo("You are Logged Out");
?>
