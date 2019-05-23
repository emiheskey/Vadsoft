<?php $get_assesement_kpi = GetKPI($connection, '', $_SESSION['id']) ; ?>

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
                    <th>Date registered</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
               <?php 
                foreach($get_assesement_kpi as $row) 
                {
                    $name = $row['kpi_name'] ;
                    $discrip = $row['kpi_discrip'] ;
                    $routine = ucwords(strtolower($row['kpi_routine']));
                    $dtime = date("jS F, Y", strtotime($row['registered_at']));

                    $id = $row['kpi_id'] ;
                    echo "<tr>
                            <td class='description'><a href='staffprofile?id=$id&measure=kpilist' class='name'>".ucwords(strtolower($name))."</a></td>
                            <td>$discrip</td>
                            <td>$routine</td>
                            <td>$dtime</td>
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
