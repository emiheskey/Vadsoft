<?php 
        // Register Users

if($_POST) 
{ 
    include('functions.php');
    $name = $_POST['name'];
    $discrip = $_POST['discrip'];
    $org = $_SESSION['id'] ;
     

    $_SESSION['flash'] = InsertDept($connection,$name,$discrip,$datetime,$org);
    header("Refresh:0; url=../department");
}
else
{
    echo "Unsuccessful";
}