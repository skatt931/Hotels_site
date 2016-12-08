<?php

class Tours extends PrfModel
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
    public $attr_id;

    /**
     *
     * @var integer
     */
    public $user_id;

    /**
     *
     * @var integer
     */
    public $place_id;

    /**
     *
     * @var integer
     */
    public $picture_id;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $name_uk;

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
     * @var double
     */
    public $price;

    /**
     *
     * @var integer
     */
    public $days;

    /**
     *
     * @var integer
     */
    public $trash;

  static function initStatic($oModel=null) {
    parent::initStatic($oModel);
    static::$fields['attr_id']['foreign_filter'] = 'parent_id=4400';
    static::$fields['user_id']['disabled'] = 'disabled';

    static::$fields['note']['hideInGrid'] = 'hideInGrid';
    foreach (Langs::load() as $kl=>$vl) {
      if (isset(static::$fields['note_'.$kl]))
        static::$fields['note_'.$kl]['hideInGrid'] = 'hideInGrid';
    }
  }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Tours[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Tours
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
