<?php 
// session_name("performance-org");
// session_start();
// include('functions.php');
if ($_GET) 
{
	$dept_id = $_GET['id'];
	// require("unitppt.php");
	// $name = $id; // $_GET['name'];

    $unit_in_dept = GetUnitInDept($connection, $dept_id, $_SESSION['id']);
}
?>

<!-- <div class="col-md-4">
    <div class='box box-success'>
        <div class='box-header with-border'>
            <h3 class='box-title'>Units</h3>
        </div>

        <table class='table table-responsive'>
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>Unit</th>
                </tr>
            </thead>
            <tbody>
                //  $sn = 1; foreach($unit_in_dept as $row) { ?> 
                //         <tr>
                //             <td><?php // echo $sn ?></td>
                //             <td><?php // echo ucwords($row[1]); ?></td>
                //         </tr>
                //  $sn++; } ?>
            </tbody>
        </table>
    </div>
</div> -->


<?php
    $name = $dept_id;
    echo "<div class='box box-success'>
            <div class='box-header with-border'>
                <h3 class='box-title'>KPIs</h3>
            </div>
            <table class='table table-responsive'>
                <thead>
                    <th>S/N</th>
                    <th>KPI</th>
                    <th>Routine</th>
                    <th>Date</th>
                </thead>
                <tbody>";

                // list assesmen types
                foreach (GetAssType($connection, $_SESSION['id']) as $row)
                {
                    // echo "<h4>".$row['astyp_name'] ."</h4>";
                    // fetch all kpis for this staff on each assesment type

                    // kpi count
                    $kpi_count = 1;
                    // get kpi of attached to staff alone
                    foreach (GetKPIForDept($connection,$name,$row['astyp_id']) as $kpi_dept)
                    {
                        // get the kpi title
                        foreach (GetKPI($connection,$kpi_dept['asp_kpi'], $_SESSION['id']) as $kpi)
                        {
                            $kid = $kpi_dept['asp_kpi']; // kpi id
                            $last_time_link_sent = $kpi['lasttimelinksent'];
                            // $routine = $kpi_staff['asp_kpi_routine'];
                            // link
                            $randvalue = md5(rand(0000,9999)).md5(rand(0000,9999)).md5($kid);
                            // echo "<div class='col-md-6' style='padding:2px;'>
                            //         <div class='box box-success'>
                            //             <div class='box-header with-border'>
                            //                 <h3 class='box-title'>KPI: <a href='#'>".$kpi['kpi_name']."</a></h3>
                            //             </div>
                            //         <div>
                            // </div>";
                            // table to list all KPIs of staff
                            echo "<tr>
                                    <td>".$kpi_count."</td>
                                    <td><strong><a href='staffprofile?tag=$randvalue&kid=$kid&id=$name&measure=historylist&type=department'>".ucwords(strtolower($kpi['kpi_name']))."</a></strong></td>
                                    <td>".ucfirst($kpi['kpi_routine'])."</td>
                                    <td><strong>".date("M j, Y", strtotime($last_time_link_sent))."</strong></td>
                                </tr>";
                            // increase count
                            $kpi_count++;
                        }
                    }
                }

            echo "</tbody>
        </table>
    </div>";
?>


<div class='box box-success'>
    <div class='box-header with-border'>
        <h3 class='box-title'>Staff in Department</h3>
    </div>
    <div class="">
        <?php include("staff_list_home.php"); ?>
    </div>
</div>