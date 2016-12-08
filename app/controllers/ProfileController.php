<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class ProfileController extends ControllerBase
{
  public function canAct($sOper='',&$aNewItem=null,&$aOldItem=null) {
    if (parent::canAct($sOper,$aNewItem,$aOldItem)) {
      if ($sOper == 'create') {
        $curModel = $this->autoNames('curModel');
        $curModel::fields();
        $curModel::$fields['picture_id']['inaccessible'] = 'inaccessible';
      }
      elseif ($sOper == 'edit' && !is_null($aOldItem)) {
        $sModel = $this->autoNames('curModel');
        $aFields = $sModel::fields();
        //Додаткові поля для редагування
        $qUser = HT::sel('users',['select' => 'email','where' => ['id'=>Hl::user('id')]]);
        $aFields['email'] = ['label' => Hl::$t->_('E-mail'),'disabled' => 'disabled'];
        $aOldItem['email'] = $qUser[0]['email'];

        $aFields['Role'] = ['label' => Hl::$t->_('Role'),'value' => 'admin','disabled' => 'disabled'];
        $qRole = HT::sel('user_groups',['where' => ['id'=>Hl::user('group_id')]]);
        $aOldItem['Role'] = $qRole[0]['name'.Hl::$pstfx];
//pictures
        unset($aFields['picture_id']['inaccessible']);
        $sFilter = 'obj_id='.$aOldItem['id'].' AND attr_id = 3500';
        $aFields['picture_id']['foreign_filter'] = $sFilter;
        $aPictures = HT::sel('pictures', $sFilter);
        if (count($aPictures) < Hl::opt('user_pictures_max',3))
          $sPictures .= '<div> <input type="file" name="file2upload" id="file2upload" accept="image/jpg, image/png"'.
            'onchange="uploadPicture(this,'.$aOldItem['id'].',3500)">'.
            //'<a onclick="uploadPicture(this,'.$aOldItem['id'].',4600)">'.
            //Hl::$t->_('Upload').
            '</div>';
        else
          $sPictures = '';
        if (count($aPictures) > 0) {
          $sPictures .= '<div>';
          foreach ($aPictures as $p)
            $sPictures .= '<img width=200px height=200px src="'.$p['path'].'" title="'.$p['path'].'" alt="'.$p['id'].'">';
          $sPictures .= '</div>';
        }
        $aFields['picture_id']['afterControl'] = $sPictures;
//
        $this->view->aFields = $aFields;
      }
      elseif ($sOper == 'editgo') {
        if (Hl::user('group_id') > 1)
        if ($aOldItem->user_id != Hl::user('id'))
          return false;
      }
      return true;
    }
    return false;
  }
}
