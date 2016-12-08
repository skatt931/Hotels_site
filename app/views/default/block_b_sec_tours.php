<?php
if (!isset($tourAttr)) $tourAttr = 6540;
?>
<section class="concerts-tur" id="toursScroll">
  <div class="container">
    <h2><?= Attrtypes::get( $tourAttr )[ 'name' ] ?></h2>
    <div class="hr concerts">
      <div class="inner"></div>
    </div>
    <p class="descr"><?= Feedbacks::get( [ 'attr_id' => $tourAttr ] )[ 0 ][ 'note' . Hl::$pstfx ] ?>
    </p>
    <div class="wr-button text-center">
      <button class="btn btn-default"
       onclick="window.location='<?=Hl::url('/toplists/'.$tourAttr)?>'"><?= Attrtypes::get( 6541 )[ 'name' ] ?></button>
    </div>
    <div class="row">
      <div class="wr-slider">
        <div class="owl-carousel owl-theme" id="turSlider">
          <?php
          $qTours = HT::sel( 'tours',
            [ 'select' => 'tours.id,tours.name'. Hl::$pstfx.' name,'.
              'places.name'.Hl::$pstfx.' place,'.
              'days,price,'.
              'tours.small_note' . Hl::$pstfx . ' as small_note,'.
              'path as picture ',
              'join' => ['pictures'=>'pictures.id=tours.picture_id',
                         'places'=>'places.id=tours.place_id'],
              'where' => ['coalesce(trash,0)=0','tours.attr_id'=>$tourAttr],
              'limit' => '10',
            ] );
          foreach ( $qTours as $tour ) {
            ?>
            <div class="item-slide">
              <a href="<?=Hl::url('tours/'.$tour['id'])?>">
              <figure>
                <div class="top-corner">
                  <p><?=$tour['days']?><span> <?=Hl::$t->_('days')?></span></p>
                  <hr>
                  <p><?=$tour['price']?><span> <?=Hl::$t->_('grn').'.'?></span></p>
                </div>
                <img src="<?= Pictures::pictSizeN($tour[ 'picture' ],350) ?>" alt="">
                <figcaption>
                  <?php
                  echo '<h3>'.$tour[ 'name' ].'</h3>';
                  echo '<p>'.$tour[ 'place' ].'</p>';
                  echo '<p class="content">'.Hl::trimText($tour['small_note'],250).'</p>';
                  ?>
                </figcaption>
              </figure>
              </a>
            </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</section>
