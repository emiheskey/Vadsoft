<?php 
// add header
// include('header.php');
// add nav bar
// include("functions.php");
// $_session['id'] = rand() ;
// echo $_session['id'];
// include('functions.php');
if ($_GET) 
{
    $name = $_GET['name'];
    $_session['staff_id'] = $name ;
    $_session['staff_dept'] = GetStaffDept($connection,$name);
    $level = $_GET['level'];
    $ass_key_id = $_GET['akid'];
    $aid = $_GET['aid'];

    if (!isset($_GET['kid'])) 
        $kid = "";
    else 
        $kid = $_GET['kid']; 
}

if (!isset($_GET['mode'])) {
    $mode="";
} else {
    $mode=$_GET['mode']; 
}

if ($level == "staff") {
    $pagetitle = "Staff Measurement"; $level_title = GetStaffName($connection, $name); $get_measure_kpi = GetGroupAssParamsStaff($connection,$name,'personal',$kid);
} elseif ($level == "unit") {
    $pagetitle = "Unit Measurement"; $level_title = GetUnitName($connection, $name); $get_measure_kpi = GetGroupAssParamsUnit($connection,$name,$kid,$level);
} elseif ($level == "department") {
    $pagetitle = "Department Measurement"; $level_title = GetDeptName($connection, $name); $get_measure_kpi = GetGroupAssParamsDept($connection,$name,$kid,$level);
}

// get staff name
// get department name
// get unit name
// using the name anchor
echo "<h3 style='margin: 0px;'>$level_title</h3><br>";
// echo $get_measure_kpi;
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
    // echo $get_measure_category."<br>";
    // echo $measure_category;
    // check for mode values
    if ($mode == "measurestaffkpi")
    {
        // echo "Measure staff KPI";
        // then fetch the category from the url anchor
        $get_measure_category = "effectiveness"; // $_GET['mcategory'];
        // lets check if the $get_measure_category is same as $measure_category
        if (strtolower($measure_category) == strtolower($get_measure_category))
        {
            // echo $measure_category;
            // include the staffmeasurementform here
            include("staffresultdetailsform.php");
        }
    }   
}

foreach ($get_measure_kpi as $crow)
{

    // get value of assesmen category id
    $k = $crow['aspcat_id'];
    // lets get the category we are to measure
    $measure_category = GetCatNameId($connection,$k);
    // echo $get_measure_category."<br>";
    // echo $measure_category;
    // check for mode values
    if ($mode == "measurestaffkpi")
    {
        // echo "Measure staff KPI";
        // then fetch the category from the url anchor
        $get_measure_category = "efficiency"; // $_GET['mcategory'];
        // lets check if the $get_measure_category is same as $measure_category
        if (strtolower($measure_category) == strtolower($get_measure_category))
        {
            // $aid = $aid + 1;
            $efficiency_value = GetEfficiencyValue($connection,$_SESSION['id'],$ass_key_id);
            foreach ($efficiency_value as $value) {
                # code...
                if ($value['ass_param_cat'] == $k) {
                    $aid = $value['ass_id'];
                }
            }
            // we will have to find assesment id for efficiency
            // echo $get_measure_category;
            // include the staffmeasurementform here
            include("staffresultdetailsform.php");
        }
    }   
}