<?php

class Currencys extends PrfModel
{
  /**
   * @var integer
   */
  public $id;

  /**
   * @var string
   */
  public $name;

  /**
   * @var string
   */
  public $name_uk;

  /**
   * @var integer
   */
  public $parent_id;

  /**
   * @var integer
   */
  public $trash;

  static function all($reload=false) {
    $session = Hl::$di->get('session');
    if ($reload || !$session->has("Currencys")) {
      $defCur = $session->has("Currency");
      $qCurr = HT::sel('currencys');
      $defCur = ($defCur==0)?1:$defCur;
      $aCurrencys = [];
      foreach ($qCurr as $vc) {
        if ($vc['id'] == $defCur)
          $aCurrencys['cur'] = $vc;
        else
          $aCurrencys[$vc['id']] = $vc;
      }
      $session->set("Currencys", $aCurrencys);
      return $aCurrencys;
    }
    else
      return $session->get("Currencys");
    //$this->view->setVar("attr", $mAttrTypes);
  }
}
