<?php


class DefaultController extends ControllerBase
{

  public function aboutAction() {
    $qFeeds = HT::sel('feedbacks','id=1');
    $this->view->text = $qFeeds[0]['note'.Hl::$pstfx];
    $qPict = HT::sel('pictures',['obj_id'=>1,'attr_id'=>10]);
    $this->view->picture = $qPict[0]['path'];
    $this->view->prompt = Attrtypes::all()[6570]['name'];
  }

  public function contactsAction() {
    $qFeeds = HT::sel('feedbacks','id=1');
    $this->view->text = $qFeeds[0]['note'.Hl::$pstfx];
    $qPict = HT::sel('pictures',['obj_id'=>1,'attr_id'=>10]);
    $this->view->picture = $qPict[0]['path'];
    $this->view->prompt = Attrtypes::all()[6570]['name'];
  }

  public function indexAction($id = NULL)
  {
    $blocks = HT::sel('blocks','page_name = "default" AND coalesce(hidden,0) = 0 AND coalesce(trash,0) = 0');
    $this->view->blocks = $blocks;
  }

  public function objectsAction() {
    if ($this->request->isAjax()) {
      $qAttrVal = HT::sel('attributes',['attr_id'=>11,'obj_id'=>$_POST['attr_id']]); //Отримуєм додаткові параметри
      $qAttrVal = $qAttrVal[0];
      $qTable = HT::sel('tables',['id'=>$qAttrVal['table_id']]);
      $aWhere = ['order'=>'name'.Hl::$pstfx];
      if (strlen($qAttrVal['val']) > 0)
        $aWhere['where'] = $qAttrVal['val'];
      $sResult = '';
      $qResult = HT::sel($qTable[0]['name'],$aWhere);
      foreach ($qResult as $r)
      $sResult .= '<option value="'.$r['id'].'">'.$r['name'.Hl::$pstfx].'</option>';
/*
   '<option value="4505">Топ вілл</option>
    <option value="4510">Топ готелів</option>
    <option value="4515">Топ санаторіїв</option>
    <option value="4540">Топ екскурсій</option>
    <option value="4550">Топ турів</option>
    <option value="6510">Популярні напрямки</option>';
*/
      $aResult['result'] =
        '<select id="'.$_POST['field'].'" name="obj_id" class="form-control col-md-7 col-xs-12">'.
        '<option value="">Choose...</option>'.
        $sResult.
        '</select>';
      $aResult['field'] = $_POST['field'];

      $this->response->setContent(json_encode($aResult));
      $this->response->send();
      return;
    }
  }


}
