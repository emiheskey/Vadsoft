<?php 

if ($_POST) {
	// Get the value of the assessment parameter 
	// run a loop to get the values 
	// insert the values into the assessment table 
	require_once("functions.php") ;

	$score = $_POST['score'] ;
	$param = $_POST['param'] ;
	$name = $_POST['unit'] ;
	$typ = $_POST['typ'] ;
	$type = $_POST['type'] ;
	$standard = $_POST['standard'] ;
	$cat = $_POST['cat'] ;
	$hours = $_POST['hours'] ;
	$dept = $_POST['dept'] ;
	$usr_typ = "unit" ;
	// $param = $_POST['param'] ;

	foreach( $score as $index => $code ) {
		RecordAssessStaffInput($connection,$name,$param[$index],$code,$datetime,$typ[$index],$cat[$index],$usr_typ,$date,$type[$index],$standard[$index],$dept);
	}		
	RecordUserHour($connection,$name,$date,$hours,$usr_typ) ;
	header("Refresh:3; url=units.php");
	echo "Successful";	
}


?>