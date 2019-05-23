<?php 
include('functions.php');

        // Register Users

if($_POST) 
{ 
    $name = $_POST['name'];
    $discrip = $_POST['discrip'];

    if (InsertCat($connection,$name,$discrip,$datetime) == true) 
    {
         header("Refresh:1; url=../organizationcategory");  
         echo "Registration Was Successful";
    }
    else{
        echo "Registration was Unsuccessful";
    }
}
else
{
    echo "Unsuccessful";
}

