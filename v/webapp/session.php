
<?php

if(!isset($_SESSION['name'])){
  //header("location:../") ;

	// echo "<li class='header-action-button'><a href='#' class='btn btn-primary' data-toggle='modal' data-target='#login'>LOGIN</a></li>";
	exit;
}

else{
	echo ($_SESSION['name']);
}

?>