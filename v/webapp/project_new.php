<?php $get_project = GetOrganizationProjectList($connection,$_SESSION['id']) ?>
<?php if (isset($_GET['mode']) != "listonly"){ ?>
<div class="box box-success">
	<div class="box-header with-border">
		<h3 class="box-title">Add New Project</h3>
	</div>
	<div class="box-body">
		<?php include('flashMessage.php'); ?>
		<form id="defaultForm" method="post" class="form-horizontal" action="webapp/project.php">
			<div class="control-group">
			    <div class="controls">
			        <input type="text" class="form-control input-lg" name="name" autocomplete="off" placeholder="Name of project" />
			    </div>
			</div>
			<br>
			<div class="control-group">
			    <div class="controls">
			        <input type="text" class="form-control input-lg" name="location" autocomplete="off" placeholder="Location of project" />
			    </div>
			</div>
			<br>
			<div class="control-group">
			    <div class="controls">
			        <input type="date" class="form-control input-lg" name="start_date" autocomplete="off" placeholder="Project start date" />
			    </div>
			</div>
			<br>
			<div class="control-group">
			    <div class="controls">
			        <input type="date" class="form-control input-lg" name="end_date" autocomplete="off" placeholder="Project end date" />
			    </div>
			</div>
			<br>
			<div class="control-group">
			    <div class="controls">
			        <input type="text" class="form-control input-lg" name="project_initiator" autocomplete="off" placeholder="Project Initiator" />
			    </div>
			</div>
			<br>
			<div class="control-group">
			    <div class="controls">
			        <input type="text" class="form-control input-lg" name="project_executor" autocomplete="off" placeholder="Project Executor" />
			    </div>
			</div>
			<br />
			<select name="linkperson" id="linkperson" class="form-control input-lg">
				<option>Select Supervisor </option>
				<!-- <option value="200">All Category</option> -->
				<?php
				    $sql = "SELECT * FROM staff WHERE sta_org = '$_SESSION[id]'" ;
				    $sql = $connection->query($sql) or die("Unsuccessful") ;
				    $sql ->setFetchMode(PDO::FETCH_ASSOC);
				    while($row = $sql->fetch())
				    {
				        ?>
				        <option value="<?php echo $row['sta_id']; ?>">
				        	<?php echo $row['sta_fname'] . " " . $row['sta_lname'] ?>
				        </option>  
				        <?php
				    }
			  	?>
			</select>
			<em>Select the staff incharge</em>
			<br><br>
			<div class="control-group">
		      	<div class="controls">
		            <textarea class="form-control input-lg" name="description" id="description" placeholder="Description" ></textarea>
		        </div>
		    </div>
		    <br>
		 	<div class="form-group">
		     	<div class="col-sm-6">
		        	<input type="submit" value="Add Project" class="btn btn-primary">
		        </div>
		    </div>
		</form>

	</div>
</div>
<?php } ?>
					

<div class="box box-success">
	<div class="box-header with-border">
		<h3 class="box-title">List of Projects</h3>
	</div>
	<div class="box-body" style="padding: 0px;">
		<table id="dept" class="table">
			<thead >
				<tr align="left">
					<th>S/N</th>
					<th>Project title</th>
					<th>Location</th>
					<th>Start date</th>
					<th>End date</th>
					<th>Staff incharge</th>
					<th>Staff initiator</th>
					<th>Staff executor</th>
					<th>Date created</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$sn = 1;
					foreach ($get_project as $row) {
						# code...
						$name = $row['project_title'] ;
			            $start_date = $row['project_start_date'];
			            $end_date = $row['project_end_date'];
			            $location = $row['project_location'];
			            $id = $row['project_id'];
			            // $supervisor = $row['project_supervisor'];
			            $reg_at = date("M j, Y", strtotime($row['registered_at']));
			            // get rand value
			            $rand = md5(rand(2333,9899)).md5($name).md5(rand(0000,9999));
			            $staff_incharge = GetStaffName($connection,$row['project_linkperson']);
			            $initiator = $row['project_initiator'];
			            $executor = $row['project_executor'];
			            
			            echo "<tr>
			            		<td>$sn</td>
			                   	<td class='description'><a href='#projectprofile?id=$id' class='name'>".ucwords(strtolower($name))."</a></td>
			                    <td>$location</td>
			                    <td>$start_date</td>
			                    <td>$end_date</td>
			                    <td>$staff_incharge</td>
			                    <td>$initiator</td>
			                    <td>$executor</td>
			                    <td>$reg_at</td>
			            </tr>";
			            $sn++;
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