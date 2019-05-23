<?php 
$_session['id'] = rand() ;
// echo $_session['id'];
//include('functions.php');
if ($_GET) {
    $name = $_GET['name'] ;
    $_session['staff_id'] = $name ;
    $_session['staff_dept'] = GetStaffDept($connection,$name);
}


if (!isset($_GET['m_staff']))
{
    $m_staff="";
}
else
{
    $m_staff=$_GET['m_staff']; 
}
?>


<?php if ($m_staff == "listkpi" or $m_staff == "") { ?>
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Measure <?php // echo $staff_name ?></h3>
        </div>
        <div class="box-body">
            <?php
                // list assesmen types
                foreach (GetAssType($connection, $_SESSION['id']) as $row)
                {
                    echo "<h4>".$row['astyp_name'] ."</h4>";
                    // fetch all kpis for this staff on each assesment type
                    // kpi count
                    $kpi_count = 0;
                    // get kpi of attached to staff alone
                    foreach (GetKPIForStaff($connection,$name,$row['astyp_id']) as $kpi_staff)
                    {
                        // get the kpi title
                        foreach (GetKPI($connection,$kpi_staff['asp_kpi'],$_SESSION['id']) as $kpi)
                        {
                            $kid = $kpi_staff['asp_kpi'];
                            // rand value
                            $randvalue = md5(rand(0000,9999).$kid.$name) . md5(rand(2222,8889).$kid.$name) . md5(rand(0000,9999).$kid.$name);
                            // link
                            echo "<a href='staffmeasurement?name=$name&kid=$kid&tag=$randvalue&m_staff=measurestaffkpi'>".$kpi['kpi_name']."</a><br>";
                            // increase count
                            $kpi_count++;
                        }
                    }

                    if ($kpi_count == 0) {
                        echo "<em>There is no Key Performance Index to measure</em>";
                    }
                }
            ?>
        </div>
    </div>
<?php } ?>


<?php
if ($m_staff == "measurestaffkpi")
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
    // get the kpi to measure
    $get_measure_kpi = GetGroupAssParamsStaff($connection,$name,'personal',$kid);
    // get kpi routime measurement
    $kpi_routine = GetKPIRoutine($connection,$kid);
    // check assesment value
    $is_assesment = '';
    // loop through to build form values
    foreach ($get_measure_kpi as $crow)
    {
        // get value of assesmen category id
        $k = $crow['aspcat_id'];

        echo "<div class='col-md-6' style='padding:0px;'>
                <div class='box box-success'>
                    <div class='box-header with-border'>
                        <h3 class='box-title'>".GetCatNameId($connection,$k)."</h3>
                    </div>
                    <div class='box-body'>
                        <form method='post' name='measure' id='measure'  action='webapp/measure-staff.php?name=$name&dept=".GetStaffDept($connection,$name)."'>";
                            // get date value
                            foreach(GetAallParaStfPersonal($connection,$k,$name,$kid) as $row)
                            {
                                $param = $row['asp_name'];      // assement name
                                $slug = $row['asp_slug'] ;      // the slug or call it url string
                                $typ = $row['asp_typ_level'] ;  // assesment level
                                $cat = $row['asp_cat'] ;        // assesment category
                                $field = $row['asp_typ'] ;      // assesment type
                                $value = $row['asp_value'] ;    // assesment value
                                $id = $row['asp_id'] ;          // assesment id

                                if ($is_assesment == '') {
                                    $is_assesment = $param;       // assign paramter to is_assesment
                                } else {
                                    $is_assesment = $is_assesment .= $param ;
                                }

                                echo "<div style=''>";
                                // build block for date selection here
                                if ($kpi_routine == "daily") {
                                    echo "<label>Enter date of assesment</label>";
                                    echo "<input type='date' class='form-control' name='kpi_routine'>";
                                } elseif ($kpi_routine == "weekly") {
                                    echo "<label>Enter week of assesment from 1,2,...,54</label>";
                                    echo "<input type='number' class='form-control' name='kpi_routine'>";
                                } elseif ($kpi_routine == "monthly") {
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
                                } elseif ($kpi_routine == "quaterly") {
                                    echo "<label>Enter quater of assesment</label>";
                                    echo "<input type='number' class='form-control' name='kpi_routine' max='1'>";
                                }

                                echo "<em>All measurement are done using the current year <b>". date("Y")."</b></em>";
                                echo "</div><hr>";


                                if ($field != "input") 
                                {

                                    echo "<div style='line-height:30px;'>
                                            $param

                                            <select name='score[]' class='form-control'> 
                                                <option value='1'> Very Low </option>
                                                <option value='2'> Low </option>
                                                <option value='3'> Average </option>
                                                <option value='4'> Good  </option>
                                                <option value='5'> Very Good </option>
                                                <option value='6'> Excellent </option>
                                            </select>

                                            <input type='hidden' value='$id' name='param[]'>
                                            <input type='hidden' value='$cat' name='cat[]'>
                                            <input type='hidden' value='$typ' name='typ[]'>
                                            <input type='hidden' value='radio' name='type[]'>
                                            <input type='hidden' value='6' name='standard[]'>
                                    </div>";
                                } 
                                else 
                                {
                                    echo "<div style='line-height:30px;'>
                                            $param
                                            <input type='text' class='form-control' name=score[] required>
                                            <em>Out of</em> 
                                            <input type='text' class='form-control' value='$value' name='standard[]' required>
                                            <input type='hidden' value='$id' name='param[]'>
                                            <input type='hidden' value='$cat' name='cat[]'>
                                            <input type='hidden' value='$typ' name='typ[]'>  
                                            <input type='hidden' value='input' name='type[]'>
                                        </div>";
                                }
                            }

                            echo "<hr>
                                    <div style='line-height:30px;'>
                                        <input type='text' value='$kid' name='kpi'>

                                        Expected Hours of Work
                                        <input type='number' name='exp_hours' placeholder='Enter expected No of hours' class='form-control'>

                                        No. of hours used
                                        <input type='number' name='hours' placeholder='Enter No. of hours Staff used' class='form-control'>

                                        Expected  No. of Days
                                        <input type='number' name='exp_days' placeholder='Enter number of days required' class='form-control'>

                                        No. of days used  </label>
                                        <input type='number' name='days' placeholder='Enter No. of days used' class='form-control'> 

                                        <br>
                                        <button type='submit' class='btn btn-primary'> <b>Measure ".GetCatNameId($connection,$k)." </b></button>
                                    </div>
                        </form>
                    </div>
                </div>
            </div>";
        }
    }
?>