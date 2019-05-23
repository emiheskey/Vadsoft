<!DOCTYPE html>
<html lang="en">
<head>

    <!-- start: Meta -->
    <meta charset="utf-8">
    <title>Login</title>
    <!-- end: Meta -->
    <!-- start: Mobile Specific -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- end: Mobile Specific -->
    <!-- start: CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Roboto:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&subset=latin,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- end: CSS -->

    <!-- start: Favicon -->
    <link rel="shortcut icon" href="img/favicon.ico">
    <!-- end: Favicon -->
</head>

<body>
<header class="header">
<div class="container">
    <div class="row">
        <div class="col-sm-6 col-md-4 col-md-offset-4">
            <div class="account-wall">

				<form class="form-signin" name="frm_setup_login" id="frm_setup_login" action="login.php" method="">
					<h2>Login</h2>
                    <!-- Error Alert -->
                    <div id="return_status_msg" class="alert alert-error"></div>

               		<input type="text" name="ad_name" id="username" class="form-control" placeholder="Email Address" required autofocus>
               		<input type="password" name="ad_pass" id="password" class="form-control" placeholder="Password" required>
               		<button class="btn btn-lg btn-default btn-block" id="btn_setup_login">Sign In</button>
					<br />
					<label class="text-left remember" for="remember"><input type="checkbox" id="remember" /> Remember me</label>
					<input type='hidden' value='setup' name='setup'id='setup' />
               	</form>

            </div>
        </div>
    </div>
</div>
</header>

 <!-- Placed at the end of the document so the pages load faster -->
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/validator.min.js"></script>
<script src="js/script.js"></script>
</body>
</html>
