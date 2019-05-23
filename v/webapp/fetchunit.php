<?php
session_name("performance-org");
session_start();
include('functions.php');
// $dept = $_POST['dept'];
GetDeptUnit($connection, "", $_SESSION['id']);