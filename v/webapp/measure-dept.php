<?php 

if ($_POST) {
	// Get the value of the assessment parameter 
	// run a loop to get the values 
	// insert the values into the assessment table 
	require_once("functions.php") ;


 
	$score = $_POST['score'] ;
	$param = $_POST['param'] ;
	$name = $_POST['staff'] ;
	$typ = $_POST['typ'] ;
	$cat = $_POST['cat'] ;
	$usr_typ = "dept" ;
	$standard = $_POST['standard'] ;
	$cat = $_POST['cat'] ;
//	$hours = $_POST['hours'] ;
	$type = $_POST['type'] ;

	// $param = $_POST['param'] ;



foreach( $score as $index => $code ) {
    //echo "$code ". $name[$index] ;
 		// echo "$usr_typ - $name -$code - " . $param[$index] ."-".$typ[$index]."-".$cat[$index]."<br>" ;

 	   if (RecordAssessDept($connection,$name,$param[$index],$code,$datetime,$typ[$index],$cat[$index],$usr_typ,$date,$type[$index],$standard[$index]) == true) { 

 	   		header("Refresh:1; url=department.php");
	    	echo "Successful";
	   }
	}
		//

		// echo "Successful";
 
	//foreach (array_combine($score, $param) as $cod => $nam) {
	    //echo  "$cod - $nam <br>" ; 

	 
}

   


?>