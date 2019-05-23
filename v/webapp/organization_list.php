<?php
if ($place == "home") {
	$table_id = "";
} else {
	$table_id = "example1";
}
?>
<div class="box box-success">
	<div class="box-header with-border">
		<h3 class="box-title">Organization List</h3>
	</div>
	<div class="">				
		<table id="<?php echo $table_id ?>" class="table table-responsive">
			<thead >
				<tr align="left">
					<th>SN</th>
					<th>License Code</th>
					<th>Name</th>
					<th>Sector</th>
					<th>Contact person</th>
					<th>Phone number</th>
					<th>Email</th>
					<th>Status</th>
					<th>Date of setup</th>
				</tr>
			</thead>
			<tbody>

				<?php
					$sn = 1;
					foreach ($get_organization as $row)
					{
						$org_name = $row['org_name'] ;
				        $org_adname = $row['org_admin'] ;
				        $org_email = $row['org_email'] ;
				        $org_address = $row['org_address'];
				        $org_phone = $row['org_phone'];
				        $org_location = GetOrganizationLocation($connection, $row['org_location']);
				        $org_id = $row['org_id'];
				        $org_category = GetOrganizationCat($connection, $row['org_category']);
				        $org_license = $row['org_license_key'];
				        $org_status = $row['org_license_status'];
				        $org_reg_date = date("jS F, Y", strtotime($row['registered_at']));

				        if ($org_status == 1) {
				        	$org_status = "Activated";
				        } else {
				        	$org_status = "Pending";
				        }

			            // $snumber = $row['sta_number'];
			            // rand value
          				// $randvalue = md5(rand(0000,9999).$id.$lname) . md5(rand(2222,8889).$id.$fname) . md5($id.$name);

			            echo "<tr>
			            		<th scope='row'>$sn</th>
			            		<td>$org_license</td>
			                    <td><b><a href='organization?mode=edit&oid=$org_id'>$org_name</a></b></td>
			                    <td><em>$org_category</em></td>
			                    <td>$org_adname</td>
			                    <td>$org_phone</td>
			                    <td>$org_email</td>
			                    <td><em>$org_status</em></td>
			                    <td>$org_reg_date</td>
			            </tr>";
			            $sn=$sn+1;
					}
				?>
			</tbody>
		</table>
	</div>
</div>