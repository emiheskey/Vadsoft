<?php $get_units = GetOrganizationDeptUnitList($connection,$_SESSION['id']) ?>

<?php if (isset($_GET['mode']) != "listonly"){ ?>
	<div class="box box-success">
		<div class="box-header with-border">
			<h3 class="box-title">Add Unit</h3>
		</div>
		<div class="box-body">
			<?php include('flashMessage.php'); ?>
			<form id="defaultForm" method="post" class="form-horizontal" action="webapp/unit_new.php">
			    <input type="text" class="form-control input-lg" name="name" autocomplete="off" placeholder="Unit name" /><br>
			    <select class="form-control input-lg" name="dept">
			    	<option>Choose Department </option>
			    	<?php
				        $sql = "SELECT * FROM department WHERE dept_org = '$_SESSION[id]' ORDER BY dept_name" ;
				        $sql = $connection->query($sql) or die("Unsuccessful") ;
				        $sql ->setFetchMode(PDO::FETCH_ASSOC);
				        while($row = $sql->fetch())
				        {
				            ?>
				                 <option value="<?php echo $row['dept_id']; ?>"><?php 
				                  echo $row['dept_name'];  ?></option>  
				            <?php
				        }
				    ?>
			    </select><br>
			    <textarea class="form-control input-lg" name="discrip" id="discrip" placeholder="Unit description" ></textarea><br>
			    <input type="submit" value="Add Unit" class="btn btn-primary btn-lg">
			</form>
		</div>
	</div>
<?php } ?>
			

<div class="box box-success">
	<div class="box-header with-border">
		<h3 class="box-title">List of Units</h3>
	</div>
	<div class="box-body" style="padding: 0px;">		
		<table id="dept" class="table">
			<thead >
				<tr align="left">
					<th>Name of Unit </th>
					<th>Department </th>
					<th>No. of staff </th>
					<th>Date of Registration</th>
					<th> </th>
				</tr>
			</thead>
			<tbody>
				<?php
					foreach($get_units as $row)
					{
						$name = $row['uni_name'] ;
			            $discrip = $row['uni_discrip'] ;
			            $dept = $row['uni_dept'] ;
			            $id = $row['uni_id'];
			            $reg_at = $row['registered_at'];

			            // get rand value
			            $rand = md5(rand(2333,9899)).md5($name).md5(rand(0000,9999));

			            echo "<tr>
			                    <td>
			                    	<a href='unitprofile?tag=$rand&id=$id&mode=summarylist' class='name'>".
			                    		ucwords(strtolower($name))
			                    	."</a></td>
			                    <td>".ucwords(strtolower(GetDeptId($connection,$dept)))."</td>
			                    <td>".GetNumStaffUnit($connection,$id)."</td>
			                    <td>".date("M j, Y", strtotime($reg_at))."</td>
			                    <td>
			                        <a href='#'>Edit</a> | 
			                        <a href='#delete' data-toggle='modal' data-target='#oc$id'>Delete</a>
			                    </td>
			                    <!--<td><a href='unittest?tag=$rand&name=$id' class='btn btn-success'>Measure Unit </a></td>-->
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
<?php require("delete-unit-modal.php") ; ?>