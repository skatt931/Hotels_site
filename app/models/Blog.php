<?php

class Blog extends PrfModel
{

    /**
     *
     * @var integer
     */
    public $id;

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
    public $picture_id;

    /**
     *
     * @var string
     */
    public $title;

    /**
     *
     * @var string
     */
    public $title_uk;

    /**
     *
     * @var string
     */
    public $small_note;

    /**
     *
     * @var string
     */
    public $small_note_uk;

    /**
     *
     * @var string
     */
    public $note;

    /**
     *
     * @var string
     */
    public $note_uk;

    /**
     *
     * @var string
     */
    public $created;

    /**
     *
     * @var integer
     */
    public $trash;

  static function initStatic($oModel=null) {
    parent::initStatic($oModel);
    static::$fields['attr_id']['foreign_filter'] = 'parent_id=6700';
    static::$fields['user_id']['disabled'] = 'disabled';

    static::$fields['note']['hideInGrid'] = 'hideInGrid';
    foreach (Langs::load() as $kl=>$vl) {
      if (isset(static::$fields['note_'.$kl]))
        static::$fields['note_'.$kl]['hideInGrid'] = 'hideInGrid';
    }
  }
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('picture_id', 'TgPictures', 'id', array('alias' => 'TgPictures'));
        $this->belongsTo('user_id', 'TgUsers', 'id', array('alias' => 'TgUsers'));
    }


    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Blog[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Blog
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
