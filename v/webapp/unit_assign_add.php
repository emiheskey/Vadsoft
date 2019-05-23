<?php 
include('functions.php');

if($_POST) 
{ 
    $staff = $_POST['assign_unit_staff'];
    $unit = $_POST['unit'];
    $unit_org  =$_POST['unit_org'];
    $linkperson = $_POST['linkperson'];

    // first of count staff selected
    $staff_count = count($staff);

    if ($staff_count > 1)
    {
        // check if link person is yes
        // if ($linkperson == "yes")
        // {
        //     // throw an error message
        //     echo "Operation aborted. Check and retry";
        //     exit();
        // }
        // else
        // {
            $assign_unit_now = true;
        // }
    }
    else
    {
        $assign_unit_now = true;
    }


    // save record
    if ($assign_unit_now == true)
    {
        if($linkperson == "yes")
        {
            $linkperson = 1;
        }
        
        foreach ($staff as $key => $value) 
        {

            $_SESSION['flash'] = AssignUnit($connection,$value,$unit,$linkperson, $unit_org,$datetime);
            header("Refresh:0; url=../assignunit"); 
        }

    }

}
else
{
    echo "Unsuccessful";
}