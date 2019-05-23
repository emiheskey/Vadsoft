<!DOCTYPE html>
<html lang="en">
<head>
    <!-- start: Meta -->
    <meta charset="utf-8">
    <title>Setup Organization</title>
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
        <div class="col-sm-6 col-md-6 col-md-offset-3">
            <div class="account-wall">

                <h2>Set up</h2>
                    <?php 

                        // get org details
                        if($_GET)
                        {
                            $org_details = base64_decode($_GET['org_details']);
                            // seperate the name from the id
                            $org_details = explode("**", $org_details);
                            // fetch teh organisation name
                            $org_name = $org_details[0];
                            // fetch the id
                            $org_id = $org_details[1];
                        }
                        else
                        {
                            // echo an error message and exit
                            echo "Bad request. Check and retry";
                            die();
                        }
                    ?>
				
				<form class="form-signin" name="frm_setup_org" id="frm_setup_org" action="" method="">


                    <input type="text" name="orgname" id="orgname" class="form-control" disabled="disabled" value="<?php echo $org_name ?>""><br><br>

                    <input type="hidden" name="orgid" id="orgid" value="<?php echo $org_id ?>"">

                    <input type="text" name="code" id="code" class="form-control" placeholder="Product Code" required="required">
                    <em>Enter code that was sent to you via SMS</em><br><br>
                    <input type="text" name="fullname" id="fullname" class="form-control" placeholder="Fullname" required autofocus><em>The name of the principal admin contact not the organization name</em><br><br>

               		<input type="text" name="username" id="username" class="form-control" placeholder="Admin email address" required>
                    <em>Your email is the username you will use to login to the application</em>
                    <hr />
               		<input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm Password" required>

                    <div id="return_status_msg"></div>
                    <button class="btn btn-lg btn-default btn-block" id="btn_setup">Setup</button>
                    <br />
					<input type='hidden' value='user_setup' name='user_setup' id='user_setup' />
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

