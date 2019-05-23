<?php 
// session_name("performance-org");
// session_start();
// include('functions.php');
if ($_GET)  
{
  $unit_id = $_GET['id'];
  // require("unitppt.php");
  $name = $unit_id; // $_GET['name'];
  // $unit_in_dept = GetUnitInDept($connection, $dept_id, $_SESSION['id']);
}

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
                foreach (GetKPIForUnit($connection,$name,$row['astyp_id']) as $kpi_unit)
                {
                    // get the kpi title
                    foreach (GetKPI($connection,$kpi_unit['asp_kpi'], $_SESSION['id']) as $kpi)
                    {
                        $kid = $kpi_unit['asp_kpi']; // kpi id
                        $last_time_link_sent = $kpi['lasttimelinksent'];
                        // $routine = $kpi_staff['asp_kpi_routine'];
                        // link
                        $randvalue = md5(rand(0000,9999)).md5(rand(0000,9999)).md5($kid);
                        // table to list all KPIs of staff
                        echo "<tr>
                                <td>".$kpi_count."</td>
                                <td><strong><a href='staffprofile?tag=$randvalue&kid=$kid&id=$name&measure=historylist&type=unit'>".ucwords(strtolower($kpi['kpi_name']))."</a></strong></td>
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

<!-- <div class='box box-success'>
    <div class='box-header with-border'>
        <h3 class='box-title'>Staff in Unit</h3>
    </div>
    <div class="">
        <?php // include("staff_list_home.php"); ?>
    </div>
</div> -->