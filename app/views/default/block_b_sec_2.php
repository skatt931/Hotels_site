<section class="advantages">
	<div class="container">
		<h2 class="text-center"><?= Attrtypes::get( 6520 )[ 'name' ] ?></h2>
		<div class="hr medal">
			<div class="inner"></div>
		</div>
		<div class="row">
			<?php
			$qPictures = HT::sel( 'pictures',
				[ 'select' =>
					'path as pict, pictures.note' . Hl::$pstfx . ' as title',
					'where' => 'attr_id = 6520',
				] );
			foreach ( $qPictures as $pict ) {
				?>
				<div class="col-md-4">
					<div class="advantages-content">
						<img src="<?= $pict[ 'pict' ] ?>" title="<?= $pict[ 'title' ] ?>" alt="">
						<h3 class="advantages-title"><?= $pict[ 'title' ] ?></h3>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
</section>
