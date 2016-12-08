<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class FeedbacksController extends ControllerBase
{
  public function rangedAction($params=null)
  {
    $curModel = $this->autoNames('curModel');
    $curModel::fields();
    foreach ($params as $kp=>$vp) {
      if (isset($curModel::$fields[$kp])) {
        if (strpos($vp,'BETWEEN') !== false)
          $sFilter = Hl::sC($sFilter,$kp.' '.$vp,' AND ');
        else {
          $sFilter = Hl::sC($sFilter,'['.$kp.'] = :'.$kp.':',' AND ');
          $aParams['bind'][$kp] = $vp;
        }
      }
      $aParams['conditions'] = $sFilter;
      $this->persistent->parameters = $aParams;
    }
    if (isset($params['backLink']))
      $this->view->backLink = $params['backLink'];
    $this->indexAction();
  }

  public function canAct($sOper='',&$aNewItem=null,&$aOldItem=null)
  {
    $curModel = $this->autoNames('curModel');
    $curModel::fields();
    if (Hl::user('group_id') > 1) {
      $curModel::$fields['obj_id']['foreign'] = 'hotels';
      if (strlen(Hl::user('user_can')) > 0)
        $curModel::$fields['obj_id']['foreign_filter'] = 'user_id in ('.Hl::user('user_can').')';
      else
        $curModel::$fields['obj_id']['foreign_filter'] = 'false';
      $curModel::$fields['attr_id']['foreign_filter'] = 'id BETWEEN 6900 AND 6999';
      $curModel::$fields['user_id']['inaccessible'] = 'inaccessible';
    }
    else
      $curModel::$fields['user_id']['foreign_order'] = 'name';
      $curModel::$fields['user_id']['foreign_show'] = 'name,email';

    if ($sOper == 'creatego' && !is_null($aNewItem)) {
      if (Hl::user('group_id') > 1)
        $aNewItem->user_id = Hl::user('id');
    }
    return true;
  }
}
