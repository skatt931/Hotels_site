<?php

class Prices extends PrfModel
{
  static function initStatic($oModel=null) {
    parent::initStatic($oModel);
    static::$fields['attr_id']['foreign_filter'] = 'id in (7000)';
    static::$fields['obj_id']['foreign'] = 'rooms';
    //Фільтри в залежності від користувача
    if (Hl::user('group_id') > 1) {
      static::$fields['obj_id']['foreign_filter'] = 'false';
      static::$aConditions['conditions'] = 'false';
      if (strlen(Hl::user('user_can')) > 0) {
        $qRooms = HT::sel('rooms',
          ['select' => 'GROUP_CONCAT(rooms.id SEPARATOR ",") rooms_list',
            'where' => 'user_id IN (' . Hl::user('user_can') . ')',
            'join' => ['hotels' => 'rooms.hotel_id = hotels.id'],
          ]
        );
        if (strlen($qRooms[0]['rooms_list']) > 0)
          static::$aConditions['conditions'] = 'attr_id = 7000 AND obj_id in (' .
            $qRooms[0]['rooms_list'] . ')';

        static::$fields['obj_id']['foreign_filter'] = 'id in (' .
          $qRooms[0]['rooms_list']. ')';
      }
      static::$fields['user_id']['inaccessible'] = 'inaccessible';
    }
    if (Hl::user('group_id') == 1000 || Hl::user('parent_id') > 0) { //Умова для менеджера
      static::$aConditions['conditions'] .= ' OR [user_id] = :parent_id:';
      static::$aConditions['bind']['user_id'] = Hl::user('parent_id');
    }
    //static::$fields['price']['label'] = Hl::$t->_('Minimal price');
  }
}
