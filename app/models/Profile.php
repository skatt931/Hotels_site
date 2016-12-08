<?php

class Profile extends PrfModel
{
  static function initStatic($oModel=null) {
    parent::initStatic($oModel);
    static::$fields['user_id']['inaccessible'] = 'inaccessible';
    static::$fields['created']['inaccessible'] = 'inaccessible';
    static::$fields['updated']['inaccessible'] = 'inaccessible';
    //Фільтри в залежності від користувача
    if (Hl::user('group_id') > 1) {
      static::$aConditions['conditions'] = '[user_id] = :user_id:';
      static::$aConditions['bind']['user_id'] = Hl::user('id');

    }
  }


}
