<?php
$cid = GetOrgCatIdArray($connection) ;
foreach ($cid as $fid)
{
?>
 <div class="modal fade" id="<?php echo "oc$fid"; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title" id="myModalLabel">Delete <?php echo GetOrgCatId($connection,$fid) ; ?> </h3>
      </div>
      <div class="modal-body">
      <form method="post" action="form-approve.php">
        <h4> Sure you want to delete <?php echo GetOrgCatId($connection,$fid) ; ?> ? </h4>
        <p></p>
        <a href="webapp/delete.php?obj=orgcat&id=<?php echo "$fid"; ?>" class="btn btn-danger"> Delete </a>

      </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php } ?>

