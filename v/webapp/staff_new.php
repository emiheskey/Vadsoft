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

    $new_staff = InsertStaff($connection,$fname,$lname,$dept,$sex,$phone,$email,$org,$datetime,$snumber,$state,$city,$grade_level,$job_title);
     
    if ($new_staff > 0) 
    {
        // update department by adding link person
        // retrieve the last user id entered to db
        if ($linkperson == 1)
        {
            AddLinkPersonToDepartment($connection, $new_staff, $dept, $org);
        }

        header("Refresh:0; url=../staff");  
        $_SESSION['flash'] = json_encode(['status' => "success", 'message' => "registration successful"]);
    }
    else
    {
        header("Refresh:0; url=../staff"); 
        $_SESSION['flash'] = json_encode(['status' => "error", 'message' => "an error occured. try again"]);
    }

}
else
{
    header("Refresh:0; url=../staff"); 
    $_SESSION['flash'] = json_encode(['status' => "error", 'message' => "an error occured. try again"]);
}