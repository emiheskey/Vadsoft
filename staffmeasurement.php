<?php
    // add nav bar
    include "v/webapp/functions.php";

    if ($_GET) {
        $assessment_type_level_id = $_GET['level_id'];
        $assessment_type_level = $_GET['level'];

        $astype_id = $_GET['astype'];

        $staff_id = $_GET['staff'];
        $_session['staff_id'] = $staff_id;
        $_session['staff_dept'] = GetStaffDept($connection, $staff_id);
        $_session['org_id'] = GetStaffOrgId($connection, $staff_id);
        $ass_key_id = $_GET['akid'];

    }

    $mode = "";
    if (isset($_GET['mode'])) {
        $mode = $_GET['mode'];
    }
    $kid = "";
    if (isset($_GET['kid']))
    {
        $kid = $_GET['kid'];
    }

    if ($assessment_type_level == "staff") {
        $pagetitle = "Staff Measurement";
    }

    if ($assessment_type_level == "unit") {
        $pagetitle = "Unit Measurement";
    }

    if ($assessment_type_level == "department") {
        $pagetitle = "Department Measurement";
    }

    if ($assessment_type_level == "project") {
        $pagetitle = "Project Measurement";
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- start: Meta -->
    <meta charset="utf-8">
    <title><?php echo $pagetitle ?></title>
    <!-- end: Meta -->
    <!-- start: Mobile Specific -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- end: Mobile Specific -->
    <!-- start: CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Roboto:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&subset=latin,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>
    <!-- end: CSS -->
    <!-- start: Favicon -->
    <link rel="shortcut icon" href="img/favicon.ico">
    <!-- end: Favicon -->
</head>

<body>
<header class="header">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="account-wall">

                        <?php
                            if ($assessment_type_level == "staff") {
                                $level_title = GetStaffName($connection, $staff_id);
                                $get_measure_kpi = GetGroupAssParamsStaff($connection, $assessment_type_level_id, 'personal', $kid, $astype_id);
                            }
                            if ($assessment_type_level == "unit") {
                                $level_title = GetUnitName($connection, $assessment_type_level_id);
                                $get_measure_kpi = GetGroupAssParamsUnit($connection, $assessment_type_level_id, $kid, $assessment_type_level, $astype_id);
                            }
                            if ($assessment_type_level == "department") {
                                $level_title = GetDeptName($connection, $assessment_type_level_id);
                                $get_measure_kpi = GetGroupAssParamsDept($connection, $assessment_type_level_id, $kid, $assessment_type_level, $astype_id);
                            }
                            if ($assessment_type_level == "project") {
                                $level_title = GetProjectName($connection, $assessment_type_level_id);
                                $get_measure_kpi = GetGroupAssParamsDept($connection, $assessment_type_level_id, $kid, $assessment_type_level, $astype_idl);
                            }

                            echo "<h3>".ucwords(strtolower($level_title))."</h3>";
                            echo "<hr />";

                            if ($mode == "showreport")
                            {
                                echo "<h4>Measuring: <a href='#'>".GetKPIId($connection,$kid)."</a></h4>";
                                echo '<p>Your assesment report have been logged and a notification sent to  your supervisor to vet the report. Once he acts on it, the system will send you an automated report via email';
                            }
                            $level = $assessment_type_level;
                            $name = $assessment_type_level_id;
                        // get kpi routime measurement Ccx 
                        $kpi_routine = GetKPIRoutine($connection,$kid);
                        // check assesment value
                        $is_assesment = '';

                        // loop through to build form values
                        foreach ($get_measure_kpi as $crow)
                        {
                            // get value of assesmen category id
                            $k = $crow['aspcat_id'];
                            // lets get the category we are to measure
                            $measure_category = GetCatNameId($connection,$k);

                            // check for mode values
                            if ($mode == "measurestaffkpi")
                            {
                                // then fetch the category from the url anchor
                                $get_measure_category = $_GET['mcategory'];

                                // lets check if the $get_measure_category is same as $measure_category
                                if (strtolower($measure_category) == strtolower($get_measure_category))
                                {
                                    // include the staffmeasurementform here
                                    include("staffmeasurementform.php");
                                }
                            }

                            // check for mode values
                            if ($mode == "listkpimeasurecategory")
                            {
                                $measure_category = strtolower($measure_category);
                                //$url = 'measurement?level_id=$name&kid=$kid&mode=measurestaffkpi&mcategory=$measure_category&level=$level&akid=$ass_key_id&astype=$astype_id&staff=$staff_id';
                                $url = "measurement?level_id=$assessment_type_level_id&mode=measurestaffkpi&kid=$kid&mcategory=$measure_category&level=$assessment_type_level&astype=$astype_id&staff=$staff_id&akid=$ass_key_id";
                              echo "Measuring: <a href='$url'>".GetKPIId($connection,$kid)." / <strong>". GetCatNameId($connection,$k)."</strong></a><hr />";
                            }
                        }
                    ?>

                </div>
            </div>
        </div>
    </div>
</header>

<!-- Placed at the end of the document so the pages load faster -->
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/validator.min.js"></script>
<script src="js/script.js"></script>
</body>
</html>