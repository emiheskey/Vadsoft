<?php 
session_name("performance-org");
session_start();
// Register Users
if($_POST) 
{ 
    include('functions.php');
    $fname          = $_POST['fname'];
    $lname          = $_POST['lname'];
    $dept           = $_POST['dept'];
    $linkperson     = $_POST['linkperson'];
    // $qualification = $_POST['qualification'];
    // $add = $_POST['add']; 
    $sex            = $_POST['sex'];
    $email          = $_POST['email'];
    $phone          = $_POST['phone'];
    $snumber        = $_POST['snumber'];
    // location of assignment
    $state          = $_POST['state']; 
    $city           = $_POST['city'];
    $org            = $_SESSION['id'];
    // new fields
    $grade_level    = $_POST['grade_level'];
    $job_title      = $_POST['job_title'];

    $staff_id       = base64_decode($_POST['staff_id']);

    $edit_staff = EditStaff($connection,$fname,$lname,$dept,$sex,$phone,$email,$org,$datetime,$snumber,$state,$city,$grade_level,$job_title,$staff_id);
     
    if ($edit_staff > 0) 
    {

        header("Refresh:0; url=../editstaff?name=".base64_encode($staff_id));  
        $_SESSION['flash'] = json_encode(['status' => "success", 'message' => "staff update successful"]);
    }
    else
    {
        header("Refresh:0; url=../staff");  
        $_SESSION['flash'] = json_encode(['status' => "error", 'message' => "an error occured. try again"]);
    }
}
else
{
    header("Refresh:1; url=../staff"); 
    $_SESSION['flash'] = json_encode(['status' => "error", 'message' => "an error occured. try again"]);
}