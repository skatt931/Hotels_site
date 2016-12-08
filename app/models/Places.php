<?php

class Places extends PrfModel
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
    public $name_uk;

    /**
     *
     * @var integer
     */
    public $attr_id;

    /**
     *
     * @var integer
     */
    public $parent_id;

  static function getParent($id,$attr_id=null,$deep=0){ //Номер місця, зупинка по атрибуту
    if ($deep > 50) return false;
    // (для пошуку країни)
    $qPlace = HT::sel('places',['id'=>$id]);
    if (count($qPlace) > 0) {
      $qPlace = $qPlace[0];
      if ($qPlace['parent_id'] != 0) //Корінь дерева
      if (!is_null($attr_id) && $attr_id != $qPlace['attr_id'] )
        return Places::getParent($qPlace['parent_id'],$attr_id,$deep+1);
    }
    else return false;
    return $qPlace;
  }

}
