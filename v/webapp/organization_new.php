<?php

if(!isset($org_name)) $org_name="";
if(!isset($org_adname)) $org_adname="";
if(!isset($org_category)) $org_category="";
if(!isset($org_email)) $org_email="";
if(!isset($org_phone)) $org_phone="";
if(!isset($org_address)) $org_address="";
if(!isset($org_location)) $org_location="";
if(!isset($org_id)) $org_id="";
?>


<!-- Form Element sizes -->
<div class="box box-success">
	<div class="box-header with-border">
		<h3 class="box-title">New Organiation</h3>
	</div>
	<div class="box-body">
    <?php include('flashMessage.php'); ?>
		<form id="defaultForm" method="post" class="form-horizontal" action="webapp/org.php?mode=new">
		  	<input class="form-control input-lg" type="text" name="name" id="name" placeholder="Name of organization" value="<?= $org_name ?>" required>
		  	<br>
		  	<select class="form-control input-lg" name="cat" id="cat" style="width: 100%;" required="required">
		  		<option value="<?=$cat_id; ?>" selected><?= GetOrganizationCat($connection,$org_category); ?></option>
                <?php
                $sql = "SELECT * FROM organization_category ORDER BY cat_name" ;
                $sql = $connection->query($sql) or die("Unsuccessful") ;
                $sql ->setFetchMode(PDO::FETCH_ASSOC);
                while($row = $sql->fetch())
                {
                    ?>
                         <option value="<?php echo $row['cat_id']; ?>"><?php echo $row['cat_name'];  ?></option>  
                    <?php
                }
              ?>
            </select>
            <br />
		  	<input type="text" class="form-control input-lg" name="ad_name" autocomplete="off" placeholder="Contact Person" value="<?= $org_adname ?>" required="required" />
		  	<br>
		  	<input type="email" class="form-control input-lg" name="email" id="u_email" placeholder="Organization email" value="<?= $org_email ?>" required="required" />
		  	<br>
		  	<input type="text" class="form-control input-lg" name="phone" id="phone" placeholder="Phone number" value="<?= $org_phone?>" required="required" />
		  	<br>
		  	<textarea class="form-control input-lg" name="add" placeholder="Address" id="add" row="3" required="required"><?= $org_address ?></textarea>
		  	<br>
		  	<select class="form-control input-lg" name="location" id="location" required="required">
		  		<option value="<?= $org_location ?>" selected><?= $org_location ?></option>
                <?php

                $sql = "SELECT * FROM area ORDER BY area_name" ;
                $sql = $connection->query($sql) or die("Unsuccessful") ;
                $sql ->setFetchMode(PDO::FETCH_ASSOC);
                while($row = $sql->fetch())
                {
                    ?>
                         <option value="<?php echo $row['area_id']; ?>"><?php 
                          echo $row['area_name'];  ?></option>  
                    <?php
                }
              ?>
            </select>
            
            <br>
            <input type="submit" value="Add Organization" class="btn btn-lg btn-primary" />
		</form>
	</div><!-- /.box-body -->
</div><!-- /.box -->

