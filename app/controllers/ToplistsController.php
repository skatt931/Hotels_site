<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class ToplistsController extends AdminControllerBase
{
  public function indexAction() {
      parent::viewAction();
  }

  public function viewAction($id=null) {
    parent::indexAction($id);
  }

}
