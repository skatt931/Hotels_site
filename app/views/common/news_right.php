<div class="news">
    <h3><?=Hl::aSafe($aAttrs,[6580,'name'])?> <img width="20"
                                                  src="/public/src/images/brochure-folded-(1).png"
                                                  alt="">
    </h3>
    <div id="news_carousel" class="owl-carousel owl-theme owl-sidebar-carousel">
        <?php
          if (is_array($aBlogs))
          foreach ($aBlogs as $b) {?>
            <div class="item" onclick="window.location='<?=Hl::url('blog/'.$b['id'])?>'">
                <div class="news_img_wrap">
                    <img src="<?=Pictures::pictSizeN($b['picture'],350)?>" alt="banner_news">
                </div>
                <div class="title"><?=$b['name'.Hl::$pstfx]?></div>
                <div class="info_block">
                    <p><?=$b['small_note'.Hl::$pstfx]?></p>
                    <a href="#" class="read_more text-center"><?=Hl::$t->_('read')?><i class="fa fa-angle-right"></i></a>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
