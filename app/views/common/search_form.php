<div class="search-form">
  <form>
    <ul class="clearfix">
      <li class="sk-form-item sk-form-autocomplete">
        <div class="sk-form-item-inner">
          <label>
            <input tabindex="0" type="text" name="city"
                   placeholder="<?= Hl::$t->_('City, hotel, sanatorium...') ?>"
                   value="Трускавець">
          </label>
        </div>
        <div class="more-settings">
          <div class="row">
            <div class="col-sm-6 hidden-xs">
              <div class="hint">
                <?= Hl::$t->_('for example') ?> <a
                  class="link-hint"
                  href="#"><?= Hl::$t->_('Truskavets') ?> </a><?= Hl::$t->_('or') ?>
                <a
                  class="link-hint"
                  href="#"><?= Hl::$t->_('Mirotel') ?> </a>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="inner-more-settings">
                <div class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?= Hl::$t->_('More options') ?>
                    <b class="caret"></b></a>
                  <ul class="dropdown-menu row">
                    <?php include 'dd_stars.php'; ?>
                    <?php include 'dd_budget.php'; ?>
                    <?php include 'dd_options.php'; ?>
                    <?php include 'dd_food.php'; ?>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </li>
      <li class="sk-form-item sk-form-datepicker from">
        <div class="sk-form-item-inner">
          <label>
            <input type="text" tabindex="1"
                   placeholder="<?= Hl::$t->_('Incoming') ?>"
                   name="checkIn" class="dp start"
                   data-date-format="mm/dd/yyyy" readonly>
						<span class="sk-form-wr-img">
							<img
                src="<?= Hl::$u ?>/public/src/images/calendar.png"
                alt="">
						</span>
          </label>
        </div>
      </li>
      <li class="sk-form-item sk-form-datepicker dEnd to">
        <div class="sk-form-item-inner">
          <label>
            <input type="text" tabindex="2"
                   placeholder="<?= Hl::$t->_('Outcoming') ?>"
                   name="checkOut"
                   class="dp end" data-date-format="mm/dd/yyyy"
                   readonly>
						<span class="sk-form-wr-img">
							<img
                src="<?= Hl::$u ?>/public/src/images/calendar.png"
                alt="">
						</span>
          </label>
        </div>
      </li>
      <li class="sk-form-item sk-form-guests">
        <div class="sk-form-item-inner">
          <div class="guests">
            <label class="guests-toggle">
              <input type="text" tabindex="3" class="guestsCount"
                     name="guestsCount"
                     placeholder="<?= Hl::$t->_('Guests') ?>"
                     readonly>
							<span class="sk-form-wr-img">
								<img
                  src="<?= Hl::$u ?>/public/src/images/businessman.png"
                  alt="">
								<img class="arrow-down"
                     src="<?= Hl::$u ?>/public/src/images/arrow-down.png"
                     alt="arrow-down">
							</span>
            </label>
            <div class="guests-menu clearfix"></div>
          </div>
        </div>
      </li>
      <li class="sk-form-item sk-form-submit">
        <div class="sk-form-item-inner">
          <a class="btn btn-default" href="<?= Hl::url('search'); ?>">
            <i class="fa fa-search"
               aria-hidden="true"></i><?= Hl::$t->_('Search'); ?>
          </a>
        </div>
      </li>
    </ul>
  </form>
  <div class="col-md-2 col-md-offset-10 wrapClearSearch">
    <a id="clearSearch" href="#"><?= Hl::$t->_('Clear search') ?> </a>
  </div>
</div>