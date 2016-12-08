<?php

use Phalcon\Tag;
class Hl /*Helper*/
{
  static $di;
  static $u;  //Шлях до кореню ресурсів
  static $t;  //Посилання на об'ект перекладач
  static $url;  //Посилання Url
  static $aCurURL;  //Біжуче посилання
  static $name;  //Ім'я поля нейм для біжучої мови
  static $pstfx;  //Розширення імені в залежності від мови
  static $l;  //Мова
  static $user;  //Дані користувача
  static $session;  //Біжуча сессія
  static $aTables;  //Таблиці системи
  static $aTableFields;  //Таблиці системи
  static $aMetaTags=[];  //Список meta tags
  static $aScriptList;  //Список javascript files

  static function l($note='def') {
    $db = Hl::$di->get("db");
    $varType = gettype($note);
    if ($varType == 'boolean')
      if ($note)
        $note = 'true';
      else
        $note = 'false';
    $result = $varType." ".print_r($note,1);
    $resultset = $db->query("INSERT INTO tg__errors (note) VALUES (:note)",
                                     ['note' => $result]);
    return $result;
  }
  
  static function getAttrs($nAttr=5000,$bPicts=false,$reload=false) {
    $session = Hl::$di->get('session');
    if ($reload || !$session->has("Attrs_".$nAttr)) {
      $aAttrtypes = Attrtypes::all($reload);
      $sOptions = '';
      $aOptions[$nAttr] = $aAttrtypes[$nAttr];
      foreach ($aAttrtypes as $kAttr=>$vAttr) {
        if ($vAttr['parent_id'] == $nAttr) {
          $sOptions .= ',"'.$kAttr.'"';
          $aOptions[$kAttr] = $vAttr;
        }
      }
      //ksort($aOptions);

      if ($bPicts) {
        $sOptions = substr($sOptions,1);
        $aPicts = HT::sel('pictures', 'attr_id = "'.$nAttr.'" AND obj_id IN ('.$sOptions.')');
        foreach ($aPicts as $p)
          $aOptions[$p['obj_id']]['pict'] = $p['path'];
      }

      $session->set("Attrs_".$nAttr, $aOptions);
      return $aOptions;
    }
    else
      return $session->get("Attrs_".$nAttr);
  }

  static function sC(&$begStr,$str2add='',$strDelim=',') {
    //smartConcat append with delimiter
    if ($begStr == '')
      $begStr = $str2add;
    else
      $begStr = $begStr.$strDelim.$str2add;
    return $begStr;
  }

  static function isAdmin()
  {
    return Hl::user('group_id') == 1;
  }

  static function user($ind='') {
//    $this->auth->isUserSignedIn()
    if (!isset(Hl::$user)) {
      Hl::$user = [
        'id' => 0,
        'name' => '',
        'group_id' => 999999,
        'picture' => '/public/src/images/user.png',
        'parent_id' => 0,
        'user_can' => '', //Перелік користувачів до інформації яких є доступ
      ];
      $identity = Hl::$di->get('auth')->getIdentity();
      if (is_array($identity)) {
        if (isset($identity['id'])) {
          Hl::$user['id'] = $identity['id'];
          $qUser = HT::sel('users', ['id' => $identity['id']]);
          Hl::$user['parent_id'] = $qUser[0]['parent_id'];
          Hl::$user['name'] = $qUser[0]['name'];
          if (count($qUser) > 0) {
            $qUserProfile = HT::sel('profile', ['user_id' => $identity['id']]);
            if (count($qUserProfile) > 0)
              if ($qUserProfile[0]['picture_id'] > 0) {
                $qPicture = HT::sel('pictures', ['id' => $qUserProfile[0]['picture_id']]);
                Hl::$user['picture'] = Pictures::pictSizeN($qPicture[0]['path'],60);
              }
            Hl::$user['group_id'] = $qUser[0]['group_id'];

            Hl::$user['user_can'] = $identity['id']; //Додаємо самого користувача
            $parent_id = 0;
            if (Hl::$user['group_id'] >=100 && Hl::$user['group_id'] < 1000)
              $parent_id = $identity['id']; //Для власника шукаємо підлеглих
            elseif (Hl::$user['group_id'] >=1000 && Hl::$user['group_id'] < 5000) {
              //Для менеджера шукаєм підлеглих шефа
              $parent_id = $identity['parent_id'];
              Hl::$user['user_can'] = ','.$parent_id; //Додаємо батьківського користувача
            }
            if ($parent_id > 0) {
              $aWhere = ['parent_id' => $parent_id ];
              $qChilds = HT::sel('users',
                ['select'=>'GROUP_CONCAT(id SEPARATOR ",") child_ids',
                 'where'=>$aWhere,
                ] );
              if (strlen($qChilds[0]['child_ids']) > 0) {
                Hl::$user['user_can'] = ','.$qChilds[0]['child_ids'];
              }
            }
          }
        }
      }
    }
    if ($ind == '')
      return Hl::$user;
    else
      return Hl::$user[$ind];
  }

  static function url($route='') {
    $url = Hl::$url->getStatic(trim($route,'/'));
    if ($url[0] != '/')
      $url = '/'.$url;
    return $url;
  }

  static function opt($optionName,$def='') {
    $qOpt = HT::sel('options',['name' => $optionName]);
    if (count($qOpt) > 0) {
      $qUserOpt = HT::sel('user_options',
        ['where'=>['option_id' => $qOpt[0]['id'],
          'user_id' => Hl::user('id')],
        ]
      );
      if (count($qUserOpt) > 0)
        return $qUserOpt[0]['val'];
      return $qOpt[0]['val'];
    }
    else
      return $def;
  }

  static function Uc($str) {
    if (preg_match('/[a-z]/ui', $str[0]))
      return ucfirst($str);

    $first = mb_strtoupper($str[0].$str[1], 'utf8');
    $str[0] = $first[0];
    $str[1] = $first[1];
    return $str;
  }

  static function q2a($aQueryResult,$keyField='id') { //,$fieldsList=[] //Винести в індекс значення ключового поля
    if (count($aQueryResult) == 0) return false;
    $aRes = [];
    foreach ($aQueryResult as $q)
      $aRes[$q[$keyField]] = $q;
    return $aRes;
  }

  static function q2sel($aQueryResult,$valueField='name',$keyField='id',$defVal='') { //Перетворити вибірку для можливості представлення у вигляді селекта хтмл
    if (!is_array($aQueryResult)) return $defVal;
    if (count($aQueryResult) == 0) return $defVal;
    if (!is_executable($valueField))
    if (Hl::aSafe($aQueryResult,[0,$valueField.Hl::$pstfx],false))
      $valueField .= Hl::$pstfx;
    $aRes = [];
    $aListFields = explode(',',$valueField);
    if (count($aListFields) < 2) $aListFields = false;
    foreach ($aQueryResult as $q) {
      if (is_executable($valueField))
        $aRes[$q[$keyField]] = $valueField($q);
      else
        if ($aListFields) {
          $sVal = '';
          foreach ($aListFields as $l) {
            if ($sVal != '') $sVal .=', ';
            $sVal .= $q[$l];
          }
        }
        else
          $sVal = $q[$valueField];
        $aRes[$q[$keyField]] = $sVal;
    }
    return $aRes;
  }

  static function aSafe($aArray,$aKey,$defValue='') {//безпечно (без повідомлення) отримати значення масиву або об'єкту
    if (is_object($aArray)) //Якщо це модель
      $aArray = get_class_vars($aArray);
    if (is_null($aArray) ||count($aArray) == 0 || !is_array($aArray)) return $defValue;
    if (is_numeric($aKey)) $aKeys = [$aKey];
    elseif (!is_array($aKey)) $aKeys = explode(',',$aKey);
    else $aKeys = $aKey;
    $lastValue = $aArray;
    foreach ($aKeys as $kv) {
      if (is_object($lastValue))
        $lastValue = get_object_vars($lastValue);
      if (isset($lastValue[$kv]))
        $lastValue = $lastValue[$kv];
      else
        return $defValue;
    }
    return $lastValue;
  }

  static function stars($cnt,$max=5,
                        $star1='<i class="fa fa-star truestar" aria-hidden="true"></i>',
                        $star2='<i class="fa fa-star-o truestar" aria-hidden="true"></i>'
  )
  {
    $sRes = '';
    for($i=0;$i<$cnt;$i++)
      $sRes .= "\n".$star1;
    while ($i<$max) {
      $i++;
      $sRes .= "\n".$star2;
    }
    return $sRes;
  }

  static function tableFields($name=false) {
    if (!isset(Hl::$aTables))
      static::tables();
    if ($name) {
      if (isset(Hl::$aTableFields[$name]))
        return Hl::$aTableFields[$name];
      else
        return false;
    }
    else
      return Hl::$aTableFields;
  }

  static function tables($name=false) {
    if (!isset(Hl::$aTables)) {
      $qTables = HT::sel('tables');

      $aTables = Hl::q2a($qTables,'name');
      foreach ($aTables as $kt=>$vt) {
        if (strlen($vt['options']) > 0)
          $aTables[$kt]['options'] = get_object_vars(json_decode($vt['options']));
      }
      Hl::$aTables = $aTables;

      $aTables = Hl::q2a($qTables,'id');
      $qTableFields = HT::sel('tablefields');
      $aTableFields = [];
      foreach ($qTableFields as $f) {
        if ($f['table_id'] == 0)
          $keyTable = 'common';
        else
          $keyTable = $aTables[$f['table_id']]['name'];
        if (strlen($f['property']) > 0)
          $aTableFields[$keyTable][$f['name']] = get_object_vars(json_decode($f['property']));
      }
      Hl::$aTableFields = $aTableFields;
    }
    if ($name) {
      if (isset(Hl::$aTables[$name]))
        return Hl::$aTables[$name];
      else
        return false;
    }
    else
      return Hl::$aTables;

  }

  static function buttons($id,$name,$opers=['edit','delete']) {
    static $aOpers;
    if (count($opers) == 0)
      $opers=['edit','delete'];
    $sRes = '';
    if (!isset($aOpers))
      $aOpers = [
        'descriptions'=>['Meta','btn btn-primary btn-xs','fa fa-tag',],
        'notes'=>['Notes','btn btn-primary btn-xs','fa fa-tag',],
        'attributes'=>['Attributes','btn btn-primary btn-xs','fa fa-tag',],
        'location'=>['Location','btn btn-primary btn-xs','fa fa-globe',],
        'view'=>['View','btn btn-primary btn-xs','fa fa-folder',],
        'edit'=>['Edit','btn btn-info btn-xs','fa fa-pencil',],
        'delete'=>['Delete','btn btn-danger btn-xs','fa fa-trash-o',],
        ];
    foreach ($opers as $k=>$o) {
      if (is_numeric($k)) {
        $aTeml = $aOpers[$o];
        $oper = $o;
      }
      else {
        $aTeml = $o;
        $oper = $k;
      }
      $sRes .= "\n".Phalcon\Tag::linkTo( [Hl::url( $name."/$oper/" . $id ),
          '<i class="'.$aTeml[2].'"></i> '.Hl::$t->_($aTeml[0]),
          'class'=> $aTeml[1]] );
    }
    return $sRes;
  }

  static function df($sDate) {//Перетворює дату на вірний формат
    $aDate = date_parse($sDate);
    $aDate['day'] = str_pad($aDate['day'],2,'0',STR_PAD_LEFT);
    $aDate['month'] = str_pad($aDate['month'],2,'0',STR_PAD_LEFT);
    return implode('.',[$aDate['day'],$aDate['month'],$aDate['year'],]);
  }

  /**
   * trims text to a space then adds ellipses if desired
   * @param string $input text to trim
   * @param int $length in characters to trim to
   * @param bool $ellipses if ellipses (...) are to be added
   * @param bool $strip_html if html tags are to be stripped
   * @return string
   */
  static function trimText($input, $length, $ellipses = true, $strip_html = true) {
    //strip tags, if desired
    if ($strip_html) {
        $input = strip_tags($input);
    }

    //no need to trim, already shorter than trim length
    if (strlen($input) <= $length) {
      return $input;
    }

    //find last space within length
    $last_space = strrpos(substr($input, 0, $length), ' ');
    $trimmed_text = substr($input, 0, $last_space);

    //add ellipses (...)
    if ($ellipses) {
      $trimmed_text .= '...';
    }

    return $trimmed_text;
  }
  static function array_column($aArray,$sKey,$bUnique=true) { //Виокремлює колонку з масиву
    $aRes = [];
    foreach ($aArray as $a) {
      if (isset($a[$sKey]))
      if (strlen($a[$sKey]) > 0) {
        if ($bUnique)
          $aRes[$a[$sKey]] = $a[$sKey];
        else
          $aRes[] = $a[$sKey];
      }
    }
    return $aRes;
  }
}
