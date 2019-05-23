<?php

// get $unit value
isset($_GET['unit']) ? $unit = $_GET['unit'] : $unit=2;
$title = '';
$WHERE = null;
// swicth options
switch ($unit) {
    case 3:
        # code...
        $WHERE = "WHERE org_grp = 'staff-ass'";
        // find orgln value
        $orglv = "staff-ass";
        $astyp_org_level = "staff-ass";
        $title = "Staff";
        // break
        break;

    case 1:
        # code
        $WHERE = "WHERE org_grp = 'punit'";
        // find orglv value
        $orglv = "punit";
        $astyp_org_level = "punit";
        $title = "Staff In Unit";
        // break
        break;

    case 4:
        # code
        $WHERE = "WHERE org_grp = 'unit'";
        // find orglv value
        $orglv = "unit";
        $astyp_org_level = "unit";
        $title = "Unit";
        // break
        break;

    case 5:
        # code
        $WHERE = "WHERE org_grp = 'project'";
        // find orglv value
        $orglv = "project";
        $astyp_org_level = "project";
        $title = "Project";
        // break
        break;

    case 6:
    $astyp_org_level = "department";
    $orglv = "department";
    $title = "Department";
    break;

    default:
        # code...
        $WHERE = "";
        // find orglv value
        $orglv = "";
        // break
        break;
}

// get organisation category
$get_values = GetAssesmentParameters($connection, $unit, $_SESSION['id']);
?>

<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title">New Assessment Parameter Category</h3>
    </div>
    <div class="box-body">
        <?php include('flashMessage.php'); ?>
        <form id="defaultForm" method="post" class="form-horizontal" action="webapp/assesment_params.php?action=params">
            <select class="form-control input-lg" required onchange="fetchAssParam()" name="cattype" id="cattype">
                <option selected="selected" value="">Choose <?php echo $title; ?> Assesment Type</option>
                <!-- <option value="200">All Category</option> -->
                <?php
                    $sql = "SELECT * FROM assessment_type WHERE org_id = '$_SESSION[id]' AND astyp_org_level  = '$astyp_org_level' ORDER BY astyp_name" ;
                    $sql = $connection->query($sql) or die("Unsuccessful") ;
                    $sql ->setFetchMode(PDO::FETCH_ASSOC);
                    while($row = $sql->fetch())
                    {
                        echo "<option value='".$row['astyp_id']."''>".$row['astyp_name']."</option>";
                    }
                ?>
            </select>
            <br> 

            <select class="form-control input-lg" required onchange="fetchAssParam()" name="kpi" id="kpii">
                <option value="">Choose KPI</option>
                <!-- <option value="200">All Category</option> -->
                <?php
                $sql = "SELECT * FROM kpi WHERE org_id='$_SESSION[id]' ORDER BY kpi_name" ;
                $sql = $connection->query($sql) or die("Unsuccessful") ;
                $sql->setFetchMode(PDO::FETCH_ASSOC);
                while($row = $sql->fetch())
                {
                    echo "<option value='".$row['kpi_id']."''>".$row['kpi_name']."</option>";
                }
                ?>
            </select>
            <br> 

            <input type="text" class="form-control input-lg" required name="name" autocomplete="off" placeholder="What do you want to measure?" />
            <br>

            <?php
                // swicth options
                if ($unit == 3)
                {
                    echo "<select class='form-control input-lg' name='staff'>
                            <option>Select Staff</option>";
                            $sql = "SELECT * FROM staff WHERE sta_org = '$_SESSION[id]' ORDER BY sta_fname" ;
                            $sql = $connection->query($sql) or die("Unsuccessful") ;
                            $sql->setFetchMode(PDO::FETCH_ASSOC);
                            while($row = $sql->fetch())
                            {
                                echo "<option value='".$row['sta_id']."'>".$row['sta_fname']." ".$row['sta_lname']."</option>";
                            }
                    echo "</select>";
                }
                elseif ($unit == 1)
                {
                    echo "<select class='form-control input-lg' name='unit'>
                            <option>Select Unit </option>";
                            $sql = "SELECT * FROM unit WHERE uni_org = '$_SESSION[id]' ORDER BY uni_name" ;
                            $sql = $connection->query($sql) or die("Unsuccessful") ;
                            $sql ->setFetchMode(PDO::FETCH_ASSOC);
                            while($row = $sql->fetch())
                            {
                                echo "<option value='".$row['uni_id']."'>".$row['uni_name']."</option>";
                            }
                    echo "</select>";
                }
                elseif ($unit == 4)
                {
                    echo "<select class='form-control input-lg' name='unit'>
                            <option>Select Unit </option>";
                            $sql = "SELECT * FROM unit WHERE uni_org = '$_SESSION[id]' ORDER BY uni_name" ;
                            $sql = $connection->query($sql) or die("Unsuccessful") ;
                            $sql ->setFetchMode(PDO::FETCH_ASSOC);
                            while($row = $sql->fetch())
                            {
                                $uni_dept_name = GetDeptName($connection, $row['uni_dept']);
                                echo "<option value='".$row['uni_id']."'>".$uni_dept_name . " / " . $row['uni_name']."</option>";  
                            }
                    echo "</select>";
                }
                else
                {
                    echo "<select class='form-control input-lg' name='dept'>
                            <option>Choose Department</option>";
                            $sql = "SELECT * FROM department WHERE dept_org = '$_SESSION[id]' ORDER BY dept_name" ;
                            $sql = $connection->query($sql) or die("Unsuccessful") ;
                            $sql ->setFetchMode(PDO::FETCH_ASSOC);
                            while($row = $sql->fetch())
                            {
                                echo "<option value='".$row['dept_id']."''>".$row['dept_name']."</option>";
                            }
                    echo "</select>";
                }
            ?>
            <br>
            
            <select class="form-control input-lg" name="type" id="type" required></select>

            <input type="hidden" name="typ" id="typ"> 
            <input type="hidden" name="orglv" id="orglv" value="<?php echo $orglv ?>"> <br>
            <select class="form-control input-lg" name="formtyp">
                <option value="input">Text/Numeric</option>
                <option value="radio-button">Radio-button</option>
            </select>
            <br>
            <input type="number" name="value" placeholder='Leave empty if radio button' class="form-control input-lg"><br>
            <textarea class="form-control input-lg" name="discrip" placeholder="What is this about?" ></textarea><br>
            <input type="submit" value="Add" class="btn btn-lg btn-primary">
        </form>
    </div>
</div>


<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title">Assessment Parameter Category List</h3>
    </div>
    <div class="box-body">
        <div>
            <table class="table table-bordered bootstrap-datatable datatable col-md-10" >
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Type Cat.</th>
                        <th>KPI</th>
                        <th>Form type </th>
                        <th>Expected Target(Value)</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($get_values as $row)
                        {
                            $name = $row['asp_name'] ;
                            $discrip = $row['asp_discrip'] ;
                            $id = $row['asp_id'];
                            $cat = $row['asp_cat'];
                            $kpi = $row['asp_kpi'];
                            $typ = $row['asp_typ'];
                            $value = $row['asp_value'];
                            $reg_at = $row['registered_at'];


                            echo "<tr>
                                    <td class='description'><a href='#' class='name'>$name </a></td>
                                    <td>".GetCatNameId($connection,$cat)."</td>
                                    <td>".GetKPIId($connection,$kpi)."</td>
                                    <td>$typ</td>
                                    <td>".CheckInput($connection,$id)."</td>
                                    <td>
                                        <a class='btn btn-sm btn-default' href='#delete' data-toggle='modal' data-target='#av$id'>Delete</a>
                                    </td>
                            </tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <?php require("delete-assessment-value-modal.php") ; ?>