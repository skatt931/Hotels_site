<li class="col-sm-3 no-padding">
	<h2><?= Hl::$t->_('Budget')?></h2>
	<div class="budget">
		<label><?= Hl::$t->_('till')?> <input type="text" class="amount"> </label>
		<div class="dropdown dropdown-submenu currency text-uppercase">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown">
				<span class="current-currency">грн</span>
				<img src="<?= Hl::$u ?>/public/src/images/arrow-down.png" alt="">
				<span>/<?= Hl::$t->_('day')?></span>
			</a>
			<ul class="dropdown-menu">
				<li><a href="#">$</a></li>
				<li><a href="#">рубл</a></li>
			</ul>
		</div>
		<div class="slider-range-min"></div>
		<div class="count-guests">
			<div class="text-center"><?= Hl::$t->_('for')?></div>
			<p class="adults"><?= Hl::$t->_('Adults')?>: <span class="dd_number">2</span></p>
			<p class="children"><?= Hl::$t->_('Children')?>: <span class="dd_number">3</span></p>
		</div>
	</div>
</li>
