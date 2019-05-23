<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title">
            <?php echo GetKPIId($connection,$kid)." / ". GetCatNameId($connection,$k); ?>
        </h3>
    </div>
    <div class="box-body">
<?php
// form ref for decline button
$ref_accept = base64_encode($name."_accept_");
$loop_count = 1;
// I introduced the loop count to check the duplication of fields. So it runs only once
        // get date value
        foreach(GetAallParaStfPersonal($connection,$k,$name,$kid) as $row)
        {
            if ($loop_count == 1)
            {
                
                // $assparam = 2;
                // get values from supervisor
                // Staff ID             - $staf_id
                // Assessment category  - $k
                // KPI                  - $kid
                // GetAssessmentValues($connection, $name, $k, $kid);
                // foreach (GetAssessmentSupervisorsRecord($connection,$aid,$k));

                $param = $row['asp_name'];     // assement name
                $slug = $row['asp_slug'];      // the slug or call it url string
                $typ = $row['asp_typ_level'];  // assesment level
                $cat = $row['asp_cat'];        // assesment category
                $field = $row['asp_typ'];      // assesment type
                $value = $row['asp_value'];    // assesment value
                $id = $row['asp_id'];          // assesment id

                // echo $aid;
                $param_value = GetAssessmentSupervisorsRecord($connection, $aid, $cat);
                $param_hours_worked = GetHoursWorkedSupervisor($connection, $aid, $kid, $name);
                $std_value = '';
                $std_point = '';
                $ass_date = '';
                foreach ($param_value as $ass_row)
                {
                    $std_value = $ass_row['std_value'];
                    $std_point = $ass_row['ass_point'];
                    $ass_date = $ass_row['ass_date'];
                }

                foreach ($param_hours_worked as $ass_labour)
                {
                    $expectedhoursworked = $ass_labour['whours_expected'];
                    $actualhoursworked = $ass_labour['whours_used'];
                    $expecteddays = $ass_labour['wdaysexpected'];
                    $actualdays = $ass_labour['wdaysused'];
                }

                // form ref for decline button
                $ref_decline = ""; // base64_encode($name."_decline_".$aid."_".$kid."_".$ass_date."_".$_GET['mcategory']."_".$ass_key_id);

                if ($is_assesment == '')
                    $is_assesment = $param;       // assign paramter to is_assesment
                else 
                    $is_assesment = $is_assesment .= $param;
            
                echo "<div style='width: 100%;'>";
                    // build block for date selection here
                    if ($kpi_routine == "daily") 
                    {
                        echo "<div class='col-md-12'>";
                        echo "<div class='col-md-3'><strong>Date of assesment</strong></div>";
                        echo "<div class='col-md-7'>$ass_date</div>";
                        echo "</div>";
                    } 
                    elseif ($kpi_routine == "weekly") 
                    {
                        echo "<div class='col-md-12'>";
                        echo "<div class='col-md-3'><strong>Week of assesment from 1,2,...,54</strong></div>";
                        echo "<div class='col-md-7'>$ass_date</div>";
                        echo "</div>";
                    } 
                    elseif ($kpi_routine == "monthly") 
                    {
                        echo "<div class='col-md-12'>";
                        echo "<div class='col-md-3'><strong>Month of assesment</strong></div>";
                        echo "<div class='col-md-7'>$ass_date</div>";
                        echo "</div>";
                    } 
                    elseif ($kpi_routine == "quaterly") 
                    {
                        echo "<div class='col-md-12'>";
                        echo "<div class='col-md-3'><strong>Quater of assesment</strong></div>";
                        echo "<div class='col-md-7'>$ass_date</div>";
                        echo "</div>";
                    }

                    // echo "<em>All measurement are done using the current year <b>". date("Y")."</b></em>";
                echo "</div>";

                echo "<div class='col-md-12'>
                        <div class='col-md-3'><strong>".ucfirst(strtolower($param))."</strong></div>
                        <div class='col-md-7'>$std_point <em>Out of</em> $std_value</div>
                </div>";
            }
            $loop_count++;
        }

        echo "<div class='col-md-12'>
                    <div class='col-md-3'><strong>Expected Hours of Work</strong></div>
                    <div class='col-md-7'>$expectedhoursworked</div>
                    <div class='col-md-3'><strong>No. of hours used</strong></div>
                    <div class='col-md-7'>$actualhoursworked</div>
                    <div class='col-md-3'><strong>Expected  No. of Days</strong></div>
                    <div class='col-md-7'>$expecteddays</div>
                    <div class='col-md-3'><strong>No. of days used</strong></div>
                    <div class='col-md-7'>$actualdays</div>
                </div>
";
?>
</div>
</div>