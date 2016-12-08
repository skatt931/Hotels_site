<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class HotelsController extends ControllerBase
{

  public function afterAct($sOper='',&$aNewItem=null,&$aOldItem=null) { //Дії після виконання
    if ($sOper == 'creatego') {
      if (is_object($aNewItem))
        $aItem = get_object_vars($aNewItem);
      else
        $aItem = $aNewItem;
      $qCount = HT::sel('hotels',['select'=>'count(*) cnt',
        'where'=>['attr_id'=>$aItem['attr_id'],'place_id'=>$aItem['place_id'] ]
        ]);
      HT::ins('counts',
        ['attr_id'=>$aItem['attr_id']+2010,
         'place_id'=>$aItem['place_id'],
         'counts'=>$qCount[0]['cnt'],
        ],
        true
      );
    }
    return true;
  }
  
  public function canAct($sOper='',&$aNewItem=null,&$aOldItem=null) {
    $curModel = $this->autoNames('curModel');
    $curModel::fields();
    if ($sOper == 'create') {
      if (Hl::user('group_id') > 1)
        $curModel::$fields['user_id']['inaccessible'] = 'inaccessible';
      else {
        $curModel::$fields['user_id']['foreign_filter'] = 'group_id<5000';
        $curModel::$fields['user_id']['foreign_order'] = 'name';
        $curModel::$fields['user_id']['foreign_show'] = 'name,email';
      }

      $curModel::$fields['picture_id']['inaccessible'] = 'inaccessible';
    }
    elseif ($sOper == 'creatego') {
      if (Hl::user('group_id') > 1)
        $aNewItem['user_id'] = Hl::user('id');
    }
    elseif ($sOper == 'edit' && !is_null($aOldItem)) {
      if (Hl::user('group_id') > 1)
        $curModel::$fields['user_id']['inaccessible'] = 'inaccessible';
      unset($curModel::$fields['picture_id']['inaccessible']);
      $sFilter = 'obj_id='.$aOldItem['id'].' AND attr_id BETWEEN 4600 AND 4999';
      $curModel::$fields['picture_id']['foreign_filter'] = $sFilter;
      $aPictures = HT::sel('pictures', $sFilter);
      if (count($aPictures) < Hl::opt('hotels_pictures_max',16))
        $sPictures .= '<div> <input type="file" name="file2upload" '.
          'id="file2upload" accept="image/jpg, image/png"'.
          'onchange="uploadPicture(this,'.$aOldItem['id'].','.
          $aOldItem['attr_id'].')">'.
      //'<a onclick="uploadPicture(this,'.$aOldItem['id'].',4600)">'.
        //Hl::$t->_('Upload').
          '</div>';
      else
        $sPictures = '';
      if (count($aPictures) > 0) {
        $sPictures .= '<div>';
        foreach ($aPictures as $p)
          $sPictures .= '<img width=100px height=100px src="'.$p['path'].'" '.
            'title="'.$p['path'].'" alt="'.$p['id'].'" '.
            'onclick="deletePicture('.$p['id'].')">';
        $sPictures .= '</div>';
      }
      $curModel::$fields['picture_id']['afterControl'] = $sPictures;
    }
    elseif ($sOper == 'editgo' && !is_null($aOldItem)) {
      if (!$this->checkUser($aOldItem->user_id))
        return false;
    }

    return parent::canAct($sOper,$aNewItem,$aOldItem);
  }

  public function locationAction($id=null)
  {
    if ($id > 0) {
      $qHotel = HT::sel('hotels',['id' => $id]);
      if (count($qHotel) > 0) {
        $qLocation = HT::sel('locations',['table_id' => 4,'obj_id' => $id]);
        if (count($qLocation) > 0) {
          $locationId = $qLocation[0]['id'];
          $qItem = $qLocation[0];
        }
        else {
          $locationId = null;
          $qItem = ['table_id' => 4,'obj_id' => $id];
        }

        $aTable = Hl::tables('hotels');
        if (strlen($aTable['adm_link'])>0)
          $qItem['backLink'] = $aTable['adm_link'];
        else
          $qItem['backLink'] = 'hotels';

        $this->dispatcher->forward(array(
          "controller" => "locations",
          "action" => "single",
          "params" => ["id" => $locationId,"item" => $qItem,]
        ));
        return false;
      }
    }
  }

  public function descriptionsAction($id=null)
  {
    if ($id > 0) {
      $qHotel = HT::sel('hotels',['id' => $id]);
      if (count($qHotel) > 0) {
        $qItems = ['table_id' => 4,'obj_id' => $id];

        $aTable = Hl::tables('hotels');
        if (strlen($aTable['adm_link'])>0)
          $qItems['backLink'] = $aTable['adm_link'];
        else
          $qItems['backLink'] = 'hotels';

        $this->dispatcher->forward(array(
          "controller" => "descriptions",
          "action" => "ranged",
          "params" => ["params" => $qItems,]
        ));
        return false;
      }
    }
  }

  public function attributesAction($id=null)
  {
    if ($id > 0) {
      $qHotel = HT::sel('hotels',['id' => $id]);
      if (count($qHotel) > 0) {
        $qItems = ['table_id' => 4,'obj_id' => $id];

        $aTable = Hl::tables('hotels');
        if (strlen($aTable['adm_link'])>0)
          $qItems['backLink'] = $aTable['adm_link'];
        else
          $qItems['backLink'] = 'hotels';

        $this->dispatcher->forward(array(
          "controller" => "attributes",
          "action" => "ranged",
          "params" => ["params" => $qItems,]
        ));
        return false;
      }
    }
  }

  public function notesAction($id=null)
  {
    if ($id > 0) {
      $qItems = ['obj_id' => $id,'attr_id' => ' BETWEEN 6900 AND 6999'];
      $this->dispatcher->forward(array(
        "controller" => "feedbacks",
        "action" => "ranged",
        "params" => ["params" => $qItems,]
      ));
      return false;
    }
  }

  public function indexAction($id=null)
  {
    if ($id > 0) {
      $this->dispatcher->forward(array(
        "controller" => "hotels",
        "action" => "view",
        "id" => $id,
      ));
    }
    else
      parent::viewAction();
  }

  public function calendarAction($id=null)
  {
    $this->view->disable();
    if ($id > 0) {
      if ($_POST['firstMonth'] == 0 || $_POST['firstYear'] == 0)
        $dStart = date('Y-m-d',mktime(0, 0, 0, date("m")  , 1, date("Y")));
      else
        $dStart = date('Y-m-d',mktime(0, 0, 0, $_POST['firstMonth'], 1, $_POST['firstYear']));
      if ($_POST['lastMonth'] == 0 || $_POST['lastYear'] == 0)
        $dEnd = date('Y-m-d',mktime(0, 0, 0, date("m")+1  , 31, date("Y")));
      else
        $dEnd = date('Y-m-d',mktime(0, 0, 0, $_POST['lastMonth']  , 31, $_POST['lastYear']));

      $aPrices = Hl::q2a(
        HT::sel('prices',[
          'select' => 'prices.*',
          'join' => ['rooms'=>'prices.obj_id = rooms.id AND prices.attr_id = 7000'],
          'where' => ['coalesce(prices.trash,0)=0','rooms.hotel_id' => $id,
            '(prices.date_f BETWEEN "'.$dStart.'" AND "'.$dEnd.'"'.
            ' OR prices.date_e BETWEEN "'.$dStart.'" AND "'.$dEnd.'"'.
            ' OR (prices.date_f < "'.$dStart.'" AND prices.date_e > "'.$dEnd.'")'.
            ')'],
          'order' => 'date_f DESC',
          //'limit' => '10',
        ]),
        'id');
      if ($aPrices)
      foreach($aPrices as $kp=>$vp) {
        $aPrices[$kp]['date_f'] = Hl::df($aPrices[$kp]['date_f']);
        $aPrices[$kp]['date_e'] = Hl::df($aPrices[$kp]['date_e']);
      }

      $aActions = Hl::q2a(
        HT::sel('actions',[
          'select' => 'date_f,date_e,id,name'.Hl::$pstfx.' name',
          'where' => ['coalesce(actions.trash,0)=0','actions.obj_id' => $id,
            'actions.table_id' => $id,
            '(actions.date_f BETWEEN "'.$dStart.'" AND "'.$dEnd.'"'.
            ' OR actions.date_e BETWEEN "'.$dStart.'" AND "'.$dEnd.'"'.
            ' OR (actions.date_f < "'.$dStart.'" AND actions.date_e > "'.$dEnd.'")'.
            ')'],
          'order' => 'date_f DESC',
          //'limit' => '10',
        ]),
        'id');
      if ($aActions)
      foreach($aActions as $kp=>$vp) {
        $aActions[$kp]['date_f'] = Hl::df($aActions[$kp]['date_f']);
        $aActions[$kp]['date_e'] = Hl::df($aActions[$kp]['date_e']);
      }
      $aResult = ['prices' => $aPrices, 'actions' => $aActions];
      echo json_encode($aResult);
    }
    else
      echo json_encode(['result'=>'error','data'=>'wrong hotel number'.(string)$id]);
  }

  public function roomsAction($id=null)
  {
    $this->view->setLayoutsDir('hotels/');
    $this->view->setLayout('view');
    $this->view->selectedRoom = $id;
    $this->viewAction(null,$id);
  }

  public function showAction($id=null)
  {
    $this->view->aButtons = ['descriptions','notes','attributes','location','edit','delete'];
    parent::indexAction($id);
  }

  public function bookAction()
    {
      if ($this->request->isPost()) {
        $this->view->disable();
        if ($_POST['action'] == 'book') {
          $aValues = ['name'=>$_POST['name'],
            'phone'=>$_POST['phone'],
            'email'=>$_POST['inputEmail3'],
          ];
          if (isset($_POST['checkIn'])) {
            $aDate = date_parse($_POST['checkIn']);
            $aValues['date_f'] = $aDate['year'].'-'.$aDate['month'].'-'.$aDate['day'];
          }
          if (isset($_POST['checkOut'])) {
            $aDate = date_parse($_POST['checkOut']);
            $aValues['date_e'] = $aDate['year'].'-'.$aDate['month'].'-'.$aDate['day'];
          }
          if (isset($_POST['fullinfo'])) {
            $aValues['note'] = $_POST['fullinfo'];
          }

          HT::ins('orders',$aValues);
          echo 'success';
          return;
        }
      }
      echo 'fail';
      return;
    }


    /**
     * View a hotel
     *
     * @param string $id
     */
  public function viewAction($id=null,$room_id=null)
  {
    Hl::$aScriptList[] = '/public/src/js/hotels.js';
    if ( (!is_null($room_id)) || !$this->request->isPost()) {

      if (!is_null($room_id)) {
        $qRoom = HT::sel('rooms',['id' => $room_id]);
        if (count($qRoom) > 0) {
          $id = $qRoom[0]['hotel_id'];
          Phalcon\Tag::prependTitle($qRoom[0]['name'.Hl::$pstfx].' - ');
        }
      }

      $hotel = HT::sel('hotels',
        ['where'=>['coalesce(trash,0)=0','id'=>$id],
        ]);
      if (count($hotel) == 0) {
        $this->dispatcher->forward(["controller" => "page404",
          'action'=>'index',
          'params'=>[$id]]);
        return false;
      }

      $qMetaTags = HT::sel('descriptions',
        ['select' => 'note'.Hl::$pstfx.' note',
         'where' => ['obj_id'=>$id,'attr_id'=>12,'table_id'=>4],
        ]
      );

      if ($qMetaTags)
        Hl::$aMetaTags['description'] = $qMetaTags[0]['note'];

      $this->view->aPictures =
        HT::sel('pictures',['obj_id'=>$id,'attr_id BETWEEN 4600 AND 4999']);
      $this->view->aRooms = Hl::q2a(
        HT::sel('rooms',
          ['select' => 'rooms.*,path picture',
           'where' =>['rooms.hotel_id'=>$id],
           'join' => ['pictures'=>'pictures.id = rooms.picture_id AND pictures.attr_id = 7000'],
          ]),
        'id');
      $this->view->aAttributes = Hl::q2a(
        HT::sel('attributes',
          ['where' => ['obj_id'=>$id,'(attr_id in (6810) OR parent_id in (6000,8000,9000))'],
           'join' => ['attrtypes'=>'attrtypes.id = attributes.attr_id'],
          ]
        ),
        'attr_id');
      $this->view->aDescriptions = Hl::q2a(
        HT::sel('feedbacks',[
          'where' => ['obj_id'=>$id,'(attr_id in (4600,6900) OR parent_id in (6900))'],
          'join' => ['attrtypes'=>'attrtypes.id = feedbacks.attr_id'],
        ]),
        'attr_id');

      $this->view->aBlogs = Hl::q2a(
        HT::sel('blog',[
          'select' => ['blog.*,pictures.path picture'],
          'where' => ['coalesce(trash,0)=0'],
          'join' => ['pictures'=>'pictures.id = blog.picture_id'],
          'order' => 'created DESC',
          'limit' => '5',
        ]),
        'id');

      $this->view->aSame = Hotels::getSame();
      $dStart = date('Y-m-d',mktime(0, 0, 0, date("m")  , 1, date("Y")));
      $dEnd = date('Y-m-d',mktime(0, 0, 0, date("m")+1  , 30, date("Y")));

      $this->view->aBooking = Hl::q2a(
        HT::sel('orders',[
          'select' => ['@rownum:=coalesce(@rownum,1)+1 id,'.
            'orders.date_f,orders.date_e,orders.room_id'],
          'join' => ['rooms'=>'orders.room_id = rooms.id'],
          'where' => ['coalesce(trash,0)=0','rooms.hotel_id' => $id,
          '(date_f BETWEEN "'.$dStart.'" AND "'.$dEnd.
          '" OR date_e BETWEEN "'.$dStart.'" AND "'.$dEnd.'")'],
          'order' => 'created DESC',
          'limit' => '10',
        ]),
        'id');

      $this->view->aLocation = HT::sel('locations',['table_id' => 4,'obj_id' => $id])[0];

      $this->view->aPrices = RoomsController::prices($id);
      $this->view->aAttrs = Attrtypes::all();
      $this->view->hotel = $hotel[0];
      Phalcon\Tag::prependTitle($hotel[0]['name'.Hl::$pstfx].' - ');
    }
  }

}

