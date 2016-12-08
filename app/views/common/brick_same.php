<?php
  if (is_array($aSame))
  foreach ($aSame as $s) {?>
<div class="wrap_block">
  <div class="tur-head clearfix">
    <p class="border-head"><?=$s['hotels_name']?>
      <br> <?=$s['places_name']?>
    </p>
    <div class="price">
      <p class="new_price">від <?=$s['price']?> <span>грн./нічь</span></p>
    </div>
  </div>
  <a href="<?= Hl::url('/hotels/'.$s['hotel_id']) ?>">
    <span class="img_wrap">
      <img class="img-responsive" src="<?=Pictures::pictSizeN($s['picture'],350)?>" alt="<?=$s['alt']?>">
    </span>
  </a>
  <div class="border">
    <div class="info_block">
      <p>0.1 km до бювету</p>
      <p>0.15 km до центру</p>
    </div>
    <div class="add_block">
      <div class="stars">
        <?=Hl::stars($s['stars'])?>
      </div>
    </div>
  </div>
</div>
<?php } ?>