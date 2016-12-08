<?php

class AdminControllerBase extends ControllerBase
{

  public function canAct($sOper='',&$aNewItem=null,&$aOldItem=null) {
    if (parent::canAct($sOper,$aNewItem,$aOldItem)) {
      if (Hl::user('group_id') == 1)
        return true;
      else
        $this->flashSession->error(
          Hl::$t->_("Only for admin")
        );
    }
    echo "Only for admin";
    return false;
  }

  public function checkAccess() {
    if ($this->auth->isUserSignedIn()) {
      $sTable = $this->autoNames('curTable');
      $aTable = Hl::tables($sTable);
      if ($aTable)
      if (Hl::user('group_id') < $aTable['acc_level'])
        return true;
    }
    return false;
  }

  public function indexAction($id=null)
  {
    if ($this->checkAccess())
      parent::indexAction($id);
  }

}
