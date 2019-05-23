<?php 
require("functions.php"); 

if ($_GET) {
	$obj = $_GET['obj'] ;
	if ($obj == 'orgcat') 
	{
		$mid = $_GET['id'] ;
		
		$_SESSION['flash'] = DeleteCat($connection,$mid);
		header("Refresh:0; url=../organizationcategory");

	}
	elseif ($obj == 'org') 
	{
		$cid = $_GET['id'] ;	

		$_SESSION['flash'] = DeleteOrg($connection,$cid);
		header("Refresh:0; url=../organization");

	}
	elseif ($obj == 'ass_param') {
		$cid = $_GET['id'] ;	

		$_SESSION['flash'] = DeleteAssParam($connection,$cid);
		header("Refresh:0; url=../assesmentvaluesetup?unit=3");
	}
	elseif ($obj == 'dept') 
	{
		$fid = $_GET['id'] ;

		$_SESSION['flash'] = DeleteDept($connection,$fid);
		header("Refresh:0; url=../department");

	}
	elseif ($obj =='unit') 
	{
		$cid = $_GET['id'];

		$_SESSION['flash'] = DeleteUnit($connection,$cid);
		header("Refresh:0; url=../unit");

	}
	elseif ($obj =='staff') 
	{
		$cid = $_GET['id'] ;

		$_SESSION['flash'] = DeleteStaff($connection,$cid);
		header("Refresh:0; url=../staff");

	}
	elseif ($obj =='assesment_category') 
	{
		$cid = $_GET['id'] ;

		$_SESSION['flash'] = DeleteAssesmentCategory($connection,$cid);
		header("Refresh:0; url=../assesmentmeasure");

	}
	elseif ($obj =='kpi') 
	{
		$cid = $_GET['id'] ;

		$_SESSION['flash'] = DeleteKPI($connection,$cid);
		header("Refresh:0; url=../kpi");

	}
	elseif ($obj =='type') 
	{
		$cid = $_GET['id'] ;

		$_SESSION['flash'] = DeleteType($connection,$cid);
		header("Refresh:0; url=../assesmenttype");

	}
}

?>