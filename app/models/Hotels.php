<?php

class Hotels extends PrfModel
{

  /**
   *
   * @var integer
   */
  public $id;

  /**
   *
   * @var string
   */
  public $name;

  /**
   *
   * @var string
   */
  //public $name_uk;

  /**
   *
   * @var integer
   */
  public $user_id;

  /**
   *
   * @var integer
   */
  public $attr_id;

  /**
   *
   * @var integer
   */
  public $stars;

  /**
   *
   * @var integer
   */
  public $trash;

  static function initStatic($oModel=null) {
    parent::initStatic($oModel);
    static::$fields['attr_id']['foreign_filter'] = 'parent_id=4600';
    //Фільтри в залежності від користувача
    if (Hl::user('group_id') > 1) {
      static::$aConditions['conditions'] = '[user_id] IN ('.Hl::user('user_can').')';
      static::$fields['user_id']['inaccessible'] = 'inaccessible';
    }
    //static::$fields['price']['label'] = Hl::$t->_('Minimal price');
  }

    /**
   * Initialize method for model.
   */
  public function initialize()
  {
    $this->hasMany('id', 'Rooms', 'hotel_id', array('alias' => 'Rooms'));
    $this->belongsTo('attr_id', 'Attrtypes', 'id', array('alias' => 'Attrtypes'));
    $this->belongsTo('user_id', 'Users', 'id', array('alias' => 'Users'));
  }

  static function getSame() {
    $aSelSame = ['select' => 'hotels.*,path picture,alt,'.
      'hotels.name'.Hl::$pstfx.' hotels_name,'.
      'hotels.id hotel_id,'.
      'places.name'.Hl::$pstfx.' places_name',
      //'where' =>['rooms.hotel_id'=>$id],
      'join' => [
        'pictures'=>'pictures.id = hotels.picture_id',
        'places'=>'places.id = hotels.place_id',
      ],
      'limit' =>'8',
    ];
    if (is_array(Hl::$session->search) && isset(Hl::$session->search['city']))
      $aSelSame['where']['hotels.place_id'] = Hl::$session->search['city'];
    return Hl::q2a(
      HT::sel('hotels',$aSelSame),
      'id');

  }


}
