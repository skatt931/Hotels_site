<?php

class Actions extends PrfModel
{

  static function initStatic($oModel=null) {
    parent::initStatic($oModel);
    static::$fields['table_id']['foreign_filter'] = 'id in (4,15)';

    static::$fields['note']['hideInGrid'] = 'hideInGrid';
    foreach (Langs::load() as $kl=>$vl) {
      if (isset(static::$fields['note_'.$kl]))
        static::$fields['note_'.$kl]['hideInGrid'] = 'hideInGrid';
    }

    static::$fields['table_id']['foreign_show'] = 'note'.Hl::$pstfx;
    //Фільтри в залежності від користувача
    if (Hl::user('group_id') > 1) {
      if (strlen(Hl::user('user_can')) > 0) {
        static::$aConditions['conditions'] = 'user_id in (' .
          Hl::user('user_can') . ')';
      }
      else
        static::$aConditions['conditions'] = 'false';
      //static::$aConditions['bind']['user_id'] = Hl::user('id');

      static::$fields['user_id']['inaccessible'] = 'inaccessible';
    }
    else {
      static::$fields['user_id']['foreign_filter'] = 'group_id<5000';
      static::$fields['user_id']['foreign_show'] = 'name,email';
    }
  }
}
