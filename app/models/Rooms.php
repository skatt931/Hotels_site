<?php

class Rooms extends PrfModel
{

    public function initialize()
    {
        $this->belongsTo('attr_id', 'Attrtypes', 'id', array('alias' => 'Attrtypes'));
        $this->belongsTo('hotel_id', 'Hotels', 'id', array('alias' => 'Hotels'));
    }

  static function initStatic($oModel=null) {
    parent::initStatic($oModel);
    static::$fields['attr_id']['foreign_filter'] = 'parent_id=-4600';
    //Фільтри в залежності від користувача
    if (Hl::user('group_id') > 1) {
      if (strlen(Hl::user('user_can')) > 0) {
        $qHotels = HT::sel('hotels',
          ['select' => 'GROUP_CONCAT(id SEPARATOR ",") hotels_list',
            'where' => 'user_id IN (' . Hl::user('user_can') . ')',
          ]
        );
        if (strlen($qHotels[0]['hotels_list']) > 0)
          static::$aConditions['conditions'] = 'hotel_id in (' .
            $qHotels[0]['hotels_list'] . ')';
        else
          static::$aConditions['conditions'] = 'false';
      }
      else
        static::$aConditions['conditions'] = 'false';
      //static::$aConditions['bind']['user_id'] = Hl::user('id');

      static::$fields['user_id']['inaccessible'] = 'inaccessible';
    }
  }

}
