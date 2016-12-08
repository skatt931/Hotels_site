<div class="col-md-3">
  <div class="filter clearfix">
    <div class="filter_group">
      <p class="filter_title"><?php echo $aAttrs[4600]['name']; ?></p>
      <div class="checkbox-wrap">
        <div class="type-object">
          <?php foreach ($aAttrs as $ka => $va)
            if ($va['parent_id'] == 4600) { ?>
              <p>
                <label>
                  <input type="checkbox" name="option_<?= $ka ?>">
                <span
                  class="checkboxx"></span><span class="text"><?= $aAttrs[$ka]['name'] ?></span>
                </label>
              </p>
            <?php } ?>
        </div>
      </div>
    </div>
    <div class="filter_group">
      <p class="filter_title"><?= $aAttrs[6000]['name']; ?></p>
      <div class="checkbox-wrap">
        <div class="food">
          <?php foreach ($aAttrs as $ka => $va)
            if ($va['parent_id'] == 6000) {
              $aParts = explode(' ', $aAttrs[$ka]['name']);
              $sPart1 = $aParts[0];
              unset($aParts[0]);
              $sPart2 = implode(' ', $aParts);
              ?>
              <p>
                <label>
                  <input type="checkbox" name="option_<?= $ka ?>">
              <span
                class="checkboxx"></span><span><strong><?= $sPart1 ?></strong>
                <small><?= $sPart2 ?></small></span>
                </label>
              </p>
            <?php } ?>
        </div>
      </div>
    </div>
    <div class="filter_group">
      <p class="filter_title"><?= Hl::$t->_('Stars') ?></p>
      <div class="checkbox-wrap">
        <div class="stars">
          <?php for ($i = 1; $i < 6; $i++) { ?>
            <p class="wrapper-star-label">
              <label>
                <input type="checkbox" name="star<?= $i ?>">
                <span class="checkboxx"></span>
                <?= Hl::stars($i, 5, '<i class="fa fa-star truestar" aria-hidden="true"></i>',
                  '<i class="fa fa-star" aria-hidden="true"></i>') ?>
              </label>
            </p>
          <?php } ?>
        </div>
      </div>
    </div>
    <div class="filter_group">
      <p class="filter_title"><?= Hl::$t->_('Budget') ?></p>
      <div class="checkbox-wrap">
        <div class="budget">
          <label>
            <input type="text" min="100" max="2300" value="1000"
                   class="amount" required>
          </label>
          <div class="dropdown search-currency text-uppercase">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <span class="current-currency">грн</span>
              <img src="<?= Hl::$u ?>/public/src/images/arrow-down.png" alt="">
              <span>/день</span>
            </a>
            <ul class="dropdown-menu">
              <li><a href="#">$</a></li>
              <li><a href="#">рубл</a></li>
            </ul>
          </div>
          <div class="slider-range-min"></div>
          <div class="count-guests">
            <div class="text-center">для:</div>
            <p class="adults">
              <span class="dd_number">2</span> дорослих
            </p>
            <p class="children">
              <span class="dd_number">0</span> дітей
            </p>
          </div>
        </div>
      </div>
    </div>
    <div class="filter_group">
      <p class="filter_title"><?= $aAttrs[5000]['name']; ?></p>
      <div class="checkbox-wrap">
        <div class="amenities">
          <?php
          foreach ($aOptions as $kOpt => $vOpt)
            if ($kOpt != 5000) {
              ?>
              <p>
                <label>
                  <input type="checkbox"
                         name="option_<?= $kOpt ?>">
                  <span class="checkboxx"></span><img
                    src="<?php echo Hl::$u . '/' . $vOpt['pict']; ?>"
                    alt="<?php echo $vOpt['name']; ?>"> <?php echo $vOpt['name']; ?>
                </label>
              </p>
            <?php } ?>
        </div>
      </div>
    </div>
    <div class="filter_group">
      <p class="filter_title"><?= $aAttrs[5500]['name']; ?></p>
      <div class="checkbox-wrap">
        <div class="goal-road">
          <?php foreach ($aAttrs as $ka => $va)
            if ($va['parent_id'] == 5500) { ?>
              <p>
                <label>
                  <input type="checkbox" name="option_<?= $ka ?>">
                  <span class="checkboxx"></span>
                  <span><?= $va['name'] ?></span>
                </label>
              </p>
            <?php } ?>
        </div>
      </div>
    </div>
  </div>

</div>
