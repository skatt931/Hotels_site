<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class Page404Controller extends ControllerBase
{
  public function viewAction($id=null) {
    $this->indexAction();
  }

  public function indexAction($id=null)
  {
    if (!is_null($id))
      echo $id;
    echo ' page not found';
    $this->response->setStatusCode(404, "Not Found");
    $this->response->send();
    return false;
  }
}
