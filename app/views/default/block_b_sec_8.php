<section class="news" id="newsScroll">
  <div class="container">
    <h2><?= Attrtypes::get( 6580 )[ 'name' ] ?></h2>
    <div class="hr news_hr">
      <div class="inner"></div>
    </div>
    <p class="descr">
      <?= Feedbacks::get( [ 'attr_id' => 6580 ] )[ 0 ][ 'note' . Hl::$pstfx ] ?>
    </p>
    <div class="wr-button text-center">
      <button class="btn btn-default"
              onclick="window.location='<?=Hl::url('blog')?>'"
      ><?= Attrtypes::get( 6581 )[ 'name' ] ?></button>
    </div>
    <div class="row">
      <div class="wr-slider">
        <div class="owl-carousel owl-theme" id="moreNewsSlider">
          <?php
          /*
          $qCTours = HT::sel( 'pictures',
            [ 'select' =>
              'path as pict, pictures.note' . Hl::$pstfx . ' as name',
              'where' => 'attr_id = 6580',
            ] );*/
          $qBlogs = HT::sel( 'blog',
            [ 'select' => 'blog.*,pictures.path picture',
              'join' => ['pictures' => 'blog.picture_id = pictures.id'],
              'where' => 'coalesce(trash,0) = 0',
              'order' => 'created DESC',
              'limit' => 3,
              //'query' =>1
            ]);
          foreach ( $qBlogs as $blog ) {
            ?>
            <div class="item-slide" onclick="window.location='<?=Hl::url('blog/'.$blog['id'])?>'">
              <figure>
                <img src="<?= Pictures::pictSizeN($blog[ 'picture' ],350) ?>" alt="">
                <figcaption>
                  <h3><?=$blog[ 'name'.Hl::$pstfx ]?></h3>
                  <time datetime="<?=$blog['created']?>"><?=Hl::df($blog['created'])?></time>
                  <p><?=Hl::trimText($blog['small_note'.Hl::$pstfx],170)?></p>
                  <div class="wr-button">
                    <a href="<?=Hl::url('blog/'.$blog['id'])?>"><?=Hl::$t->_('read')?>
                      <span><i class="fa fa-angle-right" aria-hidden="true"></i></span>
                    </a>
                  </div>
                </figcaption>
              </figure>
            </div>
          <?php } ?>
        </div>
      </div>
    </div>
</section>
