<?php

class AdminController extends AdminControllerBase
{

  public function indexAction($id=null)
  {
    if ($this->auth->isUserSignedIn()
    //  && Hl::user('group_id') < 10000
    ) {
      $id = str_replace('.html','',$id);
      $qTables = Hl::tables();
      $qTablesShow = [];
      $curGroup = Hl::user('group_id');
      foreach($qTables as $t)
        if ($curGroup < $t['acc_level'])
          $qTablesShow[] = $t;
      $this->view->aTables = $qTablesShow;
      $this->view->setLayoutsDir('admin/');
      $this->view->setLayout($id);
    }
    else {
      $this->dispatcher->forward(["controller" => "page500",
        'action'=>'index',
        'params'=>[$id]]);
      return false;
    }
  }

}
