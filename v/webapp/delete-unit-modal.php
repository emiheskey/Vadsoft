<?php
$cid = GetUnitArray($connection) ;
foreach ($cid as $fid)
{
?>
 <div class="modal fade" id="<?php echo "oc$fid"; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title" id="myModalLabel">Delete <?php echo GetUnitId($connection,$fid) ; ?> </h3>
      </div>
      <div class="modal-body">
      <form method="post" action="form-approve.php">
        <h4> Sure you want to delete <?php  echo GetUnitId($connection,$fid) ; ?> ? </h4>
        <!-- <textarea class="form-control" name="comment" rows="3"></textarea> -->
        <!-- <input type="hidden" name="id" value="<?php // echo "$fid"; ?>" > -->
        <p></p>
        <a href="webapp/delete.php?obj=unit&id=<?php echo "$fid"; ?>" class="btn btn-danger"> Delete </a>

      </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php } ?>

