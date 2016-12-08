<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"></h4>
			</div>
		</div>
	</div>
</div>

<?php
  if (is_array(Hl::$aScriptList))
  foreach (Hl::$aScriptList as $s) {
    if (substr($s,0,4) != 'http') {
      if (substr($s,0,1) != '/')
        $s = '/'.$s;
      $s = Hl::$u.$s;
    }
    echo '<script src="'.$s.'"></script>';
  }
?>
