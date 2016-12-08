<div class="col-md-9">
	<div id="typeObject"></div>
	<!--<p class="block_title"><a href="#">Трускавець:  </a> <a href="#"> Санаторії, </a> <a href="#"> Готелі, </a><a href="#"> Вілли</a></p>-->
	<!-- Nav tabs -->
	<ul class="nav nav-tabs" role="tablist">
		<li role="presentation"><a href="#tabs-map" aria-controls="tabs-map" role="tab"
		                           data-toggle="tab"><?= Hl::$t->_('Map') ?></a>
		</li>
		<li role="presentation" class="active"><a href="#tabs-list-hotels"
		                                          aria-controls="tabs-list-hotels"
		                                          role="tab"
		                                          data-toggle="tab"><?= Hl::$t->_('List') ?></a>
		</li>
	</ul>
	<div class="sort-group pull-right">
		<div class="sort-group-direction pull-right">
			<p><?= Hl::$t->_('Order') ?>:</p>
			<select class="form-control" id="sort">
				<option value="from_cheap"><?=Hl::$t->_('from cheap to great')?></option>
				<option value="from_toll"><?=Hl::$t->_('from great to cheap')?></option>
				<option value="popular"><?=Hl::$t->_('by popularity')?></option>
			</select>
		</div>
		<div class="sort-group-count pull-right">
			<p><?= Hl::$t->_('Selected') ?> <span id="findCountHotels"></span> з <span id="allHotels"></span></p>
		</div>

	</div>
	<!-- Tab panes -->
	<div class="tab-content">
		<div role="tabpanel" class="tab-pane" id="tabs-map">
			<div class="map">
				<iframe
					src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d10410.715436997963!2d23.498779484080323!3d49.28248211059344!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0000000000000000%3A0x6d73242be806805a!2z0KHQsNC90LDRgtC-0YDQuNC4INCi0YDRg9GB0LrQsNCy0YbQsA!5e0!3m2!1sru!2sru!4v1464636870281"
					width="861" height="867" frameborder="0" style="border:0"
					allowfullscreen></iframe>
			</div>
			<div class="well">
				<ul>
					<li><img src="<?= Hl::$u ?>/public/src/images/pin.png"
					         alt="">
						<?= Hl::$t->_('Found') ?></li>
					<li><img src="<?= Hl::$u ?>/public/src/images/dot.png"
					         alt="">
						<?= Hl::$t->_('Other') ?></li>
				</ul>
			</div>
		</div>
		<div role="tabpanel" class="tab-pane  active" id="tabs-list-hotels">

			<div class="row catalog"></div>
		</div>
	</div>

</div>