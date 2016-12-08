<?php

class Orders extends PrfModel
{
  static function initStatic($oModel=null) {
    parent::initStatic($oModel);
    //Фільтри в залежності від користувача
    if (Hl::user('group_id') > 1) {
      $qRooms = HT::sel('rooms',
        ['select' => 'rooms.id',
         'where' => 'user_id = '.Hl::user('id').' OR user_id = '.(int)Hl::user('parent_id'),
         'join' => ['hotels' => 'rooms.hotel_id = hotels.id'],
        ]
      );
      $aRooms = Hl::q2sel($qRooms,'id','id',[0=>0]);
      static::$aConditions['conditions'] = 'room_id in ('.
        implode(',',$aRooms).')';
      //static::$aConditions['bind']['user_id'] = Hl::user('id');

      static::$fields['obj_id']['foreign_filter'] = 'id in ('.implode(',',$aRooms).')';
      static::$fields['user_id']['inaccessible'] = 'inaccessible';
    }
    if (Hl::user('group_id') == 1000 || Hl::user('parent_id') > 0) { //Умова для менеджера
      static::$aConditions['conditions'] .= ' OR [user_id] = :parent_id:';
      static::$aConditions['bind']['user_id'] = Hl::user('parent_id');
    }
    //static::$fields['price']['label'] = Hl::$t->_('Minimal price');
  }
}
