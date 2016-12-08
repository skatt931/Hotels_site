<?php
//

class HT //Helper for table Класс хелпер таблиц, все вспомогательные функции по таблицам
{
  static $prf;
  static function insPack($tableName,$aVals,$aUpds=null) { //Добавить пакет страниц в таблицу
    if (count($aVals) == 0)
      return false;
    
    $tableName = HT::prf($tableName);
    
    $aValsBind = []; //Значения для связывания
    foreach($aVals as $k=>$aRec) {
      $curRec = [];
      foreach($aRec as $kr=>$vr) {
        $bindKey = ':'.$kr.$k;
        $curRec[] = $bindKey;
        $aValsBind[$bindKey] = $vr;
      }
      if ($k == 0) {
        //$fld = 'created,'.implode(',',$aRec); //Шаблон полей берётся из первой записи
        $fld = 'created';
        foreach($aRec as $kr=>$vr)
          $fld .= ','.$kr;
        $aValsBind[':pid'] = $aRec['pid'];
      }
      $aBnd[] = '(CURRENT_TIMESTAMP,'.implode(',',$curRec).')';
    }
    
    $opts = '';
    
    $bnd = implode(',',$aBnd);
    $qr = "INSERT {$opts} INTO {$tableName} ({$fld}) VALUES {$bnd} ";
    if (!is_null($aUpds)) {
      if (is_array($aUpds)) {
        $sUpd = $aUpds[0];
        $aUpdsBind = $aUpds[1];
      }
      else $sUpd = $aUpds;
      
      $qr .= "ON DUPLICATE KEY ".
             "UPDATE ".$aUpds;
      
    }
          
    $cmnd = \Yii::$app->db->createCommand($qr);
                                         
    foreach($aValsBind as $k=>$v)
      $cmnd->bindValue($k, $v);
      
    foreach($aUpdsBind as $k=>$v)
      $cmnd->bindValue($k, $v);

    $res = $cmnd->execute();
    return print_r($res);
  }
  
  static function rep($tableName,$aVals){
    HT::ins($tableName,$aVals,true);
  }

  static function ins($tableName,$aVals,$replace=false) //Добавить запись с произвольным количеством параметров, в любую таблицу
  {
    $tableName = HT::prf($tableName);
    $fld = '';
    $bnd = '';
    $upd = '';
    
    if (isset($aVals['option'])) {
      $opts = $aVals['option'];
      unset($aVals['option']);
    }
    else
      $opts = '';

    $aValues = $aVals;
    foreach($aValues as $k=>$v) {
      if (($v === 'CTS') || ($v === 'CURRENT_TIMESTAMP')) {
        $fld .= ','.$k;
        $bnd .= ',CURRENT_TIMESTAMP ';
        unset($aValues[$k]);
      }
      else {
        $fld .= ','.$k;
        $bnd .= ',:'.$k;
      }
    }
    
      $fld = substr($fld,1);
    $bnd = substr($bnd,1);

    if ($replace)
      $sOperation = "REPLACE";
    else
      $sOperation = "INSERT";

    $db = Hl::$di->get("db");
    $qr = $sOperation." {$opts} INTO {$tableName} ({$fld}) VALUES ({$bnd}) ";
    $cmnd = $db->prepare($qr);

    foreach($aValues as $k=>$v)
      $cmnd->bindValue(':'.$k, $v);

    if (isset($aVals['query']))//Не запускать, а вернуть запрос
      return ['query'=>$qr,'params'=>$aValues];


    $cmnd->execute();
    return $db->LastInsertID();
  }
  
  static function makeWhere($aFilters,&$aBinds) { //Построить условие
    $whr = '';
    foreach($aFilters as $k=>$v) {
      if (is_array($v)) {
        if (isset($v[3])) 
        if ($v[3] == '(') //Открыть скобку
          $whr .= $v[3];
        
        if (isset($v[2]))
          $oper = ' '.$v[2].' ';
        else
          $oper = 'AND';
        $kBind = 'wh_'.str_replace('.','_',$k);
        Hl::sC($whr,$k.' '.$v[0].':'.$kBind,' '.$oper.' '); //Маркируем where параметры wh_
        $aBinds[$kBind] = $v[1];
        
        if (isset($v[3])) 
        if ($v[3] == ')') //Закрыть скобку
          $whr .= $v[3];
      }
      else {
        if(is_numeric($k)) {
          Hl::sC($whr,$v,' AND ');
        }
        else {
          $kBind = 'wh_'.str_replace('.','_',$k);
          Hl::sC($whr,$k.' = :'.$kBind,' AND ');
          $aBinds[$kBind] = $v;
        }
      }
      
    }
    return $whr;
  }
  
  static function query($sSQL,$aBinds=null) //Будь який запит до бази
  {
    $db = Hl::$di->get("db");
    $res = $db->fetchAll($sSQL,Phalcon\Db::FETCH_ASSOC,$aBinds);
    return $res;
  }
  
  static function del($tableName,$aFilters) //Удалить записи из таблицы
  {
    $tableName = HT::prf($tableName);
    $whr = HT::makeWhere($aFilters,$aBinds);

    $sSQL = "DELETE FROM {$tableName} WHERE {$whr} ";
    $db = Hl::$di->get("db");
    $res = $db->execute($sSQL, $aBinds);
    return $res;
  }

  static function upd($tableName,$aVals,$aFilters) //Изменить запись с произвольным количеством параметров, в любую таблицу
  {
    $tableName = HT::prf($tableName);
    $upd = '';
    $whr = '';
    
    $aValues = $aVals;
    foreach($aValues as $k=>$v) {
      if (($v === 'CTS') || ($v === 'CURRENT_TIMESTAMP')) {
        $upd .= ','.$k;
        $upd .= '=CURRENT_TIMESTAMP ';
        unset($aValues[$k]);
      }
      else {
        $upd .= ','.$k;
        $upd .= '=:'.$k;
      }
    }
    
    $upd = substr($upd,1);
    $whr = HT::makeWhere($aFilters,$aBinds);
    
    $cmnd = $db->createCommand("UPDATE {$tableName} SET {$upd} WHERE {$whr} "
                                         ); 
    foreach($aValues as $k=>$v)
      $cmnd->bindValue(':'.$k, $v);
      
    foreach($aBinds as $k=>$v)
      $cmnd->bindValue(':'.$k, $v);
    $res = $cmnd->execute();
    return $res;
  }

  static function sel($tableName,$aFilters=null) //Выбрать поля из таблицы
  {
    $dbTableName = HT::prf($tableName);

    $hasSelect = isset($aFilters['select']);
    if ($hasSelect) {
      $fld = $aFilters['select'];
      if (is_array($fld))
        $fld = implode(',',$fld);
    }
    else
      $fld = '*';

    $sSQLparams = '';
    if (isset($aFilters['SQL_CALC_FOUND_ROWS'])) {
      $sSQLparams = ' SQL_CALC_FOUND_ROWS ';
      unset($aFilters['SQL_CALC_FOUND_ROWS']);
    }

    $qr = "SELECT ".$sSQLparams.$fld;
    $qr .= " FROM ".$dbTableName.' as '.$tableName.' ';

    $hasJoin = isset($aFilters['join']);
    $join = '';
    if ($hasJoin) {
      foreach($aFilters['join'] as $k=>$j) {
        $join .= ' LEFT JOIN '.HT::prf($k).' as '.$k.' ON '.$j;
      }
    }
    $qr .= $join.' ';

    $hasGroup = isset($aFilters['group']);
    $hasOrder = isset($aFilters['order']);
    $hasLimit = isset($aFilters['limit']);
    $hasBind = isset($aFilters['bind']);

    if (!is_null($aFilters))
    if (!is_array($aFilters)) {
      $aFilters = ['where' => [$aFilters]];
    }
    elseif (!isset($aFilters['where']) &&
            !($hasSelect || $hasJoin || $hasOrder || $hasLimit || $hasBind))
      $aFilters = ['where' => $aFilters]; //Якщо не вказані будь які елементи

    $aBinds = [];
    if (isset($aFilters['where'])) {
      if (is_array($aFilters['where']))
        $whr = HT::makeWhere($aFilters['where'],$aBinds);
      else
        $whr = $aFilters['where'];
      if (strlen($whr) > 0)
        $qr .= " WHERE ".$whr;
    }
    
    if ($hasGroup) {
      $qr .= " GROUP BY ".$aFilters['group'];
    }

    if ($hasOrder) {
      $qr .= " ORDER BY ".$aFilters['order'];
    }

    if ($hasLimit) {
      if (is_array($aFilters['limit']))
        $qr .= " LIMIT ".$aFilters['limit'][0].','.$aFilters['limit'][1];
      else
        $qr .= " LIMIT ".$aFilters['limit'];
    }

    if ($hasBind) //Пользовательские переменные
      $aBinds = array_merge($aBinds,$aFilters['bind']);

    if (isset($aFilters['after']))//Додати запити післядії
      $qr .= ';'.$aFilters['after'];

    if (isset($aFilters['query']))//Не запускать, а вернуть запрос
      return ['query'=>$qr,'params'=>$aBinds];

    $db = Hl::$di->get("db");
    $resultset = $db->fetchAll($qr,Phalcon\Db::FETCH_ASSOC,$aBinds);

    return $resultset;
  }

  //Добавить префикс базы к таблице для запроса
  static function prf($tableName='') {
    if (!isset(HT::$prf)) {
      $config = Hl::$di->get("config");
      HT::$prf = $config->database->prefix;
    }
    return HT::$prf.$tableName;
  }
  
}