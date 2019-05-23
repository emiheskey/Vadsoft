<?php 
if ($_GET) {
	$name = $_GET['name']; // get unit id
}

if (!isset($_GET['m_unit']))
{
    $m_unit="";
}
else
{
    $m_unit=$_GET['m_unit']; 
}

?>
 

<h2>Department Assessment for <?php echo "$name"; ?></h2>
<!-- List all the KPIs associated with the unit here -->

<?php 
$group_kpi = GetGroupKPI($connection,$name);

// echo "<pre>";
// print_r($group_kpi);
// echo "</pre>";

// loop through
foreach ($group_kpi as $row)
{
  // get rand value
  $rand = md5(rand(2333,9899)).md5($name).md5(rand(0000,9999));
  $kpi = GetKPI($connection, $row["asp_kpi"]);
  foreach ($kpi as $rowkpi)
  {
    $kpi_id = $rowkpi['kpi_id'];
    echo "<h4><a href='unittest?tag=$rand&name=$name&kid=$kpi_id&m_unit=measureunit'>".$rowkpi["kpi_name"]."</a></h4>";
  }
}
?>








                    <?php include('flashMessage.php'); ?>
  					<form method="post" action="measure-unit.php" class="form-horizontal">

  						<?php // GetGroupAssParamsUnit($connection,$name,'unit') //($connection,'unit'); ?>


  						<input type="hidden" class="btn btn-primary" name="unit" value="<?php echo "$name"; ?> ">
  						
  						<input type="hidden" class="btn btn-primary" name="dept" value="<?php echo GetUnitDept($connection,$name); ?>">
                        
                        <div class="control-group">
                      <label class="control-label" for="typeahead">Hours Worked  </label>
                          <div class="controls">
                <input type="number" name="hours" placeholder='Enter No. of hours Unit worked today' class="form-control span10">
                            </div>
                        </div>
						
						<div class="control-group">
                      <label class="control-label" for="typeahead">Days Expected  </label>
                          <div class="controls">
                                <input type="number" name="no_of_days_expected" placeholder='Enter No. of days used' class="form-control span10">
                            </div>
                        </div>

						<div class="control-group">
                      		<label class="control-label" for="typeahead">Days Used  </label>
                          <div class="controls">
                                <input type="number" name="no_of_days_used" placeholder='Enter No. of days used' class="form-control span10">
                            </div>
                        </div>




					<button type="submit" class="btn btn-primary" value="">Submit </button>
           			  </form>
					  