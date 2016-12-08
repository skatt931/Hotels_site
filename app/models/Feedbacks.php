<?php

class Feedbacks extends PrfModel
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
    public $obj_id;

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
     * @var integer
     */
    public $likeit;

    /**
     *
     * @var string
     */
    public $created;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('attr_id', 'Attrtypes', 'id', array('alias' => 'Attrtypes'));
        $this->belongsTo('user_id', 'Users', 'id', array('alias' => 'Users'));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Feedbacks[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Feedbacks
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    static function get($param) {
      if (!is_array($param))
        $param = ['id'=>$param];
      return HT::sel('feedbacks',$param);
    }

}
