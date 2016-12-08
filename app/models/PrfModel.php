<?php

class PrfModel extends \Phalcon\Mvc\Model
{

  static $prefix;
  
  static $fields;

  static $mName;  //Ім'я моделі
  static $mTable;  //Ім'я таблиці

  static $aConditions = []; //Обов'язкові умови для словника

  public function getSource()
  {
    if (!isset(static::$mTable))
      static::$mTable = strtolower(get_class($this));
    return HT::prf().static::mTable();
  }

  static function mTable() {
    if (!isset(static::$mTable))
      static::$mTable = strtolower(static::mName());
    return static::$mTable;
  }

  static function mName() {
    if (strlen(static::$mName) == 0)
      return get_called_class();
    else
      return static::$mName;
  }

  static function initStatic($oModel=null) {
    //Тут має бути ініціалізація статичних змінних з інформацією про поля
    if (!isset(static::$fields)) {
      $db = Hl::$di->get("db");
      $sTable = static::mTable();
      $qr = 'SHOW FULL COLUMNS FROM '.HT::$prf.$sTable;
      $qFields = $db->fetchAll($qr,Phalcon\Db::FETCH_ASSOC);
      $aFields = [];
      foreach ($qFields as $f) {
        $aFields[$f['Field']] = [];
        $aType = explode('(',$f['Type']);
        if (isset($aType[1])) {
          $aFields[$f['Field']]['type'] = $aType[0];
          $aFields[$f['Field']]['size'] = (int)$aType[1];
        }
        else
          $aFields[$f['Field']]['type'] = $f['Type'];
        /*
        if (strlen($f['Comment']) > 0)
          $aFields[$f['Field']]['label'] = $f['Comment'];
*/
      }
      //Вичитуємо дані з таблиці полів
      $aFieldsProps = Hl::tableFields();
      $aLangs = Langs::load();
      foreach ($aFields as $kf=>$vf) {
        if (substr($kf, -3) == Hl::$pstfx) { //Якщо є підозра що поле багатомовне, копіюєм властивості
          $sNameField = substr($kf, 0, strlen($kf) - 3);
          if (isset($aFields[$sNameField])) {
            $aFields[$kf] = $aFields[$sNameField];
            if (Hl::aSafe($aFields,[$sNameField,'label'],false))
              $aFields[$kf]['label'] .= ' ('.$aLangs[substr(Hl::$pstfx,1,2)]['name'].')';
            continue;
          }
        }
        $aProperties = Hl::aSafe($aFieldsProps,[$sTable,$kf],false);
        if (!$aProperties)
          $aProperties = Hl::aSafe($aFieldsProps,['common',$kf],false);
        if ($aProperties) {//Знайшли опис поля
          foreach ($aProperties as $kp => $vp) { //Копіюєм дані в масив ознак поля
            if (substr($kp, -3) == Hl::$pstfx) $kp = substr($kp, 0, strlen($kp) - 3);
              $aFields[$kf][$kp] = $vp;
          }
        }

      }
      static::setFields($aFields);
    }
  }

  static function fields() {
    if (!isset(static::$fields))
      static::initStatic();
    return static::$fields;
  }

  static function setFields($aFields,$oModel=null) { //Наповнює массив опису полів даних моделі
    static::$fields = [];
    $emptyLang = Hl::$di->get('config')->languages->emptyPostfix;
    $aTables = Hl::tables();
    foreach ($aFields as $kf=>$vf) {
      static::$fields[$kf] = $vf;
      if (!isset($aTablesBack)) {
        $aTablesBack = [];
        foreach ($aTables as $t)
          $aTablesBack[$t['link_field']] = $t;
      }
      $keyTable = Hl::aSafe($aTablesBack,[$kf,'id'],false);
      if ($keyTable) //Пошук поля з відомими зв'язками
        static::$fields[$kf]['foreign'] = $aTablesBack[$kf]['name'];

      //if (isset($vf['foreign'])) { //Опрацювання поля зовнішніх зв'язків
      if (isset($vf['multiLang'])) { //Розшифровка полів з Багатомовністю
        $aLangs = Langs::load();
        foreach ($aLangs as $kl=>$vl) {
          if ($vl['trash'] == 0)
          if ($emptyLang != $kl) {
            $key = $kf.'_'.$kl;
            if (is_object($oModel))
              $oModel->$key = ''; //Додаєм в модель властивість
            static::$fields[$key] = $vf;
            $l = static::$fields[$key]['label'];
            if (isset($l))
              static::$fields[$key]['label'] = $l.' '.$vl['name'];
          }
        }

      }
    }
    return static::$fields;
  }

  public function setByPost($flds) {
    $aTableFields = static::fields();
    foreach ($flds as $propertyToSet => $value) {
      if (property_exists($this,$propertyToSet) || isset($aTableFields[$propertyToSet])) {
        if (isset(static::$fields[$propertyToSet]['type'])) {
          if (static::$fields[$propertyToSet]['type'] == 'int')
            if (!is_numeric($value))
              $value = null;
            else
              $value = (int)$value;
        }
        $this->$propertyToSet = $value;
      }
    }
  }

  static function mergeConditions($parameters = null) {
    static::fields(); //Заповнюємо поля
    if (count(static::$aConditions) > 0) {
      if (isset(static::$aConditions['conditions']))
      if (isset($parameters['conditions']))
        $parameters['conditions'] .= ' AND ('.static::$aConditions['conditions'].')';
      else {
        $parameters['conditions'] = static::$aConditions['conditions'];
      }
      if (isset(static::$aConditions['bind']))
        foreach (static::$aConditions['bind'] as $kb=>$vb)
          $parameters['bind'][$kb] = $vb;
    }
    return $parameters;
  }

  public static function find($parameters = null)
  {
    return parent::find(static::mergeConditions($parameters));
  }

  /**
   * Allows to query the first record that match the specified conditions
   *
   * @param mixed $parameters
   * @return Toplists
   */
  public static function findFirst($parameters = null)
  {
    return parent::findFirst($parameters);
  }
  
}
