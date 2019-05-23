<?php 
// session_name("performance-org");
// session_start();
// include('functions.php');
if ($_GET) {
	$id = $_GET['id'];
	require("stafppt.php");
	$name = $id; // $_GET['name'];
}

if ($_GET['measure'] == "kpilist") 
{
    echo "<div class='box box-success'>
            <div class='box-header with-border'>
                <h3 class='box-title'>Staff List</h3>
            </div>
            <table class='table table-responsive'>
                <thead>
                    <th>S/N</th>
                    <th>Staff</th>
                    <th>Staff Email</th>
                    <th>Department</th>
                    <th>State</th>
                </thead>
                <tbody>";
                $randvalue = md5(rand(0000,9999)).md5(rand(0000,9999));
                // echo "<h4>".$row['astyp_name'] ."</h4>";
                // fetch all kpis for this staff on each assesment type
                // kpi count
                $kpi_count = 1;
                // get kpi of attached to staff alone
                foreach (GetStaffInKPI($connection,$name) as $kpi)
                {
                    $staff_id = $kpi['asp_staff'];
                    $staff_name = GetStaffName($connection,$staff_id);
                    $staff_email = GetStaffEmail($connection,$staff_id);
                    $staff_dept = ucwords(GetDeptName($connection,GetStaffDept($connection, $staff_id)));
                    $staff_state = GetStaffState($connection,$staff_id);
                    $ass_key_id = rand(0000,9999);

                    echo "<tr>
                        <td>".$kpi_count."</td>
                        <td>
                            <strong>
                                <a href='staffprofile?tag=$randvalue&kid=$name&id=$staff_id&measure=historylist&akid=$ass_key_id&type=staff'>".$staff_name."</a>
                            </strong>
                        </td>
                        <td><i>$staff_email</i></td>
                        <td><i>$staff_dept</i></td>
                    </tr>";
                    $kpi_count++;
                }
            echo "</tbody>
        </table>
    </div>";
} 

elseif ($_GET['measure'] == "summarylist") 
{
    echo "<div class='box box-success'>
                <div class='box-header with-border'>
                    <h3 class='box-title'>Staff List</h3>
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
        foreach (GetKPIForStaff($connection,$name,$row['astyp_id']) as $kpi_staff)
        {
            // get the kpi title
            foreach (GetKPI($connection,$kpi_staff['asp_kpi'], $_SESSION['id']) as $kpi)
            {
                $kid = $kpi_staff['asp_kpi']; // kpi id
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
                        <td><strong><a href='staffprofile?tag=$randvalue&kid=$kid&id=$name&measure=historylist&type=staff'>".ucwords(strtolower($kpi['kpi_name']))."</a></strong></td>
                        <td>".ucfirst($kpi['kpi_routine'])."</td>
                        <td><strong>".date("F d, Y", strtotime($last_time_link_sent))."</strong></td>
                    </tr>";
                // increase count
                $kpi_count++;
            }
        }
    }

    echo "</tbody>
        </table>
    </div>";
} 

elseif($_GET['measure'] == "historylist")
{
    $kid = $_GET['kid'];
    // $ass_key_id = $_GET['akid'];
    $ass_type = $_GET['type'];
    
    // list assessment types
    // foreach (GetAssType($connection, $_SESSION['id']) as $row)
    // {
    //     // echo "<h4>".$row['astyp_name'] ."</h4>";
    //     // echo $name;
    //     // fetch all kpis for this staff on each assesment type

    //     // kpi count
    //     $kpi_count = 1;
    //     // get kpi of attached to staff alone
    //     foreach (GetKPIForStaff($connection,$name,$row['astyp_id']) as $kpi_staff)
    //     {
            // get the kpi title
            foreach (GetKPI($connection,$kid, $_SESSION['id']) as $kpi)
            {
                // $kid = $kpi_staff['asp_kpi'];
                // link
                // $randvalue = md5(rand(0000,9999)).md5(rand(0000,9999)).md5($kid);
                $g_effectiveness = GetStaffKPIHistoryGraphValues($connection,$id,$kid,'historylist','',$ass_type, 'effectiveness');
                $g_efficiency = GetStaffKPIHistoryGraphValues($connection,$id,$kid,'historylist','',$ass_type, 'efficiency');
                $g_productivityindex = GetStaffKPIHistoryGraphValues($connection,$id,$kid,'historylist','',$ass_type, 'productivityindex');
                $g_dateofmeasure = GetStaffKPIHistoryGraphValues($connection,$id,$kid,'historylist','',$ass_type, 'dateofmeasure');

                // echo $g_effectiveness;

                echo "<div>
                        <div class ='box box-success'>
                            <div class='box-header with-border'>
                                <h3 class='box-title'>KPI: <a href='#'>".$kpi['kpi_name']."</a></h3>
                            </div>
                        <div>";
                        echo "<table class='table table-condensed' id=''>
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Date Measured</th>
                                        <th>Effectiveness</th>
                                        <th>Efficiency</th>
                                        <th>Productivity Index</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>";
                                    // get cummulative value of KPI for current year
                                    GetStaffKPIHistory($connection,$id,$kid,'historylist','',$ass_type);
                                    // echo "<tr><td colspan='2'> <a href='staffprofile?tag=$randvalue&id=1&measure=summarylist'>See History</a> </td></tr>";
                                echo "</tbody>
                        </table>
                </div>";
                // increase count
                // $kpi_count++;
            }
    //     }
    // }

?>

</div>

<div class="row">
            <div class="col-md-12">
              <!-- AREA CHART -->
              <div class="box box-primary" style="display: none;">
                <div class="box-header with-border">
                  <h3 class="box-title">Area Chart</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div>
                <div class="box-body">
                  <div class="chart">
                    <canvas id="areaChart" style="height:250px"></canvas>
                  </div>
                </div><!-- /.box-body -->
              </div><!-- /.box -->

              <!-- DONUT CHART -->
              <div class="box box-danger" style="display: none;">
                <div class="box-header with-border">
                  <h3 class="box-title">Donut Chart</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div>
                <div class="box-body">
                    <canvas id="pieChart" style="height:250px"></canvas>
                </div><!-- /.box-body -->
              </div><!-- /.box -->

            </div><!-- /.col (LEFT) -->
            <div class="col-md-12">
              <!-- LINE CHART -->
              <div class="box box-info" style="display: none;">
                <div class="box-header with-border">
                  <h3 class="box-title">Line Chart</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div>
                <div class="box-body">
                  <div class="chart">
                    <canvas id="lineChart" style="height:250px"></canvas>
                  </div>
                </div><!-- /.box-body -->
              </div><!-- /.box -->

              <!-- BAR CHART -->
              <div class="box box-success">
                <div class="box-header with-border">
                  <h3 class="box-title">Bar Chart</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div>
                <div class="box-body">
                  <div class="chart">
                    <canvas id="barChart" style="height:450px"></canvas>
                  </div>
                </div><!-- /.box-body -->
              </div><!-- /.box -->

            </div><!-- /.col (RIGHT) -->
          </div><!-- /.row -->

<?php } ?>