<?php
// include header
include("header.php");
// include the navigation here
include("nav.php");
?>

<?php 
    if($_SESSION['role'] == "app_admin") { 
        // get organisation category
        $get_categories = GetOrganizationCatList($connection);
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Dashboard
            <small>Control panel</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">

        <?php
            // loop
            foreach($get_categories as $row)
            {
                $org_category = GetOrganizationCatNumber($connection, $row['cat_id']);

                $color_code = rand(1,4);
                if ($color_code == 1) $bg_color = "bg-aqua";
                elseif ($color_code == 2) $bg_color = "bg-green";
                elseif ($color_code == 3) $bg_color = "bg-yellow";
                elseif ($color_code == 4) $bg_color = "bg-red";
                else $bg_color = "bg-aqua";
        ?>
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box <?php echo $bg_color ?>">
                        <div class="inner">
                            <h3><?php echo $org_category ?></h3>
                            <p><?php echo $row['cat_name']; ?></p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="organization?mode=list&caID=<?php echo $row['cat_id'] ?>&place=list" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div><!-- ./col -->
        <?php } ?>

        </div><!-- /.row -->

        <!-- Main row -->
        <div class="row">
            <!-- home page graph goes here-->
            <!-- List of staff -->
            <section class="col-lg-12 connectedSortable">
                <?php $mode = "list"; include("webapp/organization.php"); ?>
            </section><!-- /.Left col -->
        </div><!-- /.row (main row) -->

    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<?php } else { ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Dashboard
            <small>Control panel</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><?php echo recordCount($connection, "kpi", $_SESSION['id']) ?></h3>
                        <p>KPIs</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="kpi" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3><?php echo recordCount($connection, "staff", $_SESSION['id']) ?></h3>
                        <p>Staff</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="staff?mode=listonly" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3><?php echo recordCount($connection, "unit", $_SESSION['id'] ) ?></h3>
                        <p>Units</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="unit" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3><?php echo recordCount($connection, "department", $_SESSION['id']) ?></h3>
                        <p>Departments</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="department" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div><!-- ./col -->
        </div><!-- /.row -->

        <!-- Main row -->
        <div class="row">
            <!-- List of kpis -->
            <section class="col-lg-12 connectedSortable">
                <!-- TO DO List -->
                <?php include("webapp/kpi.php"); ?>
            </section><!-- /.Left col -->

            <!-- home page graph goes here-->
            <!-- List of staff -->
            <section class="col-lg-12 connectedSortable">
                <!-- TO DO List -->
                <div class="box box-primary">
                    <div class="box-header" style="border-bottom:1px solid #e8e8e8;">
                        <!-- <i class="ion ion-clipboard"></i> -->
                        <h3 class="box-title">Registered Staff</h3>
                    </div><!-- /.box-header -->
                    <div class="">
                        <?php $dept_id = ''; $unit_id = ''; include("webapp/staff_list_home.php"); ?>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </section><!-- /.Left col -->

        </div><!-- /.row (main row) -->

    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<?php } ?>

<?php
// include footer
include("footer.php");
?>