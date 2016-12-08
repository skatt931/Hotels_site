<section class="popular-way">
	<div class="container">
		<h2><?= Attrtypes::get(6510)['name'] ?></h2>
		<div class="hr bus">
			<div class="inner"></div>
		</div>
		<div class="row">
			<?php
			$popCity = HT::sel('toplists',
				['select' =>
					'places.id,places.' . Hl::$name . ' as name,' .
					'pictures.path as picture, pictures.note' . Hl::$pstfx . ' as title'
					,
					'where' => 'toplists.attr_id = 6510',
					'join' => ['places' => 'toplists.obj_id = places.id',
						'pictures' => 'pictures.obj_id = places.id AND pictures.attr_id = 3000'],

				]);
			$aAttrs = Attrtypes::all();
			foreach ($popCity as $city) {
				$hotelsCount = HT::sel('counts', ['where' => ['place_id' => $city['id']],
					'order' => 'attr_id']);
				?>
				<div class="col-md-4">
					<div class="wr-popular">
							<div class="popular-content clearfix">
								<h3><?= $city['name'] ?></h3>
								<img
									src="<?= Pictures::pictSizeN($city['picture'], 350) ?>"
									alt="<?= $city['name'] ?>"
									title="<?= $city['title'] ?>">
							</div>
							<?php if (count($hotelsCount) > 0) { ?>
								<ul>
									<?php foreach ($hotelsCount as $counter) { ?>
										<li data-type="<?='option_'.($counter['attr_id']-2010)?>"><?= $aAttrs[$counter['attr_id']]['name'] ?>
											:
                              <span class="count"><?= $counter['counts'] ?>
                              </span>
											<i class="fa fa-angle-right"
											   aria-hidden="true"></i></li>
									<?php } ?>
								</ul>
							<?php } ?>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
</section>