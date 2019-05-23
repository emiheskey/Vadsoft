<?php
include('webapp/functions.php');
$kpi = $_POST['kpi'];
$org_group = $_POST['orglv'];
$ass_typ = $_POST['ass_typ'];
$sql = "SELECT * FROM assessment_params_category WHERE aspcat_kpi ='$kpi' AND ass_typ = '$ass_typ' AND org_grp = '$org_group' ORDER BY aspcat_name" ;
$sql = $connection->query($sql) or die("Unsuccessful") ;
$sql ->setFetchMode(PDO::FETCH_ASSOC);
echo "<option>What are you measuring?</option>";
while($row = $sql->fetch())
{
    echo "<option value=".$row['aspcat_id'].">". $row['aspcat_name'] ."/" . $row['aspcat_discrip'] . "</option>";  
}
?>