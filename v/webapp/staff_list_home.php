<?php $get_staff = GetStaffList($connection,$_SESSION['id'], $dept_id); ?>

		<table id="dept" class="table table-responsive">
			<thead >
				<tr align="left">
					<th>S/N</th>
					<th>Staff Number</th>
					<th>Name</th>
					<th>Department</th>
					<th>Email</th>
					<th>Grade Level </th>
					<th>Job Title </th>
					<th>Gender</th>
					<th>State</th>
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
			            $gender = $row['sta_sex'];
			            $id = $row['sta_id'];
			            $grade_level = $row['sta_grade_level'];
			            $job_title = $row['sta_job_title'];
			            $name = "$fname ".  " $lname ";
			            $snumber = $row['sta_number'];
			            $state = $row['sta_state'];

			            // rand value
          				$randvalue = md5(rand(0000,9999).$id.$lname) . md5(rand(2222,8889).$id.$fname) . md5($id.$name);
			            echo "<tr>
			            		<th scope='row'>$sn</th>
			            		<td>$snumber</td>
			                    <td><a href='staffprofile?tag=$randvalue&id=$id&measure=summarylist' class='name'><b>$name</b></a></td>
			                    <td><em>".GetDeptId($connection,$dept)."</em></td>
			                    <td><a href='mailto:$email'>$email</a></td>
			                    <td>$grade_level</td>
			                    <td>$job_title</td>
			                    <td>$gender</td>
			                    <td>$state</td>
			            </tr>";
			            $sn=$sn+1;
					}
				?>
			</tbody>
		</table>

<!-- <td>
	<a href='staffmeasurement?name=$id&tag=$randvalue&m_staff=listkpi' class=''>Measure this staff</a>
</td> -->