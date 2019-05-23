<?php 
	$name = base64_decode($_GET['name']);
	$get_staff = GetStaffList($connection, $_SESSION['id'], $name); 
	foreach ($get_staff as $mystaff) {
?>
		<div class="box box-success">
			<div class="box-header with-border">
				<h3 class="box-title">Edit Staff</h3>
			</div>
			<div class="box-body">
				<?php include('flashMessage.php'); ?>
				<form id="defaultForm" method="post" class="form-horizontal" action="webapp/staff_edit_new.php">
					<div class="control-group">
						<input type="text" class="form-control input-lg" name="fname" autocomplete="off" placeholder="First name" value="<?php echo $mystaff['sta_fname'] ?>" />
					</div>
					<br>
					<div class="control-group">
					    <input type="text" class="form-control input-lg" name="lname" autocomplete="off" placeholder="Last name" value="<?php echo $mystaff['sta_lname'] ?>" />
					</div>
					<br>
					<div class="control-group">
						<input type="text" class="form-control input-lg" name="snumber" autocomplete="off" placeholder="Staff number" required="required" value="<?php echo $mystaff['sta_number'] ?>" />
					</div>
					<br>
					<!-- <div class="control-group">
					    <textarea class="form-control input-lg" name="discrip" placeholder="Job description"><?php echo $mystaff['sta_desc'] ?></textarea>
					</div> -->
					<br>
					<div class="control-group">
					    <input type="number" class="form-control input-lg" name="phone" placeholder="Phone number" value="<?php echo $mystaff['sta_phone'] ?>" >
					</div>
					<br>
					<div class="control-group">
						<input type="email" class="form-control input-lg" name="email" placeholder="Email" value="<?php echo $mystaff['sta_email'] ?>" >
					</div>
					<br>
					<div class="control-group">
					    <select class="form-control input-lg" name="dept" id="dept">
					    	<option value="<?php echo $mystaff['sta_dept'] ?>"><?php echo GetDeptName($connection, $mystaff['sta_dept']) ?></option>
					    	<?php GetDept($connection, $_SESSION['id']); ?>
					    </select>
					</div>
					<br>
					<div class="control-group">
						<label>Will this staff receive the assesment link for this department</label>
						<select name="linkperson" id="linkperson" class="form-control input-lg">
							<option value="" selected="selected">--Select option--</option>
							<option value="1">Yes</option>
							<option value="2">No</option>
						</select>
					</div>
					<br>
					<div class="control-group">
					    <select class="form-control input-lg" name="sex">
					    	<option value="<?php echo $mystaff['sta_sex'] ?>"><?php echo $mystaff['sta_sex'] ?></option>
					    	<option value="Male">Male</option>
					    	<option value="Female">Female</option>
					    </select>
					</div>
					<br>
					<div class="control-group">
					    <select class="form-control  input-lg" name="state" id="state" required>
							<option value="<?php echo $mystaff['sta_state'] ?>" selected><?php echo $mystaff['sta_state'] ?></option>
							<option value="Abia">Abia</option>
							<option value="Abuja">Abuja</option>
							<option value="Sokoto">Sokoto</option>
						</select>
					</div>
					<br>
					<div class="control-group">
						<input type="text" class="form-control input-lg" name="city" placeholder="Office town" value="<?php echo $mystaff['sta_city'] ?>" >
					</div>
					<br>
					<div class="control-group">
						<input type="text" class="form-control input-lg" name="grade_level" placeholder="Grade and/or Level" value="<?php echo $mystaff['sta_grade_level'] ?>" />
					</div>
					<br>
					<div class="control-group">
						<input type="text" class="form-control input-lg" name="job_title" placeholder="Job title" value="<?php echo $mystaff['sta_job_title'] ?>" />
					</div>
					<br>
					<input type="hidden" value="<?php echo base64_encode($mystaff['sta_id']) ?>" name="staff_id" />
					<div class="control-group">
						<input type="submit" value="Edit Staff" class="btn btn-lg btn-primary">
					</div>

				</form>
			</div>
		</div>
<?php } ?>