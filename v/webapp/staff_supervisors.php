<div class="box box-success">
	<div class="box-header with-border">
		<h3 class="box-title">Setup Supervisors</h3>
	</div>
	<div class="box-body">
		<?php include('flashMessage.php'); ?>
		<form method="post" action="webapp/staff_perform_supervisor.php?mode=create">
			<label>Select Supervisor</label>
			<select id="supervisor[]" name="supervisor[]" class="form-control select2 input-lg" multiple>
				<option>Select Staff </option>
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
			<br /><br /><br />
			<input type="submit" class="btn btn-success btn-lg" value="Save">
		</form>
	</div>
</div>
							
		
<?php $get_supervisors = GetSupervisors($connection, $_SESSION['id']); ?> 

<div class="box box-success">
	<div class="box-header with-border">
		<h3 class="box-title">List of Supervisors</h3>
	</div>
	<div class="">	
		<table id="" class="table table-responsive">
			<thead>
				<tr>
					<th>S/N</th>
					<th>Supervisor</th>
					<th>No. of Staff</th>
					<th>No. of Units</th>
					<th>No. of Departments</th>
				</tr>
			</thead>
			<tbody>
			<?php
				$sn = 1;
				foreach ($get_supervisors as $row)
				{
					$supervisor_name = GetStaffName($connection,$row['supervisor_staff_id']);
					// get the number of staff, unit and department under this supervisor
					$supervisor_to_staff = CountSupervisorToUnit($connection,'staff',$row['supervisor_staff_id']);
					$supervisor_to_unit = CountSupervisorToUnit($connection,'unit',$row['supervisor_staff_id']);
					$supervisor_to_dept = CountSupervisorToUnit($connection,'department',$row['supervisor_staff_id']);

					echo "<tr>
					        <th scope='row'>$sn</th>
					        <td>$supervisor_name</td>
					        <td>$supervisor_to_staff</td>
					        <td>$supervisor_to_unit</td>
					        <td>$supervisor_to_dept</td>
					     </tr>";
					$sn++;
				}
			?>
			</tbody>
		</table>
	</div>
</div>