<?php 
if (isset($_GET['mode'])) {
    $mode = $_GET['mode'];
}

if ($mode != "list")
{
    $get_organization = GetOrganizations($connection, $_SESSION['id'], '', ''); // connection, org_id, category_id, limit 
    // fetch data
    foreach($get_organization as $row)
    {
        $org_name = $row['org_name'] ;
        $org_adname = $row['org_admin'] ;
        $org_email = $row['org_email'] ;
        $org_address = $row['org_address'];
        $org_phone = $row['org_phone'];
        $org_location = GetOrganizationLocation($connection, $row['org_location']);
        $org_id = $row['org_id'];
        $org_category = $row['org_category'];
        $org_license = $row['org_license_key'];
        $org_status = $row['org_license_status'];
        $org_reg_date = date("jS F, Y", strtotime($row['registered_at']));
    }   
}
else
{
    if (isset($_GET['caID'])) {
        $caid = $_GET['caID'];
    } else {
        $caid = "";
    }
    $get_organization = GetOrganizations($connection, $_SESSION['id'], $caid, ''); // connection, org_id, category_id, limit 
}


if ($mode == "edit")
{
	// include file for edit and update
	include("organization_edit.php"); 
}
elseif ($mode == "list")
{
    if (isset($_GET['place'])) {
        $place = $_GET['place'];
    } else {
        $place = "home";
    }
    include("organization_list.php");
}
else
{
	// include file for edit and update
	include("organization_new.php"); 
}
?>