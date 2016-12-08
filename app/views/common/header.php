<nav class="top-navigation clearfix">
  <a id="user_id" style="display: none"><?=Hl::user('id')?></a>
  <div class="logo pull-left">
    <a href="/"><img src="<?=Hl::$u ?>/public/src/images/logo.png" alt="Tur-Galychyna"></a>
  </div>
  <ul class="nav navbar-nav top-nav-left text-uppercase hidden-xs no-padding">
    <?php
      $sActive = 'cities';
      $aMenu = ['cities'=>'Cities',
        //'tours'=>'Tours',
        'blog'=>'News',
        //'actions'=>'Actions',
      ];
      if (isset(Hl::$aCurURL[0]))
      if (isset($aMenu[Hl::$aCurURL[0]]))
        $sActive = Hl::$aCurURL[0];
      foreach($aMenu as $kh=>$vh) {
      echo '<li'.($kh==$sActive?' class="active"':'').'><a href="'.Hl::url($kh).'">'.Hl::$t->_($vh).'</a></li>';
    }
    ?>
  </ul>

  <a href="#menu-toggle" class="pull-right" id="menu-toggle"><i class="fa fa-bars" aria-hidden="true"></i></a>

  <ul class="top-nav-right list-inline text-uppercase text-right hidden-sm hidden-xs hidden-md">
    <li class="text-lowercase phone-number">
      <small><?=Hl::$t->_('free call')?></small>
      <br>
      <span><?=Hl::opt('free_phone')?></span>
    </li>
    <li class="camera"><a href="#"><img src="<?=Hl::$u ?>/public/src/images/camera.png" alt="Web camera"></a></li>
    <?php if ( Hl::isAdmin() ) { ?>
      <li class="dropdown">
        <a href="<?=Hl::url('admin')?>">Admin</a>
      </li>
    <?php } ?>
    <li class="dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
         aria-haspopup="true" aria-expanded="false"><?php
        $aCurrencys = Currencys::all( true );
        echo $aCurrencys[ 'cur' ][ 'name' ];
        ?> &#8372; <span class="caret"></span></a>
      <ul class="dropdown-menu">
        <?php foreach ( $aCurrencys as $kCurr => $vCurr ) {
          if ( $kCurr != 'cur' )
            echo '<li><a href="#" title="' . $vCurr[ 'sign' ] . '">' .
              $vCurr[ 'name' ] . '</a></li>';
        } ?>
      </ul>
    </li>
    <li class="separator">|</li>
    <li class="dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
         aria-haspopup="true" aria-expanded="false"><?php
        $aLangs = Langs::load( true );
        $curLang = Hl::$l;//$this->dispatcher->getParam( 'language' );
        foreach ( $aLangs as $kl => $vl ) {
          if ( $curLang == $kl ) {
            echo $kl;
            break;
          }
        }
        ?> <span class="caret"></span></a>
      <ul class="dropdown-menu">
        <?php
        foreach ( $aLangs as $kl => $vl ) {
          if ( $curLang != $kl )
            echo '<li><a href="/' . $kl . '/' . implode('/',Hl::$aCurURL) . '" title="' . $vl[ 'name' ] . '">' . $kl . '</a></li>';
        }
        ?>
      </ul>
    </li>
    <li class="wr-btn-enter">
      <a href="<?=$this->auth->isUserSignedIn() ? Hl::url( 'user/signout' ) : Hl::url( 'login' ) ?>"
         class="btn btn-default btn-enter">
        <?=$this->auth->isUserSignedIn() ? ( Hl::$t->_( 'Logout' ) . ' ' . $this->auth->getIdentity()[ 'name' ] ) : Hl::$t->_( 'Login' ) ?>
      </a>
    </li>
  </ul>

</nav>

<button id="topScroll">
  <i class="fa fa-angle-double-up" aria-hidden="true"></i>
</button>


