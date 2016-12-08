<?php

class Tables extends PrfModel
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
    public $adm_link;

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
    public $img;

    /**
     *
     * @var string
     */
    public $multilang;

    /**
     *
     * @var integer
     */
    public $system;

    /**
     *
     * @var integer
     */
    public $userinfo;

    /**
     *
     * @var string
     */
    public $has_feed;

    /**
     *
     * @var string
     */
    public $link_field;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Tables[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Tables
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
