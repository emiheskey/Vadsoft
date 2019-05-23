<?php $get_assesement_kpi = GetKPI($connection, '', $_SESSION['id']) ; ?>

<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title">New Key Perfomance Index</h3>
    </div>
    <div class="box-body">
        <?php include('flashMessage.php'); ?>
        <form id="defaultForm" method="post" class="form-horizontal" action="webapp/assesment_params.php?action=kpi">
            <div class="control-group">
                <input type="text" class="form-control input-lg" name="name" autocomplete="off" placeholder="Title" />
            </div>
            <br>
            <div class="control-group">
                <textarea class="form-control input-lg" name="discrip" placeholder="Description"></textarea>
            </div>
            <br>
            <div class="control-group">
                <select name="routine" class="form-control input-lg">
                    <option value="">Select Assesment routine</option>
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="monthly">Monthly</option>
                    <option value="quaterly">Quaterly</option>
                </select>
            </div>
            <br>
            <div class="control-group">
                <input type="submit" value="Add" class="btn btn-primary">
            </div>
        </form>
    </div>
</div>

<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title">Key Performance Index List</h3>
    </div>
    <div class="box-body">
        <table class="table table-bordered table-condensed table-responsive" >
        	<thead>
        		<tr>
        			<th>Title</th>
        			<th>Description</th>
                    <th>Asssment routine</th>
                    <th></th>
        		</tr>
        	</thead>
        	<tbody>
        	   <?php 
                foreach($get_assesement_kpi as $row) 
                {
                    $name = $row['kpi_name'] ;
                    $discrip = $row['kpi_discrip'] ;
                    $routine = $row['kpi_routine'];
                    $id = $row['kpi_id'] ;
                    echo "<tr>
                            <td class='description'><a href='staffprofile?id=$id&measure=kpilist' class='name'>".ucwords(strtolower($name))."</a></td>
                            <td>$discrip</td>
                            <td>$routine</td>
                            <td> <a href='#' data-toggle='modal' data-target='#oc$id'>Delete</a></td>
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
<?php require("delete-kpi-modal.php") ; ?>