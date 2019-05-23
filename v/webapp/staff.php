<?php $get_staff = GetStaffList($connection, $_SESSION['id'], ''); ?>

<?php if (isset($_GET['mode']) != "listonly"){ ?>
<div class="box box-success">
	<div class="box-header with-border">
		<h3 class="box-title">Add New Staff</h3>
	</div>
	<div class="box-body">
		<?php include('flashMessage.php'); ?>	
		<form id="defaultForm" method="post" class="form-horizontal" action="webapp/staff_new.php">
			<div class="control-group">
				<input type="text" class="form-control input-lg" name="fname" autocomplete="off" placeholder="First name" />
			</div>
			<br>
			<div class="control-group">
			    <input type="text" class="form-control input-lg" name="lname" autocomplete="off" placeholder="Last name" />
			</div>
			<br>
			<div class="control-group">
				<input type="text" class="form-control input-lg" name="snumber" autocomplete="off" placeholder="Staff number" required="required" />
			</div>
			<br>
			<div class="control-group">
			    <textarea class="form-control input-lg" name="discrip" placeholder="Job description" ></textarea>
			</div>
			<br>
			<div class="control-group">
			    <input type="number" class="form-control input-lg" name="phone" placeholder="Phone number" >
			</div>
			<br>
			<div class="control-group">
				<input type="email" class="form-control input-lg" name="email" placeholder="Email" >
			</div>
			<br>
			<div class="control-group">
			    <select class="form-control input-lg" name="dept" id="dept">
			    	<option value="">--Select Department--</option>
			    	<?php GetDept($connection, $_SESSION['id']); ?>
			    </select>
			</div>
			<br>
			<div class="control-group">
				<label>Will this staff receive the assesment link for this department</label>
				<select name="linkperson" id="linkperson" class="form-control input-lg">
					<option value="1">Yes</option>
					<option value="2" selected="selected">No</option>
				</select>
			</div>
			<br>
			<div class="control-group">
			    <select class="form-control input-lg" name="sex">
			    	<option value="">Select gender</option>
			    	<option value="Male">Male</option>
			    	<option value="Female">Female</option>
			    </select>
			</div>
			<br>
			<div class="control-group">
			    <select class="form-control  input-lg" name="state" id="state" required>
					<option value="" selected>Select State</option>
					<option value="Abia">Abia</option>
					<option value="Abuja">Abuja</option>
					<option value="Sokoto">Sokoto</option>
				</select>
			</div>
			<br>
			<div class="control-group">
				<input type="text" class="form-control input-lg" name="city" placeholder="Office town" >
			</div>
			<br>
			<div class="control-group">
				<input type="text" class="form-control input-lg" name="grade_level" placeholder="Grade and/or Level" >
			</div>
			<br>
			<div class="control-group">
				<input type="text" class="form-control input-lg" name="job_title" placeholder="Job title" >
			</div>
			<br>
			<div class="control-group">
				<input type="submit" value="Add Staff" class="btn btn-lg btn-primary">
			</div>

		</form>
	</div>
</div>

<?php } ?>

<!--<h1 id="type" class="big-header">Staff Lists</h1>-->


<div class="box box-success">
	<div class="box-header with-border">
		<h3 class="box-title">Staff List</h3>
	</div>
	<div class="">				
		<table id="example1" class="table table-responsive">
			<thead >
				<tr align="left">
					<th>SN</th>
					<th>Staff Number</th>
					<th>Name</th>
					<th>Department </th>
					<th>Grade Level </th>
					<th>Job Title </th>
					<th>Gender</th>
					<th>State</th>
					<th>City</th>
					<th></th>
				</tr>
			</thead>
			<tbody>

				<?php
					$sn = 1;
					foreach ($get_staff as $row)
					{
						$fname = $row['sta_fname'] ;
			            $lname = $row['sta_lname'] ;
			            $dept = $row['sta_dept'] ;
			            $phone = $row['sta_phone'];
			            $email = $row['sta_email'];
			            $sex = $row['sta_sex'];
			            $id = $row['sta_id'];
			            $sex = $row['sta_sex'];
			            $state = $row['sta_state'];
			            $city = $row['sta_city'];
			            $grade_level = $row['sta_grade_level'];
			            $job_title = $row['sta_job_title'];
			            $name = $fname." ".$lname;
			            $staffno = $row['sta_number'];
			            // $snumber = $row['sta_number'];
			            // rand value
          				$randvalue = md5(rand(0000,9999).$id.$lname) . md5(rand(2222,8889).$id.$fname) . md5($id.$name);
          				$mynumber = base64_encode($id);

			            echo "<tr>
			            		<th scope='row'>$sn</th>
			            		<td>$staffno</td>
			                    <td><b><a href='staffprofile?id=$id&measure=summarylist' class='name'>$name</a></b></td>
			                    <td><em>".GetDeptId($connection,$dept)."</em></td>
			                    <td>$grade_level</td>
			                    <td>$job_title</td>
			                    <td>$sex</td>
			                    <td>$state</td>
			                    <td>$city</td>
			                    <td>
			                    	<a class='' href='editstaff?name=$mynumber'>Edit</a>
			                        <a class='' href='#delete' data-toggle='modal' data-target='#oc$id'>Delete</a> 
			                    </td>
			                    <!--<td>
			                    	<a href='staffmeasurement?name=$id&tag=$randvalue&m_staff=listkpi' class='btn btn-success'>Measure this staff</a>
			                    </td>-->
			            </tr>";
			            $sn=$sn+1;
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
<?php require("delete-staff-modal.php") ; ?>