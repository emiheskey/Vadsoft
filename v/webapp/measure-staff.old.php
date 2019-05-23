<?php 
session_name("performance-org");
session_start();
if ($_POST) {
	// Get the value of the assessment parameter 
	// run a loop to get the values 
	// insert the values into the assessment table 
	require_once("functions.php") ;
 
	$score = $_POST['score'] ;
	$param = $_POST['param'] ;
	$name = $_GET['name'] ;
	$typ = $_POST['typ'] ;
	$type = $_POST['type'] ;
	$standard = $_POST['standard'] ;
	$cat = $_POST['cat'] ;
	$exp_hours = $_POST['exp_hours'];
	$hours = $_POST['hours'] ;
	$days = $_POST['days'] ;
	$exp_days = $_POST['exp_days'] ;
	$dept =  $_GET['dept']  ;
	$usr_typ = "staff" ;
	$kpi = $_POST['kpi'];
	$kpi_routine = $_POST['kpi_routine'];
	// $param = $_POST['param'] ;

	foreach( $score as $index => $code ) 
	{
		$ass_id = RecordAssessStaffInput($connection,$name,$param[$index],$code,$datetime,$typ[$index],$cat[$index],$usr_typ,$date,$type[$index],$standard[$index],$dept,$kpi,$kpi_routine);
	}		
	RecordUserHour($connection,$name,$date,$hours,$exp_hours,$days,$exp_days,$ass_id,$kpi);
	header("Refresh:3; url=../../staffmeasurement?name=$name&kid=$kpi&m_staff=showreport");
	// echo $_GET['as'];
	echo "Successful";	
}

   


?>