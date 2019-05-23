<?php   
// add header
include('../header.php');

// add nav bar
include("../nav.php");
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->

    <!-- Main content -->
    <section class="content">
		<?php
		// route the pages
		$page_mode = $_GET['page_mode'];
		// switch pages
		switch ($page_mode) {
			case 'addproject':
				# code...
				include ("project_new.php");
				break;

			case 'organization':
				# code...
				include ("organization.php");
				break;

			case 'addorganization':
				# code...
				include ("organization_new.php");
				break;
				
			case 'organizationcategory':
				# code...
				include ("organization_category.php");
				break;

			# switch for department
			case 'department':
				# code...
				include ("department.php");
				break;

			# switch for department test
			case 'departmenttest':
				# code
				include ("departmenttest.php");
				break;

			# switch for unit profile 
			case 'departmentprofile':
				# code
				include ("departmentprofile.php");
				break;

			# switch for units
			case 'unit':
				# code...
				include ("unit.php");
				break;

			# switch for unit profile 
			case 'unitprofile':
				# code
				include ("unitprofile.php");
				break;
			
			# switch for unit profile 
			case 'unittest':
				# code
				include ("unittest.php");
				break;

			case 'assignunit':
				# code...
				include ("unit_assign.php");
				break;

			# switch for staff
			case 'staff':
				# code...
				include("staff.php");
				break;

			case 'editstaff':
				# code...
				include("staff_edit.php");
				break;
			
			case 'createsupervisors':
				# code...
				include("staff_supervisors.php");
				break;

			case 'assignstafftosupervisors':
				# code...
				include("staff_assign_supervisors.php");
				break;

			# assesment and test
			case 'assesmenttype':
				# code
				include("assesment_type.php");
				break;

			case 'assesmentkpi':
				# code
				include("assesment_kpi.php");
				break;

			case 'assesmentmeasure':
				# code
				include("assesment_category.php");
				break;

			case 'assesmentvaluesetup':
				# code
				include("assesment_value.php");
				break;

			case 'assesmentpersonality':
				# code
				include("assesment_personality.php");
				break;

			// # switch for measurement
			// case 'staffmeasurement':
			// 	# code
			// 	include("staffmeasurement.php");
			// 	break;

			case 'staffprofile':
				# code
				include("staffprofile.php");
				break;

			case 'resultdetails':
				# code
				include("staffresultdetails.php");
				break;

			case 'kpi':
				# code
				include("kpi.php");
				break;


			default:
				# code...
				break;
		}

		?>

	</section>
</div>

<?php
    // include footer
include("../footer.php");
?>