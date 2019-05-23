<?php 
session_name("performance-org");
session_start();
include('functions.php');
if ($_GET) {
	$id = $_GET['id'] ;

require("unitppt.php") ;
}

 ?>
 
<!DOCTYPE html>
<html lang="en">
<head>
	
	<!-- start: Meta -->
	<meta charset="utf-8">
	<title>Bootstrap Metro Dashboard by Dennis Ji for ARM demo</title>
	<meta name="description" content="Bootstrap Metro Dashboard">
	<meta name="author" content="Dennis Ji">
	<meta name="keyword" content="Metro, Metro UI, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
	<!-- end: Meta -->
	
	<!-- start: Mobile Specific -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- end: Mobile Specific -->
	
	<!-- start: CSS -->
	<link id="bootstrap-style" href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/bootstrap-responsive.min.css" rel="stylesheet">
	<link id="base-style" href="css/style.css" rel="stylesheet">
	<link id="base-style-responsive" href="css/style-responsive.css" rel="stylesheet">
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&subset=latin,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>
	<!-- end: CSS -->
	

	<!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	  	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<link id="ie-style" href="css/ie.css" rel="stylesheet">
	<![endif]-->
	
	<!--[if IE 9]>
		<link id="ie9style" href="css/ie9.css" rel="stylesheet">
	<![endif]-->
		
	<!-- start: Favicon -->
	<link rel="shortcut icon" href="img/favicon.ico">
	<!-- end: Favicon -->
	
		
		
		
</head>

<body>
		<!-- start: Header -->

		<?php require "navbar.php" ; ?> 

	<!-- start: Header -->
	
		<div class="container-fluid-full">
		<div class="row-fluid">
				
			<!-- start: Main Menu -->

			<?php require("sidebar.php"); ?>

			<!-- end: Main Menu -->
			
			<noscript>
				<div class="alert alert-block span10">
					<h4 class="alert-heading">Warning!</h4>
					<p>You need to have <a href="http://en.wikipedia.org/wiki/JavaScript" target="_blank">JavaScript</a> enabled to use this site.</p>
				</div>
			</noscript>
			
			<!-- start: Content -->
			<div id="content" class="span10">
			
			
			<ul class="breadcrumb">
				<li>
					<i class="icon-home"></i>
					<a href="./">Home</a> 
					<i class="icon-angle-right"></i>
				</li>
				<li><a href="./">Dashboard</a></li>
			</ul>

			<div class="container">





			<div class="row-fluid ">	
				<div class="box span6">
					<div class="box-header">
						<h2><i class="bullhorn"></i><span class="break"></span> <?php echo strtoupper($name); ?> </h2>
						<div class="box-icon">
							<a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>
							<a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
							<a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>
						</div>
					</div>

					<div class="box-content alerts">
				
                 <div class="container span10">


                 <div class="panel panel-default">
  <!-- Default panel contents -->
<!--   <div class="panel-heading">Panel heading</div> -->
  
  </div>
  <div class="panel-body">

  <!-- Table -->
  <table  class="table table-bordered bootstrap-datatable">
    <thead>
    	<tr>
    		<th></th>
    		<th></th>
    	</tr>
    </thead>
    <tbody>
    	<tr>
    		<td><b>Department: </b> </td>
    		<td><?php echo GetDeptId($connection,$dept); ?></td>
    	</tr>
    	<tr>
    		<td><b>Job Discription: </b> </td>
    		<td><?php echo "$discrip"; ?> </td>
    	</tr>
    	<tr>
    		<td> <b>No. of Staff: </b> </td>
    		<td> <?php echo GetNumStaffUnit($connection,$id) ?> </td>
    		
    	</tr>
    </tbody>
  </table>
 </div>

						</div>

					</div>
					
				</div><!--/span-->
			</div>
			<div class="row-fluid">	
				<div class="box span6">
					<div class="box-header">
						<h2><i class="halflings-icon tasks"></i><span class="break"></span>Performance  Bars</h2>
					</div>
					<div class="box-content">
						<ul class="skill-bar">
						
							
				            	<?php // echo GetUnitReport($connection,$id); ?>
				      	</ul>
					</div>	
				</div><!--/span-->
				
			</div><!--/row-->

			<div class="row-fluid ">	
				<div class="box span10 ">
					
					<div class="box-header">
						<h2><i class="bullhorn"></i><span class="break"></span> Daily Reports </h2>
						<div class="box-icon">
							<a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>
							<a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
							<a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>
						</div>
					</div>

					<div class="box-content alerts">
				
                 <div class="container span10">


                 <div class="panel panel-default">
  <!-- Default panel contents -->
<!--   <div class="panel-heading">Panel heading</div> -->
  
  </div>
  <div class="panel-body">

			<div class="row-fluid">
				<div class="box span12">
				<?php GetTestDates($connection,$id,$type = 'unit') ; ?>

				</div>

			</div>


			
  
 </div>

						</div>

					</div>
					
				</div><!--/span-->
			</div>
			<hr>
				<!-- <h1 id="type" class="big-header"></h1> -->
<div class="row-fluid ">	
				<div class="box span10 ">
					
					<div class="box-header">
						<h2><i class="bullhorn"></i><span class="break"></span>  Performance/Measurements Data </h2>
						<div class="box-icon">
							<a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>
							<a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
							<a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>
						</div>
					</div>

					<div class="box-content alerts">
				
                 <div class="container span10">


                 <div class="panel panel-default">
  <!-- Default panel contents -->
<!--   <div class="panel-heading">Panel heading</div> -->
  
  </div>
  <div class="panel-body">
						
						<div class="panel panel-default">
							  <!-- Default panel contents -->
							 <!--  <div class="panel-heading">Panel heading</div> -->

							  <!-- Table -->
							  <table class="table table-bordered bootstrap-datatable datatable">
							    <thead align="left">
							    	<tr>
							    		<th >Assesment Parameter</th>
							    		<th>Category</th>
							    		<th> score </th>
							    		<th>Date</th>
							    		<th>Remark</th>
							    		<th></th>
							    	</tr>
							    </thead>
							    <tbody>
							    	<?php  GetStaffAssId($connection,$id,$type='unit') ; ?>
							    </tbody>
							  </table>
						</div>

			</div><!--/row-->
						<div class='row-fluid'>
				<div class='box span12'>
				<?php GetStaffTestAll($connection,$id,$type='unit') ; ?>
				</div>

			</div>
			<hr/>


			</div>
				</div>
			   </div></div>    

	</div><!--/.fluid-container-->
	
			<!-- end: Content -->
		</div><!--/#content.span10-->
		</div><!--/fluid-row-->
		
	<div class="modal hide fade" id="myModal">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Settings</h3>
		</div>
		<div class="modal-body">
			<p>Here settings can be configured...</p>
		</div>
		<div class="modal-footer">
			<a href="#" class="btn" data-dismiss="modal">Close</a>
			<a href="#" class="btn btn-primary">Save changes</a>
		</div>
	</div>
	
	<div class="clearfix"></div>
	
	<footer>

		<p>
			<span style="text-align:left;float:left">&copy; 2013 <a href="http://jiji262.github.io/Bootstrap_Metro_Dashboard/" alt="Bootstrap_Metro_Dashboard">Bootstrap Metro Dashboard</a></span>
			
		</p>

	</footer>
	
	<!-- start: JavaScript-->

		<script src="js/jquery-1.9.1.min.js"></script>
	<script src="js/jquery-migrate-1.0.0.min.js"></script>
	
		<script src="js/jquery-ui-1.10.0.custom.min.js"></script>
	
		<script src="js/jquery.ui.touch-punch.js"></script>
	
		<script src="js/modernizr.js"></script>
	
		<script src="js/bootstrap.min.js"></script>
	
		<script src="js/jquery.cookie.js"></script>
	
		<script src='js/fullcalendar.min.js'></script>
	
		<script src='js/jquery.dataTables.min.js'></script>

		<script src="js/excanvas.js"></script>
	<script src="js/jquery.flot.js"></script>
	<script src="js/jquery.flot.pie.js"></script>
	<script src="js/jquery.flot.stack.js"></script>
	<script src="js/jquery.flot.resize.min.js"></script>
	
		<script src="js/jquery.chosen.min.js"></script>
	
		<script src="js/jquery.uniform.min.js"></script>
		
		<script src="js/jquery.cleditor.min.js"></script>
	
		<script src="js/jquery.noty.js"></script>
	
		<script src="js/jquery.elfinder.min.js"></script>
	
		<script src="js/jquery.raty.min.js"></script>
	
		<script src="js/jquery.iphone.toggle.js"></script>
	
		<script src="js/jquery.uploadify-3.1.min.js"></script>
	
		<script src="js/jquery.gritter.min.js"></script>
	
		<script src="js/jquery.imagesloaded.js"></script>
	
		<script src="js/jquery.masonry.min.js"></script>
	
		<script src="js/jquery.knob.modified.js"></script>
	
		<script src="js/jquery.sparkline.min.js"></script>
	
		<script src="js/counter.js"></script>
	
		<script src="js/retina.js"></script>

		<script src="js/custom.js"></script>
	<!-- end: JavaScript-->
	
</body>
</html>
