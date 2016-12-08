  <meta charset="UTF-8">
  <?php echo $this->tag->getTitle(); ?>
  <!--title>Document</title-->
  <?php
  if (count(Hl::$aMetaTags) > 0) {
    foreach (Hl::$aMetaTags as $kt=>$vt)
      echo '<meta name="'.$kt.'" content="'.$vt.'">'."\n";
  }
  include 'head_links.php';
  ?>
