<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class Page500Controller extends ControllerBase
{
  public function viewAction($id=null) {
    $this->indexAction();
  }

  public function indexAction($id=null)
  {
    if (!is_null($id))
      echo $id;
    echo ' page not accessible';
    $this->response->setStatusCode(500, "Not accessible");
    $this->response->send();
    return false;
  }
}
