<?php
// get organisation category
$get_personality = GetPersonalityTest($connection);
?>

<div class="box box-success">
	<div class="box-header with-border">
		<h3 class="box-title">Add Personality Test Parameters</h3>
	</div>
	<div class="box-body">
		<?php include('flashMessage.php'); ?>
		<form id="defaultForm" method="post" class="form-horizontal" action="webapp/assesment_params.php?action=pers">
			<div class="control-group">
			    <input type="text" class="span8 form-control" name="name" autocomplete="off" placeholder="What are you measuring?" />
			</div>
			<br>
			<div class="control-group">
				<textarea class="form-control" name="discrip" placeholder="What is this all about?" ></textarea>
			</div>
			<br>
			<input type="submit" value="Add" class="btn btn-lg btn-primary"></center> 
		</form>
	</div>
</div>





<div class="box box-success">
	<div class="box-header with-border">
		<h3 class="box-title">Personality Assessment Parameters</h3>
	</div>
	<div class="box-body">
		<table id="dept" class="table table-responsive">
			<thead >
				<tr align="left">
					<th>Title</th>
					<th>Description</th>
					<th> </th>
				</tr>
			</thead>
			<tbody>
				<?php
					foreach ($get_personality as $row)
					{
						$name = $row['pname'] ;
			            $discrip = $row['discrip'] ;
			            $id = $row['id'];
			            $reg_at = $row['registered_at'];

			            echo "<tr>
			                    <td class='description'><a href='#' class='name'>$name</a></td>
			                    <td>$discrip</td>
			                    <td>
			                        <a class='btn btn-default' href='#'>Edit</a>
			                        <a class='btn btn-danger' href='#delete'  data-toggle='modal' data-target='#oc$id'>Delete</a>
			                    </td>
			            </tr>";
					}
				?>
			</tbody>
		</table>
	</div>
</div>

<div class="modal hide fade" id="myModal">
	<div class="modal-footer">
		<a href="#" class="btn" data-dismiss="modal">Close</a>
		<a href="#" class="btn btn-primary">Save changes</a>
	</div>
</div>

<div class="clearfix"></div>
<?php // require("delete-staff-modal.php") ; ?>

