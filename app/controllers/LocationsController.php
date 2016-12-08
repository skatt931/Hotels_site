<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class LocationsController extends ControllerBase
{
  public function singleAction($id=null,$item=null)
  {
    $curModel = $this->autoNames('curModel');
    $curModel::fields();
    $curModel::$fields['language']['inaccessible'] = 'inaccessible';
    $curModel::$fields['formatted_address']['inaccessible'] = 'inaccessible';
    $curModel::$fields['city']['inaccessible'] = 'inaccessible';
    $curModel::$fields['country']['inaccessible'] = 'inaccessible';
    $curModel::$fields['geo_point']['inaccessible'] = 'inaccessible';
    $curModel::$fields['created_at']['inaccessible'] = 'inaccessible';
    $curModel::$fields['updated_at']['inaccessible'] = 'inaccessible';
    $curModel::$fields['table_id']['hidden'] = 'hidden';
    $curModel::$fields['table_id']['value'] = $item['table_id'];
    $curModel::$fields['obj_id']['hidden'] = 'hidden';
    $curModel::$fields['obj_id']['value'] = $item['obj_id'];
    if ($id == 0)
      $this->view->sOperation = 'create';
    if (isset($item['backLink']))
      $this->view->backLink = $item['backLink'];
    $this->editAction($id,$item);
  }
}
