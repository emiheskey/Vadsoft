<?php
require("functions.php"); 

if($_POST)
{
    echo  GetAssParamTypeDetailId($connection, $_POST['Id']);
}
?>