      <div class="footer_menu">
        <div class="container">
          <ul>
            <li><a href="<?= Hl::url('info')?>"><?= Hl::$t->_('Information')?></a></li>
            <li><a href="<?= Hl::url('about')?>"><?= Hl::$t->_('About')?></a></li>
            <li><a href="<?= Hl::url('contacts')?>"><?= Hl::$t->_('Contacts')?></a></li>
            <li><a href="<?= Hl::url('vacancy')?>"><?= Hl::$t->_('Vacancy')?></a></li>
            <li><a href="<?= Hl::url('sitemap')?>"><?= Hl::$t->_('Site map')?></a></li>
          </ul>
        </div>

      </div>
      <div class="main_footer">
        <div class="container">
          <div class="row">
            <div class="col-md-3 col-md-offset-2">
              <div class="footer_icon pull-left">
                <i class="fa fa-map-marker" aria-hidden="true"></i>
              </div>
              <div class="adres_info">
                <p><?= Hl::$t->_(Hl::opt('adres_info_1'.Hl::$pstfx))?></p>
                <p><?= Hl::$t->_(Hl::opt('adres_info_2'.Hl::$pstfx))?></p>
              </div>
            </div>
            <div class="col-md-2">
              <div class="footer_icon pull-left">
                <i class="fa fa-mobile" aria-hidden="true"></i>
              </div>
              <div class="phone_info">
                <p><?= Hl::$t->_(Hl::opt('phone_info_1'))?> </p>
                <p><?= Hl::$t->_(Hl::opt('phone_info_2'))?> </p>
              </div>
            </div>
            <div class="col-md-4">
              <ul class="soc_wrap">
                <?php
                $qSocials = HT::sel('pictures',['user_id'=>3,'attr_id'=>10,'obj_id'=>6500,]);
                foreach ($qSocials as $s) {
                  echo '<li><a href="'.$s['note'.Hl::$pstfx].
                    '" class="'.$s['alt'].'"><i class="'.$s['path'].
                    '"aria-hidden="true"></i></a></li>';
                } ?>
              </ul>
            </div>
          </div>
        </div>
      </div>
