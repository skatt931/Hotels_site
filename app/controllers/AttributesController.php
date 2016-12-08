<?php
 


class AttributesController extends ControllerBase
{
  public function rangedAction($params=null)
  {
    $curModel = $this->autoNames('curModel');
    $curModel::fields();
    foreach ($params as $kp=>$vp) {
      if (isset($curModel::$fields[$kp])) {
        $sFilter = Hl::sC($sFilter,'['.$kp.'] = :'.$kp.':',' AND ');
        $aParams['bind'][$kp] = $vp;
        //$curModel::$fields[$kp]['hidden'] = 'hidden';
        //$curModel::$fields[$kp]['value'] = $vp;
      }
      $aParams['conditions'] = $sFilter;
      $this->persistent->parameters = $aParams;
    }
    if (isset($params['backLink']))
      $this->view->backLink = $params['backLink'];
    $this->indexAction();
  }

  public function createAction() {
    $this->view->sOperation = 'create';
    $curModel = $this->autoNames('curModel');
    $curModel::fields();
    foreach ($this->persistent->parameters['bind'] as $kp=>$vp) {
      $aItems[$kp] = $vp;
      $curModel::$fields[$kp]['readonly'] = 'readonly';
    }

    $this->editAction(null,$aItems);
  }
}
