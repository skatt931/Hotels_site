<li class="col-sm-3 no-padding">
    <h2><?php
        $aFoods = Hl::getAttrs( 6000, false, true );
        echo $aFoods[ 6000 ][ 'name' ]; ?></h2>
    <div class="food">
        <?php foreach ($aFoods as $kFood=>$vFood)
              if ($kFood != 6000) { ?>
        <p>
            <label>
                <input type="checkbox" name="option_<?= $kFood?>">
                <span class="checkboxx"></span>
                <span><strong><?= substr($vFood['name'],0,3)?></strong>
                    <small><?= substr($vFood['name'],3)?></small></span>
            </label>
        </p>
        <?php } ?>
    </div>
</li>
