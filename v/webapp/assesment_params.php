<?php 

// include the function page
include('functions.php');
if (file_exists($_SERVER['DOCUMENT_ROOT']."/config/MailSettings.php")) {
    include($_SERVER['DOCUMENT_ROOT']."/config/MailSettings.php");
} else {
    $path = substr(__DIR__, 0, -9);
    include($path."/config/MailSettings.php");
}

// Register Users
if($_POST) 
{
    // get the action
    $action = $_GET['action'] ;

    // swiych actions using the if..else if logic
    if ($action == "asstyp") 
    {
        $name = $_POST['name'];
        $discrip = $_POST['discrip'];
        $cat = $_POST['cat'];
        $level = $_POST['org_level']; 
     
        if (InsertParamType($connection,$name,$discrip,$cat,$datetime,$level,$_SESSION['id']) == true) 
        {
            $_SESSION['flash'] = json_encode(['status' => "success", 'message' => "action successful"]);
            header("Refresh:0; url=../assesmenttype");  
        } 
        else 
        {
            $_SESSION['flash'] = json_encode(['status' => "error", 'message' => "an error occured. try again"]);
            header("Refresh:0; url=../assesmenttype");  
        }
    }

    elseif($action == "asstypEdit") {
     
        $id = $_POST['id'];
        $name = $_POST['name'];
        $discrip = $_POST['discrip'];
        $cat = $_POST['cat'];
        $level = $_POST['org_level']; 
     
        if (EditParamType($connection,$id,$name,$discrip,$cat,$datetime,$level,$_SESSION['id']) == true) 
        {
            $_SESSION['flash'] = json_encode(['status' => "success", 'message' => "action successful"]);
            header("Refresh:0; url=../assesmenttype");  
        } 
        else 
        {
            $_SESSION['flash'] = json_encode(['status' => "error", 'message' => "an error occured. try again"]);
            header("Refresh:0; url=../assesmenttype");  
        }
    }
    
    elseif ($action == "paramcat") 
    {
        $name = $_POST['name'];
        $discrip = $_POST['discrip'];
        $cat = $_POST['type'];
        $typ = $_POST['typ'];
        $typss = $_POST['typss'];
        $kpi = $_POST['kpi'];
        // die($typ);
        $count = 0;
        // find the org_grp
        $typss = getAssTypeGroup($connection, $cat);

        if(isset($kpi)) 
        {  
            // Check if selections were made
            // print_r($staff);
            foreach ($kpi as $key => $value) 
            {

                if (InsertAssParamCat($connection,$name,$discrip,$cat,$datetime,$typss,$value,$_SESSION['id']) == true) 
                {
                    $count = $count + 1;
                }
                else
                {
                    $count = 0;
                }

            }
        }

        if ($count>0)
        {
            $_SESSION['flash'] = json_encode(['status' => "success", 'message' => "action successful"]);
            header("Refresh:0; url=../assesmentmeasure");  
        } 
        else
        {
            $_SESSION['flash'] = json_encode(['status' => "error", 'message' => "an error occured. try again"]);
            header("Refresh:0; url=../assesmentmeasure");  
        }
    }
    

    elseif ($action == "params") 
    {
        if (isset($_POST['dept'])) 
        {
            $dept = $_POST['dept']; $unit = ""; $staff = ""; $project = ""; $mode = 6;
            // get staff id
            $staff_id = GetLinkPersonDept($connection, $dept);
            // get department name
            $dept_name = GetDeptName($connection, $dept);
            // type
            $type = "your department " . $dept_name;
        }
        elseif(isset($_POST['unit']))
        {
            $unit = $_POST['unit']; $dept = ""; $staff = ""; $project = ""; $mode = 4;
            // get link person staff id
            $staff_id = GetLinkPersonUnit($connection, $unit);
            // get the unit name
            $unit_name = GetUnitName($connection, $unit);
            // type
            // echo "i am unit";
            $type = "your unit " . $unit_name;
        }
        elseif(isset($_POST['staff']))
        {
            $staff = $_POST['staff']; $dept = ""; $unit = ""; $project = ""; $mode = 3;
            // staff id
            $staff_id = $staff;
            // type
            $type = "you";
        }
        elseif(isset($_POST['project']))
        {
            $project = $_POST['project']; $dept = ""; $unit = ""; $staff = "";  $mode = 5;
            // get link person staff id
            $staff_id = GetLinkPersonProject($connection, $project);
            // get the unit name
            $project_name = GetProjectName($connection, $project);
            // type
            $type = "your project " . $project_name;
        }

        $name = $_POST['name'];
        $discrip = $_POST['discrip'];
        $cat = $_POST['type'];
        $form = $_POST['formtyp'];
        $value = $_POST['value'];
        $kpi = $_POST['kpi'];
        $typ = $_POST['typ'];
        $lv = $_POST['orglv'];
        $cattype = $_POST['cattype'];
             
        if (InsertAssParam($connection,$name,$discrip,$cat,$datetime,$form,$value,$kpi,$dept,$unit,$staff,$project,$typ,$lv,'',$cattype,$_SESSION['id']) == true) 
        {
            $kpi_measure = GetKPIId($connection, $kpi);
            // send email to personeel
            // notifying him/her that assesment parameters have been logged
            // and also when to expect the first measurement link
            // get email of receiver
            // find staff email
            $staff_email = GetStaffEmail($connection,$staff_id);
            // find staff name
            $staff_name = GetStaffName($connection,$staff_id);

            // format link
            // $measure_link = "http://app.vadsoft.com.ng/measurement?name=$staff_id&m_staff=listkpimeasurecategory&kid=$kpi_id;";
            // format email body | subject
            $message = "Hello $staff_name, an assesment template has been generated for $type. It is to measure your Value Added on the Key Performance Index for $kpi_measure\n\nRegards";

            // send email to user
            SendMail($staff_email, "", "VADSoft Assesment Template", $message);

            
            $_SESSION['flash'] = json_encode(['status' => "success", 'message' => "action successful"]);
            header("Refresh:0; url=../assesmentvaluesetup?unit=$mode");  
        }
        else
        {
            
            $_SESSION['flash'] = json_encode(['status' => "error", 'message' => "an error occured. try again"]);
            header("Refresh:0; url=../assesmentvaluesetup?unit=$mode");  
        }
    }

    elseif ($action == "kpi") 
    {

        $name = $_POST['name'];
        $discrip = $_POST['discrip'];
        $routine = $_POST['routine'];
     
        if (InsertKPI($connection,$name,$discrip,$routine,$datetime,$_SESSION['id']) == true) 
        {
            // automatically send link to staff
            // do this as a cron script on its own
             
            $_SESSION['flash'] = json_encode(['status' => "success", 'message' => "action successful"]);
            header("Refresh:0; url=../assesmentkpi"); 
        } 
        else 
        {
            $_SESSION['flash'] = json_encode(['status' => "error", 'message' => "an error occured. try again"]);
            header("Refresh:0; url=../assesmentkpi"); 
        }
    }
}
else
{
    echo "Access Denied";
}