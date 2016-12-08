<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class PicturesController extends ControllerBase
{

  public function unlinkAction() {
    $sResult = 'error';
    $sMessage = Hl::$t->_("Nothing happened");
    if ($this->request->isAjax())
      $this->view->disable();
    $id = $this->request->get('id');
    $qPicture = HT::sel('pictures',['id'=>$id]);
    if (count($qPicture) > 0) {
      $qPicture = $qPicture[0];
      if ($this->checkUser($qPicture['user_id'])) {
        HT::del('pictures',['id'=>$id]);
        $sPath = __DIR__.'/../..'.$qPicture['path'];
        unlink($sPath);
        $sMessage = Hl::$t->_("Picture %path% successfully removed",
          ['path'=>$qPicture['path']]);
        $sResult = 'success';

        $baseName = basename($qPicture['path']);
        $sPath = str_replace($baseName,
          str_replace('.','*.',$baseName),
          $sPath);
        foreach (glob($sPath) as $filename) {
          unlink($filename);
        }
      }
      else
        $sMessage = Hl::$t->_("Not enough rights");
    }
    else
      $sMessage = Hl::$t->_("Record %id% of %table% was not found",
        ['id'=>$id,'table'=>'pictures' ]);
    $sRes = 'json_result='.json_encode(['result'=>$sResult,'message'=>$sMessage,'action'=>'refresh']);
    echo $sRes;

  }

  public function canAct($sOper='',&$aNewItem=null,&$aOldItem=null) {
    if ($res = parent::canAct($sOper,$aNewItem,$aOldItem)) {
      if ($sOper == 'delete')
        unlink(__DIR__.'/../../'.trim($aOldItem->path,'/'));
    }
    return $res;
  }

  public function makeAction($id,$sMode='fromForm') {
    $this->view->disable();
    $qPicture = HT::sel('pictures',['id'=>$id]);
    $aSizes = Pictures::getSizesByAttr($qPicture[0]['attr_id']);
    $sResult = Pictures::make($qPicture[0]['path'],$aSizes,false);
    $sRes = 'json_result='.json_encode(['result'=>'success','message'=>$sResult,'action'=>'back']);
    if ($sMode == 'fromForm')
      echo $sRes;
    else
      return $sResult;
  }

  public function indexAction($id=null) {
    $this->view->aButtons = ['view','edit','delete',
      'make'=>['Make','btn btn-success btn-xs','fa fa-folder',],];
    parent::indexAction($id);
  }

  public function uploadAction() {
    $obj_id=$_POST['obj_id'];
    $attr_id=$_POST['attr_id'];
    $picture=$_POST['picture'];

    $qCount = HT::sel('pictures',['select' => 'count(*) cnt',
      'where' => ['obj_id' => $obj_id, 'attr_id' => $attr_id] ]);
    if ($qCount[0]['cnt'] >= Hl::opt('hotels_pictures_max')) {
      echo 'limit exceeded';
      return false;
    }

    if (Hl::user()['id'] > 0) {
      $part_path = '/public/src/images/'.date("Y").'/'.date("m");
      $server_path = __DIR__.'/../..';
      $new_path = $server_path.$part_path;
      if (!is_dir($new_path))
        mkdir($new_path, 0777,true);
      $sPath = $part_path.'/'.Pictures::randName().'.jpg';
      $aPicture = explode(',',$picture);
      file_put_contents($server_path.$sPath,base64_decode($aPicture[1]));
      $sNewPath = Pictures::make($sPath,
        Pictures::getSizesByAttr($attr_id),true);
      $aNames = explode(';',$sNewPath);
      HT::ins('pictures',['path' => $aNames[0],'obj_id' => $obj_id,
        'attr_id' => $attr_id,'user_id' => Hl::$user['id']]);
      $sRes = 'json_result='.json_encode(['result'=>'success','message'=>$sNewPath,'action'=>'refresh']);
      echo $sRes;
    }
    else
      echo 'log in';
  }

}
