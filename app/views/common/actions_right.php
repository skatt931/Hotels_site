<?php
if(!isset($aActions))
  $aActions = Hl::q2a(
    HT::sel('actions',[
      'select' => ['actions.*,pictures.path picture, pictures.alt, tables.name tablename'],
      'where' => ['coalesce(trash,0)=0'],
      'join' => ['tables'=>'tables.id = actions.table_id',
                 'pictures'=>'pictures.id = actions.picture_id'],
      'order' => 'date_e DESC',
      'limit' => '5',
    ]),
    'id');
?>
<div class="akcii">
    <h3 class="text-left"><?=Hl::aSafe($aAttrs,[6590,'name'])?> <img width="20" src="/public/src/images/discount-tag.png"
                                                   alt="">
    </h3>
    <div id="akcii_carousel" class="owl-carousel owl-theme owl-sidebar-carousel">
        <?php
          if (is_array($aActions))
          foreach ($aActions as $a) {?>
            <div class="item" onclick="window.location='<?=Hl::url($a['tablename'].'/'.$a['obj_id'])?>'">
                <div class="akcii_img_wrap">
                    <img src="<?=$a['picture']?>" alt="<?=$a['alt']?>">
                    <?php if ($a['percent'] != 0)
                        echo '<div class="percent text-center">'.$a['percent'].'%</div>';
                    ?>
                </div>
                <div class="title text-center"><?=$a['name'.Hl::$pstfx]?></div>
                <div class="info_block">
                    <p><?=$a['small_note'.Hl::$pstfx]?></p>
                    <?php if ($a['date_e']>0) {?>
                        <div class="date_time text-center"><span>
                                        <?=Hl::$t->_('to')?></span> <?=$a['date_e']?>
                        </div>
                    <?php } ?>
                    <a href="
                    <?php
                      echo Hl::url($a['tablename'].'/'.$a['obj_id'])
                    ?>" class="read_more text-center"><?=Hl::$t->_('detail')?><i class="fa fa-angle-right"></i></a>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
