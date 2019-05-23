
<?php
// get organisation category
$get_measure = GetAssesmentParameterCategory($connection, $_SESSION['id']);
?>

<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title">New Assessment Parameter Category</h3>
    </div>
    <div class="box-body">
        <?php include('flashMessage.php'); ?>
        <form method="post" class="form-horizontal" action="webapp/assesment_params.php?action=paramcat">
            
            <input type="text" class="form-control input-lg" name="name" autocomplete="off" placeholder="What do want to measure? e.g Efficency" />
            
            <br>
        
            <select class="form-control input-lg" name="type" id="type">
                <option>Select Assesment Type</option>
                <?php
                    $sql = "SELECT * FROM assessment_type WHERE org_id='$_SESSION[id]' ORDER BY astyp_name" ;
                    $sql = $connection->query($sql) or die("Unsuccessful") ;
                    $sql ->setFetchMode(PDO::FETCH_ASSOC);
                    while($row = $sql->fetch())
                    {
                        ?>
                             <option value="<?php echo $row['astyp_id']; ?>"><?php echo $row['astyp_name'];  ?></option>  
                        <?php
                    }
                ?>
            </select>
            <input type="hidden" name="typ" id="typ">
            <input type="hidden" name="typss" value="">
            
            <br>
             <label><em>Select the KPI you are measuring</em></label>
            <select class="form-control select2 input-lg" name="kpi[]" id="kpi[]" multiple>
                <option>Select Assesment KPI</option>
                <?php
                    $sql = "SELECT * FROM kpi WHERE org_id = '$_SESSION[id]' ORDER BY kpi_name" ;
                    $sql = $connection->query($sql) or die("Unsuccessful") ;
                    $sql ->setFetchMode(PDO::FETCH_ASSOC);
                    while($row = $sql->fetch())
                    {
                        ?>
                             <option value="<?php echo $row['kpi_id']; ?>"><?php echo $row['kpi_name'];  ?></option>  
                        <?php
                    }
                ?>
            </select>
            

            <br><br>
            <textarea class="form-control input-lg" name="discrip" placeholder="What is this all about?" ></textarea>
            
            <br>
            
            <input type="submit" value="Add Assesment Category" class="btn btn-primary btn-lg">
            

        </form>
    </div>
</div>


<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title">Assessment Parameter Category List</h3>
    </div>
    <div class="">
        <table class="table table-condensed table-responsive" >
        	<thead>
        		<tr>
        			<th style='padding-left:10px;'>Title</th>
        			<th>Type</th>
                    <th>Date Registered</th>
        			<th></th>
        		</tr>
        	</thead>
        	<tbody>
        	<?php  
                foreach ($get_measure as $row)
                {
                    $name = $row['aspcat_name'] ;
                    $discrip = $row['aspcat_discrip'] ;
                    $id = $row['aspcat_id'];
                    $cat = $row['ass_typ'];
                    $reg_at = date("l jS F, Y", strtotime($row['registered_at']));
                    $kid = $row['aspcat_kpi'];


                    echo "<tr>
                            <td style='padding-left:10px;'><a href='#' class='name'><b>$name</b></a> / <br>".GetKPIId($connection,$kid)."</td>
                            <td>".GetAssParamTypId($connection,$cat)."</td>
                            <td>$reg_at</td>
                            <td>
                                <!-- <a href='#'>Edit</a> -->
                                <a href='#' data-toggle='modal' data-target='#oc$id'>Delete</a>
                            </td>
                    </tr>";
                }

            ?>
        	</tbody>
        </table>
    </div>
</div>

<div class="modal hide fade" id="myModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
	</div>
</div>
<div class="clearfix"></div>
<?php require("delete-assesment-category.php") ; ?>
