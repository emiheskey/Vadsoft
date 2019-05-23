<?php 
error_reporting(E_ALL);
session_name("performance-org");
session_start();

require_once("functions.php");

// mail function
if (file_exists($_SERVER['DOCUMENT_ROOT']."/config/MailSettings.php")) {
    require_once($_SERVER['DOCUMENT_ROOT']."/config/MailSettings.php");
} else {
    $path = substr(__DIR__, 0, -9);
    require_once($path."/config/MailSettings.php");
}

// decode the url anchor value
$ref = base64_decode($_GET['ref']);
echo $ref;
// explode using the underscore(_) seperator
$ref = explode("_", $ref);
// // extract the values from the array
$action = $ref[1]; // value for action. either decline or accept
$name = $ref[0]; // this is the id of teh staff, department or unit
$kpi_id = $ref[3]; // the KPI that is to be measured
$level_id = $ref[0];
$ass_id = 0;
$staff_id = $ref[8];
$level = $ref[6];
if ($action == "decline")
{
	// echo "This request have been declined";
	$aid = $ref[2]; // this is the id of the measurement parameter and template
	$measure_date = $ref[4];
	$measure_category = $ref[5];
	$level = $ref[6];
	$assesment_type_id = $ref[7];
	$staff_id = $ref[8];
	$ass_key_id = $ref[9];
	$level_id = $ref[0];

	// updates the db table
	if ( DeclineAssessStaffInput($connection, $aid) == true)
	{
		echo "Request has been declined";
		// send email to staff that measurement was refused by supervisor
		// staff id
		$staff_id = $name;
		// get staff supervisor
		$staff_supervisor = GetStaffSupervisor($connection, $name, $level);
		// get staff name
		$staff_name = GetStaffName($connection, $staff_id);
			

		if ($level == "unit") {
			// get nodal staff
			$staff_id = GetLinkPersonUnit($connection, $name);
			$staff_name = GetUnitName($connection, $name);

		} elseif ($level == "department") {
			// get nodal staff
			$staff_id = GetLinkPersonDept($connection, $name);
			$staff_name = GetDeptName($connection, $name);

		}

		// get staff email
		$staff_email = GetStaffEmail($connection, $staff_id);
		// get supervisor email
		$supervisor_name = GetStaffName($connection, $staff_supervisor);
		// find kpi title
		$kpi_title = GetKPIId($connection, $kpi_id);

		//http://app.vadsoft.com.ng/measurement?level_id=$unit_id&mode=listkpimeasurecategory&kid=$kpi_id&level=unit&astype=$assesment_type_id&staff=$staff_id&akid=$ass_key_id
		//$variables = ?name=$staff_id&kid=$kpi_id&mode=measurestaffkpi&mcategory=$measure_category&level=$level;
		$measure_link = "http://app.vadsoft.com.ng/measurement?level_id=$level_id&kid=$kpi_id&mode=measurestaffkpi&mcategory=$measure_category&level=$level&astype=$assesment_type_id&staff=$staff_id&akid=$ass_key_id";

		// format email body | subject
		$email_message = "Hello $staff_name, your supervisor $supervisor_name have declined your measurement data for the KPI $kpi_title / $measure_category for $measure_date. Kindly click on the link below to measure yourself again ". $measure_link;

		$email_subject = "VADSoft - Measurement Declined";
		echo $email_subject."<br>".$email_message;

		// send email to staff supervisor / organisation
		$mail = SendMail($staff_email,"",$email_subject, $email_message);
	}
}
else
{
	// fetch action value
	if ($_POST) 
	{
		// Get the value of the assessment parameter 
		// run a loop to get the values 
		// insert the values into the assessment table 

		$score = $_POST['score'];
		$param = $_POST['param'];
		// $name = $_POST['name'];
		$typ = $_POST['typ'];
		$astype_id = $_POST['typ_id'];
		$type = $_POST['type'];
		$standard = $_POST['standard'];
		$cat = $_POST['cat'];
		$exp_hours = $_POST['exp_hours'];
		$hours = $_POST['hours'];
		$days = $_POST['days'];
		$exp_days = $_POST['exp_days'];
		$dept =  $_GET['dept'];
		$usr_typ = $_POST['level']; // "staff";
		$kpi = $_POST['kpi'];
		$kpi_routine = $_POST['kpi_routine'];
		$param = $_POST['param'];
		$aid = $_POST['aid'];
		$labour = 1;
		$ass_key_id = $_POST['akid'];
		$level = $_POST['level'];

		// echo "<br>". $aid;
		foreach( $score as $index => $code ) 
		{
			// calculate value
	        // calculate value
	        $input_per_person_unit = $standard[$index] / $labour * $hours * $days;
			$output_per_person_unit = $score[$index] / $labour * $hours * $days;

			$value = 0;
			if($input_per_person_unit != 0) {
				$value = $output_per_person_unit / $input_per_person_unit;
			}

			$value = number_format((float)$value, 2, '.', '');

			
			// lets get the category we are to measure
	  		$measure_category = GetCatNameId($connection,$cat[$index]);
			if (strtolower($measure_category) == strtolower("effectiveness")) 
			{
			    // echo $ass_key_id;
				// perform calculation
				$effectiveness = $value;
				// register effectiveness
				RecordAssessStaffResult($connection,$kpi,$aid,$kpi_routine,time(),$ass_key_id,$effectiveness,'0.00',$name,$level);
			} 
			elseif (strtolower($measure_category) == "efficiency")
			{
				// perform calculation
				$efficiency = $value;
				// register efficiency
				RecordAssessStaffResult($connection,$kpi,$aid,$kpi_routine,time(),$ass_key_id,'0.00',$efficiency,$name,$level);
			}

			$ass_id = RecordAssessStaffInput($connection,$name,$param[$index],$code,$datetime,$astype_id[$index],$cat[$index],$usr_typ,$date,$type[$index],$standard[$index],$dept,$kpi,$kpi_routine,$aid,$ass_key_id);

		}		
		
		// ?name=$name&kid=$kpi&m_staff=showreport
		$astype_id = $astype_id[0];
//		$variables = "?level_id=$level_id&kid=$kpi&m_staff=showreport&level=$level&akid=$ass_key_id&astype=$astype_id&staff=$staff_id&aid=$ass_id";
		echo "Successful";
		echo "<script>";
		echo "window.location.replace('../../handlemeasurement?level_id=$level_id&kid=$kpi&mode=showreport&level=$level&akid=$ass_key_id&astype=$astype_id&staff=$staff_id&aid=$ass_id')";
		echo "</script>";
	}
}
?>