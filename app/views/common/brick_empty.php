<div class="col-md-4 col-sm-6 col-xs-6" xmlns:javascript="http://www.w3.org/1999/xhtml">
    <div class="wrap_block">
        <a href="#">
            <img  class="img-responsive" src="<?= Hl::$u ?>/public/src/images/room.jpg" alt="item">
        </a>
        <div class="border">
            <p onclick="javascript:window.location.replace(
              '<?= Hl::url('/hotels/view/1') ?>')"
            >Royal Grand Hotel & Spa</p>
            <div class="info_block">
                <p>0.1 km до бювету</p>
                <p>0.15 km до центру</p>
            </div>
            <div class="add_block">
                <div class="stars">
                    <i class="fa fa-star" aria-hidden="true"></i>
                    <i class="fa fa-star" aria-hidden="true"></i>
                    <i class="fa fa-star" aria-hidden="true"></i>
                    <i class="fa fa-star" aria-hidden="true"></i>
                    <i class="fa fa-star" aria-hidden="true"></i>
                </div>
                <div class="rating_wrap">
                    <span>чудово</span>
                    <span class="rating">99%</span>
                </div>
                <button class="like">
                    <i class="fa fa-thumbs-up" aria-hidden="true"></i>
                </button>
            </div>
            <div class="price">
                <p class="new_price">24000 </p>
                <span class="new_price">грн./нічь</span>
            </div>
        </div>
        <div class="coast clearfix">
            <button class="btn btn-buy center-block" type="submit">
                <?= Hl::$t->_('Show') ?></button>
        </div>
    </div>
</div>
