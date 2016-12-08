<section class="we-offer">
	<div class="container">
		<h2><?= Attrtypes::get( 6530 )[ 'name' ] ?></h2>
		<div class="hr firstPlace">
			<div class="inner"></div>
		</div>
		<div class="row">
			<?php
			$qPropose = HT::sel( 'pictures',
				[ 'select' =>
					'obj_id,path as pict, pictures.note' . Hl::$pstfx . ' as title',
					'where' => 'attr_id = 6530',
				] );
			foreach ( $qPropose as $propose ) {
				?>
				<div class="col-md-4">
					<a class="link-top-item" href="<?=Hl::url('/toplists/'.$propose[ 'obj_id' ])?>"
             data-type="option_<?=$propose[ 'obj_id' ]+100?>">
						<div class="we_offer-items">
							<img src="<?= $propose[ 'pict' ] ?>" alt="">
							<h3><?= $propose[ 'title' ] ?></h3>
						</div>
					</a>
				</div>
			<?php } ?>
		</div>
	</div>
</section>