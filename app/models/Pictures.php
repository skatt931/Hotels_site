<?php

class Pictures extends PrfModel
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $obj_id;

    /**
     *
     * @var integer
     */
    public $attr_id;

    /**
     *
     * @var integer
     */
    public $user_id;

    /**
     *
     * @var string
     */
    public $path;

    /**
     *
     * @var string
     */
    public $note;

    /**
     *
     * @var string
     */
    public $note_uk;

    /**
     *
     * @var string
     */
    public $alt;

    /**
     * Initialize method for model.
     */
    static $aSizes = [60=>60,95=>95,150=>150,255=>195,260=>150,270=>280,350=>360,980=>520];

  static function getSizesByAttr($attr_id) { //Завантажити необхідні формати картинок за отрибутом
    $sSizes = '60:60,80:80,260:260,270:270';
    if ($attr_id >= 4600 &&
      $attr_id < 5000)
      $sSizes = Hl::opt('image_sizes_hotels',$sSizes);
    if ($attr_id >= 7000 &&
      $attr_id <= 7000)
      $sSizes = Hl::opt('image_sizes_rooms',$sSizes);
    return Pictures::makeSizes($sSizes);
  }

  static function sizes($ind=null) {
    if (!isset(Pictures::$aSizes))
      Pictures::$aSizes = Pictures::makeSizes(Hl::opt('image_sizes'));
    if (is_null($ind))
      return Pictures::$aSizes;
    else
      return Pictures::$aSizes[$ind];
  }

  static function pictSizeN($sPath,$numb='') {
    $aName = explode('.',basename($sPath));
    if (!isset($aName[1]))
      return $sPath;
    $newName = $aName[0].'_'.$numb.'x'.Pictures::sizes($numb).'.'.$aName[1];
    $newName = str_replace(basename($sPath),$newName,$sPath);
    if (file_exists(__DIR__.'/../../'.$newName))
      return $newName;
    else
      return $sPath;
  }

  static function randName($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }

  static function make($path,$aSize=[],$changeName=true) {//Перетворює картинку в потрібні формати і знищує її
    $server_path = __DIR__.'/../..';
    $full_path = $server_path.'/'.trim($path,'/');
    $baseName = basename($path);
    $aName = explode('.',$baseName);
    $ext = '.'.($aName[1]);

    if ($changeName) {
      $part_path = '/public/src/images/'.date("Y").'/'.date("m");
      $new_path = $server_path.$part_path;
      if (!is_dir($new_path))
        mkdir($new_path, 0777,true);
      $new_path = $new_path.'/';
      $new_name =  Pictures::randName();
      $new_full = $new_path.$new_name.$ext;
      if(file_exists($new_full)) {
        $new_name =  Pictures::randName();
      }
      $new_full = $new_path.$new_name.$ext;
    }
    else {
      $new_name =  $aName[0];
      $new_full = $full_path;
      $new_path = str_replace($baseName,'',$full_path);
      $part_path =  $new_path;
      $ret_path = $full_path;
    }

    $image = new Phalcon\Image\Adapter\Imagick($full_path);
    $imgWidth = $image->getWidth();
    $imgHeight = $image->getHeight();
    $maxSize = Hl::opt('image_size_max',1000);

    if ($imgWidth > $maxSize || $imgHeight > $maxSize) { //Якщо завелика, то зменшуємо
      if ($imgWidth > $imgHeight)
        $imgMax = $imgWidth;
      else
        $imgMax = $imgHeight;
      $image->resize(($imgWidth/$imgMax)*$maxSize, ($imgHeight/$imgMax)*$maxSize);
      //Перенос картинки до правильного каталогу
      $image->save($new_full);
      $ret_path = $part_path.'/'.$new_name.$ext;
    }
    else
      if ($changeName) {
        $image->save($new_full);
        $ret_path = $part_path.'/'.$new_name.$ext;
      }

    //if ($changeName)
    //unlink($full_path);
    $ret_path .= ';remember to ulink';

    $full_path = $new_full;
//    Pictures::sizes();
    foreach ($aSize as $ks=>$vs) {
      $new_full = $new_path.$new_name.'_'.$ks.'x'.$vs.$ext;
      Pictures::resizeImage($full_path,$new_full,$ks,$vs);
      $ret_path .= ';'.$part_path.$new_name.'_'.$ks.'x'.$vs.$ext;
        //echo 'save '.$new_full;
    }
    return $ret_path;
  }

  static function resizeImage($fullPath,$newPath,$imgNewWidth, $imgNewHeight) {
    //Зменшити фото і вирізати середину
    $image = new Phalcon\Image\Adapter\Imagick($fullPath);
    $imageWidth = $image->getWidth();
    $imageHeight = $image->getHeight();

    //Зменшуємо так, щоб нова сторона була більшою за нову обрізку
    $ResizeWidth = $imgNewWidth;
    $ResizeHeight = ($imgNewWidth/$imageWidth) * $imageHeight;
    if ($ResizeHeight < $imgNewHeight) {
      $ResizeWidth = ($imgNewHeight/$imageHeight) * $imageWidth;
      $ResizeHeight = $imgNewHeight;
      $imgWidthOf = ($ResizeWidth - $imgNewWidth)/2;
      $imgHeightOf = 0;
    }
    else {
      $imgWidthOf = 0;
      $imgHeightOf = ($ResizeHeight - $imgNewHeight)/2;
    }

    $image->resize($ResizeWidth, $ResizeHeight);
    $image->crop($imgNewWidth, $imgNewHeight, $imgWidthOf, $imgHeightOf);
    $image->save($newPath);
  }

  static function initStatic($oModel=null) {
    parent::initStatic($oModel);
    static::$fields['attr_id']['foreign_filter'] = 'parent_id=4600 OR id in (3500,4600,7000)';
    //Фільтри в залежності від користувача
    if (Hl::user('group_id') > 1) {
      //Фото юзера
      $sCondUser = '([user_id] = '.Hl::user('user_can').' AND attr_id in (3500) )';
      if (strlen(Hl::user('user_can')) > 0)
        static::$aConditions['conditions'] = '( (attr_id in (7000) '. //Фото номерів
          'OR attr_id BETWEEN 4600 AND 4999) '. //Фото готелів
          'AND [user_id] IN ('.Hl::user('user_can').') ) OR '.$sCondUser;
      else
        static::$aConditions['conditions'] = $sCondUser;
      //static::$aConditions['conditions'] = '[user_id] = :user_id:';
      //static::$aConditions['bind']['user_id'] = Hl::user('id');
      static::$fields['user_id']['inaccessible'] = 'inaccessible';
    }
    //static::$fields['price']['label'] = Hl::$t->_('Minimal price');
  }

  public function initialize()
  {
      $this->belongsTo('user_id', 'TgUsers', 'id', array('alias' => 'TgUsers'));
      $this->belongsTo('attr_id', 'TgAttrtypes', 'id', array('alias' => 'TgAttrtypes'));
  }

  static function makeSizes($sSizes) {
    $aRes = [];
    Pictures::sizes();
    foreach (explode(',',$sSizes) as $v) {
      $aVal = explode(':',$v);
      if (isset($aVal[1]))
        $aRes[$aVal[0]] = $aVal[1];
      else {
        if (isset(Pictures::$aSizes[$v]))
          $aRes[$v] = Pictures::$aSizes[$v];
        else
          $aRes[$v] = $v;
      }
    }
    return $aRes;
  }


}
