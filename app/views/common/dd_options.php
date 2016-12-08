<li class="col-sm-3 no-padding">
	<h2><?php
		$aOptions = Hl::getAttrs( 5000, true, true );
		echo $aOptions[ 5000 ][ 'name' ];
		?></h2>
	<div class="amenities">
		<?php
		foreach ( $aOptions as $kOpt => $vOpt )
			if ( $kOpt != 5000 ) {
				?>
				<p>
					<label>
						<input type="checkbox" name="option_<?= $kOpt ?>">
						<span class="checkboxx"></span><img
							src="<?php echo Hl::$u . '/' . $vOpt[ 'pict' ]; ?>"
							alt="<?php echo $vOpt[ 'name' ]; ?>"> <?php echo $vOpt[ 'name' ]; ?></label>
				</p>
			<?php } ?>
	</div>
</li>