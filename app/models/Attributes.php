<?php

class Attributes extends PrfModel
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
    public $obj_id;

    /**
     *
     * @var integer
     */
    public $attr_id;

    /**
     *
     * @var integer
     */
    public $table_id;

    /**
     *
     * @var string
     */
    public $val;

    /**
     *
     * @var string
     */
    public $val_uk;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('attr_id', 'TgAttrtypes', 'id', array('alias' => 'TgAttrtypes'));
        $this->belongsTo('table_id', 'TgTables', 'id', array('alias' => 'TgTables'));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Attributes[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Attributes
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
