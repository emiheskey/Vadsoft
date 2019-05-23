<?php

if( !session_id() ) session_name("performance-org");
if( !session_id() ) @session_start();

if($_POST)
{
	// get username
	$name = $_POST['ad_name'];
	// get password
	$_SESSION['password'] = $_POST['ad_pass'];
	// define the email variable
	$email = ""; 
}
else
{
	// access denied
	echo "Access denied!";
	exit;
}
	

switch ($_GET['mode']) 
{
	case 'setup_login':
		# code...
		login_setup($name, $_SESSION['password'], $email);
		break;

	default:
		# code...
		login($name, $_SESSION['password'], $email);
		break;
}




// function to perform login
function login($name, $password, $email)
{
	if($name && $_SESSION['password'])
	{
		// include functions.php
		include("v/webapp/functions.php");
		// run sql query
		$sql = "SELECT * from organization_admin where user_email='$name'";

		$query = $connection ->prepare($sql);
		$query ->execute();
		$num = 	$query->rowCount();

		if($num !=0) 
		{
			$result = $query->setFetchMode(PDO::FETCH_ASSOC);
			while ($row = $query->fetch() ) 
			{
				$_SESSION['name'] = $row['user_fullname'];
				$pass = $row['user_pass'] ;
				$email = $row['user_email'];
				$_SESSION['id'] = $row['user_org'];
				$_SESSION['role'] = "app_user";
			}

			if($name==$email)
			{
				$time = time();

				$query = $connection ->query($sql) ;
				$fetch =  $query->fetch();
				$user_id = $fetch['user_id'];

				if($fetch['user_last_login'] < "1528179500"){
					if(md5($_SESSION['password']) == $pass)
					{
						$password = password_hash($_SESSION['password'], PASSWORD_DEFAULT);
						$query = $connection->query("UPDATE organization_admin SET  user_pass = '$password', user_last_login = '$time' WHERE user_id = '$user_id' ");
						echo "1";
					}else {
						echo "Invalid username/password. Retry";
					}
				}else{
					if(password_verify($_SESSION['password'], $pass))
					{
						$query = $connection->query("UPDATE organization_admin SET  user_last_login = '$time' WHERE user_id = '$user_id' ");
						echo "1";
					}else {
						echo "Invalid username/password. Retry";
					}
				}
			
			} 
			else 
			{	
				echo "Invalid username/password. Retry";	
			}
		
		} 
		else 
		{	
			echo "Invalid username/password. Retry";		
		}
		
	} 
	else 
	{	
		echo "You have to type a name and password!";
	}
}


// function handle login into setup
function login_setup($name, $password, $email)
{

	if($name && $_SESSION['password'])
	{
		// include functions.php
		include("v/webapp/functions.php");
		// run sql query
		$sql = "SELECT ad_name as name, ad_email as email, ad_pass as pass, ad_id as id, ad_last_login as last_login FROM app_admin WHERE ad_email = '$name'";
		$query = $connection ->prepare($sql) ;
		$query ->execute();
		$num = 	$query->rowCount();
		if($num !=0) 
		{
			$result = $query->setFetchMode(PDO::FETCH_ASSOC);
			while ($row = $query->fetch() ) {
				$_SESSION['name'] = $row['name'];
				$pass = $row['pass'];
				$email = $row['email'];
				$_SESSION['id'] = "0";
				$_SESSION['role'] = "app_admin";
			}
			if($name==$email)
			{
				$time = time();
				$datetime = date('Y-m-d h:i:s');

				$query = $connection ->query($sql) ;
				$fetch =  $query->fetch();
				$ad_id = $fetch['id'];

				if($fetch['last_login'] < "2018-06-02"){
					if(md5($_SESSION['password']) == $pass)
					{
						$password = password_hash($_SESSION['password'], PASSWORD_DEFAULT);
						$query = $connection->query("UPDATE app_admin SET  ad_pass = '$password', ad_last_login = '$datetime' WHERE ad_id = '$ad_id' ");
						echo "1";
					}else {
						echo "Invalid username/password. Retry";
					}
				}
				else{
					if(password_verify($_SESSION['password'], $pass))
					{
						$query = $connection->query("UPDATE app_admin SET  ad_last_login = '$datetime' WHERE ad_id = '$ad_id' ");
						echo "1";
					}else {
						echo "Invalid username/password. Retry";
					}
				}
			} 
			else 
			{
				echo "Invalid username/password. Retry";	
			}
		} 
		else 
		{
			echo "Invalid username/password. Retry";		
		}
	} 
	else 
	{	
		echo "You have to type a name and password!";
	}
}



// function to verify license code
function verify_code($license_code, $fullname, $username, $password)
{

	// run sql query to verify code
	$sql = "SELECT * organization where org_license_key='$license_code'";
	$query = $connection ->prepare($sql) ;
	$query ->execute();
	//fetch mail
	$num = 	$query->setFetchMode(PDO::FETCH_NUM);

	if($num !=0) 
	{
		$result = $query->setFetchMode(PDO::FETCH_ASSOC);
		while ($row = $query->fetch()) 
		{
			$user_org = $row['org_id'];
		}	

		$last_login = time();
		
		// hash password
		$password = password_hash($password, PASSWORD_DEFAULT);

		// setup the super admin user
		$sql_admin = "INSERT INTO app_admin set user_email='$username', user_pass='$password', user_fullname='$fullname', user_org = '$user_org'";
		$query_admin = $connection ->prepare($sql_admin) ;
		$query_admin->execute();
	}
}