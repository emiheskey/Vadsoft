<?php $get_department = GetOrganizationDeptList($connection,$_SESSION['id']) ?>

<div class="box box-success">
	<div class="box-header with-border">
		<h3 class="box-title">Add New Department</h3>
	</div>
	<div class="box-body">
		<?php include('flashMessage.php'); ?>
		<form id="defaultForm" method="post" class="form-horizontal" action="webapp/dept.php">
			<div class="control-group">
			    <div class="controls">
			        <input type="text" class="form-control input-lg" name="name" autocomplete="off" placeholder="Name of department" />
			    </div>
			</div>
			<br>
			<div class="control-group">
		      	<div class="controls">
		            <textarea class="form-control input-lg" name="discrip" id="discrip" placeholder="Description" ></textarea>
		        </div>
		    </div>
		    <br>
		 	<div class="form-group">
		     	<div class="col-sm-6">
		        	<input type="submit" value="Add Department" class="btn btn-primary">
		        </div>
		    </div>
		</form>

	</div>
</div>

					

<div class="box box-success">
	<div class="box-header with-border">
		<h3 class="box-title">List of Departments</h3>
	</div>
	<div class="box-body" style="padding: 0px;">
		<table id="dept" class="table">
			<thead >
				<tr align="left">
					<th>Name</th>
					<th>No. of Units</th>
					<th>No. of Staff</th>
					<th>Date Registered</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php
					foreach ($get_department as $row) {
						# code...
						$name = $row['dept_name'] ;
			            // $discrip = $row['dept_discrip'] ;
			            $id = $row['dept_id'];
			            $reg_at = date("M j, Y", strtotime($row['registered_at']));
			            // get rand value
			            $rand = md5(rand(2333,9899)).md5($name).md5(rand(0000,9999));
			            
			            echo "<tr>
			                   	<td class='description'><a href='departmentprofile?id=$id' class='name'>".ucwords(strtolower($name))."</a></td>
			                    <td>".GetNumUnitDept($connection,$id)."</td>
			                    <td>".GetNumStaffDept($connection,$id)."</td>
			                    <td>$reg_at</td>
			                    <td>
                                    <a class='text-right' href='#delete' data-toggle='modal' data-target='#oc$id'>Delete</a>
			                    </td>
			                    <!--<td><a href='departmenttest?tag=$rand&name=$id' class='text-right'>Measure Department </a></td>-->
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
<?php require("delete-dept-modal.php") ; ?>