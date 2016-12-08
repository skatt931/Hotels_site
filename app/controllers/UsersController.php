<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class UsersController extends ControllerBase
{
  public function checkAccess() {
    $bCan = parent::checkAccess();
    if ($bCan) {

    }
    return $bCan;
  }

  public function canAct($sOper='',&$aNewItem=null,&$aOldItem=null)
  {
    $curModel = $this->autoNames('curModel');
    $curModel::fields();
    if ($sOper == 'create') {
      if (Hl::user('group_id') > 1) {
        $curModel::$fields['parent_id']['inaccessible'] = 'inaccessible';
        $curModel::$fields['group_id']['inaccessible'] = 'inaccessible';
      }
    }
    elseif ($sOper == 'creatego' && !is_null($aNewItem)) {
      if (Hl::user('group_id') > 1) {
        $aNewItem->parent_id = Hl::user('id');
        $aNewItem->group_id = 1000;
      }
    }
    return true;
  }
}
