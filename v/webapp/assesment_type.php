<?php $get_assesment_type = GetAssType($connection, $_SESSION['id']) ; ?>

<!--Assesment type-->
<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title">Assessment Types List</h3>
    </div>

    <div class="box-body">

        <form id="defaultForm" name="defaultForm" method="post" class="form-horizontal" action="webapp/assesment_params.php?action=asstyp">
            <?php include('flashMessage.php'); ?>
            <input type="hidden" name="id" id="id" >  
            <div class="control-group">
                <input type="text" class="form-control input-lg" required name="name" id="name" autocomplete="off" placeholder="Title" />
            </div>
            <br>
            <div class="control-group">
                <select class="form-control input-lg" name="cat" id="cat" required>
                    <option>Select Category</option>
                    <!--<option value="200">All Category</option> -->
                    <?php

                        $sql = "SELECT * FROM organization_category ORDER BY cat_name" ;
                        $sql = $connection->query($sql) or die("Unsuccessful") ;
                        $sql ->setFetchMode(PDO::FETCH_ASSOC);
                        while($row = $sql->fetch())
                        {
                            ?>
                                 <option value="<?php echo $row['cat_id']; ?>"><?php echo $row['cat_name'];  ?></option>  
                            <?php
                        }
                    ?>
                </select>
            </div>
            <br>
            <div class="control-group">
                <select class="form-control input-lg" name="org_level" required id="org_level">
                	<option value="personal">General staff</option>
                    <option value="staff-ass">Staff assessment</option>
                    <option value="unit">Unit</option>
                	<option value="punit">Staff In Unit</option>
                	<option value="department">Departmental</option>
                	<option value="organization">Organizational</option>
                    <option value="project">Project</option>
                </select>
            </div>
            <br>
            <div class="control-group">
                <textarea class="form-control input-lg" name="discrip" id="discrip"></textarea>
            </div>
            <br>
            <div class="control-group">
                <input type="submit" value="Add Assesment Type" class="btn btn-lg btn-primary">
                <a href="javascript:;" id="discardEditBtn" class="pull-right text-warning" style="text-decoration:underline; display:none;" onclick="discardEdit()">Discard Edit</a>
            </div>
        </form> 
    </div>
</div>

                 
<!--Assessment Types List-->
<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title">Assessment Types List</h3>
    </div>
    <div class="box-body">

        <table class="table table-striped table-bordered">
        	<thead>
        		<tr>
        			<th>Title</th>
        			<th>Type Cat.</th>
        			<th></th>
        		</tr>
        	</thead>
        	<tbody>
                <?php
                    foreach ($get_assesment_type as $row) 
                    {
                        $name = $row['astyp_name'];
                        $discrip = $row['astyp_discrip'] ;
                        $id = $row['astyp_id'];
                        $cat = $row['astyp_orgcat'];
                        $reg_at = $row['registered_at'];

                        echo "<tr>
                                <td class='description'>$name</td>
                                <td>".GetOrgTypId($connection,$cat)."</td>
                                <td>
                                    <a href='javascript:;' onclick='GetType(".$row['astyp_id'].")'>Edit</a> | <a href='javascript:;' data-toggle='modal' data-target='#oc$id'>Delete</a>
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
<?php require("delete-type-modal.php") ; ?>

<script>

function GetType(typeId) {
    $.post('webapp/getAssType.php', { Id: typeId }, function(result) { 
        $("#cat").val(result.cat); 
        $("#discrip").val(result.desc);
        $("#id").val(result.id);
        $("#org_level").val(result.level);
        $("#name").val(result.name);
        $("#discardEditBtn").show();
        document.defaultForm.action = "webapp/assesment_params.php?action=asstypEdit";
    }, "json");

}

function discardEdit() {
    $("#cat").val(""); 
    $("#discrip").val("");
    $("#id").val("");
    $("#org_level").val("");
    $("#name").val("");
    $("#discardEditBtn").hide();
    document.defaultForm.action = "webapp/assesment_params.php?action=asstyp";

}

$("#type").change(function()
{
    var xmlhttp = new XMLHttpRequest();
    var ty = $("select#type").val();
    var url = 'we.php?ty='+ty;

    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            myFunction(xmlhttp.responseText);
        } else {
        	
        }
    }
    xmlhttp.open("GET", url, true);
    xmlhttp.send();

    function myFunction(response) {
        var arr = JSON.parse(response);
        var i;
        var out = "";

        for(i = 0; i < arr.length ; i++) {
             out += arr[i].valu  ;
        }
        // document.getElementById("typ").innerHTML = out ; 
        $("#typ").val(out);
        //  $("#cum").html(response.cum);
        // $("#flash").fadeIn(6000).html(' ');
    }
});
</script>

