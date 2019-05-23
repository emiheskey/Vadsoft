<?php

// add nav bar
include("v/webapp/functions.php");

if ($_GET) 
{
    $name = $_GET['name'];
    $_session['staff_id'] = $name ;
    $_session['staff_dept'] = GetStaffDept($connection,$name);
    $level = $_GET['level'];
    $ass_key_id = $_GET['akid'];
}


if (!isset($_GET['mode']))
{
    $mode="";
}
else
{
    $mode=$_GET['mode']; 
}

if ($level == "staff") $pagetitle = "Staff Measurement";
if ($level == "unit") $pagetitle = "Unit Measurement";
if ($level == "department") $pagetitle = "Department Measurement";
if ($level == "project") $pagetitle = "Project Measurement";
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
                        // get staff name
                        // get department name
                        // get unit name
                        // usin the name anchor
                        if ($level == "staff")
                        {
                            $level_title = GetStaffName($connection, $name);
                        }

                        if ($level == "unit")
                        {
                            $level_title = GetUnitName($connection, $name);
                        }

                        if ($level == "department")
                        {
                            $level_title = GetDeptName($connection, $name);
                        }

                        if ($level == "project")
                        {
                            $level_title = GetProjectName($connection, $name);
                        }

                        echo "<h3>".ucwords(strtolower($level_title))."</h3>";
                        echo "<hr />";

                        if ($mode == "showreport")
                        {
                            if (!isset($_GET['kid']))
                            {
                                $kid = "";
                            }
                            else
                            {
                                $kid = $_GET['kid']; 
                            }
                            
                            echo "<h4>Measuring: <a href='#'>".GetKPIId($connection,$kid)."</a></h4>";
                            echo '<p>Your assesment report have been logged and a notification sent to  your supervisor to vet the report. Once he acts on it, the system will send you an automated report via email';
                        }

                        if (!isset($_GET['kid']))
                        {
                            $kid = "";
                        }
                        else
                        {
                            $kid = $_GET['kid']; 
                        }

                        // get the kpi to measure

                        if ($level == "staff")
                        {
                            $get_measure_kpi = GetGroupAssParamsStaff($connection,$name,'personal',$kid);
                        }
                        elseif ($level == "unit")
                        {
                            $get_measure_kpi = GetGroupAssParamsUnit($connection,$name,$kid,$level);
                        }
                        elseif ($level == "department")
                        {
                            $get_measure_kpi = GetGroupAssParamsDept($connection,$name,$kid,$level);
                        }
                        elseif ($level == "project")
                        {
                            $get_measure_kpi = GetGroupAssParamsDept($connection,$name,$kid,$level);
                        }

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
                              echo "Measuring: <a href='measurement?name=$name&kid=$kid&mode=measurestaffkpi&mcategory=$measure_category&level=$level&akid=$ass_key_id'>".GetKPIId($connection,$kid)." / <strong>". GetCatNameId($connection,$k)."</strong></a><hr />";
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