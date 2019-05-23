<?php
include("functions.php");

if (file_exists($_SERVER['DOCUMENT_ROOT']."/config/MailSettings.php")) {
    include($_SERVER['DOCUMENT_ROOT']."/config/MailSettings.php");
} else {
    $path = substr(__DIR__, 0, -9);
    include($path."/config/MailSettings.php");
}

// cron process to manage measurement process
// checks for active businesses first
$activeOrganizations = GetOrganizations($connection, '0');
$staff_id = 0;

// get individual Organizations
foreach ($activeOrganizations as $row) 
{
	$org_id = $row['org_id'];
	echo "<br>Org Id: ".$org_id."<br>";
	// retrieves the organisation's kpis
	$organizationKPI = GetKPI($connection, '', $org_id);

	// get all the assesment type connected to this company
	$organizationAssesmentType = GetAssType($connection, $org_id);

	// loop through to pick each assesment type for the selected organisation
	foreach ($organizationAssesmentType as $assesment_row)
	{
		echo "<br>assessment type: ".$assesment_row['astyp_id']."<br>";
		// assesment_type_id
		$assesment_type_id = $assesment_row['astyp_id'];
    
		// assessment_level
		$assessment_level = $assesment_row['astyp_org_level'];

		// loop through to fetch each kpi
		foreach ($organizationKPI as $kpi_row)
		{
			$kpi_id = $kpi_row['kpi_id'];
			echo "<br>KPI Id: ".$kpi_id."<br>";
			// get the kpi routine
			$kpiRoutine = GetKPIRoutine($connection,$kpi_id);

			// can we get the last time this KPI was sent to for measurement
			$kpi_last_assesement_date = GetKPILastAssesmentDate($connection, $kpi_id);

			$start_time = strtotime(date($kpi_last_assesement_date));
			$end_time = strtotime(date("Y-m-d h:i:s", time()));

			$difference_date = $end_time - $start_time;
			$difference_hours = round($difference_date / 3600);

			// get the nature of this KPI 
			// department | Unit | staff
			// get the staff responsible to receive link for department and unit

			// can we find the last time this kpi was measured

			// get staffs attached to this kpi for this particular organisation 
			$staffOnKPI = GetKPIForStaff($connection, null, $assesment_type_id, $kpi_id);
			// $deptOnKPI = GetKPIForDept($connection, null, $assesment_type_id, $kpi_id);
			// $unitOnKPI = GetKPIForUnit($connection, null, $assesment_type_id, $kpi_id);

			echo "<br>";
			echo "ass type: ".$assesment_type_id;
			echo "<br>";
			echo "KPI ".$kpi_id." staff count:".count($staffOnKPI);
			echo "<br>";

			$ass_key_id = rand(0000,9999);

			if(is_array($staffOnKPI) && count($staffOnKPI) > 0){

				
				foreach ($staffOnKPI as $staff_row)
				{
					$type_id = 0;
					$staffs_id = [];
					if ($assessment_level == "staff-ass")
					{
						echo "Staff<br>";
						array_push($staffs_id, $staff_row['asp_staff']);
						$type_id = $staff_row['asp_staff'];
					}

					if ($assessment_level == "unit")
					{
						echo "Unit<br>";
						// find the name of unit
						$unit_id = $staff_row['asp_unit'];
						$type_id = $unit_id;
						// find the staff for whom the link will be sent to
						$staffs_id = GetLinkPersonsUnit($connection, $unit_id);
						echo "<br> staffs unit: ".json_encode($staffs_id);
						// get unit name
						$unit_name = GetUnitName($connection, $unit_id);
					}

					if ($assessment_level == "department")
					{
						echo "Department<br>";
						// find the name of unit
						$dept_id = $staff_row['asp_dept'];
						$type_id = $dept_id;
						// find the staff for whom the link will be sent to
						$staffs_id = GetLinkPersonsDept($connection, $dept_id);
						// get department name
						$dept_name = GetDeptName($connection, $dept_id);
					}


					if ($assessment_level == "project")
					{
						echo "Project<br>";
						// find the name of unit
						$project_id = $staff_row['asp_project'];
						$type_id = $project_id;
						// find the staff for whom the link will be sent to
						$staffs_id = GetLinkPersonsProject($connection, $project_id);
						// get department name
						$project_name = GetProjectName($connection, $project_id);
					}


					for ($i_new=0; $i_new < count($staffs_id); $i_new++) { 
						$staff_id = $staffs_id[$i_new];
						echo "<br> staff Id: ".$staff_id;
						// find staff email
						$staff_email = GetStaffEmail($connection,$staff_id);

						echo "<br>";
						echo "Email for KPI for ".$assessment_level.": ".$staff_email;
						echo "<br>";
						// find staff name
						$staff_name = GetStaffName($connection,$staff_id);

						// $condt = GetKpiViaCategory($connection, $kpi_id, $org_id);
						$condt = GetKpiViaCategoryy($connection, $kpi_id, $org_id, $assesment_type_id);
						if(is_array($condt) && count($condt) > 0) {

							$countt = 0;
							$i = 0;
							foreach($condt as $c) {
								$params = GetAssessmentParameter($connection, $assessment_level, $type_id, $c['aspcat_id']);
								if(count($params) > 0) {
									$i++;
								}
								$countt++;
								echo "aspcat:----".$c['aspcat_id']."<br>";
								echo "i-----:". $i."<br>";
								echo "count----:".$countt."<br>";
							}

							if($i == $countt){
								//$params = GetAssessmentParameter($assessment_level, $type_id, $asp_cat_id);
								if ($assessment_level == "staff-ass")
								{

									// format link
									$measure_link = "http://app.vadsoft.com.ng/measurement?level_id=$staff_id&mode=listkpimeasurecategory&kid=$kpi_id&level=staff&astype=$assesment_type_id&staff=$staff_id&akid=$ass_key_id";
									// format email body | subject
									$message = "Hello $staff_name, click on the link below to measure yourself ". $measure_link;
								}

								if ($assessment_level == "unit")
								{
									// format link
									$measure_link = "http://app.vadsoft.com.ng/measurement?level_id=$unit_id&mode=listkpimeasurecategory&kid=$kpi_id&level=unit&astype=$assesment_type_id&staff=$staff_id&akid=$ass_key_id";
									// format email body | subject
									$message = "Hello $staff_name, click on the link below to measure your unit $unit_name ". $measure_link;
								}

								if ($assessment_level == "department")
								{
									// format link
									$measure_link = "http://app.vadsoft.com.ng/measurement?level_id=$dept_id&mode=listkpimeasurecategory&kid=$kpi_id&level=department&astype=$assesment_type_id&staff=$staff_id&akid=$ass_key_id";
									// format email body | subject
									$message = "Hello $staff_name, click on the link below to measure your department $dept_name ". $measure_link;
								}

								if ($assessment_level == "project")
								{
									// format link
									$measure_link = "http://app.vadsoft.com.ng/measurement?level_id=$project_id&mode=listkpimeasurecategory&kid=$kpi_id&level=project&astype=$assesment_type_id&akid=$ass_key_id";
									// format email body | subject
									$message = "Hello $staff_name, click on the link below to measure your Project $project_name ". $measure_link;
								}


								$v = queryLastUserQueue($connection, $staff_email);
								$count = 0;
								$insertQueueTest = true;
								foreach($v as $q) {
									$cmp1 = substr($q[4], 0, strpos($q[4], "&akid"));
									$cmp2 = substr($message, 0, strpos($message, "&akid"));
									if($cmp1 == $cmp2) {
										$insertQueueTest = false;
										break;
									}
								}

								if ($kpiRoutine == "daily")
								{
									$time = time();
									$datetimelinksent = date("Y-m-d h:i:s", $time);
									// update the PI table with last time stamp link was sent
									// send email to user
									echo "Daily Measure<br>----------<br>";

									if($insertQueueTest) {

										echo "<br>";
										echo "Email: ".$staff_email;
										echo "<br>";
										queueEmail($connection, $staff_email, $staff_name, "VADSoft Measurement Link", $message, '0', $datetimelinksent);
									}
									
									UpdateKPILastTimeLinkSent($connection, $kpi_id, $org_id, $datetimelinksent);
									echo "sent<br>$measure_link<br>$datetimelinksent";
								}

								if ($kpiRoutine == "weekly")
								{
									echo $difference_hours;
									// // check for last date difference
									if ($difference_hours >= 168 and $difference_hours <=200)
									{
										$time = time();
										$datetimelinksent = date("Y-m-d h:i:s", $time);
										if($insertQueueTest) {

											echo "<br>";
											echo "Email to be sent to: ".$staff_email;
											echo "<br>";
											queueEmail($connection, $staff_email, $staff_name, "VADSoft Measurement Link", $message, '0', $datetimelinksent);
										}

										// update the PI table with last time stamp link was sent
										UpdateKPILastTimeLinkSent($connection, $kpi_id, $org_id, $datetimelinksent);
										echo "sent<br>$measure_link<br>$datetimelinksent";
									}
								}

								if ($kpiRoutine == "monthly")
								{
									echo $difference_hours;
									// // check for last date difference
									if ($difference_hours >= 672 and $difference_hours <=700)
									{
										$time = time();
										$datetimelinksent = date("Y-m-d h:i:s", $time);
										if($insertQueueTest) {

											echo "<br>";
											echo "Email: ".$staff_email;
											echo "<br>";
											queueEmail($connection, $staff_email, $staff_name, "VADSoft Measurement Link", $message, '0', $datetimelinksent);
										}

										UpdateKPILastTimeLinkSent($connection, $kpi_id, $org_id, $datetimelinksent);
										echo "sent<br>$measure_link<br>$datetimelinksent";
									}
								}
							}
						}
					}

				}
			}
		}
	}
}
