<?php 

include('functions.php');

if($_POST) 
{
    if ($_GET['mode']=="create")
    {
        $supervisor = $_POST['supervisor'];

        // print_r($staff);
        foreach ($supervisor as $key => $value) 
        {
            // die(print_r($value)) ;
            // array_push($shiftarraycalc,$value);
            $date = time();
            
            $_SESSION['flash'] = InsertSupervisor($connection,$value,$_SESSION['id'],$date);
            header("Refresh:0; url=../createsupervisors");
        }
    }
    elseif ($_GET['mode']=="assign")
    {
        $type_value = $_POST['type_value'];
        $supervisor = $_POST['supervisor'];
        $type = $_POST['type'];

        // print_r($staff);
        foreach ($type_value as $key => $value) 
        {
            if(in_array($supervisor, $type_value)){
                $_SESSION['flash'] = json_encode(['status' => "error", 'message' => "Cannot assign same supervisor and staff"]);
                header("Refresh:0; url=../assignstafftosupervisors?mode=$type");
                return false;
            }
            // die(print_r($value)) ;
            // array_push($shiftarraycalc,$value);
            $date = time();

            $_SESSION['flash'] = AssignSupervisor($connection,$type,$value,$supervisor,$date);
            header("Refresh:0; url=../assignstafftosupervisors?mode=$type");
        }
    }

}
else
{
    echo "Unsuccessful";
}