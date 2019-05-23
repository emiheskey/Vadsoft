<div class="box box-success">
	<div class="box-header with-border">
		<h3 class="box-title">Assign Supervisors to <?php echo ucfirst($_GET['mode']); ?></h3>
	</div>
	<div class="box-body">
		<?php include('flashMessage.php'); ?>
		<form method="post" action="webapp/staff_perform_supervisor.php?mode=assign">
			<?php
				if ($_GET['mode'] == "staff")
				{
			?>
					<label>Select Staff </label>
					<select id="type_value[]" name="type_value[]" required class="form-control select2 input-lg" multiple>
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
			<?php
				}
				elseif ($_GET['mode'] == "department")
				{
			?>
					<label>Select Department </label>
					<select id="type_value[]" name="type_value[]" class="form-control select2 input-lg" multiple>
						<?php
						    $sql = "SELECT * FROM department WHERE dept_org = '$_SESSION[id]'" ;
						    $sql = $connection->query($sql) or die("Unsuccessful") ;
						    $sql ->setFetchMode(PDO::FETCH_ASSOC);
						    while($row = $sql->fetch())
						    {
						        ?>
						        <option value="<?php echo $row['dept_id']; ?>"><?php echo $row['dept_name'];  ?></option>  
						        <?php
						    }
					  	?>
					</select>
			<?php
				}
				elseif ($_GET['mode'] == "unit")
				{
			?>
				<label>Select Unit </label>
				<select id="type_value[]" name="type_value[]" required class="form-control select2 input-lg" multiple>
					<?php
					    $sql = "SELECT * FROM unit WHERE uni_org = '$_SESSION[id]' ORDER BY uni_dept" ;
					    $sql = $connection->query($sql) or die("Unsuccessful") ;
					    $sql ->setFetchMode(PDO::FETCH_ASSOC);
					    while($row = $sql->fetch())
					    {
					    	// get the department name
					    	$uni_dept_name = GetDeptName($connection, $row['uni_dept']);
					        ?>
					        <option value="<?php echo $row['uni_id']; ?>"><?php echo $uni_dept_name ." - " . $row['uni_name'];  ?></option>  
					        <?php
					    }
				  	?>
				</select>
			<?php
				}
				elseif ($_GET['mode'] == "project")
				{
			?>
				<label>Select Project </label>
				<select id="type_value[]" name="type_value[]" required class="form-control select2 input-lg" multiple>
					<?php
					    $sql = "SELECT * FROM project WHERE project_org = '$_SESSION[id]' ORDER BY project_title" ;
					    $sql = $connection->query($sql) or die("Unsuccessful") ;
					    $sql ->setFetchMode(PDO::FETCH_ASSOC);
					    while($row = $sql->fetch())
					    {
					        ?>
					        <option value="<?php echo $row['project_id']; ?>"><?php echo $row['project_title'];  ?></option>  
					        <?php
					    }
				  	?>
				</select>
			<?php
				}
			?>

			<br /><br />
			<select name="supervisor" required id="supervisor" class="form-control input-lg">
				<option>Select Supervisor</option>
				<!-- <option value="200">All Category</option> -->
				<?php
					$org = $_SESSION['id'];
				    $sql = "SELECT * FROM supervisors where supervisor_org = '$org' " ;
				    $sql = $connection->query($sql) or die("Unsuccessful") ;
				    $sql ->setFetchMode(PDO::FETCH_ASSOC);
				    while($row = $sql->fetch())
				    {
				        ?>
				        <option value="<?php echo $row['supervisor_staff_id']; ?>">
				        	<?php echo GetStaffName($connection, $row['supervisor_staff_id']); ?>
				        </option>  
				        <?php
				    }
			  	?>
			</select>

			<input type="hidden" value="<?php echo strip_tags($_GET['mode']) ?>" name="type" id="type">
			<br>
			<input type="submit" class="btn btn-success btn-lg" value="Assign">
		</form>
	</div>
</div>
							
		
<?php $get_staff = GetStaffToSupervisors($connection, $_SESSION['id']) ;?> 
<div class="box box-success">
	<div class="box-header with-border">
		<h3 class="box-title">List of Supervisors</h3>
	</div>
	<div class="">	
		<table id="example1" class="table table-responsive">
			<thead>
				<tr>
					<th>S/N</th>
					<th>Supervisor</th>
					<th>Supervisory Role</th>
					<th>Supervisory Title</th>
					<th>Date assigned</th>
				</tr>
			</thead>
			<tbody>
			<?php
				$sn = 1;
				foreach ($get_staff as $row)
				{
					$supervisor_name = GetStaffName($connection,$row['supervisor']);
					$supervisor_role = ucfirst($row['type']);
					$orgId = GetStaffOrgId($connection, $row['supervisor']);

					if($_SESSION['id'] == $orgId || $_SESSION['id'] == "0") {
						if ($row['type'] == "staff")
						{
							$name = GetStaffName($connection,$row['type_value']);
						}
						elseif($row['type'] == "dept")
						{
							$supervisor_role = "Department";
							$name = GetDeptName($connection,$row['type_value']);
						}
						elseif($row['type'] == "unit")
						{
							$dept = GetUnitDept($connection,$row['type_value']);
							$name = GetDeptName($connection, $dept) . " / ". GetUnitName($connection,$row['type_value']);
						}
						elseif($row['type'] == "project")
						{
							$supervisor_role = "Project";
							$name = GetProjectName($connection,$row['type_value']);
						}
						else
						{
							$name = GetStaffName($connection,$row['type_value']);
						}
						
						$date_reg = date("jS M Y", $row['registered_at']);
						$name = ucfirst($name);
						echo "<tr>
								<th scope='row'>$sn</th>
								<td>$supervisor_name</td>
								<td>$supervisor_role</td>
								<td>$name $orgId </td>
								<td>$date_reg</td>
							</tr>";
							$sn=$sn+1;
					}
				}
			?>
			</tbody>
		</table>
	</div>
</div>