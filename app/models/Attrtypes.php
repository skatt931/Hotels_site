<?php

class Attrtypes extends PrfModel
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
    public $parent_id;

    /**
     *
     * @var integer
     */
    public $trash;

  /**
   * Initialize method for model.
   */
    public function initialize()
    {
      $this->hasMany('id', 'Attrtypes', 'parent_id', array('alias' => 'Attrtypes'));
      $this->hasMany('id', 'Attributes', 'attr_id', array('alias' => 'Attributes'));
      $this->hasMany('id', 'Feedbacks', 'attr_id', array('alias' => 'Feedbacks'));
      $this->hasMany('id', 'Hotels', 'attr_id', array('alias' => 'Hotels'));
      $this->hasMany('id', 'Places', 'attr_id', array('alias' => 'Places'));
      $this->hasMany('id', 'Tours', 'attr_id', array('alias' => 'Tours'));
      $this->belongsTo('parent_id', 'Attrtypes', 'id', array('alias' => 'Attrtypes'));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Attrtypes[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Attrtypes
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

  static function get($attrTNumb) {
    $aAttrtypes = Attrtypes::all();
    return $aAttrtypes[$attrTNumb];
  }

  static function all($reload=false) {
    $session = Hl::$session;
    if ($reload || !$session->has("Attrtypes")) {
      $mAttrtypes = Attrtypes::find(["order" => "coalesce(parent_id,id),id"]); //['trash' => 0]
      $aAttrtypes = [];
      foreach ($mAttrtypes as $a) {
        $a = (array)$a;
        $aAttrtypes[$a['id']] = ['name' => $a['name'.Hl::$pstfx],
          'parent_id' => $a['parent_id'],
        ];
      }
      unset($mAttrtypes);
      $session->set("Attrtypes", $aAttrtypes);

      return $aAttrtypes;
    }
    else
      return $session->get("Attrtypes");
    //$this->view->setVar("attr", $mAttrTypes);
  }

}
