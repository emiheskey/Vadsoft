<?php 

// Function Get Staff
// require_once("../admin/functions.php") ;

//function GetStaffDetailsId($connection,$id){
    $sql = "SELECT * FROM staff WHERE sta_id = '$id' ";

    $query = $connection ->query($sql) ;
    
    $num =  $query->setFetchMode(PDO::FETCH_NUM);
    
    if($num !=0) {
    $result = $query->setFetchMode(PDO::FETCH_ASSOC);
        
        while ($row = $query->fetch() ) {
            
            $fname = $row['sta_fname'] ;
            $lname = $row['sta_lname'] ;
            $dept = $row['sta_dept'] ;
            $phone = $row['sta_phone'];
            $email = $row['sta_email'];
            $sex = $row['sta_sex']; 
            $id = $row['sta_id'];

            $name = "$fname ".  " $lname ";


        }
    }
//}




?>