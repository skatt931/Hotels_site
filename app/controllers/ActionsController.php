<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class ActionsController extends ControllerBase
{
  /**
   * Searches for actions
   */
  public function viewAction($id=null) {
    parent::indexAction($id);
  }

  public function indexAction($id=null)
  {
    parent::viewAction($id);
  }

  public function canAct($sOper='',&$aNewItem=null,&$aOldItem=null)
  {
    $curModel = $this->autoNames('curModel');
    $curModel::fields();
    if ($sOper == 'create')
      $curModel::$fields['picture_id']['inaccessible'] = 'inaccessible';
    elseif ($sOper == 'creatego') {
      if (Hl::user('group_id') > 1)
        $aNewItem['user_id'] = Hl::user('id');
    }
    elseif ($sOper == 'edit' && !is_null($aOldItem)) {
      $sFilter = 'obj_id='.$aOldItem['id'].' AND attr_id = 6590';
      $curModel::$fields['picture_id']['foreign_filter'] = $sFilter;
    }
    return true;
  }
}
