
<div class="box box-success">
	<div class="box-header with-border">
		<h3 class="box-title">Assign Staff to Unit/Task</h3>
	</div>
	<div class="box-body">
		<?php include('flashMessage.php'); ?>
		<form method="post" action="webapp/unit_assign_add.php">
			<label>Select Staff </label>
			<select id="assign_unit_staff[]" name="assign_unit_staff[]" required class="form-control select2 input-lg" multiple required="required">
				<!-- <option value="200">All Category</option> -->
				<?php
				    $sql = "SELECT * FROM staff WHERE sta_org = '$_SESSION[id]' ORDER BY sta_fname" ;
				    $sql = $connection->query($sql) or die("Unsuccessful") ;
				    $sql ->setFetchMode(PDO::FETCH_ASSOC);
				    while($row = $sql->fetch())
				    {
				        ?>
				        <option value="<?php echo $row['sta_id']; ?>"><?php echo $row['sta_fname'] . " " . $row['sta_lname'];  ?></option>  
				        <?php
				    }
			  	?>
			</select>
			<br /><br />
			<label>Select Unit</label>
			<select name="unit" id="unit" required class="form-control input-lg">
				<option>Select Unit </option>
				<!-- <option value="200">All Category</option> -->
				<?php
				    $sql = "SELECT * FROM unit WHERE uni_org = '$_SESSION[id]' ORDER BY uni_name" ;
				    $sql = $connection->query($sql) or die("Unsuccessful") ;
				    $sql ->setFetchMode(PDO::FETCH_ASSOC);
				    while($row = $sql->fetch())
				    {
				    	// get the department name
					    $uni_dept_name = GetDeptName($connection, $row['uni_dept']);
				?>
				             <option value="<?php echo $row['uni_id']; ?>"><?php echo $uni_dept_name . " / " . $row['uni_name'];  ?></option>  
				        <?php
				    }
				?>
			</select>
				<input value="<?php echo $_SESSION['id']; ?>" type="hidden" name="unit_org">
			<br />
			<label>Will this staff receive the assesment link for this unit?</label>
			<select name="linkperson" required id="linkperson" class="form-control input-lg">
				<option value="yes">Yes</option>
				<option value="no" selected="selected">No</option>
			</select>

			<br>
			<input type="submit" class="btn btn-success btn-lg" value="Assign">
		</form>
	</div>
</div>
							


<div class="box box-success">
	<div class="box-header with-border">
		<h3 class="box-title">Unit List</h3>
	</div>
	<div class="">

		<table class="table table-responsive table-condensed">
			<thead>
				<tr>
					<th>Unit </th>
					<th>Number of Staff</th>
					<th>Department</th>
					<th>Assesment Staff</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			<?php GetStaffUnit($connection, $_SESSION['id']) ;?> 
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
	
	