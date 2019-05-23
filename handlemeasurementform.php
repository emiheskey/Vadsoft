<?php

$aid = $_GET['aid'];
$ass_date = "";
// form ref for decline button
$ref_accept = base64_encode($assessment_type_level_id."_accept_".$aid."_".$kid."_".$ass_date."_".$_GET['mcategory']."_".$assessment_type_level."_".$astype_id."_".$staff_id."_".$ass_key_id);
$loop_count = 1;

$dept = "";
if($_session['staff_dept']){
    $dept = $_session['staff_dept'];
}

// I introduced the loop count to check the duplication of fields. So it runs only once
include('v/webapp/flashMessage.php');
echo "
    <form method='post' name='measure' id='measure' action='v/webapp/measure-staff-supervisor.php?ref=$ref_accept&dept=$dept'>";
        echo "<h3>Measuring: ".GetKPIId($connection,$kid)." / <strong>". GetCatNameId($connection,$k)."</strong></h3><hr />";
        // get date value
        foreach(GetAallParaStfPersonal($connection,$k,$assessment_type_level_id,$kid) as $row)
        {

            if ($loop_count == 1)
            {
                $aid = $_GET['aid'];

                $param = $row['asp_name'];     // assement name
                $slug = $row['asp_slug'];      // the slug or call it url string
                $typ = $row['asp_typ_level'];  // assesment level
                $typ_id = $row['asp_astyp'];
                $cat = $row['asp_cat'];        // assesment category
                $field = $row['asp_typ'];      // assesment type
                $value = $row['asp_value'];    // assesment value
                $id = $row['asp_id'];          // assesment id
                $param_value = GetAssessmentSupervisorsRecord($connection, $aid, $cat);
                $param_hours_worked = GetHoursWorkedSupervisor($connection, $aid, $kid, $assessment_type_level_id);

                $std_value = "";
                $std_point = "";
                $ass_date = "";
                foreach ($param_value as $ass_row)
                {
                    $std_value = $ass_row['std_value'];
                    $std_point = $ass_row['ass_point'];
                    $ass_date = $ass_row['ass_date'];
                }

                $expectedhoursworked = "";
                $actualhoursworked = "";
                $expecteddays="";
                $actualdays="";
                foreach ($param_hours_worked as $ass_labour)
                {
                    $expectedhoursworked = $ass_labour['whours_expected'];
                    $actualhoursworked = $ass_labour['whours_used'];
                    $expecteddays = $ass_labour['wdaysexpected'];
                    $actualdays = $ass_labour['wdaysused'];
                }

                // form ref for decline button
                $ref_decline = base64_encode($assessment_type_level_id."_decline_".$aid."_".$kid."_".$ass_date."_".$_GET['mcategory']."_".$assessment_type_level."_".$astype_id."_".$staff_id."_".$ass_key_id);

                if ($is_assesment == '')
                {
                    $is_assesment = $param;       // assign paramter to is_assesment
                } 
                else 
                {
                    $is_assesment = $is_assesment .= $param;
                }
                echo "<div style=''>";
                    // build block for date selection here
                    if ($kpi_routine == "daily") 
                    {
                        echo "<label>Enter date of assesment</label>";
                        echo "<input type='date' class='form-control' name='kpi_routine' value='$ass_date' readonly>";
                    } 
                    elseif ($kpi_routine == "weekly") 
                    {
                        echo "<label>Enter week of assesment from 1,2,...,54</label>";
                        echo "<input type='number' class='form-control' name='kpi_routine'>";
                    } 
                    elseif ($kpi_routine == "monthly") 
                    {
                        echo "<label>Select month of assesment</label>";
                        echo "<select class='form-control' name='kpi_routine'>
                                <option value='january'>January</option>
                                <option value='february'>February</option>
                                <option value='march'>March</option>
                                <option value='april'>April</option>
                                <option value='may'>May</option>
                                <option value='june'>June</option>
                                <option value='july'>July</option>
                                <option value='august'>August</option>
                                <option value='september'>September</option>
                                <option value='october'>October</option>
                                <option value='november'>November</option>
                                <option value='december'>December</option>
                        </select>";
                    } 
                    elseif ($kpi_routine == "quaterly") 
                    {
                        echo "<label>Enter quater of assesment</label>";
                        echo "<input type='number' class='form-control' name='kpi_routine' max='1'>";
                    }

                    echo "<em>All measurement are done using the current year <b>". date("Y")."</b></em>";
                echo "</div><hr>";
                echo "<div style='line-height:30px;'>
                        $param
                        <input type='text' class='form-control' name=score[] value='$std_point' required readonly>
                        <em>Out of</em> 
                        <input type='text' class='form-control' value='$std_value' name='standard[]' required readonly>
                        <input type='hidden' value='$id' name='param[]'>
                        <input type='hidden' value='$cat' name='cat[]'>
                        <input type='hidden' value='$typ' name='typ[]'>
                        <input type='hidden' value='$typ_id' name='typ_id[]'>
                        <input type='hidden' value='input' name='type[]'>
                </div>";
                // }
            }
            $loop_count++;
        }

        if ($assessment_type_level == "unit" or $assessment_type_level == "department" or $assessment_type_level == "project")
        {
            echo "<hr>";
            echo "Labour <br>";
            echo "<input type='number' name='labour' placeholder='Number of poeple involved' class='form-control'>";
        }
        echo "<hr>
                <div style='line-height:30px;'>
                    <input type='hidden' value='$kid' name='kpi'>
                    <input type='hidden' value='$aid' name='aid'>
                    <input type='hidden' value='$assessment_type_level' name='level'>
                    <input type='hidden' value='$ass_key_id' name='akid'>
                    Expected Hours of Work
                    <input type='number' name='exp_hours' placeholder='Enter expected No of hours' class='form-control' value='$expectedhoursworked' readonly>
                    No. of hours used
                    <input type='number' name='hours' placeholder='Enter No. of hours Staff used' class='form-control' value='$actualhoursworked' readonly>
                    Expected  No. of Days
                    <input type='number' name='exp_days' placeholder='Enter number of days required' class='form-control' value='$expecteddays' readonly>
                    No. of days used  </label>
                    <input type='number' name='days' placeholder='Enter No. of days used' class='form-control' value='$actualdays' readonly> 
                    <br>
                    <button type='submit' class='btn btn-primary'> <b>Accept ".GetCatNameId($connection,$k)." </b></button>
                    <a href='v/webapp/measure-staff-supervisor.php?ref=$ref_decline' class='btn btn-primary'> <b>Decline ".GetCatNameId($connection,$k)." </b></a>
                </div>
    </form>
";
?>