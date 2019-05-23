<?php 
// Register Users
if($_POST) 
{ 
    include('functions.php');
    $name = $_POST['name'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $location = $_POST['location'];
    $linkperson = $_POST['linkperson'];
    $initiator = $_POST['project_initiator'];
    $executor = $_POST['project_executor'];
    $org = $_SESSION['id'] ;
     
    if (InsertProject($connection,$name,$description,$start_date,$end_date,$location,$linkperson,$initiator,$executor,$datetime,$org) == true) 
    {
        $_SESSION['flash'] = json_encode(['status' => "success", 'message' => "action successful"]);
        header("Refresh:0; url=../addproject");  
    }
    else
    {
        $_SESSION['flash'] = json_encode(['status' => "error", 'message' => "an error occured. try again"]);
        header("Refresh:0; url=../addproject");
    }
}
else
{
    $_SESSION['flash'] = json_encode(['status' => "error", 'message' => "an error occured. try again"]);
    header("Refresh:0; url=../addproject");
}