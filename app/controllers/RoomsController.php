<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class RoomsController extends ControllerBase
{
  public function afterAct($sOper='',&$aNewItem=null,&$aOldItem=null)
  { //Дії після виконання
    if ($sOper == 'creatego') {
      $qCount = HT::sel('rooms', ['select' => 'count(*) cnt',
        'where' => ['coalesce(trash,0) = 0']]);
      HT::ins('counts',
        ['attr_id' => 7000,
          'place_id' => 1,
          'counts' => $qCount[0]['cnt'],
        ],
        true
      );
    }
  }

  public function canAct($sOper='',&$aNewItem=null,&$aOldItem=null) {
    if ($sOper == 'edit' || $sOper == 'create') {
      $curModel = $this->autoNames('curModel');
      $curModel::fields();
      if ($this->view->sOperation == 'create') {
        $curModel::$fields['picture_id']['inaccessible'] = 'inaccessible';
      }

      $curModel::$fields['hotel_id']['foreign_order'] = 'name'.Hl::$pstfx;
      if (Hl::user('group_id') > 1)
      if (strlen(Hl::user('user_can')) > 0) {//Фільтрація готелів до вибору
        $curModel::$fields['hotel_id']['foreign_filter'] =
          'user_id IN (' . Hl::user('user_can') . ')';
      }
      else
        $curModel::$fields['hotel_id']['foreign_filter'] = 'false';

      if ($this->view->sOperation == 'edit' && !is_null($aOldItem)) {
        $curModel::$fields['hotel_id']['disabled'] = 'disabled';
        unset($curModel::$fields['picture_id']['inaccessible']);
        unset($curModel::$fields['picture_id']['inaccessible']);
        $sFilter = 'obj_id='.$aOldItem['id'].' AND attr_id BETWEEN 7000 AND 7000';
        $curModel::$fields['picture_id']['foreign_filter'] = $sFilter;
        $aPictures = HT::sel('pictures', $sFilter);
        if (count($aPictures) < Hl::opt('rooms_pictures_max',2))
          $sPictures .= '<div> <input type="file" name="file2upload" '.
            'id="file2upload" accept="image/jpg, image/png"'.
            'onchange="uploadPicture(this,'.$aOldItem['id'].',7000)">'.
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
    }
    return parent::canAct($aOldItem);
  }

  public function priceAction($hotel_id=null,$dStart=null,$dEnd=null) {
    if ($this->request->isAjax())
      $this->view->disable();
    $aPrices = RoomsController::prices($hotel_id,$dStart,$dEnd);
    if ($this->request->isAjax())
      echo json_encode(['prices'=>$aPrices,'roomsCount'=>count($aPrices)]);
    else
      print_r($aPrices);
  }

  static function prices($hotel_id=null,$dStart=null,$dEnd=null) {
    $aWhere = ['attr_id'=>7000,'coalesce(trash,0)=0','obj_id in ('.(int)$hotel_id.')'];
    $sPeriod = '';
    if (!is_null($dStart))
      $sPeriod = $dStart .' BETWEEN date_f AND date_e ';
    if (!is_null($dEnd))
      $sPeriod = Hl::sC($sPeriod,$dEnd .' BETWEEN date_f AND date_e ',' OR ');
    if (!is_null($dStart) && !is_null($dEnd))
      $sPeriod = Hl::sC($sPeriod, '('.$dStart.'>= date_f AND date_e <='.$dEnd.')',' OR ');

    if ($sPeriod != '')
      $aWhere[] = '('.$sPeriod.')';

    $aPrices = Hl::q2a(
      HT::sel('prices',
        ['where' => $aWhere,
         //'quere'=>1
        ]));
    return $aPrices;
  }

}
