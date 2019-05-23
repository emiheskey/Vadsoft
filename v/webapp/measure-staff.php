<?php 
error_reporting(E_ALL);
session_name("performance-org");
session_start();
if ($_POST) 
{
	// Get the value of the assessment parameter 
	// run a loop to get the values 
	// insert the values into the assessment table 
	require_once("functions.php");

	if (file_exists($_SERVER['DOCUMENT_ROOT']."/config/MailSettings.php")) {
		require_once($_SERVER['DOCUMENT_ROOT']."/config/MailSettings.php");
	} else {
		$path = substr(__DIR__, 0, -9);
		require_once($path."/config/MailSettings.php");
	}

	$score = $_POST['score'];
	$param = $_POST['param'];
	$level_id = $_GET['level_id'];
	$typ = $_POST['typ'];
	$typ_id = $_POST['typ_id'];
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

	$assessment_type_level_id = $_POST['level_id'];
	$assessment_type_level = $_POST['level'];
	$astype_id = $_POST['astype_id'];
	$staff_id = $_POST['staff_id'];

	$ass_key_id = $_POST['akid'];

	// $labour = $_POST['labour'];

	$level = $_POST['level'];

	foreach($score as $index => $code) 
	{

		$ass_id = RecordAssessStaffInputForSupervisor($connection,$level_id,$param[$index],$code,$datetime,$typ_id[$index],$cat[$index],$usr_typ,$date,$type[$index],$standard[$index],$dept,$kpi,$kpi_routine,$ass_key_id, $staff_id);

		// get staff supervisor
		$staff_supervisor = GetStaffSupervisor($connection, $level_id, $level);
		// get supervisor email
		$supervisor_email = GetStaffEmail($connection, $staff_supervisor);
		// get supervisor email
		$supervisor_name = GetStaffName($connection, $staff_supervisor);
		// echo $staff_supervisor ."/". $supervisor_name . "/" . $supervisor_email."<br>";

		if ($level == "department") {
			// get dept name
			$staff_name = GetDeptName($connection, $level_id);
		} elseif ($level == "unit") {
			// get staff name
			$staff_name = GetUnitName($connection, $level_id);
		} else {
			// get staff name
			$staff_name = GetStaffName($connection, $level_id);
			// echo $staff_name;
		}
		
		// find kpi title
		$kpi_title = GetKPIId($connection, $kpi);

		// get the kpi routine
		$kpiRoutine = GetKPIRoutine($connection,$kpi);
		// lets get the category we are to measure
   		$measure_category = GetCatNameId($connection,$cat[$index]);

        if ($kpiRoutine == "daily")
        {
        	$measure_time = date("D, d M Y", strtotime($kpi_routine));
        } 
        elseif ($kpiRroutine == "monthly") 
        {
        	$measure_time = date("M Y", strtotime($kpi_routine));
        }

		//$variables = "?name=$name&kid=$kpi&mode=measurestaffkpi&aid=$ass_id&mcategory=".strtolower($measure_category)."&level=$level&akid=$ass_key_id";
		$variables = "?level_id=$assessment_type_level_id&kid=$kpi&mode=measurestaffkpi&mcategory=".strtolower($measure_category)."&level=$assessment_type_level&akid=$ass_key_id&astype=$astype_id&staff=$staff_id&aid=$ass_id";
		// format link
		$measure_link = "http://app.vadsoft.com.ng/handlemeasurement".$variables."";

		// format email subject
		$email_subject = $staff_name . " " . $measure_category ." report on " . $kpi_title . " ". $measure_time ;
		// format email message
		$email_message = "Hello ". $supervisor_name . "\n\nKindly find attached $level report for the period as stated in the subject. Click on the link below to access the $level ". $measure_link . "\n\nWarm Regards";

		// send email to staff supervisor / organisation
		SendMail($supervisor_email, "", $email_subject, $email_message);
	}		
	RecordUserHourSupervisor($connection,$level_id,$date,$hours,$exp_hours,$days,$exp_days,$ass_id,$kpi);

	echo "Successful";	

	echo "<script>";

	echo "</script>";
}
?>