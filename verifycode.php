<?php
session_name("performance-org");
session_start();

if($_POST)
{
	// get license code
	$license_code = $_POST['code'];
	// get username
	$fullname = $_POST['fullname'];
	// get password
	$setup_pass_word = $_POST['password'];
	// define the email variable
	$setup_user_name = $_POST['username'];
	// get the organisation id
	$org_id = $_POST['orgid'];
}
else
{
	// access denied
	echo "Access denied!";
	exit;
}


switch ($_GET['mode']) {
	case 'setup_user':
	    setup_user($license_code, $fullname, $setup_user_name, md5($setup_pass_word), $org_id);
		break;
	
	default:
		break;
}



// function to verify license code
function setup_user($license_code, $fullname, $setup_user_name, $setup_pass_word, $org_id)
{
	// include functions.php
	include("v/webapp/functions.php");
	// run sql query to verify code
	$sql = "SELECT * FROM organization WHERE org_license_key=:license_code and org_id=:org_id";
	$query = $connection->prepare($sql) ;
	$query->execute(array(':license_code'=>$license_code, ':org_id'=>$org_id));
	//fetch mail
	$num = 	$query->rowCount();

	if($num !=0 ) 
	{
		$result = $query->setFetchMode(PDO::FETCH_ASSOC);
		while ($row = $query->fetch()) 
		{
			$org_id = $row['org_id'];

			$license_status = $row['org_license_status'];

			if ($license_status == "1")
			{
				echo "Code do not match record. It may be invalid or have been used. Please contact the vendor";	
			}
			else
			{
				$user_last_login = time();
				// setup the super admin user
				$sql_admin = "INSERT INTO organization_admin set user_email='$setup_user_name', user_pass='$setup_pass_word', user_fullname='$fullname', user_org = '$org_id', user_last_login='$user_last_login'";
				$query_admin = $connection ->prepare($sql_admin);
				$query_admin->execute();

				// setup the super admin user
				$sql_update = "UPDATE organization SET org_license_status='1' WHERE org_id = '$org_id'";
				$query_update = $connection ->prepare($sql_update);
				$query_update->execute();

				$_SESSION['name'] = $fullname;
				$pass = $setup_pass_word;
				$email = $setup_user_name;
				$_SESSION['id'] = $org_id;
				$_SESSION['role'] = "app_user";
				echo "1";
			}

		}
	}
	else
	{
		echo "Code do not match record. It may be invalid or have been used. Please contact the vendor";
	}
}