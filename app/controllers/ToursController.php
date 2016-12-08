<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class ToursController extends ControllerBase
{
  public function viewAction($id=null) {
    parent::indexAction($id);
  }

  public function indexAction($id=null)
  {
    parent::viewAction($id);
  }

}
