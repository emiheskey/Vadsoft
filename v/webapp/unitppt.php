<?php 

// Function Get Staff
// require_once("../admin/functions.php") ;

//function GetStaffDetailsId($connection,$id){
    $sql = "SELECT * FROM unit WHERE uni_id = '$id' ";

    $query = $connection ->query($sql) ;
    
    $num =  $query->setFetchMode(PDO::FETCH_NUM);
    
    if($num !=0) {
    $result = $query->setFetchMode(PDO::FETCH_ASSOC);
        
        while ($row = $query->fetch() ) {
            
            $name = $row['uni_name'] ;
            $dept = $row['uni_dept'] ;
            $discrip = $row['uni_discrip'];
            $id = $row['uni_id'];



        }
    }
//}




?>