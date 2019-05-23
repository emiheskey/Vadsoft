<?php 
session_name("performance-org");
session_start();
// Register Users

if($_POST) 
{ 
    include('functions.php');
    $name = $_POST['name'];
    $discrip = $_POST['discrip'];
    $dept = $_POST['dept'] ;
    $org = $_SESSION['id'] ;
     
    $_SESSION['flash'] = InsertUnit($connection,$name,$discrip,$datetime,$dept,$org);
    header("Refresh:0; url=../unit");  
 }
 else
 {
    echo "Unsuccessful";
 }

