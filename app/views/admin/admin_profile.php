<!-- menu profile quick info -->
<div class="profile">
  <div class="profile_pic">
    <img src="<?=Hl::user('picture')?>" alt="..." class="img-circle profile_img">
  </div>
  <div class="profile_info">
    <span>
      <a id="history" style="display: none"></a>
      <a id="curUrl" href="#" onclick="return getContent(this)"
      ><?=Hl::$t->_('Welcome')?>,</a>
    </span>
    <h2><?=Hl::user('name')?></h2>
  </div>
</div>
<!-- /menu profile quick info -->
