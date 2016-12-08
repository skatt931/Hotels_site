<?php

class SearchController extends ControllerBase
{

  protected function addError($sError,&$aErrors) {
    if (strlen($sError) > 2) $aErrors[] = $sError;
  }

  public function indexAction($id=null)
  {
    if ($this->request->isAjax()) {
      $aResult = [];
      $aErrors = [];

      $perPage = 9;
      $sLimit = 2*$perPage;
      if ($this->request->has('page')) {
        $nPage = (int)$this->request->get('page');
        if ($nPage == 0)
          $sLimit = 2*$perPage;
        else
          $sLimit = ($nPage+1)*$perPage.','.$perPage;
      }

      $aSelect =
        ['select' =>
          'hotels.id,hotels.name'.Hl::$pstfx.' name,'.
          'hotels.attr_id,hotels.place_id,hotels.stars,'.
          'pictures.path picture',
          //'order' =>'created DESC',
          'limit' => $sLimit,
          //'query' => 1,
        ];

      $oParams = json_decode($this->request->get('params'));
      if (is_object($oParams))
        $aParams = get_object_vars($oParams);
      else
        $aParams = [];
      if (isset($aParams['lang'])) { //Якщо прислали мову
        $lang = Langs::check($aParams['lang']);
        Langs::setVars($lang);
      }

      $aJoin = [];
      $aJoin['rooms'] = 'rooms.hotel_id=hotels.id';
      $aJoin['pictures'] = 'pictures.id=rooms.picture_id';
      $aWhere = ['coalesce(hotels.trash,0)=0'];

      $aSelect['order'] = 'price ASC';
      if (isset($aParams['sortingBy'])) {
        if ($aParams['sortingBy'] == 'from_toll')
          $aSelect['order'] = 'price DESC';
        elseif ($aParams['sortingBy'] == 'popular') {
          $aJoin['toplists'] = 'hotels.id = toplists.obj_id AND toplists.attr_id BETWEEN 4505 AND 4515';
          $aSelect['order'] = 'coalesce(toplists.sort,99999) ASC';
        }
      }

      if (isset($aParams['city'])) {
        $sError = 'City parameter igored';
        if (strlen($aParams['city']) > 2)
        {
          $qCity = HT::sel('places',
            ['select'=>'GROUP_CONCAT(id SEPARATOR ",") lst, '.
              'GROUP_CONCAT(name'.Hl::$pstfx.' SEPARATOR ",") names '
              ,
             'where'=>'name'.Hl::$pstfx.' LIKE :place',
             'bind'=>[':place' => '%'.$aParams['city'].'%'],
             //'query'=>1,
            ]);
          if (strlen($qCity[0]['lst']) > 0) { //Шукаєм місто
            $qCity = $qCity[0];
            $aWhere[] = 'hotels.place_id in ('.$qCity['lst'].')';
            $sError = '';
            $iCity = explode(',',$qCity['lst'])[0];

            if (is_array($this->session->search))
              $aSearch = $this->session->search;
            else
              $aSearch = [];
            $aSearch['city'] = $iCity;
            $this->session->search = $aSearch;

            $aResult['country'] = Places::getParent($iCity,1000)['name'.Hl::$pstfx];
            $aResult['cities'] = $qCity['names'];
          }
          else { //Шукаєм готель
            $qHotel = HT::sel('hotels',
              ['select'=>'GROUP_CONCAT(id SEPARATOR ",") lst, '.
                'min(place_id) place_id,'.
                'GROUP_CONCAT(name'.Hl::$pstfx.' SEPARATOR ",") names '
                ,
               'where'=>'name'.Hl::$pstfx.' LIKE :place',
               'bind'=>[':place' => '%'.$aParams['city'].'%'],
               //'query'=>1,
              ]);
            if (strlen($qHotel[0]['lst']) > 0) {
              $qHotel = $qHotel[0];
              $aWhere[] = 'hotels.id in (' . $qHotel['lst'] . ')';
              $sError = '';
              $iCity = $qHotel['place_id'];
              $aResult['country'] = Places::getParent($iCity, 1000)['name' . Hl::$pstfx];
              $qCity = HT::sel('places',['where'=>['id'=>$iCity],
                //'query'=>1,
              ]);
              $aResult['cities'] = $qCity[0]['name'.Hl::$pstfx];
            }
          }
        }
        $this->addError($sError,$aErrors);
      }


      $aStars = [];
      $aAttrs = Attrtypes::all();
      $aGrouped = []; //Згрупповані за батьківським номером відбірки

      $sPrice = Hl::aSafe($aParams,['budget','amount'],false);
      if ($sPrice) {
        $aPrice = explode('-',$sPrice);
        if (isset($aPrice[0]))
          $aWhere[] = 'rooms.price >= '.floatval($aPrice[0]);
        if (isset($aPrice[1]))
          $aWhere[] = 'rooms.price <= '.floatval($aPrice[1]);
        unset($aParams['budget']);
      }

      $iAdults = Hl::aSafe($aParams,['guests','dd_adults']);
      if ($iAdults > 0)
        $aWhere[] = '(rooms.adult = 0 OR rooms.adult >= '.floatval($iAdults).')';

      $iChilds = Hl::aSafe($aParams,['guests','dd_children']);
      if ($iChilds > 0)
        $aWhere[] = '(rooms.child = 0 OR rooms.child >= '.floatval($iChilds).')';

      if ($iAdults || $iChilds)
        unset($aParams['guests']);

      foreach ($aParams as $kp=>$vp) {
        if (is_object($vp))
          $aSubParams = get_object_vars($vp);
        else
          $aSubParams[$kp] = $vp;
        foreach ($aSubParams as $ksp=>$vsp) {
          if (substr($ksp,0,7) == 'option_') {//Витягуємо номер опції
            $optNumber = (int)substr($ksp,7);
            if ($vsp) {
              $parentIndex = $aAttrs[$optNumber]['parent_id'];
              if ($parentIndex == 4600)
                $aGrouped[$parentIndex][] = $optNumber;
              else
                $aWhere[] = 'hotels.id in (SELECT obj_id FROM '.HT::prf('attributes').
                  ' WHERE attr_id ='.$optNumber.' AND table_id="4")';

            }
          }
          if ((substr($ksp,0,4) == 'star') && $vsp) //Кількість зірок
            $aStars[] = (int)substr($ksp,4,1);
        }
      }

      foreach ($aGrouped as $kg=>$vg) //для группових значень
        $aWhere[] = 'hotels.attr_id in ('.implode(',',$vg).')';

      if (count($aStars) > 0)
        $aWhere[] = 'hotels.stars in ('.implode(',',$aStars).')';

      $dStart = date('Y-m-d',mktime(0, 0, 0, date("m")  , 1, date("Y")));
      $dEnd = date('Y-m-d',mktime(0, 0, 0, date("m")+1  , 30, date("Y")));

      $sOrder = HT::sel('orders',[
        'select' => ['count(*)'],
        'where' => [
          '(date_f BETWEEN "'.$dStart.'" AND "'.$dEnd.
          '" OR date_e BETWEEN "'.$dStart.'" AND "'.$dEnd.'")',
        'rooms.id = orders.room_id'],
        'query' => '1',
      ]);

      $aWhere[] = '(coalesce(rooms.cnt,0) = 0 OR coalesce(rooms.cnt,0) > '.
        '('.$sOrder['query'].'))';

//      if (count($aOptions) > 0) {
//        $aJoin['attributes'] = 'attributes.obj_id=hotels.id AND attributes.table_id="hotels"';
//        $aWhere[] = 'attributes.attr_id in ('.implode(',',$aOptions).')';
        //$aWhere['attributes.obj_id'] = ['>',0];
//      }

      $aSelect['select'] .= ',rooms.price,rooms.id room_id';
      $aWhere[] = 'not rooms.id is NULL';

      $aSelect['join'] = $aJoin;
      $aSelect['where'] = $aWhere;
//      $aSelect['query'] = 1;

      //if ()
      //$aSelect['query'] = 'query';
      $aSelect['SQL_CALC_FOUND_ROWS'] = 'SQL_CALC_FOUND_ROWS';
      $aSelect['after'] = 'REPLACE INTO '.HT::$prf.
        'session_vars (session,name,val) VALUES ("'.
        session_id().'","hotels",FOUND_ROWS())';
      $qHotels = HT::sel('hotels',$aSelect);
      $qTotal = HT::sel('session_vars',['session'=>session_id(),'name'=>'hotels']);
      $aResult['roomsCount'] = $qTotal[0]['val'];
      $qTotal = HT::sel('counts',
        ['attr_id' => 7000,
          'place_id' => 1,
        ]
      );
      $aResult['roomsTotal'] = $qTotal[0]['counts'];
      $aHotels = Hl::q2a($qHotels,'room_id');
      if ($aHotels)
      foreach ($aHotels as $kh=>$vh) {
        $aHotels[$kh]['url'] = '/hotels/rooms/'.$kh;
        $aHotels[$kh]['picture'] = Pictures::pictSizeN($aHotels[$kh]['picture'],255);
      }

      $aResult['hotels'] = $aHotels;

      $this->view->disable();
      if (count($aErrors) > 0)
        $aResult['errors'] = $aErrors;
      $this->response->setContent(json_encode($aResult));
      $this->response->send();
      return;
    }

    $this->view->aAttrs = Attrtypes::all();
    Phalcon\Tag::prependTitle(Hl::$t->_('Search').' - ');
  }

}
