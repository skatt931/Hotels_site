<section class="shares" id="actionsScroll">
	<div class="container">
		<h2><?= Attrtypes::get(6590)['name'] ?></h2>
		<div class="hr shares_hr">
			<div class="inner"></div>
		</div>
		<p class="descr">
			<?= Feedbacks::get(['attr_id' => 6590])[0]['note' . Hl::$pstfx] ?>
		</p>
		<div class="wr-button text-center">
			<button
				class="btn btn-default"
        onclick="window.location='<?=Hl::url('/actions')?>'"
      ><?= Attrtypes::get(6591)['name'] ?></button>
		</div>
		<div class="row">
			<div class="wr-slider">
				<div class="owl-carousel owl-theme" id="moreSharesSlider">
					<?php
					$qActions = HT::sel('actions',
						['select' => 'actions.id,name' . Hl::$pstfx .
              ' name, date_f,date_e,percent,actions.obj_id,' .
							'actions.small_note' . Hl::$pstfx . ' as small_note,' .
							'path as picture, tables.name tablename ',
							'join' => ['pictures' => 'pictures.id=actions.picture_id',
                         'tables' => 'tables.id=actions.table_id',
              ],
							'where' => 'coalesce(trash,0)=0',
							'limit' => '10',
						]);
					foreach ($qActions as $act) {
						?>
						<div class="item-slide">
							<figure>
								<img
									src="<?= Pictures::pictSizeN($act['picture'], 350) ?>"
									alt="">
								<figcaption>
									<?php if ($act['percent'] != 0) {?>
									<div class="wr-percent">
										<div class="percent">-<?=$act['percent']?>%</div>
									</div>
                  <?php } ?>
									<h3><?=$act['name']?></h3>
									<div class="content-items">
										<p><?=Hl::trimText($act['small_note'],100)?></p>
                    <?php if ($act['date_e'] > 0) {?>
										<div class="for-date text-center">
											<time><span><?= Hl::$t->_('till') ?> </span><?=Hl::df($act['date_e'])?></time>
										</div>
                    <?php } ?>
										<div class="wr-button">
											<a href="<?=Hl::url($act['tablename'].'/' . $act['obj_id']) ?>">
                        <?= Hl::$t->_('read') ?>
												<span><i class="fa fa-angle-right"
												         aria-hidden="true"></i></span>
											</a>
										</div>
									</div>
									<?php
									/*									echo '<h3>' . $act['name'] . '</h3>';
																		if ($act['date_f'] > 0)
																			echo '<time datetime="' . Hl::df($act['date_f']) .
																				'">' . Hl::df($act['date_f']) . '</time>';
																		echo '<p>' . $act['small_note'] . '</p>';
																		*/ ?>
									<!-- text in button must be "details"-->
								</figcaption>
							</figure>
						</div>
					<?php } ?>
				</div>
			</div>

		</div>
	</div>
</section>