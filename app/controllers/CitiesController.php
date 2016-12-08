<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class CitiesController extends AdminControllerBase
{
    public function viewAction($id=null) {
      parent::indexAction($id);
    }

    public function indexAction($id=null)
    {
      $qCities = HT::sel('toplists',
        ['where' => 'toplists.attr_id = 6510',
         'join' => ['places'=> 'toplists.obj_id = places.id']
        ]
      );
      foreach ($qCities as $s) {
        print_r($s);
        echo "<br>";
      }
    }

}
