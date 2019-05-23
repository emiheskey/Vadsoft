<?php 

if (file_exists($_SERVER['DOCUMENT_ROOT']."/config/dbconfig.php")) {
    include($_SERVER['DOCUMENT_ROOT']."/config/dbconfig.php");
} else {
    $path = substr(__DIR__, 0, -9);
    include($path."/config/dbconfig.php");
}

if( !session_id() ) session_name("performance-org");
if( !session_id() ) @session_start();


// Function log email queue
function queueEmail($connection, $staff_email, $staff_name, $title, $message, $status, $queued_at){

    $query = $connection->query("INSERT INTO email_queue SET  
    staff_email = '$staff_email',
    staff_name = '$staff_name',
    title = '$title',
    message = '$message',
    status = '$status',
    queued_at = '$queued_at'
    ");

    if($query) {
        return true;
    }else {
        return false;
    }
}

function queryEmailQueue($connection, $staff_email, $message) {

    $sql = "SELECT *  FROM email_queue WHERE staff_email='$staff_email' and message = '$message' and status = 0";
    $query = $connection ->query($sql) ;
    $num =  $query->rowCount();
    return $num;
    
}

function queryLastUserQueue($connection, $staff_email) {
    $sql = "SELECT *  FROM email_queue WHERE staff_email='$staff_email' and status = 0";
    $query = $connection ->query($sql);
    $num =  $query->rowCount();

    if($num > 0) {
        return $query->fetchAll();
    }
    return [];
    // if($num > 0) {
    //     $query = $query->fetchAll();
    //     return $query[$num];
    // }else{
    //     return [];
    // }

}

function pendingEmailQueues($connection) {
    $sql = "SELECT *  FROM email_queue where status = '0'";

    // query
    $query = $connection ->query($sql) ;
    // execute
    $num =  $query->execute();
    
    if($num !=0) 
    {
        // return $query->setFetchMode(PDO::FETCH_ASSOC);
        return $query->fetchAll();
    }
    else
    {
        return [];
    }
}

function updateQueue($connection, $id, $status, $sent_at) {

    $query = $connection->query("UPDATE email_queue SET  status = '$status', sent_at = '$sent_at' WHERE id = '$id' ");
    
    if ($query) {
        return true;
    } else {
        return false;
    }
}

function updateFailedQueue($connection, $id, $reason) {
    $query = $connection->query("UPDATE email_queue SET  status = '0', failure_reason = '$reason' WHERE id = '$id' ");
}
// Insert Organization 

function InsertOrg($connection,$name,$ad_name,$email,$phone,$add,$location,$cat,$datetime,$license_key)
{
    $query = $connection->query("INSERT INTO organization SET  org_name = '$name', org_admin = '$ad_name', org_phone = '$phone', org_email = '$email', org_category = '$cat', registered_at = '$datetime', org_address = '$add', org_location = '$location', org_license_key = '$license_key'");
    if (!$query) {
        return false;
    } else {
        return $connection->lastInsertId();
    }
}


function UpdateOrg($connection,$name,$ad_name,$email,$phone,$add,$location,$cat,$datetime,$org_id)
{
    $query = $connection->query("UPDATE organization SET  org_name = '$name', org_admin = '$ad_name', org_phone = '$phone', org_email = '$email', org_category = '$cat', registered_at = '$datetime', org_address = '$add', org_location = '$location' WHERE org_id = '$org_id' ");
    
    if (!$query) {
        return json_encode(['status' => "error", 'message' => "an error occured. Try again"]);
    } else {
        return json_encode(['status' => "success", 'message' => "update successful"]);
    }
}


// Insert Organization Category
function InsertCat($connection,$name,$discrip,$datetime)
{
    $query = $connection->query("INSERT INTO organization_category SET  cat_name = '$name', cat_discrip = '$discrip',registered_at = '$datetime' ");
    if (!$query) {
        // header("Refresh:1; url=org-category.php");
        // die("Unsuccessful") ;
        return false;
    } else {
        return true;
    }
}


// Insert KPI
function InsertKPI($connection,$name,$discrip,$routine,$datetime,$org_id)
{
    $query = $connection->query("INSERT INTO kpi SET  kpi_name = '$name', kpi_discrip = '$discrip', kpi_routine = '$routine', registered_at = '$datetime', org_id = '$org_id', lasttimelinksent = '$datetime' ");
    if (!$query) {
        // header("Refresh:1; url=test-params.php");
        // die("Unsuccessful") ;
        return false;
    }
    else
    {
        return true;
    }
}

// Function to grt KPI 
function GetKPI($connection, $kpi_id, $org_id)
{
    if ($kpi_id != "") {
        $WHERE = "WHERE kpi_id = '$kpi_id' and org_id = '$org_id'";
    } else {
        $WHERE = "WHERE org_id = '$org_id'";
    }

    // assign query
    $sql = "SELECT * FROM kpi ". $WHERE;
    // query
    $query = $connection ->query($sql) ;
    // execute
    $num =  $query->execute();
    
    if($num !=0) 
    {
        // return $query->setFetchMode(PDO::FETCH_ASSOC);
        return $query->fetchAll();
    }
    else
    {
        return false;
    }
}

function GetKpiViaCategory($connection, $kpi_id, $org_id) {
    if ($kpi_id != "") {
        $WHERE = "WHERE aspcat_kpi = '$kpi_id' and org_id = '$org_id'";
    } else {
        $WHERE = "WHERE org_id = '$org_id'";
    }

    // assign query
    $sql = "SELECT * FROM assessment_params_category ". $WHERE;
    // query
    $query = $connection ->query($sql) ;
    // execute
    $num =  $query->execute();
    
    if($num !=0) 
    {
        // return $query->setFetchMode(PDO::FETCH_ASSOC);
        return $query->fetchAll();
    }
    else
    {
        return [];
    }
}

function GetKpiViaCategoryy($connection, $kpi_id, $org_id, $ass_typ){

    $sql = "SELECT * FROM assessment_params_category WHERE aspcat_kpi = '$kpi_id' and org_id = '$org_id' and ass_typ = '$ass_typ' ";
    // query
    $query = $connection ->query($sql) ;
    // execute
    $num =  $query->execute();
    
    if($num !=0) 
    {
        // return $query->setFetchMode(PDO::FETCH_ASSOC);
        return $query->fetchAll();
    }
    else
    {
        return [];
    }
}

function GetAssessmentParameter($connection, $type, $type_id, $asp_cat_id) {
    if($type == 'staff-ass') {
        $sql = "SELECT * FROM assessment_params WHERE asp_cat='$asp_cat_id' and asp_staff='$type_id'  ";
    }elseif($type == 'unit') {
        $sql = "SELECT * FROM assessment_params WHERE asp_cat='$asp_cat_id' and asp_unit='$type_id'  ";
    }elseif($type == 'punit') {
        $sql = "SELECT * FROM assessment_params WHERE asp_cat='$asp_cat_id' and asp_uni='$type_id'  ";
    }elseif($type == 'department') {
        $sql = "SELECT * FROM assessment_params WHERE asp_cat='$asp_cat_id' and asp_dept='$type_id'  ";
    }elseif($type == 'project') {
        $sql = "SELECT * FROM assessment_params WHERE asp_cat='$asp_cat_id' and asp_project='$type_id'  ";
    }

    // query
    $query = $connection->query($sql);
    // execute
    $num =  $query->execute();
    
    if($num !=0) 
    {
        // return $query->setFetchMode(PDO::FETCH_ASSOC);
        return $query->fetchAll();
    }
    else
    {
        return false;
    }
}

// Function to get KPI attached to staff, $astyp_id)
function GetKPIForStaff($connection,$staff_id = null , $astyp_id, $kpi_id = null){ 
    // weather personal or in a unit
    if ($staff_id != null)

        if($kpi_id){
            $sql = "SELECT *  FROM assessment_params WHERE asp_astyp='$astyp_id' and asp_staff = '$staff_id' and asp_kpi = '$kpi_id' ";
        }else{
            $sql = "SELECT *  FROM assessment_params WHERE asp_astyp='$astyp_id' and asp_staff = '$staff_id' group by asp_kpi";
        }
        
        // assign query
    else
        // assign query

        if($kpi_id){
            $sql = "SELECT *  FROM assessment_params WHERE asp_astyp='$astyp_id' and asp_kpi = '$kpi_id' ";
        }else{
            $sql = "SELECT * FROM assessment_params WHERE asp_astyp='$astyp_id' group by asp_kpi";
        }

    // query
    $query = $connection->query($sql);
    // execute
    $num =  $query->execute();
    
    if($num !=0) 
    {
        // return $query->setFetchMode(PDO::FETCH_ASSOC);
        return $query->fetchAll();
    }
    else
    {
        return false;
    }
}


// Function to get KPI attached to staff, $astyp_id)
function GetKPIForDept($connection,$dept_id,$astyp_id, $kpi_id = null){ 
    // weather personal or in a unit
    if ($dept_id)
        $sql = "SELECT * FROM assessment_params WHERE asp_astyp='$astyp_id' and asp_dept = '$dept_id' and asp_kpi = '$kpi_id' GROUP BY asp_kpi";
        // assign query
    else
        // assign query
        $sql = "SELECT * FROM assessment_params WHERE asp_astyp='$astyp_id' and asp_kpi = '$kpi_id' GROUP BY asp_kpi";

    // query
    $query = $connection->query($sql);
    // execute
    $num =  $query->execute();
    
    if($num !=0) 
    {
        // return $query->setFetchMode(PDO::FETCH_ASSOC);
        return $query->fetchAll();
    }
    else
    {
        return false;
    }
}


// Function to get KPI attached to unit)
function GetKPIForUnit($connection,$unit_id,$astyp_id, $kpi_id = null){ 
    // weather personal or in a unit
    if ($unit_id)
        $sql = "SELECT * FROM assessment_params WHERE asp_astyp='$astyp_id' and asp_unit = '$unit_id' and asp_kpi = '$kpi_id'  group by asp_kpi";
        // assign query
    else
        // assign query
        $sql = "SELECT * FROM assessment_params WHERE asp_astyp='$astyp_id' and asp_kpi = '$kpi_id' group by asp_kpi";

    // query
    $query = $connection->query($sql);
    // execute
    $num =  $query->execute();
    
    if($num !=0) 
    {
        // return $query->setFetchMode(PDO::FETCH_ASSOC);
        return $query->fetchAll();
    }
    else
    {
        return false;
    }
}


// function GetKPIForStaff_Unit_Dept($connection,$staff_id, $unit_id, $dept, $astyp_id, $kpi_id = null) {

// // For Staff
// if ($staff_id != null){

//     if($kpi_id){
//         $sql_staff = "SELECT *  FROM assessment_params WHERE asp_astyp='$astyp_id' and asp_staff = '$staff_id' and asp_kpi = '$kpi_id' ";
//     }else{
//         $sql_staff = "SELECT *  FROM assessment_params WHERE asp_astyp='$astyp_id' and asp_staff = '$staff_id' group by asp_kpi";
//     }
    
//     // assign query
// }else {
//     if($kpi_id){
//         $sql = "SELECT *  FROM assessment_params WHERE asp_astyp='$astyp_id' and asp_kpi = '$kpi_id' ";
//     }else{
//         $sql = "SELECT * FROM assessment_params WHERE asp_astyp='$astyp_id' group by asp_kpi";
//     }
// }

// // For Unit
// if ($unit_id != null) {
//     if($kpi_id){
//         $sql_staff = "SELECT *  FROM assessment_params WHERE asp_astyp='$astyp_id' and asp_unit = '$unit_id' and asp_kpi = '$kpi_id' ";
//     }else{
//         $sql_staff = "SELECT *  FROM assessment_params WHERE asp_astyp='$astyp_id' and asp_unit = '$unit_id' group by asp_kpi";
//     }
// }else {
//     if($kpi_id){
//         $sql = "SELECT *  FROM assessment_params WHERE asp_astyp='$astyp_id' and asp_kpi = '$kpi_id' ";
//     }else{
//         $sql = "SELECT * FROM assessment_params WHERE asp_astyp='$astyp_id' group by asp_kpi";
//     }
// }

// // For Dept
// if ($dept_id != null) {
//     $sql_dept = "SELECT * FROM assessment_params WHERE asp_astyp='$astyp_id' and asp_dept = '$dept_id' GROUP BY asp_kpi";
//     // assign query
// }

// }


// to get all staff under a KPI
function GetStaffInKPI($connection,$kpi_id)
{ 
    // weather personal or in a unit
    $sql = "SELECT asp_staff FROM assessment_params WHERE asp_kpi='$kpi_id' GROUP BY asp_staff";
    // query
    $query = $connection->query($sql);
    // execute
    $num =  $query->execute();
    
    if($num !=0) 
    {
        // return $query->setFetchMode(PDO::FETCH_ASSOC);
        return $query->fetchAll();
    }
    else
    {
        return false;
    }
}

// get sassesment org grp
function getAssTypeGroup($connection, $cat)
{
    $sql = "SELECT * FROM assessment_type where astyp_id='$cat' ";
    $query = $connection ->query($sql) ;
    $num =  $query->setFetchMode(PDO::FETCH_NUM);
    
    if($num !=0) {
        $result = $query->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $query->fetch() ) {
            return $row['astyp_org_level'] ;
        }
    }
}

// Insert Organization Department
function InsertDept($connection,$name,$discrip,$datetime,$org)
{
    $sql = "SELECT * FROM department WHERE dept_name = '$name' and dept_org = '$org' ";
    $query = $connection ->query($sql) ;
    $num =  $query->fetchColumn();
    if($num > 0)
    {
        return json_encode(['status' => "warning", 'message' => "department already registered"]);
    }else{
        $query = $connection->query("INSERT INTO department SET  dept_name = '$name', linkperson = '0' ,dept_discrip = '$discrip',dept_org = '$org',registered_at = '$datetime' ");
        if (!$query) 
        {
            return json_encode(['status' => "error", 'message' => "an error occured. Try again"]);
        }
        else
        {
            return json_encode(['status' => "success", 'message' => "department added successfully"]);
        }
    }
}

// Insert Organization Project
function InsertProject($connection,$name,$description,$start_date,$end_date,$location,$linkperson,$initiator,$executor,$datetime,$org)
{
    $query = $connection->query("INSERT INTO project SET project_title = '$name', project_description = '$description', project_org = '$org', registered_at = '$datetime', project_start_date = '$start_date', project_end_date = '$end_date', project_location = '$location', project_linkperson = '$linkperson', project_initiator = '$initiator', project_executor = '$executor'");
    if (!$query) 
    {
        // header("Refresh:1; url=department.php");
        // die("Unsuccessful") ;
        return false;
    }
    else
    {
        return true;
    }
}

// Insert Organization Unit
function InsertUnit($connection,$name,$discrip,$datetime,$dept, $org)
{
    $query = $connection->query("INSERT INTO unit SET  uni_name = '$name',uni_discrip = '$discrip',uni_dept = '$dept',uni_org = '$org',registered_at = '$datetime', org_id = '0' ");
    if (!$query) 
    {
        return json_encode(['status' => "error", 'message' => "an error occured. Try again"]);
    }
    else
    {
        return json_encode(['status' => "success", 'message' => "Unit added successfully"]);
    }

}


// Insert Assessment Types
function InsertParamType($connection,$name,$discrip,$cat,$datetime,$level,$org_id)
{
    $query = $connection->query("INSERT INTO assessment_type SET astyp_name = '$name', astyp_discrip = '$discrip', astyp_orgcat = '$cat', astyp_org_level = '$level', registered_at = '$datetime', org_id = '$org_id', astyp_formtyp = '0' ");
    if (!$query) 
    {
        // header("Refresh:1; url=test-params.php");
        // die("Unsuccessful") ;
        return false;
    }
    else
    {
        return true;
    }

}

// Edit Assessment Types
function EditParamType($connection, $id, $name,$discrip,$cat,$datetime,$level,$org_id)
{
    $query = $connection->query("UPDATE assessment_type SET astyp_name = '$name', astyp_discrip = '$discrip', astyp_orgcat = '$cat', astyp_org_level = '$level'  WHERE astyp_id = '$id'");

    if (!$query) 
    {

        return false;
    }
    else
    {
        return true;
    }

}


// Insert Assessment Parameter Category
function InsertAssParamCat($connection,$name,$discrip,$cat,$datetime,$typ,$kpi,$org_id)
{
    $query = $connection->query( "INSERT INTO assessment_params_category SET  
        aspcat_name = '$name',
        aspcat_discrip = '$discrip',
        ass_typ = '$cat',
        org_grp = '$typ',
        registered_at = '$datetime',
        aspcat_kpi = '$kpi',
        org_id = '$org_id',
        aspcat_status = '0'"
    );
    
    if (!$query) 
    {
        // header("Refresh:1; url=test-params.php");
        // die("Unsuccessful");
        return false;
    }
    else
    {
        return true;
    }

}


// Insert Assessment Personality Ass Params
function InsertPersonality($connection,$name,$discrip,$datetime)
{
    $slug = CreateSlug($name);
    $query = $connection->query("INSERT INTO personality SET  pname = '$name', discrip = '$discrip',slug = '$slug',registered_at = '$datetime' ");
    if (!$query) {
        return false ;
    }
    else{
        return true;
    }

}

// Insert Assessment Parameter 
function InsertAssParam($connection,$name,$discrip,$cat,$datetime,$typ,$value,$kpi,$dept,$unit,$staff,$project,$type,$lv,$uni,$cattype,$org_id = null)
{
    $slug = CreateSlug($name);

    if(!is_numeric($value)){ $value = 0;}
    if(!is_numeric($dept)){ $dept = 0;}
    if(!is_numeric($unit)){ $unit = 0;}
    if(!is_numeric($staff)){ $staff = 0;}
    if(!is_numeric($uni)){ $uni = 0;}
    if(!is_numeric($project)){ $project = 0;}
    $query = $connection->query("
        INSERT INTO assessment_params 
        SET  asp_name = '$name',
        asp_discrip = '$discrip',
        asp_cat = '$cat',
        asp_typ = '$typ',
        asp_value = '$value',
        asp_slug = '$slug',
        asp_kpi = '$kpi',
        asp_dept = '$dept',
        asp_unit = '$unit',
        asp_staff = '$staff',
        asp_project = '$project',
        asp_astyp = '$cattype',
        asp_typ_level = '$lv',
        asp_uni = '$uni',
        org_id = '$org_id',
        registered_at = '$datetime'");

    if (!$query) {
        // header("Refresh:1; url=test-params.php");
        return false ;
    }
    else{
        return true;
    }

}


// Function Get Organiations 
function GetOrganizations($connection, $org_id = NULL, $category_id = NULL, $limit = NULL)
{
    $WHERE = ""; $LIMIT = "";
    if ($org_id == 0) {

        if ($category_id) {
            $WHERE = "WHERE org_category = '$category_id'";
        }

        if ($limit) {
            $LIMIT = "LIMIT $limit";
        }
        
    } else {
        // get the organisation ID value
        if ($org_id)
            $WHERE = "WHERE org_id = '$org_id'";
    
    }

    // form query statemnet
    $sql = "SELECT * FROM organization $WHERE $LIMIT";
    // run query
    $query = $connection->query($sql) ;
    // execute query
    $num = $query->execute();
    
    if($num != 0) 
    {
        // return $query->setFetchMode(PDO::FETCH_ASSOC);
        return $query->fetchAll();
    }
    else
    {
        return false;
    }
}


// Function Get Organiations Name
function GetOrganizationName($connection, $org_id)
{
    // form query statemnet
    $sql = "SELECT org_name FROM organization where org_id='$org_id'";
    // run query
    $query = $connection->query($sql) ;
    // execute query
    $num = $query->execute();
    
    if($num !=0) 
    {
        $result = $query->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $query->fetch()) 
        {
            return $row['org_name'];
        }   
    }
    else
    {
        return false;
    }
}


// Function Get Organiations Name
function GetOrganizationLocation($connection, $area_id)
{
    // form query statemnet
    $sql = "SELECT * FROM area where area_id='$area_id'";
    // run query
    $query = $connection->query($sql) ;
    // execute query
    $num = $query->execute();
    
    if($num !=0) 
    {
        $result = $query->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $query->fetch()) 
        {
            return $row['area_name'];
        }
        
    }
    else
    {
        return false;
    }
}

// Function Get Organiations Category
function GetOrganizationCat($connection, $cat_id){
    $sql = "SELECT * FROM organization_category where cat_id='$cat_id' ";
    $query = $connection ->query($sql) ;
    $num =  $query->setFetchMode(PDO::FETCH_NUM);
    
    if($num !=0) {
        $result = $query->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $query->fetch() ) {
            return $row['cat_name'] ;
        }
    }
}


// Function Get Organiations Category
function GetOrganizationCatList($connection)
{
    $sql = "SELECT * FROM organization_category ";
    // run query
    $query = $connection ->query($sql) ;
     // execute query
    $num = $query->execute();
    
    if($num !=0) 
    {
        // return $query->setFetchMode(PDO::FETCH_ASSOC);
        return $query->fetchAll();
    }
    else
    {
        return false;
    }
}

// Function Get number of organisations in an Organiations Category
function GetOrganizationCatNumber($connection, $cat_id)
{
    $sql = "SELECT * FROM organization WHERE org_category = '$cat_id'";
    // run query
    $query = $connection ->query($sql) ;
    // execute query
    $num = $query->rowCount() ;
    return $num;
}

// Function Get Organiations Category
function GetPersonalityTest($connection)
{
    $sql = "SELECT * FROM personality";
    // query
    $query = $connection ->query($sql) ;
    // execute
    $num =  $query->execute();
    
    if($num !=0) 
    {
        // return $query->setFetchMode(PDO::FETCH_ASSOC);
        return $query->fetchAll();
    }
    else
    {
        return false;
    }
}


// Function Organisation category From Id 
function GetOrgCatId($connection,$id){
    $query = $connection ->query("SELECT * FROM organization_category WHERE cat_id = '$id' ") ;
    // initialize idvariable
    while ($row = $query->fetch()) {
        $ids = $row['cat_name'] ;
        return ("$ids") ;
    }
}

// Function Organisation  From Id 
function GetOrgId($connection,$id){
    $query = $connection ->query("SELECT * FROM organization WHERE org_id = '$id' ") ;
    // initialize idvariable
    while ($row = $query->fetch()) {
        $ids = $row['org_name'] ;
        return ("$ids") ;
    }
}

function CheckOrgId($connection, $org_id)
{
    // assign statement 
    $sql = "SELECT * FROM organization WHERE org_id = '$org_id'";
    // run query
    $query = $connection ->query($sql) ;
     // execute query
    $num = $query->execute();
    
    if($num != 0) 
    {
        // return $query->setFetchMode(PDO::FETCH_ASSOC);
        return $num;
    }
    else
    {
        return false;
    }    
}


// function to delete Category
function DeleteOrg($connection,$cid){
    $sql = "DELETE FROM `organization` WHERE org_id = '$cid' " ;
    $query = $connection->query($sql);
    return json_encode(['status' => "success", 'message' =>"deleted successfully"]);
}


// function to delete Department
function DeleteDept($connection,$cid){
    $sql = "DELETE FROM `department` WHERE dept_id = '$cid' " ;
    $query = $connection->query($sql);
    return json_encode(['status' => "success", 'message' =>"deleted successfully"]);
}

function DeleteAssParam($connection, $cid) {
    $sql = "DELETE FROM `assessment_params` WHERE asp_id = '$cid' " ;
    $query = $connection->query($sql);
    return json_encode(['status' => "success", 'message' =>"deleted successfully"]);
}

// function to delete KPI
function DeleteKPI($connection,$cid){
    $sql = "DELETE FROM `kpi` WHERE kpi_id = '$cid' " ;
    $query = $connection->query($sql);
    return json_encode(['status' => "success", 'message' =>"deleted successfully"]);
}

// function to delete Assessment type
function DeleteType($connection,$cid){
    $sql = "DELETE FROM `assessment_type` WHERE astyp_id = '$cid' " ;
    $query = $connection->query($sql);
    return json_encode(['status' => "success", 'message' =>"deleted successfully"]);
}

// function to delete Unit
function DeleteUnit($connection,$cid){
    $sql = "DELETE FROM `unit` WHERE uni_id = '$cid' " ;
    $query = $connection->query($sql);
    return json_encode(['status' => "success", 'message' =>"deleted successfully"]);
}

// function to delete Staff
function DeleteStaff($connection,$cid){
    $sql = "DELETE FROM `staff` WHERE sta_id = '$cid' " ;
    $query = $connection->query($sql);
    return json_encode(['status' => "success", 'message' =>"deleted successfully"]);
}


// function to delete Staff
function DeleteAssesmentCategory($connection,$cid){
    $sql = "DELETE FROM `assessment_params_category` WHERE aspcat_id = '$cid' " ;
    $query = $connection->query($sql);
    return json_encode(['status' => "success", 'message' =>"deleted successfully"]);
}


// function to delete Organization
function DeleteCat($connection,$cid){
    $sql = "DELETE FROM `organization_category` WHERE cat_id = '$cid' " ;
    $query = $connection->query($sql);
    return json_encode(['status' => "success", 'message' =>"deleted successfully"]);
}


// Function Organisation category array For modal 
function GetOrgCatIdArray($connection){
    $query = $connection ->query("SELECT * FROM organization_category ") ;
    // initialize idvariable
    $c_id = 0;
    while ($row = $query->fetch()) {
        $ids = $row['cat_id'] ;
        // print("$ids<br>");
        // return ("$ids") ;
        if ($c_id == 0){
            $c_id = $ids;
        } else {
            $c_id = $c_id. "," .$ids;
        }
    }
    // return menu_id
    return explode(",",$c_id);
}


// Function Organisation array For modal 
function GetOrgIdArray($connection){
    $query = $connection ->query("SELECT * FROM organization ") ;
    // initialize idvariable
    $c_id = 0;
    while ($row = $query->fetch()) {
        $ids = $row['org_id'] ;
        // print("$ids<br>");
        // return ("$ids") ; 
        if ($c_id == 0){
            $c_id = $ids;
        } else {
            $c_id = $c_id. "," .$ids;
        }
    }
    // return menu_id
    return explode(",",$c_id);
}


// Function Departments  array For modal 
function GetDeptIdArray($connection){ 
    $query = $connection ->query("SELECT * FROM department ") ;
    // initialize idvariable
    $c_id = 0;
    while ($row = $query->fetch()) {
        $ids = $row['dept_id'] ;
        // print("$ids<br>");
        // return ("$ids") ;
        if ($c_id == 0){
            $c_id = $ids;
        } else {
            $c_id = $c_id. "," .$ids;
        }
    }
    // return menu_id
    return explode(",",$c_id);
}

function GetAssessmentParamArray($connection) {
    $query = $connection ->query("SELECT * FROM assessment_params ") ;
    // initialize idvariable
    $c_id = 0;
    while ($row = $query->fetch()) {
        $ids = $row['asp_id'] ;
        // print("$ids<br>");
        // return ("$ids") ;
        if ($c_id == 0){
            $c_id = $ids;
        } else {
            $c_id = $c_id. "," .$ids;
        }
    }
    // return menu_id
    return explode(",",$c_id);
}

// Function KPI array For modal 
function GetKPIIdArray($connection){ 
    $query = $connection ->query("SELECT * FROM kpi") ;
    // initialize idvariable
    $c_id = 0;
    while ($row = $query->fetch()) {
        $ids = $row['kpi_id'] ;
        // print("$ids<br>");
        // return ("$ids") ;
        if ($c_id == 0){
            $c_id = $ids;
        } else {
            $c_id = $c_id. "," .$ids;
        }
    }
    // return menu_id
    return explode(",",$c_id);
}

// Function Type array For modal 
function GetTypeIdArray($connection){ 
    $query = $connection ->query("SELECT * FROM assessment_type") ;
    // initialize idvariable
    $c_id = 0;
    while ($row = $query->fetch()) {
        $ids = $row['astyp_id'] ;
        // print("$ids<br>");
        // return ("$ids") ;
        if ($c_id == 0){
            $c_id = $ids;
        } else {
            $c_id = $c_id. "," .$ids;
        }
    }
    // return menu_id
    return explode(",",$c_id);
}


// Function Assesment id array For modal 
function GetAssesmentIdArray($connection){ 
    $query = $connection ->query("SELECT * FROM assessment_params_category") ;
    // initialize idvariable
    $c_id = 0;
    while ($row = $query->fetch()) {
        $ids = $row['aspcat_id'];
        // print("$ids<br>");
        // return ("$ids") ;
        if ($c_id == 0){
            $c_id = $ids;
        } else {
            $c_id = $c_id. "," .$ids;
        }
    }
    // return menu_id
    return explode(",",$c_id);
}

// Function Unit array For modal 
function GetUnitArray($connection){
    $query = $connection ->query("SELECT * FROM unit ") ;
    // initialize idvariable
    $c_id = 0;
    while ($row = $query->fetch()) {
        $ids = $row['uni_id'] ;
        // print("$ids<br>");
        // return ("$ids") ;
        if ($c_id == 0){
            $c_id = $ids;
        } else {
            $c_id = $c_id. "," .$ids;
        }
    }
    // return menu_id
    return explode(",",$c_id);
}

// Function Staff array For modal 
function GetStaffArray($connection){
    $query = $connection ->query("SELECT * FROM staff ") ;
    // initialize idvariable
    $c_id = 0;
    while ($row = $query->fetch()) {
        $ids = $row['sta_id'] ;
        // print("$ids<br>");
        // return ("$ids") ;
        if ($c_id == 0){
            $c_id = $ids;
        } else {
            $c_id = $c_id. "," .$ids;
        }
    }
    // return menu_id
    return explode(",",$c_id);
}

// Function Staff's Unit array 
function GetStaffUnitArray($connection,$id){
    $query = $connection ->query("SELECT unit FROM staff_unit WHERE staff = '$id' ") ;
    // initialize idvariable
    $c_id = 0;
    while ($row = $query->fetch()) {
        $ids = $row['unit'] ;
        if ($c_id == 0){
            $c_id = $ids;
        } else {
            $c_id = $c_id. "," .$ids;
        }
    }
    // return menu_id
    return explode(",",$c_id);
}

// Function Get Organiations Departmnts
function GetOrganizationDeptList($connection,$org)
{
    // assign statement 
    $sql = "SELECT * FROM department WHERE dept_org = '$org' ";
    // run query
    $query = $connection ->query($sql) ;
     // execute query
    $num = $query->execute();
    
    if($num !=0) 
    {
        // return $query->setFetchMode(PDO::FETCH_ASSOC);
        return $query->fetchAll();
    }
    else
    {
        return false;
    }    
}

// Function Get Organiations Departmnts
function GetOrganizationDeptUnitList($connection,$org)
{
    // assign statement 
    $sql = "SELECT * FROM unit WHERE uni_org = '$org' ";
    // run query
    $query = $connection ->query($sql) ;
    // execute query
    $num = $query->execute();
    
    if($num !=0) 
    {
        // return $query->setFetchMode(PDO::FETCH_ASSOC);
        return $query->fetchAll();
    }
    else
    {
        return false;
    }
}


// Function Get Organiations Project
function GetOrganizationProjectList($connection,$org)
{
    // assign statement 
    $sql = "SELECT * FROM project WHERE project_org = '$org' ";
    // run query
    $query = $connection ->query($sql) ;
     // execute query
    $num = $query->execute();
    
    if($num !=0) 
    {
        // return $query->setFetchMode(PDO::FETCH_ASSOC);
        return $query->fetchAll();
    }
    else
    {
        return false;
    }    
}


// Function Get Assesment Type
function GetAssType($connection, $org_id)
{
    // assign query
    $sql = "SELECT * FROM assessment_type WHERE org_id = '$org_id'";
    // query
    $query = $connection->query($sql);
    // execute
    $num =  $query->execute();
    
    if($num !=0) 
    {
        // return $query->setFetchMode(PDO::FETCH_ASSOC);
        return $query->fetchAll();
    }
    else
    {
        return false;
    }
}

function GetAssParamCatId($connection,$id)
{
    $sql = "SELECT * FROM assessment_params_category WHERE aspcat_id = '$id' ";
    $query = $connection ->query($sql) ;
    $num =  $query->setFetchMode(PDO::FETCH_NUM);    
    if($num !=0) 
    {
        $result = $query->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $query->fetch() ) 
        {
            $name = $row['aspcat_name'] ;
            return($name) ;
        }
    }
}

function GetAssParamTypeDetailId($connection,$id)
{
    $sql = "SELECT * FROM assessment_type WHERE astyp_id = '$id' ";
    $query = $connection ->query($sql);
    $num =  $query->setFetchMode(PDO::FETCH_NUM);
    if($num !=0) 
    {
        $result = $query->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $query->fetch() ) 
        {    
            $result = json_encode([
                'id' => $row['astyp_id'],
                'name' => $row['astyp_name'],
                'desc' => $row['astyp_discrip'],
                'cat' => $row['astyp_orgcat'],
                'level' => $row['astyp_org_level']
            ]);
            return  $result;
        }
    }
}

function GetAssParamTypId($connection,$id)
{
    $sql = "SELECT * FROM assessment_type WHERE astyp_id = '$id' ";
    $query = $connection ->query($sql);
    $num =  $query->setFetchMode(PDO::FETCH_NUM);
    if($num !=0) 
    {
        $result = $query->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $query->fetch() ) 
        {    
            $name = $row['astyp_name'] ;
            return($name) ;
        }
    }
}


// Function Get Assesment parameter Categories
function GetAssesmentParameterCategory($connection, $org_id)
{
    // assign query
    $sql = "SELECT * FROM assessment_params_category WHERE org_id = '$org_id'";
    // query
    $query = $connection ->query($sql) ;
    // execute
    $num =  $query->execute();
    
    if($num !=0) 
    {
        // return $query->setFetchMode(PDO::FETCH_ASSOC);
        return $query->fetchAll();
    }
    else
    {
        return false;
    }
}

// Function Get Assesment parameter Categories for staff in Unit
function GetAssParamCatUnit($connection){
    $sql = "SELECT * FROM assessment_params_category WHERE org_grp = 'punit' ";
    $query = $connection ->query($sql) ;
    $num =  $query->setFetchMode(PDO::FETCH_NUM);
    
    if($num !=0) 
    {
        $result = $query->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $query->fetch()) 
        {
            $name = $row['aspcat_name'] ;
            $discrip = $row['aspcat_discrip'] ;
            $id = $row['aspcat_id'];
            $cat = $row['ass_typ'];
            $reg_at = $row['registered_at'];

            echo "<tr>
                    <td class='description'>
                        <a href='#profile.php?id=$id' class='name'>$name </a>
                    </td>
                    <td>".GetAssParamTypId($connection,$cat)."</td>
                    <td>
                        <a class='btn btn-sm btn-info' href='#'>
                            <i class='halflings-icon white edit'></i>  
                        </a>
                        <a class='btn btn-sm btn-danger' href='#'>
                            <i class='halflings-icon white trash'></i> 
                        </a>  
                    </td>
            </tr>";
        }
    }
}


// Function Get Assesment parameter Categories for staff in Unit
function GetAssParamCatPersonal($connection){
    $sql = "SELECT * FROM assessment_params_category WHERE org_grp = 'staff-ass' ";
    $query = $connection ->query($sql) ;
    $num =  $query->setFetchMode(PDO::FETCH_NUM);
    if($num !=0) 
    {
        $result = $query->setFetchMode(PDO::FETCH_ASSOC);    
        while ($row = $query->fetch()) 
        {
            $name = $row['aspcat_name'];
            $discrip = $row['aspcat_discrip'];
            $id = $row['aspcat_id'];
            $cat = $row['ass_typ'];
            $reg_at = $row['registered_at'];

            echo "<tr>
                    <td class='description'>
                        <a href='#profile.php?id=$id' class='name'>$name </a>
                    </td>
                    <td>".GetAssParamTypId($connection,$cat)."</td>
                    <td>
                        <a class='btn btn-sm btn-info' href='#'>
                            <i class='halflings-icon white edit'></i>  
                        </a>
                        <a class='btn btn-sm btn-danger' href='#'>
                            <i class='halflings-icon white trash'></i> 
                        </a>
                    </td>
            </tr>";
        }
    }
}


// Function Get Assesment parameter Categories for staff in Unit
function GetAssParamCatUnitUni($connection){
    $sql = "SELECT * FROM assessment_params_category WHERE org_grp = 'unit' ";
    $query = $connection ->query($sql) ;
    $num =  $query->setFetchMode(PDO::FETCH_NUM);
    if($num !=0) 
    {
        $result = $query->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $query->fetch()) 
        {    
            $name = $row['aspcat_name'] ;
            $discrip = $row['aspcat_discrip'] ;
            $id = $row['aspcat_id'];
            $cat = $row['ass_typ'];
            $reg_at = $row['registered_at'];


            echo "<tr>
                    <td class='description'><a href='#profile.php?id=$id' class='name'>$name </a></td>
                    <td>".GetAssParamTypId($connection,$cat)."</td>
                    <td>
                        <a class='btn btn-sm btn-info' href='#'>
                            <i class='halflings-icon white edit'></i>  
                        </a>
                        <a class='btn btn-sm btn-danger' href='#'>
                            <i class='halflings-icon white trash'></i> 
                        </a>
                    </td>
            </tr>";
        }
    }
}


// fuction to get Org type from id 

function GetOrgTypId($connection,$id){
    $sql = "SELECT * FROM organization_category WHERE cat_id = '$id' ";
    $query = $connection ->query($sql) ;
    $num =  $query->setFetchMode(PDO::FETCH_NUM);
    
    if($num !=0) {
        $result = $query->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $query->fetch() ) {
            $name = $row['cat_name'] ;
            return($name) ;
        }
    }
}



// Function Get Assesment parameter 
function GetAssesmentParameters($connection,$unit,$org_id)
{
    if ($unit == 4) {
        $sql = "SELECT * FROM assessment_params WHERE asp_unit != '0' and org_id = '$org_id'";
    } elseif ($unit == 2) {
         $sql = "SELECT * FROM assessment_params WHERE org_id = '$org_id'";
    } elseif ($unit == 3) {
         $sql = "SELECT * FROM assessment_params WHERE asp_staff !='0' and org_id = '$org_id'";
    } elseif ($unit == 1) {
         $sql = "SELECT * FROM assessment_params WHERE asp_uni !='0' and org_id = '$org_id'";
    } elseif ($unit == 5) {
        $sql = "SELECT * FROM assessment_params WHERE asp_project !='0' and org_id = '$org_id'";
    } elseif ($unit == 6) {
        $sql = "SELECT * FROM assessment_params WHERE asp_dept !='0' and org_id = '$org_id'";
    }

    // query
    $query = $connection ->query($sql) ;
    // execute
    $num =  $query->execute();
    
    if($num !=0) 
    {
        // return $query->setFetchMode(PDO::FETCH_ASSOC);
        return $query->fetchAll(); 
    }
    else
    {
        return false;
    }
}


// Function to check input trye and value 
function CheckInput($connection,$id)
{
    $query = $connection->query("SELECT * FROM assessment_params WHERE asp_id = '$id' ") ;
    while ($row = $query->fetch()) {
        $typ = $row['asp_typ'] ;
        $value = $row['asp_value'] ;
        if ($typ == 'input') {
            return($value);
        }else{
            return("Standard (Scale of 1 to 6)");
        }
    }
}


// Function to get dept Select 
function GetProjectName($connection, $project_id){
    $query = $connection ->query("SELECT * FROM project WHERE project_id='$project_id'") ;
    while ($row = $query->fetch()) {
        return $row['project_title'];
    }
}


// Function to get dept Select 
function GetDept($connection, $orgid){
    $query = $connection ->query("SELECT * FROM department WHERE dept_org='$orgid'") ;
     while ($row = $query->fetch()) {
         $dept = $row['dept_name'] ;
         $id = $row['dept_id'] ;
         echo "<option value='$id'> $dept </option>";
     }
}

// Function to get dept Select 
function GetDeptName($connection, $dept_id){
    $query = $connection ->query("SELECT * FROM department WHERE dept_id='$dept_id'") ;
    while ($row = $query->fetch()) {
        return $row['dept_name'];
    }
}

// Function Get Unit Dept
function GetDeptUnit($connection,$dept,$orgid)
{
    $sql = "SELECT unit FROM unit WHERE uni_org='$orgid'";
    $query = $connection ->query($sql) ;  
    $num =  $query->setFetchMode(PDO::FETCH_NUM);
    if($num !=0) 
    {
        $result = $query->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $query->fetch() ) 
        {
            $uni_name = $row['uni_name'];
            echo "<option value='$id'> $uni_name </option>";
        }
    }
}


// Function to get link person for project
function GetLinkPersonProject($connection, $project_id){
    $query = $connection->query("SELECT * FROM project WHERE project_id='$project_id'");
    while ($row = $query->fetch()) {
        return $row['project_linkperson'];
    }
}

// Function to get link persons for project
function GetLinkPersonsProject($connection, $project_id){
    $query = $connection->query("SELECT * FROM project WHERE project_id='$project_id'");
    $res = [];
    $rows = $query->fetchAll(); 
    for ($i=0; $i < count($rows); $i++) { 
        $row = $rows[$i];
        array_push($res, $row['staff']);
    }
    return $res;
}

// Function to get link person for dept
function GetLinkPersonDept($connection, $dept_id){
    $query = $connection->query("SELECT * FROM department WHERE dept_id='$dept_id'");
    while ($row = $query->fetch()) {
        return $row['linkperson'];
    }
}

// Function to get link persons for dept
function GetLinkPersonsDept($connection, $dept_id){
    $query = $connection->query("SELECT * FROM department WHERE dept_id='$dept_id'");
    $res = [];
    $rows = $query->fetchAll(); 
    for ($i=0; $i < count($rows); $i++) { 
        $row = $rows[$i];
        array_push($res, $row['linkperson']);
    }
    return $res;
}


// Function to get link person for unit
function GetLinkPersonUnit($connection, $unit_id){
    $query = $connection->query("SELECT * FROM staff_unit WHERE unit='$unit_id' and linkperson='1'");
    while ($row = $query->fetch()) {
        return $row['staff'];
    }
}

// Function to get link person for unit
function GetLinkPersonsUnit($connection, $unit_id){
    $query = $connection->query("SELECT * FROM staff_unit WHERE unit='$unit_id' and linkperson='1'"); 
    $res = [];
    $rows = $query->fetchAll(); 
    for ($i=0; $i < count($rows); $i++) { 
        $row = $rows[$i];
        array_push($res, $row['staff']);
    }
    return $res;
}


// function to register Staff 
function InsertStaff($connection,$fname,$lname,$dept,$sex,$phone,$email,$org,$datetime,$snumber,$state,$city,$grade_level,$job_title)
{
    $date = date('Y-m-d H:i:s');
    $query = $connection->prepare("INSERT INTO staff SET sta_fname = '$fname', sta_lname = '$lname',sta_dept = '$dept', sta_sex = '$sex', sta_org = '$org', sta_email = '$email', sta_phone = '$phone', sta_number = '$snumber', sta_state = '$state', sta_city = '$city', sta_grade_level = '$grade_level', sta_job_title = '$job_title', registered_at = '$date' ");
    $query ->execute();
    if (!$query) 
    {
        return false;
    }
    else
    {
        // return true;
        return $connection->lastInsertId();
    }
}

// function to edit Staff 
function EditStaff($connection,$fname,$lname,$dept,$sex,$phone,$email,$org,$datetime,$snumber,$state,$city,$grade_level,$job_title,$id)
{
    $query = $connection->prepare("UPDATE staff SET sta_fname = '$fname', sta_lname = '$lname',sta_dept = '$dept', sta_sex = '$sex', sta_org = '$org', sta_email = '$email', sta_phone = '$phone', sta_number = '$snumber', sta_state = '$state', sta_city = '$city', sta_grade_level = '$grade_level', sta_job_title = '$job_title' WHERE sta_id = '$id'");
    $query ->execute();
    if (!$query) 
    {
        return false;
    }
    else
    {
        return true;
        // return $connection->lastInsertId();
    }
}


function AddLinkPersonToDepartment($connection, $new_staff, $dept_id, $org_id)
{
    $query = $connection->prepare("UPDATE department SET linkperson='$new_staff' WHERE dept_id='$dept_id' and dept_org='$org_id'");
    $query ->execute();
    if (!$query) 
    {
        return false;
    }
    else
    {
        return true;
    }
}


function Insertsupervisor($connection,$supervisor_staff_id,$org_id,$date)
{
    $sql = "SELECT * FROM supervisors WHERE supervisor_staff_id = '$supervisor_staff_id' and supervisor_org='$org_id' ";
    $query = $connection ->query($sql) ;
    $num =  $query->fetchColumn();
    if($num > 0)
    {
        return json_encode(['status' => "warning", 'message' => "supervisor already registered"]);
    }else{
        $query = $connection->prepare("INSERT INTO supervisors SET supervisor_staff_id= '$supervisor_staff_id', supervisor_org='$org_id', date='$date'");
        $query ->execute();
        if (!$query) {
            return json_encode(['status' => "error", 'message' => "an error occured. Try again"]);
        }
        else
        {
            return json_encode(['status' => "success", 'message' => "supervisor added successfully"]);
        }
    }
}

/*function UpdateStaffID($connection){
    $query = $connection->query("SELECT * FROM staff");
    while ($row = $query->fetch()) {
        
        $snumber = gennumber();
        $connection->query("UPDATE staff SET sta_phone = '$snumber'");
        $snumber = "";
    }
}

function gennumber($length = 8)
{
    $characters = "0123456789";
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return "080".$randomString;
}*/


// Function Get Staff

function GetStaffId($connection,$id)
{
    $sql = "SELECT * FROM staff WHERE sta_id = '$id' ";
    $query = $connection ->query($sql) ;
    $num =  $query->setFetchMode(PDO::FETCH_NUM);
    if($num !=0) 
    {
        $result = $query->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $query->fetch()) 
        {    
            $fname = $row['sta_fname'];
            $lname = $row['sta_lname'];
            return ("$fname $lname");
        }
    }
}

function GetStaffOrgId($connection,$id)
{
    $sql = "SELECT * FROM staff WHERE sta_id = '$id' ";
    $query = $connection ->query($sql) ;
    $num =  $query->setFetchMode(PDO::FETCH_NUM);
    if($num !=0) 
    {
        $result = $query->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $query->fetch()) 
        {    
            return $row['sta_org'];
        }
    }
}

function GetStaffName($connection,$id)
{
    $sql = "SELECT * FROM staff WHERE sta_id = '$id' ";
    $query = $connection ->query($sql) ;
    $num =  $query->setFetchMode(PDO::FETCH_NUM);
    if($num !=0)
    {
        $result = $query->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $query->fetch() ) 
        {    
            $fname = $row['sta_fname'] ;
            $lname = $row['sta_lname'] ;
            return $fname ." ". $lname;
        }
    }
}


function GetStaffEmail($connection,$id)
{
    $sql = "SELECT * FROM staff WHERE sta_id = '$id' ";
    $query = $connection ->query($sql) ;
    $num =  $query->setFetchMode(PDO::FETCH_NUM);
    if($num !=0)
    {
        $result = $query->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $query->fetch() ) 
        {    
            $email = $row['sta_email'] ;
            // $lname = $row['sta_lname'] ;
            return ("$email") ;
        }
    }
}


function GetStaffState($connection,$id)
{
    $sql = "SELECT * FROM staff WHERE sta_id = '$id' ";
    $query = $connection ->query($sql) ;
    $num =  $query->setFetchMode(PDO::FETCH_NUM);
    if($num !=0)
    {
        $result = $query->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $query->fetch() ) 
        {    
            $state = $row['sta_state'] ;
            // $lname = $row['sta_lname'] ;
            return ("$state") ;
        }
    }
}

// Function Get Unit Id

function GetUnitId($connection, $id)
{
    $sql = "SELECT * FROM unit WHERE uni_id = '$id'";
    $query = $connection ->query($sql) ;
    $num =  $query->setFetchMode(PDO::FETCH_NUM);
    if($num !=0) 
    {
        $result = $query->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $query->fetch()) 
        {
            $name = $row['uni_name'] ;
            return ("$name") ;
        }
    }
}


// Function Get Staff
function GetStaffDept($connection,$id)
{
    $sql = "SELECT sta_dept FROM staff WHERE sta_id = '$id'";
    $query = $connection ->query($sql) ;
    $num =  $query->setFetchMode(PDO::FETCH_NUM);
    if($num !=0) 
    {
        $result = $query->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $query->fetch()) 
        { 
            $dept = $row['sta_dept'] ;
            return ("$dept") ;
        }
    }
}



// Function Get Units in a dept
function GetUnitInDept($connection,$id,$org_id)
{
    $sql = "SELECT * FROM unit WHERE uni_dept = '$id' and uni_org = '$org_id'";
    $query = $connection ->query($sql) ;  
    $num =  $query->setFetchMode(PDO::FETCH_NUM);
    if($num !=0) 
    {
        return $query->fetchAll();
    }
}

// Function Get Unit Dept
function GetUnitDept($connection,$id)
{
    $sql = "SELECT uni_dept FROM unit WHERE uni_id = '$id'";
    $query = $connection ->query($sql) ;  
    $num =  $query->setFetchMode(PDO::FETCH_NUM);
    if($num !=0) 
    {
        $result = $query->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $query->fetch() ) 
        {
            $dept = $row['uni_dept'] ;
            return ("$dept") ;
        }
    }
}


// Function to get dept Select 
function GetUnitName($connection, $unit_id){
    $query = $connection ->query("SELECT * FROM unit WHERE uni_id='$unit_id'") ;
    while ($row = $query->fetch()) {
        return $row['uni_name'];
    }
}


// Function to get dept Select 
function GetUnitLinkPerson($connection, $unit_id){
    $query = $connection ->query("SELECT * FROM staff_unit WHERE unit='$unit_id' and linkperson='1'") ;
    while ($row = $query->fetch()) {
        return $row['staff'];
    }
}





// Function Get Supervisors
function GetSupervisors($connection,$org_id)
{
    // assign query
    $sql = "SELECT * FROM supervisors WHERE supervisor_org='$org_id'";
    // query
    $query = $connection ->query($sql) ;
    // execute
    $num =  $query->execute();
    
    if($num !=0) 
    {
        // return $query->setFetchMode(PDO::FETCH_ASSOC);
        return $query->fetchAll();
    }
    else
    {
        return false;
    }
}

// Function Get Staff
function GetStaffToSupervisors($connection,$org)
{
    // assign query
    $sql = "SELECT * FROM staff_supervisor";
    // query
    $query = $connection ->query($sql) ;
    // execute
    $num =  $query->execute();
    
    if($num !=0) 
    {
        // return $query->setFetchMode(PDO::FETCH_ASSOC);
        return $query->fetchAll();
    }
    else
    {
        return  false;
    }
}


// Function Get Staff
function GetStaffSupervisor($connection, $id, $type)
{
    // assign query
    $sql = "SELECT * FROM staff_supervisor where type_value = '$id' and type = '$type'";
    // query
    $query = $connection ->query($sql) ;
    // execute
    $num =  $query->execute();
    
    if($num !=0) 
    {
        // result
        $result = $query->setFetchMode(PDO::FETCH_ASSOC);
        // get supervisor
        while ($row = $query->fetch()) 
        {
            return $row['supervisor'];
        }
    }
    else
    {
        return false;
    }
}


// Function Get Staff
function GetStaffList($connection,$org,$dept_id)
{
    // assign query
    if ($dept_id)
    {
        $ANDWHERE = "AND (sta_dept = '$dept_id' OR sta_id = '$dept_id')";
    }
    else
    {
        $ANDWHERE = "";
    }

    $sql = "SELECT * FROM staff WHERE sta_org = '$org' $ANDWHERE";
    // query
    $query = $connection ->query($sql) ;
    // execute
    $num =  $query->execute();
    
    if($num !=0) 
    {
        // return $query->setFetchMode(PDO::FETCH_ASSOC);
        return $query->fetchAll();
    }
    else
    {
        return false;
    }
}



// Function Get A Staff Assessment
function GetStaffAssId($connection,$id,$type){
    $sql = "SELECT * FROM assessment WHERE ass_user = '$id' AND usr_typ = '$type' ";

    $query = $connection ->query($sql) ;
    
    $num =  $query->setFetchMode(PDO::FETCH_NUM);
    
    if($num !=0) {
    $result = $query->setFetchMode(PDO::FETCH_ASSOC);
        
        while ($row = $query->fetch() ) {
            
            $param = $row['ass_param'] ;
            $point = $row['ass_point'] ;
            $id = $row['ass_id'] ;
            $date = $row['ass_date'] ;
            $category = $row['ass_param_cat'] ;

            //$name = "$fname ".  " $lname ";


            echo "<tr height='30px'>
                      <td class='description'>
                            ".GetAssParamId($connection,$param)."
                            </td>
                            <td>
                                ".GetCatNameId($connection,$category)."
                            </td>
                            <td>
                                ".CheckResult($connection,$id)."
                            </td>
                            <td >
                                $date
                            </td>
                        </tr>";
        }
    }
}

// Check result if input or radio for presentation 

function CheckResult($connection,$id){
    $query = $connection->query("SELECT * FROM assessment WHERE ass_id = '$id' ") ;
    while ($row = $query->fetch()) {
        $type = $row['param_type'] ;
        $standard = round($row['std_value'], 0)  ;
        $point = $row['ass_point'] ;

        if ($type == 'input') {
            return("$point out of $standard"); 

        }else{
            return("$point out of 6") ;
        }
    }
}



// Function Get A Department Assessment

function GetDeptAssId($connection,$id){
    $sql = "SELECT * FROM assessment WHERE ass_user = '$id' AND usr_typ = 'dept' ";

    $query = $connection ->query($sql) ;
    
    $num =  $query->setFetchMode(PDO::FETCH_NUM);
    
    if($num !=0) {
    $result = $query->setFetchMode(PDO::FETCH_ASSOC);
        
        while ($row = $query->fetch() ) {
            
            $param = $row['ass_param'] ;
            $point = $row['ass_point'] ;
            $date = $row['ass_date'] ;
            $aid = $row['ass_id'] ;
            $category = $row['ass_param_cat'] ;

            echo "<tr >
                      <td class='description'>
                            ".GetAssParamId($connection,$param)."
                            </td>
                            <td>
                                ".GetCatNameId($connection,$category)."
                            </td>
                            <td>
                                ".CheckResult($connection,$aid)."
                            </td>
                            <td >
                                $date
                            </td>
                            <td >
                                <button class='btn btn-info btn-xs'>  Good </button>
                            </td>
                        </tr>";
        }
    }
}






// Function to get dept from ID )

function GetDeptId($connection,$id){
    $query = $connection ->query("SELECT * FROM department WHERE dept_id = '$id' ") ;
    while ($row = $query->fetch()) {
        $dept = $row['dept_name'] ;
        return " $dept ";  
    }
}


// Function to get Assessment Parameter from ID )
function GetAssParamId($connection,$id){
    $query = $connection ->query("SELECT asp_name FROM assessment_params WHERE asp_id = '$id'");
    while ($row = $query->fetch()) {
        $dept = $row['asp_name'];
        return " $dept ";  
    }
}


// function to get unit assesment kpi
function GetGroupKPI($connection,$unit_id){
    $query = $connection->query("SELECT * FROM assessment_params WHERE asp_typ_level='punit' AND asp_unit='$unit_id' GROUP BY asp_kpi");
    // query
    // $query = $connection->query($sql) ;
    // execute
    $num =  $query->execute();
    
    if($num !=0) 
    {
        // return $query->setFetchMode(PDO::FETCH_ASSOC);
        return $query->fetchAll();
    }
    else
    {
        return false;
    }
}


// Function to get Measurement Parameters 
function GetGroupAssParams($connection,$id)
{
    $query = $connection ->query("SELECT astyp_id FROM assessment_type WHERE astyp_org_level = '$id'");
    while ($row = $query->fetch())
    {
        $group = $row['astyp_id'];
        // echo $group;
        if(CheckIfTypIsAppStaff($connection,$id,$group) != 0) 
        {
            # code...
            echo GetTypNameId($connection,$group);
            // foreach ($row as $k ) {
            echo GetParamCatAss($connection,$group);
            //}
        }
    }
}


// Function to get Measurement Parameters  for units
function GetGroupAssParamsUnit($connection,$astyp_level_id, $kid, $typ, $astyp_id = null){
    
    if($astyp_id === null) {
        $query = $connection ->query("SELECT * FROM assessment_type WHERE astyp_org_level = '$typ' ") ;
        while ($row = $query->fetch()){
            $astyp_id = $row['astyp_id'];
            if (CheckIfTypIsAppUnit($connection, $astyp_level_id, $astyp_id) != 0) 
            {
                return GetParamCatAssStaffPersonal($connection, $astyp_id, $astyp_level_id, $kid);
            }
        }
    }
    return GetParamCatAssStaffPersonal($connection, $astyp_id, $astyp_level_id, $kid);
}


// Function to get Measurement Parameters  for units
function GetGroupAssParamsDept($connection, $astyp_level_id, $kid, $typ, $astyp_id = null){
    
    if($astyp_id === null){
        $query = $connection ->query("SELECT astyp_id FROM assessment_type WHERE astyp_org_level = '$typ' ") ;
        while ($row = $query->fetch() ) {
            $astyp_id = $row['astyp_id'];
            if (CheckIfTypIsAppDept($connection, $astyp_level_id, $astyp_id) != 0) 
            {
                return GetParamCatAssStaffPersonal($connection, $astyp_id, $astyp_level_id, $kid);
            }
        }
    }
    elseif (CheckIfTypIsAppDept($connection, $astyp_level_id, $astyp_id) != 0) 
    {
        return GetParamCatAssStaffPersonal($connection, $astyp_id, $astyp_level_id, $kid);
    }
    return false;
}



// function to get Param Category for assessment 
function GetParamCatAss($connection,$id)
{
    $query = $connection->query("SELECT aspcat_id FROM assessment_params_category WHERE ass_typ = '$id'") ;
    while ($row = $query->fetch()) {

        //    foreach ($row as $key) {
                # code...
            
                $k = $row['aspcat_id'] ;
               // echo "" ;
                /*echo "
                            <div id='$id' class='panel-collapse collapse in' role='tabpanel' aria-labelledby='headingOne'>
                              <div class='panel-body'> 
                              <table class='table table-striped ' border='2px' width='90%'>
                                      <thead align='left'>
                                        <tr>
                                            <th width='70%' height='30'>Personality</th>
                                            <th> </th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                      "; */
                GetAallPara($connection,$k); 
           // echo "";
          //  echo "";
      //   }
     }/*echo "       </tbody>
                                </table>
                                 </div>
                            </div>";*/
}


// function to check if staff belongs to a group 
function CheckIfTypIsAppStaff($connection,$id,$type){
    $query = $connection->query("SELECT asp_id from assessment_params WHERE asp_astyp = '$type' AND asp_staff = '$id'"); 
    $num = $query->rowCount() ;
    return $num;
}

// function to check if unit belongs to a group 
function CheckIfTypIsAppUnit($connection,$id,$type){
    $query = $connection->query("SELECT asp_id from assessment_params WHERE asp_astyp = '$type' AND asp_unit = '$id' "); 
    $num = $query->rowCount() ;
    return $num ;
}

// function to check if unit belongs to a group 
function CheckIfTypIsAppDept($connection,$id,$type){
    $query = $connection->query("SELECT asp_id from assessment_params WHERE asp_astyp = '$type' AND asp_dept = '$id' "); 
    $num = $query->rowCount() ;
    return $num ;
}

// function to check if staff belongs to a group 

 function CheckIfInGrp($connection,$id){
    $query = $connection->query("SELECT staff from staff_unit WHERE staff = '$id' "); 
    $num = $query->rowCount() ;
    return $num ;
 }


// function to check if staff Has a set of test Params 

 function CheckIfParamP($connection,$id){
    $query = $connection->query("SELECT * from assessment_params WHERE asp_staff = '$id' "); 
    $num = $query->rowCount() ;
    return $num ;
 }




// Function to get Measurement Parameters for staff in Unit
function GetGroupAssParamsStfUnit($connection,$id,$unit){
    $query = $connection ->query("SELECT astyp_id FROM assessment_type WHERE astyp_org_level = 'punit' ") ;
    while ($row = $query->fetch() ) {
            $group = $row['astyp_id'];
          //  echo "string";

            if (CheckIfTypIsAppUnit($connection,$id,$group) !=0) {
            echo "  <div class='panel panel-default'>
                                <div class='panel-heading' role='tab' id='headingOne'>
                                  <h5 class='panel-title'>
                                    <a role='button' data-toggle='collapse' data-parent='#accordion' href='#$group' aria-expanded='true' aria-controls='collapseOne'>".GetTypNameId($connection,$group)."  
                                    </a>
                                  </h5>
                                </div> ";
          //  foreach ($row as $k ) {
          echo      GetParamCatAssStaff($connection,$group,$unit) ;
            //}
        }echo "  </div>";
     }
 }


// function to check if staff has a test parameter in the category 
function CheckIfCatParamStaff($connection,$id,$cat){
    $query = $connection->query("SELECT * FROM assessment_params WHERE asp_staff = '$id' AND asp_cat = '$cat' ") ; 
    $num = $query ->rowCount() ;
}


// function to get Param Category for assessment 
function GetParamCatAssStaff($connection,$id,$unit){

            $query = $connection->query("SELECT  aspcat_id FROM assessment_params_category WHERE ass_typ = '$id' ") ;
            while ($row = $query->fetch()) {

                $k= $row['aspcat_id'] ;
               // echo "" ;
                echo "
                            <div id='$id' class='panel-collapse collapse in' role='tabpanel' aria-labelledby='headingOne'>
                              <div class='panel-body'> 
                              <table class='table table-striped ' border='2px' width='90%'>
                                      <thead align='left'>
                                        <tr>
                                            <th width='70%' height='30'>".GetCatNameId($connection,$k)."</th>
                                            <th> </th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                      "; 
                                      GetAallParaStfUni($connection,$k,$unit); 
           // echo "";
          //  echo "";
         }echo "</tbody></table></div></div>";
 }


// Function to get Measurement Parameters 
function GetGroupAssParamsStaff($connection,$astyp_level_id, $typ, $kid, $astyp_id = null)
{
    // fetch assesment type to track staff performance
    if (CheckIfParamP($connection,$astyp_level_id) != 0) 
    {
        return GetGroupAssParamsStfPersonal($connection, $astyp_level_id, $kid, 'staff-ass', $astyp_id);
    }
    
}


// Function to get Measurement Parameters for staff assesment
function GetGroupAssParamsStfPersonal($connection,$astyp_level_id, $kid,$ass_typ , $astyp_id = null)
{
    if($astyp_id === null) {
        $query = $connection ->query("SELECT * FROM assessment_type WHERE astyp_org_level = '$ass_typ'") ;
        while ($row = $query->fetch() ) {
            
            $astyp_id = $row['astyp_id'];
            if (CheckIfTypIsAppStaff($connection,$astyp_level_id,$astyp_id) !=0) {
                # code...
                return GetParamCatAssStaffPersonal($connection, $astyp_id, $astyp_level_id, $kid);
            }
        }
    }elseif (CheckIfTypIsAppStaff($connection, $astyp_level_id, $astyp_id) !=0) {
        # code...
        return GetParamCatAssStaffPersonal($connection, $astyp_id, $astyp_level_id, $kid);
    }


}


// function to get Param Category for assessment 
function GetParamCatAssStaffPersonal($connection,$astyp_id,$staff,$kid)
{

    $query = $connection->query("SELECT * FROM assessment_params_category WHERE ass_typ = '$astyp_id' and aspcat_kpi = '$kid'");
    // execute
    $num =  $query->execute();
    if($num !=0) 
    {
        return $query->fetchAll();
    }
    else
    {
        return false;
    }
}


// function to get assessment Parameters for staff in Units
function GetAallParaStfPersonal($connection,$k,$id,$kid)
{
    $query = $connection->query("SELECT * FROM assessment_params WHERE (asp_cat = '$k' AND asp_staff = '$id') OR (asp_cat = '$k' AND asp_unit = '$id') OR (asp_cat = '$k' AND asp_dept = '$id')");
    // execute
    $num =  $query->execute();
    // check record
    if($num !=0) 
    {
        // return $query->setFetchMode(PDO::FETCH_ASSOC);
        return $query->fetchAll();
    }
    else
    {
        return false;
    }
}


// Function to creat Slug field
function CreateSlug($string) 
{ 
    $table = array(
            'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
            'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
            'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
            'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
            'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
            'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
            'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r', '/' => '-', ' ' => '-'
    );
    // -- Remove duplicated spaces
    $stripped = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $string);
    // -- Returns the slug
    return strtolower(strtr($string, $table));
}



function GetAallPara($connection,$k){
    $query = $connection->query("SELECT * FROM personality  ") ;  
    $iss = '' ;
    echo " <form method='post'  name='measure' id='measure' class='form-horizontal'>";
    while ($row = $query->fetch()) {
        $param = $row['pname'] ;
        $slug = $row['slug'] ;
        $value = $row['value'] ;
        $id = $row['id'] ;

        $ass = "" ;
                
                    $ass  .="<tr>
                    <td> $param </td> 
                    <td>  

                    <!--   <input type='radio' name='score[]' value='1'>
                    <input type='radio' name='score[]' value='2'>
                    <input type='radio' name='score[]' value='3'>
                    <input type='radio' name='score[]' value='4'> 
                    <input type='radio' name='score[]' value='5'>
                    <input type='radio' name='score[]' value='6'>
                    <input type='hidden' value='$id' name='param[]'> --> 


                    <select name='score[]'> 
                    <option value='1'> Very Low </option>
                    <option value='2'> Low </option>
                    <option value='3'> Average </option>
                    <option value='4'> Good  </option>
                    <option value='5'> Very Good </option>
                    <option value='6'> Excellent </option>
                    </select>
                    </td>  " ;
                    //               }

                    // if ($field == 'input') 
                    //        else{
                    // $ass .= "<td>$param</td><td><input type='text' name=score[]> &nbsp;&nbsp; Out of 
                    //             <input type='text' value='$value' name='standard[]'>
                    //             <input type='hidden' value='$id' name='param[]'>
                    //             <input type='hidden' value='$cat' name='cat[]'>
                    //             <input type='hidden' value='$typ' name='typ[]'>  
                    //               <input type='hidden' value='input' name='type[]'>
                    //         </td>" ;
                    //    }
                    $ass .= "</tr>";
                    //  $ass .="<tr> <td> 


            }
//" ;   
                        $ass .= "<tr><td><button type='submit' class='btn btn-primary'> <b>Measure Personality </b></button></td></tr></form>" ;
                        

                echo "$ass";
}


// function to get assessment Parameters for staff in Units
function GetAallParaStfUni($connection,$k,$unit){

                $query = $connection->query("SELECT * FROM assessment_params WHERE asp_cat = '$k' AND asp_unit = '$unit' ") ;  

                $iss = '' ;
                echo " <form method='post'   name='measure' id='measure' class='form-horizontal'>";
            while ($row = $query->fetch()) {
                $param = $row['asp_name'] ;
                $slug = $row['asp_slug'] ;
                $typ = $row['asp_typ_level'] ;
                $cat = $row['asp_cat'] ;
                $field = $row['asp_typ'] ;
                $value = $row['asp_value'] ;
                $id = $row['asp_id'] ;
                if ($iss == '') {
                    $iss = $param;
                }
                else{
                    $iss = $iss .= $param ;
                    //$ass .=$ass;
                }
                    $ass = "<tr> " ;
                    if ($field != "input") {
                        
                    
                    $ass  .="
                                          <td> $param </td> 
                                          <td>  
                                        <!--   <input type='radio' name='score[]' value='1'>
                                          <input type='radio' name='score[]' value='2'>
                                          <input type='radio' name='score[]' value='3'>
                                          <input type='radio' name='score[]' value='4'> 
                                          <input type='radio' name='score[]' value='5'>
                                          <input type='radio' name='score[]' value='6'>
                                          <input type='hidden' value='$id' name='param[]'> --> 


                                         <select name='score[]'> 
                                          <option value='1'> Very Low </option>
                                          <option value='2'> Low </option>
                                          <option value='3'> Average </option>
                                          <option value='4'> Good  </option>
                                          <option value='5'> Very Good </option>
                                          <option value='6'> Excellent </option>
                                           </select>
                                          <input type='hidden' value='$id' name='param[]'>
                                          <input type='hidden' value='$cat' name='cat[]'>
                                          <input type='hidden' value='$typ' name='typ[]'>
                                          <input type='hidden' value='radio' name='type[]'>
                                        <input type='hidden' value='6' name='standard[]'>
                                          </td>
                                      " ;
                                      }

                        // if ($field == 'input') 
                                      else{
                            $ass .= "<td>$param</td><td><input type='text' name=score[] required> &nbsp;&nbsp; Out of 
                                        <input type='text' value='$value' name='standard[]'>
                                        <input type='hidden' value='$id' name='param[]'>
                                        <input type='hidden' value='$cat' name='cat[]'>
                                        <input type='hidden' value='$typ' name='typ[]'>  
                                          <input type='hidden' value='input' name='type[]'>
                                    </td>" ;
                        }
                        $ass .= "</tr>";

                    //    $ass .="" ;


                echo "$ass";
            }echo "<tr> <td> 

                                <div class='control-group'>
                      <label class='control-label' for='typeahead'>Expectes Hours of Work  </label>
                          <div class='controls'>
                                <input type='number' name='exp_hours' placeholder='Enter expected No.s of hours ' class='form-control span10'>
                            </div>
                        </div>    
                        
                    <div class='control-group'>
                      <label class='control-label' for='typeahead'> No. of hours used  </label>
                          <div class='controls'>
                                <input type='number' name='hours' placeholder='Enter No. of hours Staff used' class='form-control span10'>
                            </div>
                        </div>    

                    <div class='control-group'>
                      <label class='control-label' for='typeahead'> Expected  No. of Days  </label>
                          <div class='controls'>
                                <input type='number' name='exp_days' placeholder='Enter number of days required' class='form-control span10'>
                            </div>
                        </div>    

                    <div class='control-group'>
                      <label class='control-label' for='typeahead'> No. of days used  </label>
                          <div class='controls'>
                                <input type='number' name='days' placeholder='Enter No. of days used' class='form-control span10'>
                            </div>
                        </div>     

                    <button type='submit' class='btn btn-primary'> <b>Measure ".GetCatNameId($connection,$k)." </b></button>
                                 </td>  </tr></form>";
}




// function to get assesment type from id
function GetTypNameId($connection,$id){
    $query= $connection->query("SELECT * FROM assessment_type WHERE astyp_id = '$id' ") ;
    while ($row = $query->fetch()) {
        $name = $row['astyp_name'] ;
        return ("$name") ;
    }
}

// function to get assesment param Ctegory from id
function GetCatNameId($connection,$id){
    $query= $connection->query("SELECT * FROM assessment_params_category WHERE aspcat_id ='$id'") ;
    while ($row = $query->fetch()) {
        $name = $row['aspcat_name'];
        return("$name");
    }
}

// function to get Personality Ass Id

 function GetPersoNameId($connection,$id){
    $query= $connection->query("SELECT * FROM personality WHERE id ='$id' ") ;
    while ($row = $query->fetch()) {
        $name = $row['pname'] ;
        return("$name");
    }
}

// function to get kpi from id
function GetKPIId($connection,$id){
    $query= $connection->query("SELECT * FROM kpi WHERE kpi_id ='$id' ") ;
    while ($row = $query->fetch()) {
        $name = $row['kpi_name'] ;
        return("$name") ;
    }
}


// function to get kpi routine from id
function GetKPIRoutine($connection,$id){
    $query= $connection->query("SELECT * FROM kpi WHERE kpi_id ='$id' ") ;
    while ($row = $query->fetch()) {
        $name = $row['kpi_routine'] ;
        return ("$name");
    }
}

// function to get kpi last time link was sent out
function GetKPILastAssesmentDate($connection, $kpi)
{
    $query= $connection->query("SELECT * FROM kpi WHERE kpi_id ='$kpi'") ;
    while ($row = $query->fetch()) {
        $name = $row['lasttimelinksent'] ;
        return ("$name");
    }
}


// update last time kpi link was sent out
function UpdateKPILastTimeLinkSent($connection, $kpi, $org_id, $lasttimelinksent)
{
    $query = $connection->prepare("UPDATE kpi SET lasttimelinksent='$lasttimelinksent' WHERE kpi_id='$kpi' and org_id='$org_id'");
    $query ->execute();
    if (!$query) 
    {
        return false;
    }
    else
    {
        return true;
    }
}


// Function to register an assessment for a staff 
function RecordAssessStaff($connection,$name,$param,$score,$datetime,$typ,$cat,$usr_typ,$date,$type){
    $query =$connection->prepare("INSERT INTO `assessment`(`ass_param`, `ass_point`, `ass_typ`, `ass_param_cat`, `registered_at`, `usr_typ`, `ass_user`, `ass_date`, `param_type`) VALUES (:v1,:v2,:v3,:v4,:v5,:v6,:v7,:v8,:v9)") ;
    $query->execute(array(
            "v1" => "$param",
            "v2" => "$score",
            "v3" => "$typ",
            "v4" => "$cat",
            "v5" => "$datetime",
            "v6" => "$usr_typ",
            "v7" => "$name",
            "v8" => "$date",
            "v9" => "$type" )); 

    if ($query) {
        return true ;
    }else{
        return false ;
    }

}


// Function to register an assessment for a staff If Input fild os text
function RecordAssessStaffInput($connection,$name,$param,$score,$datetime,$typ,$cat,$usr_typ,$date,$type,$standard,$dept,$kpi,$kpi_routine,$aid,$ass_key_id, $usr_unit = 0, $ass_typ_status = 0, $session_id = 0)
{
    $query = $connection->prepare("INSERT INTO `assessment`(`ass_param`, `ass_point`, `ass_typ`, `ass_param_cat`, `registered_at`, `usr_typ`, `ass_user`, `ass_date`, `param_type`, `std_value`, `usr_dept`, `ass_kpi`, `ass_kpi_routine`, `ass_key_id`, `usr_unit`, `ass_typ_status`, `session_id` ) 
    VALUES (:v1,:v2,:v3,:v4,:v5,:v6,:v7,:v8,:v9,:v10,:v11,:v12,:v13,:v14,:v15,:v16,:v17)");
    $query->execute(array(
            "v1" => "$param",
            "v2" => "$score",
            "v3" => "$typ",
            "v4" => "$cat",
            "v5" => "$datetime",
            "v6" => "$usr_typ",
            "v7" => "$name",
            "v8" => "$date",
            "v9" => "$type",
            "v10" => "$standard",
            "v11" => "$dept",
            "v12" => "$kpi",
            "v13" => "$kpi_routine",
            "v14" => "$ass_key_id",
            "v15" => "$usr_unit",
            "v16" => "$ass_typ_status",
            "v17" => "$session_id")); 

    if ($query) {
        // get the last insert id
        $ass_id = $connection->lastInsertId();
        // update the supervisor table and set supervisor status to 1
        $connection->query("UPDATE `assessment_supervisor` SET supervisor_status='1' WHERE ass_id='$aid'");
        // return the last insert id
        return $ass_id;
    }else{
        // return false;
        echo $query->errorInfo();
    }
}


// staff assesment result
function RecordAssessStaffResult($connection,$kpi,$aid,$date_measure,$date_reg,$ass_key_id,$ass_effectiveness,$ass_efficiency,$ass_user,$ass_type)
{

    /**
     * Step 1: check if record already exist using the ass_key_id
     * Step 2: if record exist, check for effectiveness and efficiency by running empty column test
     * Step 3: else insert new row
     */

    // check if record already exist using the ass_key_id
    $query = $connection->query("SELECT * FROM assessment_result WHERE ass_key_id = '$ass_key_id' and ass_user = '$ass_user' and ass_type = '$ass_type'");
    // execute
    $query->execute();
    // count
    $num = $query->rowCount();
    // check record
    if($num == 0 ) 
    {
        // this means "record not found"
        // return $query->setFetchMode(PDO::FETCH_ASSOC);
        // return $query->fetchAll();
        // insert new row
        $query_new_row = $connection->prepare(
            "INSERT INTO `assessment_result`(
                `kpi_id`, `ass_id`, `ass_date_measure`, `ass_date_reg`, `ass_key_id`, `effectiveness`, `efficiency`, `ass_user`, `ass_type` 
            ) VALUES (
                :v1,:v2,:v3,:v4,:v5,:v6,:v7,:v8,:v9
            )
        ");

        $query_new_row->execute(
            array(
                "v1" => "$kpi",
                "v2" => "$aid", 
                "v3" => "$date_measure",
                "v4" => "$date_reg",
                "v5" => "$ass_key_id",
                "v6" => "$ass_effectiveness",
                "v7" => "$ass_efficiency",
                "v8" => "$ass_user",
                "v9" => "$ass_type"
            )
        ); 
    }
    else
    {
        // return false;
        // if record exist, check for effectiveness and efficiency by running empty column test
        // update the table and set supervisor status to 1
        // $query_check = $connection->query("SELECT * FROM assessment_result WHERE kpi_id ='$kpi'") ;
        while ($row = $query->fetch()) {
            // check if effectiveness is empty
            if ($row['effectiveness'] == 0) {
                $connection->query("UPDATE `assessment_result` SET effectiveness='$ass_effectiveness' WHERE ass_key_id='$ass_key_id'");
            } elseif ($row['efficiency'] == 0) {
                $connection->query("UPDATE `assessment_result` SET efficiency='$ass_efficiency' WHERE ass_key_id='$ass_key_id'");
            }
        }
    }
}



// Function to decline assesment
function DeclineAssessStaffInput($connection,$aid)
{
    $query = $connection->query("UPDATE assessment_supervisor SET supervisor_status='2' WHERE ass_id='$aid'");
    if ($query) 
    {
        // return the last insert id
        return true;
    }
    else
    {
        return false ;
    }
}


// Function Get Supervisors
function GetAssessmentSupervisorsRecord($connection,$id,$assparam)
{
    // assign query
    $sql = "SELECT * FROM assessment_supervisor where ass_id='$id' and ass_param_cat='$assparam'";
    // query
    $query = $connection ->query($sql) ;
    // execute
    $num =  $query->execute();
    
    if($num !=0) 
    {
        // return $query->setFetchMode(PDO::FETCH_ASSOC);
        return $query->fetchAll();
    }
    else
    {
        return false;
    }
}


// Function Get Supervisors
function GetHoursWorkedSupervisor($connection,$id,$kpi,$staff_id)
{
    // assign query
    $sql = "SELECT * FROM hours_worked_supervisor where ass_id='$id' and ass_kpi='$kpi' and user='$staff_id'";
    // query
    $query = $connection ->query($sql) ;
    // execute
    $num =  $query->execute();
    
    if($num !=0) 
    {
        // return $query->setFetchMode(PDO::FETCH_ASSOC);
        return $query->fetchAll();
    }
    else
    {
        return false;
    }
}


// Function Get Supervisors
function GetEfficiencyValue($connection,$org_id,$ass_key_id)
{
    // assign query
    $sql = "SELECT ass_id,ass_key_id,ass_param_cat FROM assessment_supervisor where ass_key_id='$ass_key_id'";
    // query
    $query = $connection ->query($sql) ;
    // execute
    $num =  $query->execute();
    
    if($num !=0) 
    {
        // return $query->setFetchMode(PDO::FETCH_ASSOC);
        return $query->fetchAll();
    }
    else
    {
        return false;
    }
}

// Function to register an assessment for a staff If Input fild os text
// this is first going to the supervisor
// he approves or declines
function RecordAssessStaffInputForSupervisor($connection,$name,$param,$score,$datetime,$typ,$cat,$usr_typ,$date,$type,$standard,$dept,$kpi,$kpi_routine,$ass_key_id,$session_id = 0, $unit = 0, $ass_typ_status=0)
{
    $query =$connection->prepare("INSERT INTO assessment_supervisor(`ass_param`, `ass_point`, `ass_typ`, `ass_param_cat`, `registered_at`, `usr_typ`, `ass_user`, `ass_date`, `param_type`, `std_value`, `usr_dept`, `usr_unit`,`ass_kpi`, `ass_kpi_routine`, `ass_key_id`, `ass_typ_status`, `session_id`) VALUES (:v1,:v2,:v3,:v4,:v5,:v6,:v7,:v8,:v9,:v10,:v11,:v12,:v13,:v14,:v15,:v16,:v17)") ;
    $query->execute(array(
            "v1" => "$param",
            "v2" => "$score",
            "v3" => "$typ",
            "v4" => "$cat",
            "v5" => "$datetime",
            "v6" => "$usr_typ",
            "v7" => "$name",
            "v8" => "$date",
            "v9" => "$type",
            "v10" => "$standard",
            "v11" => "$dept",
            "v12" => "$unit",
            "v13" => "$kpi",
            "v14" => "$kpi_routine",
            "v15" => "$ass_key_id",
            "v16" => "$ass_typ_status",
            "v17" => "$session_id")); 

    if ($query) {
        // get the last insert id
        $ass_id = $connection->lastInsertId();
        // return the last insert id
        return $ass_id;
    }else{
        return false ;
    }

}




// function to record a staff No of working Hour 
function RecordUserHour($connection,$name,$date,$hours,$exp_hours,$days,$exp_days,$ass_id,$kpi)
{
    $query = $connection ->query("INSERT INTO hours_worked SET user = '$name',  
                                                               wdate = '$date', 
                                                               whours_used = '$hours', 
                                                               whours_expected = '$exp_hours', 
                                                               wdaysexpected = '$exp_days', 
                                                               wdaysused = '$days',
                                                               ass_id = '$ass_id',
                                                               ass_kpi = '$kpi'") ;
    if ($query) {
        return true ;
    }
}


// function to record a staff No of working Hour 
function RecordUserHourSupervisor($connection,$name,$date,$hours,$exp_hours,$days,$exp_days,$ass_id,$kpi,$index=0)
{
    $query = $connection->query("INSERT INTO hours_worked_supervisor SET user = '$name',  
                                                               wdate = '$date', 
                                                               whours_used = '$hours', 
                                                               whours_expected = '$exp_hours', 
                                                               wdaysexpected = '$exp_days', 
                                                               wdaysused = '$days',
                                                               w_index = '$index',
                                                               ass_id = '$ass_id',
                                                               ass_kpi = '$kpi'") ;
    if ($query) {
        return true ;
    }
}


// Function to register an assessment for a Department 

function RecordAssessDept($connection,$name,$param,$score,$datetime,$typ,$cat,$usr_typ,$date,$type,$standard){
    $query =$connection->prepare("INSERT INTO `assessment`(`ass_param`, `ass_point`, `ass_typ`, `ass_param_cat`, `registered_at`, `usr_typ`, `ass_user`, `ass_date`, `param_type`, `std_value`) VALUES (:v1,:v2,:v3,:v4,:v5,:v6,:v7,:v8,:v9,:v10)") ;
    $query->execute(array(
            "v1" => "$param",
            "v2" => "$score",
            "v3" => "$typ",
            "v4" => "$cat",
            "v5" => "$datetime",
            "v6" => "$usr_typ",
            "v7" => "$name",
            "v8" => "$date", 
            "v9" => "$type",
            "v10" => "$standard")); 

    if ($query) {
        return true ;
    }else{
        return false ;
    }

}

// Function to get tne Number of staff in a department 
function GetNumStaffDept($connection,$id)
{
    $query = $connection->query("SELECT * FROM staff WHERE sta_dept = '$id' ") ;
    $num = $query->rowCount() ;
    return($num) ;
}

// Function to get tne Number of units in a department 
function GetNumUnitDept($connection,$id)
{
    $query = $connection->query("SELECT * FROM unit WHERE uni_dept = '$id' ") ;
    $num = $query->rowCount() ;
    return($num) ;
}

// Function to get tne Number of staff in a Unit 
function GetNumStaffUnit($connection,$id)
{
    $query = $connection->query("SELECT distinct staff FROM staff_unit WHERE unit = '$id' ") ;
    $num = $query->rowCount() ;
    return($num) ;
}


// Function to get tne Number of staff in org 

function GetNumStafft($connection)
{
    $query = $connection->query("SELECT sta_id FROM staff  ") ;

    $num = $query->rowCount() ;
    return($num) ;
}


// Function to get tne Number of dept in org 

function GetNumDeptt($connection)
{
    $query = $connection->query("SELECT * FROM department") ;

    $num = $query->rowCount() ;
    return($num) ;
}
// Function to get tne Number of units in org 

function GetNumUnit($connection)
{
    $query = $connection->query("SELECT * FROM unit  ") ;

    $num = $query->rowCount() ;
    return($num) ;
}


// Function to get tne Number of test params in org 

function GetNumAssParam($connection)
{
    $query = $connection->query("SELECT * FROM  assessment_params  ") ;

    $num = $query->rowCount() ;
    return($num) ;
}





// GET Analetics For report for staff perfomance

// Function to get staff expected target Category
function GetStaffExpTargetCat($connection,$id,$cat,$kpi,$ass_id)
{
    if (!empty($ass_id)) {
        $query = $connection->query("SELECT SUM(std_value) FROM assessment WHERE ass_id = '$ass_id' AND ass_user = '$id' AND ass_param_cat = '$cat' AND ass_kpi = '$kpi'") ;
    } else {
        $query = $connection->query("SELECT SUM(std_value) FROM assessment WHERE ass_user = '$id' AND ass_param_cat = '$cat' AND ass_kpi = '$kpi'");
    }

    while ($row = $query->fetch()) {
        $expected = $row['SUM(std_value)'] ;
        return "$expected" ;
    }
}


// Function to get staff expected target Observed
function GetStaffObservTargetCat($connection,$id,$cat,$kpi,$ass_id)
{
    if (!empty($ass_id)) {
        $query = $connection->query("SELECT SUM(ass_point) FROM assessment WHERE ass_id = '$ass_id' AND ass_user = '$id' AND ass_param_cat = '$cat' AND ass_kpi = '$kpi'") ;
    } else {
        $query = $connection->query("SELECT SUM(ass_point) FROM assessment WHERE ass_user = '$id' AND ass_param_cat = '$cat' AND ass_kpi = '$kpi'");
    }
    while ($row = $query->fetch()) {
        $observed = $row['SUM(ass_point)'] ;
        return "$observed" ;
    }
}


// 

function GetStaffReport($connection,$id)
{
    $query = $connection->query("SELECT distinct ass_param_cat FROM assessment WHERE usr_typ = 'staff' ") ; 
    while ($row = $query->fetch()) 
    {
        $cat = $row['ass_param_cat'];
        // Get Cummulative for a staff and their performance 
        GetCumParam($connection,$id,$cat);
    }
}


function GetStaffReportTimely($connection,$id,$tym){
    $query = $connection->query("SELECT distinct ass_param_cat FROM assessment WHERE usr_typ = 'staff'  AND ass_date = '$tym' ") ;

    echo "<table class='table datatable striped'>
                    <tbody>";
    while ($row = $query->fetch()) {

        $cat = $row['ass_param_cat'] ;
      //  foreach ($row as $key ) {
            // Get Cummulative for a staff and their performance 
      echo      GetCumParamTimely($connection,$id,$cat) ;

      //  }
    }
    echo "</tbody></table>";
}


function GetStaffReportAllss($connection,$id    ){
    $query = $connection->query("SELECT distinct ass_param_cat FROM assessment WHERE usr_typ = 'staff'  ") ; 
    echo "<table class='table datatable striped'>
                    <tbody>";
    while ($row = $query->fetch()) {

        $cat = $row['ass_param_cat'] ;
      //  foreach ($row as $key ) {
            // Get Cummulative for a staff and their performance 
       echo     GetCumParamTimely($connection,$id,$cat) ;

      //  }
    }
    echo "</tbody></table>";
}


function GetUnitReport($connection,$id){
    $query = $connection->query("SELECT distinct ass_param_cat FROM assessment WHERE usr_typ = 'unit' ") ; 
    while ($row = $query->fetch()) {

        $cat = $row['ass_param_cat'] ;
      //  foreach ($row as $key ) {
            // Get Cummulative for a staff and their performance 
            GetCumParam($connection,$id,$cat) ;

      //  }
    }
}



function GetCumParamTimely($connection,$id,$cat)
{
    $query = $connection->query("SELECT sum(ass_point) as sum FROM assessment WHERE  ass_user = '$id' AND ass_param_cat = '$cat' ") ;

    $expected = GetStaffExpTargetCat($connection,$id,$cat) ;
    $observed = GetStaffObservTargetCat($connection,$id,$cat) ;

   // $count = $count * 6 ;

 //   while ($row = $query->fetch()) {
   //     $sum = $row['sum'] ;
      //  $perscent = $sum * 100 / $count ; 
    if ($expected && $observed != 0) {
        # code...
    
        $perscent = $observed/$expected*100 ;

        $perscent = round($perscent, 2) ;
       // echo "$perscent" ;
    }
    else
    {
        $perscent = 0;
    }

    //  echo "$perscent";
    return "<tr><td>".GetCatNameId($connection,$cat)." </td>
    <td>".($perscent/100) ." <span align='right'> (<b>".$perscent."%</b>)</span></td></tr>";
}
        



function GetCumParam($connection,$id,$cat){
    $query = $connection->query("SELECT sum(ass_point) as sum FROM assessment WHERE  ass_user = '$id' AND ass_param_cat = '$cat' ") ;

    $expected = GetStaffExpTargetCat($connection,$id,$cat) ;
    $observed = GetStaffObservTargetCat($connection,$id,$cat) ;

   // $count = $count * 6 ;

 //   while ($row = $query->fetch()) {
   //     $sum = $row['sum'] ;
      //  $perscent = $sum * 100 / $count ; 
    if ($expected && $observed != 0) {
        # code...
    
        $perscent = $observed/$expected*100 ;

        $perscent = round($perscent, 2) ;
       // echo "$perscent" ;
    }
    else{
        $perscent = 0;
    }

        echo "<li>
                                <h5> ".GetAssParamCatId($connection,$cat)." ( $perscent% )</h5>
                                <div class='meter red'><span style='width: $perscent%'></span></div><!-- Edite width here -->
                            </li>";
    }
        



// GET Analetics For report for dept  perfomance

// 

function GetDeptReport($connection,$id){
    $query = $connection->query("SELECT distinct ass_param_cat FROM assessment WHERE usr_typ = 'dept' ") ; 
    while ($row = $query->fetch()) {

        $cat = $row['ass_param_cat'] ;  
      //  foreach ($row as $key ) {
            // Get Cummulative for a staff and their performance 
            GetCumParam($connection,$id,$cat) ;

      //  }
    }
}


// function GetCumParam($connection,$id,$cat){
//     $query = $connection->query("SELECT sum(ass_point) as sum FROM assessment WHERE  ass_user = '$id' AND ass_param_cat = '$cat' ") ;

//     $count = $query->rowCount();

//     $count = $count * 6 ;

//     while ($row = $query->fetch()) {
//         $sum = $row['sum'] ;
//         $perscent = $sum * 10 / $count ;
//        // echo "$perscent" ;
//         echo "<li>
//                                 <h5> ".GetAssParamCatId($connection,$cat)." ( $perscent% )</h5>
//                                 <div class='meter yellow'><span style='width: $perscent%''></span></div><!-- Edite width here -->
//                             </li>";
        
//     }
// }

 function GetStaffInDeptPerfom($connection,$id){
    $query = $connection->query("SELECT dept_id FROM department ") ;
    while ($row = $query->fetch()) {
        $did = $row['dept_id']; 
    //    foreach ($row as $key ) {
        echo "<div class='singleBar'>
                            
                                <div class='bar'>
                                
                                    <div class='value'>
                                        <span>".doSomthing($connection,$did)."%</span>
                                    </div>
                                
                                </div>
                                
                                <div class='title'>".GetDeptId($connection,$did)."</div>
                            
                            </div>";

             


      //  }
    }
   
 }

function doSomthing($connection,$did){
     $query = $connection->query("SELECT ass_user FROM assessment WHERE usr_dept = '$did' ") ;
     if ($query->rowCount() != 0) {
             while ($row = $query->fetch()) {
             $sid = $row['ass_user']; 
             return  DoSomthingElse($connection,$did) ;
             }
     }else{
        return 0 ;
     }
}

function DoSomthingElse($connection,$did){
     
     $expected = GetDeptExpIndex($connection,$did) ;
     $observed = GetDeptObservIndex($connection,$did) ;

             $sum =$observed/$expected *100 ;
             if ($sum != 0) {
             
             return round($sum, 2);
       }
       else {
        return 0 ;
       }
}


// Function to get dept On index expected target Category

function GetDeptExpIndex($connection,$id){
    $query = $connection->query("SELECT SUM(std_value) FROM assessment WHERE usr_dept = '$id' ") ;
    while ($row = $query->fetch()) {
        $expected = $row['SUM(std_value)'] ;
        return "$expected" ;
    }
}


// Function to get dept On index expected target Observed

function GetDeptObservIndex($connection,$id){
    $query = $connection->query("SELECT SUM(ass_point) FROM assessment WHERE usr_dept = '$id' ") ;
    while ($row = $query->fetch()) {
        $observed = $row['SUM(ass_point)'] ;
        return "$observed" ;
    }
}



function GetNumOfAssRows($connection,$id,$typ){
    $query = $connection->query("SELECT ass_user from assessment WHERE ass_user = '$id' AND usr_typ = '$typ'AND usr_typ = 'staff'   "); 
    $num = $query->rowCount(); 
    return("$num") ;
}


// expected Target = sum of std_value in 

// first get date of staff 

function GetTestDates($connection,$id,$type)
{
    $query = $connection->query("SELECT registered_at,  ass_date  FROM assessment WHERE ass_user ='$id' AND usr_typ = '$type' GROUP BY ass_date ") ;

    // echo "<div class='panel-group' id='accordion' role='tablist' aria-multiselectable='true'>";
    while ($row = $query->fetch()) 
    {
        $dates = $row['ass_date'] ;
        $times = $row['registered_at'] ;

        $hours = GetStaffHoursWorkedDate($connection,$id,$times,$type) ;
        $exp_hours = GetStaffExpextedHoursDate($connection,$id,$times,$type) ;
        $days = GetStaffDaysWorkedDate($connection,$id,$times,$type) ;
        $exp_days = GetStaffExpextedDaysDate($connection,$id,$times,$type) ;
        // $time = $row['registered_at'] ;
        // $hours = GetStaffHoursWorkedDate($connection,$id,$dates,$type) ;
        $observed = GetStaffAchTargetDate($connection,$id,$dates,$type) ;
        $expected = GetStaffExpTargetDate($connection,$id,$dates,$type) ;

        echo "$dates<hr>";
        GetStaffTestDay($connection,$id,$dates,$hours,$exp_hours,$days,$exp_days,$expected,$observed,$type) ;
       
        // echo "<hr><a class='btn btn-sm btn-danger'> Print Report </a> </div> </div> </div>";

    }
    // echo "</div></div>";
}

// Get Tests of a staff in a day 
function GetStaffTestDay($connection,$id,$date,$hours,$exp_hours,$days,$exp_days,$expected,$observed,$type){
    $query = $connection->query("SELECT * FROM assessment WHERE ass_user = '$id' AND ass_date = '$date' AND usr_typ = '$type'  ") ;
     $num = $query->rowCount() ;
             
        while ($row = $query->fetch() ) {
            
            $param = $row['ass_param'] ;
            $point = $row['ass_point'] ;
            $ids = $row['ass_id'] ;
            $date = $row['ass_date'] ;
            $category = $row['ass_param_cat'];

            $labour = CheckLabour($connection,$type,$id) ;

            $input_per_person_per_unit = round($expected/(1*$exp_hours*$labour), 2); 

            $output_per_person_per_unit = round($observed/(1*$hours*$labour), 2); 
            $efficiency = round($output_per_person_per_unit / $input_per_person_per_unit, 2)  ;
            $perscent = round($efficiency *100, 2) ;

            //$name = "$fname ".  " $lname ";


            echo "<tr height='30px'>
                      <td class='description'>
                                ".GetAssParamId($connection,$param)."
                            </td>
                            <td>
                                ".GetCatNameId($connection,$category)."
                            </td>
                            <td>
                                ".CheckResult($connection,$ids)."
                            </td>
                        </tr>";

        }
        echo "     </tbody>
                              </table>     
                <table class='table datatable striped'>
                    <tbody>
                     <!--   <tr>
                            <td>Expected Target </td>
                            <td>$expected points</td>
                        </tr>
                        <tr>
                            <td>Observed  </td>
                            <td>$observed points</td>
                        </tr> 
                        <tr>
                            <td>Labour </td>
                            <td> ".$labour." </td>
                        </tr>
                        <tr>
                            <td>Hours worked </td>
                            <td>$hours hour(s)</td>
                        </tr>
                        <tr>
                            <td>No. of days </td>
                            <td>$days day</td>
                        </tr>-->
                        <tr>
                            <td>Output Per Person per Unit </td>
                            <td>$output_per_person_per_unit</td>
                        </tr>
                        <tr>
                            <td>Input Per Person per Unit </td>
                            <td>$input_per_person_per_unit</td>
                        </tr>
                      <!--  <tr>
                            <td>Efficiency </td>
                            <td>$efficiency <span align='right'> (<b>".$perscent."%</b>)</span></td>
                        </tr> -->
                    </tbody>
                </table>
                        ".GetStaffReportTimely($connection,$id,$date)."
";
}

// Get Input per personper unit for categories 

function GetInputPerPersonPerUnitCat($connection,$dates,$cats){
    // $query = "SELECT"
}

// Function to check and retun No of Labour 

function CheckLabour($connection,$type,$id){
    if ($type == "staff") {
        return "1";
    }elseif ($type == "unit") {
      $labour =  GetNumStaffUnit($connection,$id) ;
      return "$labour" ;
    }
}


// INPUT PER PERSON PER UNIT =  EXPECTED TARGET /LABOUR X DAILY HOURS WORKED X TOTAL NUMBER OF DAYS WORKED

// Function to get staff expected target 
function GetStaffExpTargetDate($connection,$id,$date,$type){
    $query = $connection->query("SELECT SUM(std_value) FROM assessment WHERE ass_user = '$id' AND ass_date = '$date' AND usr_typ = '$type'  ") ;
    while ($row = $query->fetch()) {
        $expected = $row['SUM(std_value)'] ;
        return "$expected" ;
    }
}


// Function to get staff Hours Worked 
function GetStaffHoursWorkedDate($connection,$id,$ass_id,$kpi)
{
    if ($kpi)
    {
        $query = $connection->query("SELECT whours_used FROM hours_worked WHERE user = '$id' AND ass_kpi = '$kpi'");
    }
    else
    {
        $query = $connection->query("SELECT whours_used FROM hours_worked WHERE user = '$id' AND ass_id = '$ass_id'");
    }
    while ($row = $query->fetch())
    {
        $expected = $row['whours_used'];
        return "$expected" ;
    }
}



// Function to get staff Expexcted Hours Worked 
function GetStaffExpextedHoursDate($connection,$id,$ass_id,$kpi)
{
    if($kpi)
    {
        $query = $connection->query("SELECT whours_expected FROM hours_worked WHERE user = '$id' AND ass_kpi = '$kpi'");
    }
    else
    {
        $query = $connection->query("SELECT whours_expected FROM hours_worked WHERE user = '$id' AND ass_id = '$ass_id'");
    }
    while ($row = $query->fetch()) 
    {
        $expected = $row['whours_expected'];
        return "$expected";
    }
}



// Function to get staff Expexcted Days Worked 
function GetStaffExpextedDaysDate($connection,$id,$ass_id,$kpi)
{
    if ($kpi)
    {
        $query = $connection->query("SELECT wdaysexpected FROM hours_worked WHERE user = '$id' AND ass_kpi = '$kpi'");
    }
    else
    {
        $query = $connection->query("SELECT wdaysexpected FROM hours_worked WHERE user = '$id' AND ass_id = '$ass_id'");
    }
    
    while ($row = $query->fetch()) 
    {
        $expected = $row['wdaysexpected'] ;
        return "$expected" ;
    }
}


// Function to get staff  Days Worked 

function GetStaffDaysWorkedDate($connection,$id,$ass_id,$kpi)
{
    if ($kpi)
    {
        $query = $connection->query("SELECT wdaysused FROM hours_worked WHERE user = '$id' AND ass_kpi = '$kpi'");
    }
    else
    {
        $query = $connection->query("SELECT wdaysused FROM hours_worked WHERE user = '$id' AND ass_id = '$ass_id'");
    }
    while ($row = $query->fetch()) {
        $expected = $row['wdaysused'] ;
        return "$expected" ;
    }
}


// Function to get staff Achieved Points 

function GetStaffAchTargetDate($connection,$id,$date,$type){
    $query = $connection->query("SELECT SUM(ass_point) FROM assessment WHERE ass_user = '$id' AND ass_date = '$date' AND usr_typ = '$type' ") ;
    while ($row = $query->fetch()) {
        $expected = $row['SUM(ass_point)'] ;
        return "$expected" ;
    }
}


// Get efficiency of a staff in a day 

function GetStaffEfficiencyDay($connection,$id,$date,$type){
    $query = $connection->query("SELECT * FROM assessment WHERE ass_user = '$id' and ass_date = '$date' AND usr_typ = '$type' ") ;
     $num = $query->rowCount() ;
     while ($row = $query->fetch()) { 

         
     }
}

// Function to get staff expected target 

function GetStaffExpTargetAll($connection,$id,$type){
    $query = $connection->query("SELECT SUM(std_value) FROM assessment WHERE ass_user = '$id' AND usr_typ = '$type' ") ;
    while ($row = $query->fetch()) {
        $expected = $row['SUM(std_value)'] ;
        return "$expected" ;
    }
}


// Function to get staff Hours Worked 

function GetStaffHoursWorkedAll($connection,$id,$type){
    $query = $connection->query("SELECT SUM(whours_used) AS sum FROM hours_worked WHERE user = '$id' AND user_type = '$type'   ") ;
    while ($row = $query->fetch()) {
        $expected = $row['sum'] ;
        return "$expected" ;
    }
}


// Function to get expected staff Hours Worked 

function GetStaffExpHoursWorkedAll($connection,$id,$type){
    $query = $connection->query("SELECT SUM(whours_expected) AS sum FROM hours_worked WHERE user = '$id' AND user_type = '$type'   ") ;
    while ($row = $query->fetch()) {
        $expected = $row['sum'] ;
        return "$expected" ;
    }
}



// Function to get staff Days Expected Worked 

function GetStaffExpDaysWorkedAll($connection,$id,$type){
    $query = $connection->query("SELECT SUM(wdaysexpected) AS sum FROM hours_worked WHERE user = '$id' AND user_type = '$type'   ") ;
    while ($row = $query->fetch()) {
        $expected = $row['sum'] ;
        return "$expected" ;
    }
}


// Function to get staff Days Worked 

function GetStaffDaysWorkedAlls($connection,$id,$type){
    $query = $connection->query("SELECT SUM(wdaysused) AS sum FROM hours_worked WHERE user = '$id' AND user_type = '$type'   ") ;
    while ($row = $query->fetch()) {
        $expected = $row['sum'] ;
        return "$expected" ;
    }
}


// // Function to get staff Hours Worked 

// function GetStaffHoursWorkedDate($connection,$id,$times,$type){
//     $query = $connection->query("SELECT whours_used FROM hours_worked WHERE user = '$id' AND registered_at = '$times' AND user_type = '$type' ") ;
//     while ($row = $query->fetch()) {
//         $expected = $row['whours_used'] ;
//         return "$expected" ;
//     }
// }


// Function to get staff Days Worked 

function GetStaffDaysWorkedAll($connection,$id,$type){
    $query = $connection->query("SELECT distinct wdate  FROM hours_worked WHERE user = '$id' AND user_type = '$type' ") ;
   
        $count = $query ->rowCount() ;
        return "$count" ;
}

// Function to get staff Achieved Points 

function GetStaffAchTargetAll($connection,$id,$type){
    $query = $connection->query("SELECT SUM(ass_point) FROM assessment WHERE ass_user = '$id' AND usr_typ = '$type' ") ;
    while ($row = $query->fetch()) {
        $expected = $row['SUM(ass_point)'] ;
        return "$expected" ;
    }
}




// Get Tests of a staff  

function GetStaffTestAll($connection,$id,$type){
    $query = $connection->query("SELECT * FROM assessment WHERE ass_user = '$id' AND usr_typ = '$type' ") ;
     $num = $query->rowCount() ;

     if ($num != 0) {
         
     
             
        while ($row = $query->fetch() ) {
            
            $param = $row['ass_param'] ;
            $point = $row['ass_point'] ;
            $id = $row['ass_user'] ;
            $aid = $row['ass_id'] ;
            $date = $row['ass_date'] ;
            $category = $row['ass_param_cat'] ;
            $labour = CheckLabour($connection,$type,$id) ;

            $hours = GetStaffHoursWorkedAll($connection,$id,$type) ;

            $exp_hours = GetStaffExpHoursWorkedAll($connection,$id,$type) ; 

            $days = GetStaffDaysWorkedAlls($connection,$id,$type) ; 

            $exp_days = GetStaffExpDaysWorkedAll($connection,$id,$type) ;




            $observed = GetStaffAchTargetAll($connection,$id,$type) ;
            $expected = GetStaffExpTargetAll($connection,$id,$type) ; 

            //  $days =  GetStaffDaysWorkedAll($connection,$id,$type) ;

            $input_per_person_per_unit = round($expected/($labour*$exp_hours*$exp_days), 2); 

            $output_per_person_per_unit = round($observed/($labour*$hours*$days), 2); 
            $efficiency = round($output_per_person_per_unit / $input_per_person_per_unit, 2)  ;
            $perscent = round($efficiency *100, 2) ;

            //$name = "$fname ".  " $lname ";
}
        echo "<table class='table datatable striped'>
                    <tbody>
                       <!-- <tr>
                            <td>Expected Target </td>
                            <td>$expected points</td>
                        </tr>
                        <tr>
                            <td>Observed  </td>
                            <td>$observed points</td>
                        </tr> 
                        <tr>
                            <td>Labour </td>
                            <td> $labour </td>
                        </tr>
                        <tr>
                            <td>Hours worked </td>
                            <td>$hours hour(s)</td>
                        </tr>
                        <tr>
                            <td>No. of days </td>
                            <td>$days day</td>
                        </tr>-->
                        <tr>
                            <td>Output Per Person per Unit </td>
                            <td>$output_per_person_per_unit</td>
                        </tr>
                        <tr>
                            <td>Input Per Person per Unit </td>
                            <td>$input_per_person_per_unit</td>
                        </tr>
                    <!--    <tr>
                            <td>Efficiency </td>
                            <td>$efficiency <span align='right'> (<b>".$perscent."%</b>)</span></td>
                        </tr> -->
                    </tbody>
                </table>
                ".GetStaffReportAllss($connection,$id)."
";

       }
}
// rewriten for the process Above 

function GetStaffTestAllss($connection,$id,$type,$kpi,$listtype)
{
    $v = 1;
    $query = $connection->query("SELECT * FROM assessment WHERE usr_typ = 'staff' and ass_kpi = '$kpi' and ass_user = '$id' group by ass_param_cat"); 
    while ($row = $query->fetch())
    {
        $cat = $row['ass_param_cat'];
        $ass_id = $row['ass_id'];
        $ass_kpi_routine = $row['ass_kpi_routine'];      
        // Get Cummulative for a staff and their performance 
        GetCumParamAS($connection,$id,$cat,$kpi,$ass_id,$ass_kpi_routine,'');
        // index performance
        $v = $v * GetCumParamVAI($connection,$id,$cat,$kpi,$ass_id);
    }

    echo "<tr><td><b>Value Added Index:</b></td><td><b>$v</b></td></tr>";
}


function GetCumParamAS($connection,$id,$cat,$kpi,$ass_id,$ass_kpi_routine,$listtype)
{
    // error_reporting(0);
    // $value_added_index = 1;
    // $query = $connection->query("SELECT sum(ass_point) as sum FROM assessment WHERE  ass_user = '$id' AND ass_kpi = '$kpi'");
    
    if ($listtype == "historylist") {
        $expected = GetStaffExpTargetCat($connection,$id,$cat,$kpi,$ass_id);
        $observed = GetStaffObservTargetCat($connection,$id,$cat,$kpi,$ass_id);

        $hours = GetStaffHoursWorkedDate($connection,$id,$ass_id,'');
        $exp_hours = GetStaffExpextedHoursDate($connection,$id,$ass_id,''); 
        //$days = GetStaffDaysWorkedAlls($connection,$id,$type="staff") ; 
        $days = GetStaffDaysWorkedDate($connection,$id,$ass_id,''); 
        $exp_days = GetStaffExpextedDaysDate($connection,$id,$ass_id,'');

    } else {
        $expected = GetStaffExpTargetCat($connection,$id,$cat,$kpi,'') ;
        $observed = GetStaffObservTargetCat($connection,$id,$cat,$kpi,'');

        $hours = GetStaffHoursWorkedDate($connection,$id,$ass_id,$kpi);
        $exp_hours = GetStaffExpextedHoursDate($connection,$id,$ass_id,$kpi); 
        //$days = GetStaffDaysWorkedAlls($connection,$id,$type="staff") ; 
        $days = GetStaffDaysWorkedDate($connection,$id,$ass_id,$kpi); 
        $exp_days = GetStaffExpextedDaysDate($connection,$id,$ass_id,$kpi) ;

    }

    
    $input_per_person_per_unit = round($expected/(1 *$exp_hours*$exp_days), 2); 
    $output_per_person_per_unit = round($observed/(1*$hours*$days), 2);

    // $count = $count * 6 ;

    //   while ($row = $query->fetch()) {
    //     $sum = $row['sum'] ;
      //  $perscent = $sum * 100 / $count ; 
    if ($expected && $observed != 0) {
        # code...
        $perscent = $observed/$expected ;
        $perscent = round($perscent, 2) ;
    }
    else
    {
        $perscent = 0;
    }

    if ($listtype == "historylist")
    {
        echo "<tr>
            <td><b>".ucfirst($ass_kpi_routine)." ".date("Y")."</b></td>
            <td><b>".GetCatNameId($connection,$cat)."</b></td>
            <td><b>$perscent</b></td>
            <td>$input_per_person_per_unit</td>
            <td>$output_per_person_per_unit</td>
            <td>".GetCumParamVAI($connection,$id,$cat,$kpi,$ass_id)."</td>
        </tr>";

        // echo "<tr><td colspan='5'>".GetCumParamVAI($connection,$id,$cat,$kpi,$ass_id)."</td></tr>";
    }

    else

    {

        echo "<tr><td><b>".GetCatNameId($connection,$cat)."</b></td><td><b>$perscent</b></td></tr> 
        <tr><td>Input Per Person Person Unit</td><td> $input_per_person_per_unit </td></tr> 
        <tr><td>Output Per Person per unit </td><td> $output_per_person_per_unit </td></tr>";
    }

    /**/
}

function GetCumParamVAI($connection,$id,$cat,$kpi,$ass_id)
{
    
    $value_added_index = 1;
    // $query = $connection->query("SELECT sum(ass_point) as sum FROM assessment WHERE  ass_user = '$id' AND ass_param_cat = '$cat' ") ;

    $expected = GetStaffExpTargetCat($connection,$id,$cat,$kpi,$ass_id);
    $observed = GetStaffObservTargetCat($connection,$id,$cat,$kpi,$ass_id);
    $hours = GetStaffHoursWorkedDate($connection,$id,$ass_id,'');
    $exp_hours = GetStaffExpextedHoursDate($connection,$id,$ass_id,''); 

    //$days = GetStaffDaysWorkedAlls($connection,$id,$type="staff") ; 
    $days = GetStaffDaysWorkedDate($connection,$id,$ass_id,''); 
    $exp_days = GetStaffExpextedDaysDate($connection,$id,$ass_id,'');
    
    $input_per_person_per_unit = round($expected/(1 *$exp_hours*$exp_days), 2);
    $output_per_person_per_unit = round($observed/(1*$hours*$days), 2);

    if ($expected && $observed != 0) {
        # code...
        $perscent = $observed/$expected ;
        $perscent = round($perscent, 2) ;
        // echo "$perscent" ;
        $value_added_index = $value_added_index * $perscent;
    }
    else
    {
        $perscent = 0;
    }
    return $value_added_index;
}


function GetStaffKPIHistory($connection,$id,$kpi,$listtype,$ass_key_id,$ass_type)
{
    $v = 1;
    // query using the Kpi ID and user id
    $query = $connection->query("SELECT * FROM assessment_result WHERE ass_user='$id' AND kpi_id='$kpi' and ass_type='$ass_type'"); 
    while ($row = $query->fetch())
    {
        // $cat = $row['ass_param_cat'];
        // $ass_id = $row['ass_id']; 
        $date_measured = $row['ass_date_measure'];
        $effectiveness = $row['effectiveness'];
        $efficiency = $row['efficiency'];
        $value = $effectiveness * $efficiency;
        $ass_id = $row['ass_id'];
        $ass_key_id = $row['ass_key_id'];
        $level = $ass_type;

        // // // Get Cummulative for a staff and their performance 
        // // GetCumParamAS($connection,$id,$cat,$kpi,$ass_id,$ass_kpi_routine,$listtype);
        // // // index performance
        // // $v = $v * GetCumParamVAI($connection,$id,$cat,$kpi,$ass_id);
        // $measure_category = GetCatNameId($connection,$cat);

        // http://app.vadsoft.com.ng/handlemeasurement?name=7&kid=1&mode=measurestaffkpi&aid=23&mcategory=effectiveness&level=staff&akid=2784
        
        
        echo "<tr>
            <td><b>".$v."</b></td>
            <td><b>".date("F d, Y", strtotime($date_measured))."</b></td>
            <td>".$effectiveness."</td>
            <td>".$efficiency."</td>
            <td>".$value."</td>  
            <td><a href='resultdetails?name=$id&kid=$kpi&mode=measurestaffkpi&aid=$ass_id&level=$level&akid=$ass_key_id'><strong>Details</strong></a></td>
        </tr>";
        // <td><b>".GetCatNameId($connection,$cat)."</b></td>
        $v++;
    }
    // echo "<tr><td><b>Value Added Index:</b></td><td><b>$v</b></td></tr>";
}


function GetStaffKPIHistoryGraphValues($connection,$id,$kpi,$listtype,$ass_key_id,$ass_type,$ass_)
{
    $v = 1; $v_ = "";
    // query using the Kpi ID and user id
    $query = $connection->query("SELECT * FROM assessment_result WHERE ass_user='$id' AND kpi_id='$kpi' and ass_type='$ass_type' ORDER BY ass_date_measure ASC"); 
    while ($row = $query->fetch())
    {
        // $cat = $row['ass_param_cat'];
        // $ass_id = $row['ass_id']; 
        $date_measured = $row['ass_date_measure'];
        $effectiveness = $row['effectiveness'] * 100;
        $efficiency = $row['efficiency'] * 100;;
        $value = ($effectiveness * $efficiency) / 100;

        // // // Get Cummulative for a staff and their performance 
        // // GetCumParamAS($connection,$id,$cat,$kpi,$ass_id,$ass_kpi_routine,$listtype);
        // // // index performance
        // // $v = $v * GetCumParamVAI($connection,$id,$cat,$kpi,$ass_id);
        // $measure_category = GetCatNameId($connection,$cat);
        if ($ass_ == "effectiveness") {
            $v_ = $effectiveness . ", ". $v_;
        }

        if ($ass_ == "efficiency") {
            $v_ = $efficiency . ", ". $v_;
        }

        if ($ass_ == "productivityindex") {
            $v_ = $value . ", ". $v_;
        }

        if ($ass_ == "dateofmeasure") {
            $v_ = "'".$date_measured . "', ". $v_;
        }


        // <td><b>".GetCatNameId($connection,$cat)."</b></td>
        $v++;
    }
    return $v_;
    // echo "<tr><td><b>Value Added Index:</b></td><td><b>$v</b></td></tr>";
}


// Function to get staff expected target Btw Dates
function GetStaffExpTargetBtw($connection,$id,$start,$end)
{
   // $start = DATE_FORMAT($start,'%Y-%m-%d') ;
    $query = $connection->query("SELECT SUM(std_value) FROM assessment WHERE ass_user = '$id' AND ass_date >= '$start' AND ass_date <= '$end' ") ;
    while ($row = $query->fetch()) 
    {
        $expected = $row['SUM(std_value)'] ;
        return "$expected" ;
    }
}


// Function to get staff Hours Worked 
function GetStaffHoursWorkedBtw($connection,$id,$start,$end)
{
    $query = $connection->query("SELECT SUM(whours_used) AS sum FROM hours_worked WHERE user = '$id' AND user_type = 'staff'  AND wdate >= '$start' AND wdate <= '$end' ");
    while ($row = $query->fetch()) {
        $expected = $row['sum'] ;
        return "$expected" ;
    }
}

// Function to get staff Expected Hours Worked 

function GetStaffHoursExpWorkedBtw($connection,$id,$start,$end){
    $query = $connection->query("SELECT SUM(whours_expected) AS sum FROM hours_worked WHERE user = '$id' AND user_type = 'staff'  AND wdate >= '$start' AND wdate <= '$end' ") ;
    while ($row = $query->fetch()) {
        $expected = $row['sum'] ;
        return "$expected" ;
    }
}


// Function to get staff Days Worked 

function GetStaffDaysWorkedBtw($connection,$id,$start,$end){
    $query = $connection->query("SELECT SUM(wdaysused) AS sum FROM hours_worked WHERE user = '$id' AND user_type = 'staff'  AND wdate >= '$start' AND wdate <= '$end' ") ;
    while ($row = $query->fetch()) {
        $expected = $row['sum'] ;
        return "$expected" ;
    }
}

// Function to get staff Days Worked 

function GetStaffDaysExpWorkedBtw($connection,$id,$start,$end){
    $query = $connection->query("SELECT SUM(wdaysexpected) AS sum FROM hours_worked WHERE user = '$id' AND user_type = 'staff'  AND wdate >= '$start' AND wdate <= '$end' ") ;
    while ($row = $query->fetch()) {
        $expected = $row['sum'] ;
        return "$expected" ;
    }
}

// Function to get staff Achieved Points 

function GetStaffAchTargetBtw($connection,$id,$start,$end){
    $query = $connection->query("SELECT SUM(ass_point) FROM assessment WHERE ass_user = '$id'  AND ass_date >= '$start' AND ass_date <= '$end'  ") ;
    while ($row = $query->fetch()) {
        $expected = $row['SUM(ass_point)'] ;
        return "$expected" ;
    }
}




// Get Tests of a staff  

function GetStaffTestBtw($connection,$id,$start,$end){

    $parts = explode('/',$start);
    $start = $parts[2] . '-' . $parts[0] . '-' . $parts[1] ;

    $part = explode('/',$end);
    $end = $part[2] . '-' . $part[0] . '-' . $part[1] ;

    $query = $connection->query("SELECT * FROM assessment WHERE ass_user = '$id'  AND ass_date >= '$start' AND ass_date <= '$end'  ") ;
     $num = $query->rowCount() ;

     if ($num != 0) {
         
     
             
        while ($row = $query->fetch() ) {
            
            $param = $row['ass_param'] ;
            $point = $row['ass_point'] ;
            $id = $row['ass_user'] ;
            $aid = $row['ass_id'] ;
            $date = $row['ass_date'] ;
            $category = $row['ass_param_cat'] ;

        $hours = GetStaffHoursWorkedBtw($connection,$id,$start,$end) ;
        $exp_hours = GetStaffHoursExpWorkedBtw($connection,$id,$start,$end);
        $days = GetStaffDaysWorkedBtw($connection,$id,$start,$end) ;
        $exp_days = GetStaffDaysExpWorkedBtw($connection,$id,$start,$end);

        $observed = GetStaffAchTargetBtw($connection,$id,$start,$end) ;
        $expected = GetStaffExpTargetBtw($connection,$id,$start,$end) ;
      //  $days =  GetStaffDaysWorkedBtw($connection,$id,$start,$end) ;

            $input_per_person_per_unit = round($expected/(1*$exp_hours*$exp_days), 2); 

            $output_per_person_per_unit = round($observed/(1*$hours*$days), 2); 
            $efficiency = round($output_per_person_per_unit / $input_per_person_per_unit, 2)  ;
            $perscent = round($efficiency *100, 2) ;

            //$name = "$fname ".  " $lname ";
}
        echo "<table class='table datatable striped'>
                    <tbody>
                        <tr>
                            <td>Expected Target </td>
                            <td>$expected points</td>
                        </tr>
                        <tr>
                            <td>Observed  </td>
                            <td>$observed points</td>
                        </tr>
                        <tr>
                            <td>Labour </td>
                            <td> 1 </td>
                        </tr>
                        <tr>
                            <td>Hours worked </td>
                            <td>$hours hour(s)</td>
                        </tr>
                        <tr>
                            <td>No. of days </td>
                            <td>$days day</td>
                        </tr>
                        <tr>
                            <td>Output Per Person per Unit </td>
                            <td>$output_per_person_per_unit</td>
                        </tr>
                        <tr>
                            <td>Input Per Person per Unit </td>
                            <td>$input_per_person_per_unit</td>
                        </tr>
                        <tr>
                            <td>Efficiency </td>
                            <td>$efficiency <span align='right'> (<b>".$perscent."%</b>)</span></td>
                        </tr>
                    </tbody>
                </table>
";

       }
}


// Assign Staff to unit 

function AssignUnit($connection,$staff,$unit,$linkperson, $unit_org, $datetime)
{
    // check if staff is already in the unit selected
    $query = $connection->query("INSERT INTO staff_unit SET staff = '$staff', unit = '$unit', unit_dept = '0', unit_org = '$unit_org', status = '1', linkperson = '$linkperson', registered_at = '$datetime'");
    if (!$query) 
    {
        return json_encode(['status' => "error", 'message' => "an error occured. Try again"]);
    }
    else
    {
        return json_encode(['status' => "success", 'message' => "added successfully"]);
    }

}

// Assign Staff to unit 
function AssignSupervisor($connection,$type,$type_value,$supervisor,$datetime)
{
    $sql = "SELECT * FROM staff_supervisor WHERE type = '$type' and type_value = '$type_value' and supervisor = '$supervisor' ";
    $query = $connection ->query($sql) ;
    $num =  $query->fetchColumn();
    if($num > 0)
    {
        return json_encode(['status' => "warning", 'message' => "supervisor and staff already assigned"]);

    }else{
        $query = $connection->query("INSERT INTO staff_supervisor SET type = '$type', type_value = '$type_value', supervisor = '$supervisor', registered_at = '$datetime', status = '1' ");
        if (!$query) 
        {
            return json_encode(['status' => "error", 'message' => "an error occured. Try again"]);
        }
        else
        {
            return json_encode(['status' => "success", 'message' => "supervisor assigned successfully"]);
        }
    }
}

// Assign Staff to unit
function CountSupervisorToUnit($connection,$type,$supervisor_id)
{
    $query = $connection->query("SELECT * FROM staff_supervisor WHERE type = '$type' AND supervisor = '$supervisor_id'"); 
    $num = $query->rowCount();
    return $num;
}


// Assign Staff to unit 
function GetStaffUnit($connection, $id)
{
    $query = $connection->query("SELECT * FROM unit WHERE uni_org = '$id'"); 
    $num = $query->rowCount();
    if ($num !=0) 
    {
        while ($row = $query->fetch()) 
        {
            $uid = $row['uni_id'];
            $linkperson = GetStaffName($connection, GetUnitLinkPerson($connection, $uid));
            echo "<tr>
                <td><strong>".ucwords(strtolower(GetUnitId($connection,$uid)))."</strong></td>
                <td>".GetNumStaffUnit($connection,$uid)."</td>
                <td>".GetDeptName($connection,$row['uni_dept'])."</td>
                <td>".$linkperson."</td>
                <td><a href='unitprofile?id=$uid&mode=summarylist'> View </a></td>
            </tr>";
        }
        //return false;
    }
    else
    {
        return false;
    }
}



// Assign Staff to unit 
function GetStaffInDept($connection, $dept, $orgid)
{
    $query = $connection->query("SELECT * FROM staff_unit WHERE unit_dept = '$dept' and unit_org='$orgid'"); 
    $num = $query->rowCount();
    if ($num !=0) {
        while ($row = $query->fetch()) 
        {
            echo "<tr>
                <td>".GetUnitId($connection, $row['unit'])."</td>
                <td>".GetStaffName($connection, $row['staff'])."</td>
                <td></td>
            </tr>";
        }
        //return false;
    }
    else
    {
        return false;
    }
}






// Function to get staff in a unit 
function GetStaffInUnit($connection,$uid){
    $query = $connection ->query("SELECT * FROM staff_unit WHERE unit = '$uid'") ;
    // initialize idvariable
    $sid = 0;
    while ($row = $query->fetch()) {
        $ids = $row['staff'] ;
        // print("$ids<br>");
        // return ("$ids") ;
        if ($sid == 0) {
            $sid = $ids;
            $a = GetStaffId($connection,$ids);
            return $a;
        } else
        {
            $sid = $sid. "," .$ids;
            $a = GetStaffId($connection,$ids) .", ". $a;
            return $a;
        }
    }
    // return menu_id
    // return ($a) ;
    //$
}

 


// count
// Function to grt KPI 
function recordCount($connection, $table, $org_id)
{
    switch ($table) {
        case 'kpi':
            # code...
            $field = "org_id";
            break;

        case 'staff':
            # code...
            $field = "sta_org";
            break;

        case 'unit':
            # code...
            $field = "uni_org";
            break;

        case 'department':
            # code...
            $field = "dept_org";
            break;
        
        default:
            # code...
            break;
    }
    // assign query
    $sql = "SELECT * FROM $table WHERE $field = '$org_id'";
    // query
    $query = $connection ->query($sql) ;
    // execute
    $num =  $query->execute();
    // return count
    return $query->rowCount();
}