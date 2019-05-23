<?php
// get organisation category
$get_categories = GetOrganizationCatList($connection);
?>

<div class="box box-success">
	<div class="box-header with-border">
		<h3 class="box-title">Add Category</h3>
	</div>
	<div class="box-body">
		<?php include('flashMessage.php'); ?>
		<form id="defaultForm" method="post" class="form-horizontal" action="webapp/cat.php">
			<div class="control-group">
			    <div class="controls">
			    	<input type="text" class="form-control input-lg" name="name" autocomplete="off" placeholder="Category title" />
			    </div>
			</div>
			<br />
			<div class="control-group">
		  		<div class="controls">
		        	<textarea class="form-control input-lg" name="discrip" id="description" placeholder="Description" ></textarea>
		    	</div>
			</div>
			<br />
			<div class="control-group">
		    	<div class="controls">
		            <input type="submit" value="Add Category" class="btn btn-primary btn-lg">
		        </div>
		    </div>
		</form>

	</div>
</div>



<div class="box box-success">
	<div class="box-header with-border">
		<h3 class="box-title">Category Listing</h3>
	</div>
	<div class="box-body">
		<table id="user" class="table table-striped">
			<thead>
			    <tr>
			        <th>Category Title</th>
			        <th>Added At</th>
			        <th></th>
			    </tr>
			</thead>
			<tbody>
				
			<?php
				// loop
				foreach($get_categories as $row)
				{
					$name = $row['cat_name'] ;
		            $discrip = $row['cat_discrip'] ;
		            $cat_id = $row['cat_id'];
		            $reg_at = $row['registered_at'];


		            echo "<tr>
		                    <td class='description'><a href='#profile.php?id=$cat_id' class='name'>$name </a></td>
		                    <td>$reg_at</td>
		                    <td>
		                        <a class='btn btn-info' href='#'>Edit</a>
		                         <a class='btn btn-danger' href='#delete'  data-toggle='modal' data-target='#oc$cat_id'>
		                            Delete
		                        </a>
		                    </td>
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
<?php require("delete-org-cat-modal.php") ; ?>