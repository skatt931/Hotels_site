<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class DescriptionsController extends ControllerBase
{
  public function canAct($sOper='',&$aNewItem=null,&$aOldItem=null)
  {
    $bResult = parent::canAct($sOper, $aNewItem, $aOldItem);
    if ($bResult) {
      $curModel = $this->autoNames('curModel');
      $curModel::fields();
      if ($this->view->sOperation == 'edit' && !is_null($aOldItem)) {
        if ($aOldItem['table_id'] == 4)
          $curModel::$fields['obj_id']['foreign'] = 'hotels';
        else
          $curModel::$fields['obj_id']['foreign'] = 'blog';
      }
      elseif ($sOper == 'creatego') {
        if (Hl::user('group_id') > 1)
          $aNewItem['user_id'] = Hl::user('id');
      }
    }
    return $bResult;
  }
}
