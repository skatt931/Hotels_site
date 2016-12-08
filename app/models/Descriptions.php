<?php

class Descriptions extends PrfModel
{
  static function initStatic($oModel=null) {
    parent::initStatic($oModel);
    static::$fields['attr_id']['foreign_filter'] = 'id=12';
    static::$fields['table_id']['foreign_filter'] = 'id in (4,15)';
    static::$fields['obj_id']['foreign'] = 'hotels';
    static::$fields['obj_id']['foreign_filter'] = 'user_id IN ('.Hl::user('user_can').')';
    //Фільтри в залежності від користувача
    if (Hl::user('group_id') > 1) {
      static::$aConditions['conditions'] = '[user_id] IN ('.Hl::user('user_can').')';
      static::$fields['user_id']['inaccessible'] = 'inaccessible';
    }
  }
}
