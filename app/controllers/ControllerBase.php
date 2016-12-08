<?php

use Phalcon\Mvc\View;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class ControllerBase extends Phalcon\Mvc\Controller
{
  public function onConstruct()
  {
    if ($this->request->isAjax()) //Якщо це аджаксовий зпит - не використовувати відмальовку сторінки, а саме не додавати заголовок
      $this->view->disableLevel([View::LEVEL_MAIN_LAYOUT => true]);
  }

  public function rangedAction($params=null)
  {
    $curModel = $this->autoNames('curModel');
    $curModel::fields();
    foreach ($params as $kp=>$vp) {
      if (isset($curModel::$fields[$kp])) {
        $sFilter = Hl::sC($sFilter,'['.$kp.'] = :'.$kp.':',' AND ');
        $aParams['bind'][$kp] = $vp;
      }
      $aParams['conditions'] = $sFilter;
      $this->persistent->parameters = $aParams;
    }
    if (isset($params['backLink']))
      $this->view->backLink = $params['backLink'];
    $this->indexAction();
  }

  public function goPage($action=null,$id=null) {
    $sTable = $this->autoNames('curTable');
    $aTable = Hl::tables($sTable);
    if (strlen($aTable['adm_link'])>0) {
      $aLink = explode('/',$aTable['adm_link']);
      if (is_null($action))
        $action = $aLink[1];
      $aGo = array(
        "controller" => $aLink[0],
        "action" => $action,
      );
    }
    else {
      if (is_null($action))
        $action = 'index';
      $aGo = array(
        "controller" => $sTable,
        "action" => $action
      );
    }
    if (!is_null($id))
      $aGo['id'] = $id;

    $this->dispatcher->forward($aGo);
  }

  public function searchAction() {
    $this->view->sOperation = 'search';
    $aConditions = $this->persistent->parameters;
//Hl::l($aConditions['bind']);

    if (!$this->canAct($this->view->sOperation)) {
      $this->flashSession->error(
        Hl::$t->_("Not enough rights to %act% record",
          ['act'=>Hl::$t->_($this->view->sOperation)])
      );
      $this->goPage();
      return;
    }

    if ($aConditions)
      $this->view->aItem = $aConditions['bind'];

    $this->editAction();
  }

  public function searchgoAction() {
    if ($this->request->isPost()) {
      $query = Criteria::fromInput($this->di, $this->autoNames('curModel'),
        $this->clearPost($_POST));
      $this->persistent->parameters = $query->getParams();
      $this->goPage();
    }
  }


  public function createAction() {
    $this->view->sOperation = 'create';
    $this->editAction();
  }

  public function creategoAction()
  {

    if (!$this->request->isPost()) {
      $this->goPage();
      return;
    }

    $curModel = $this->autoNames('curModel');
    $editedModel = new $curModel;

    $aPost = $this->request->getPost();
    $editedModel->setByPost($aPost);

    $sOperation = 'creatego';
    if (!$this->canAct($sOperation,$editedModel)) {
      $this->flashSession->error(
        Hl::$t->_("Not enough rights to %act% record",
          ['act'=>Hl::$t->_($sOperation)])
      );
      $this->goPage();
      return;
    }

    if (!$editedModel->save()) {
      foreach ($editedModel->getMessages() as $message) {
        $this->flashSession->error($message);
      }
      $this->view->sOperation = 'create';
      $this->editAction(null,$this->request->getPost());
      return;
    }

    $aOldItem = null;
    $this->afterAct('creatego',$editedModel,$aOldItem);

    $recordName = 'new record';
    if (isset($aPost['name']))
      $recordName = $aPost['name'];
    $this->flashSession->error($curModel.' '. $recordName.' '.Hl::$t->_("successfull saved"));
    $this->goPage();
  }

  public function checkUser($user_id) {//Перевірка чи може користувач опрацьовувати чужий запис
    if (Hl::user('group_id') > 1) //Не для адміна
    if (strpos(','.Hl::user('user_can').',',','.$user_id.',') === false) {
      $this->flashSession->error(
        Hl::$t->_("You cannot act alien record")
      );
      return false;
    }
    return true;
  }

  public function canAct($sOper='',&$aNewItem=null,&$aOldItem=null) { //Чи можливо виконувати дію
    if ($user_id = Hl::aSafe($aOldItem,'user_id',false)) {//Якщо прописаний вхідний запис
      return $this->checkUser($user_id); //Повернути перевірку прав
    }
    return true;
  }

  public function afterAct($sOper='',&$aNewItem=null,&$aOldItem=null) { //Дії після виконання
    return true;
  }

  public function editAction($id=null,$qItem=null) {
    if (!isset($this->view->sOperation))
      $this->view->sOperation = 'edit';
    if (!$this->canAct($this->view->sOperation))  //Перевірка на можливість редагування
      return false;
    $sTable = $this->autoNames('curTable');
    if (is_null($qItem)) {
      if (!is_null($id)) {
        $qItem = HT::sel($sTable, ['id' => $id]);
        if (count($qItem) > 0) {
          $qItem = $qItem[0];
          $aFake = null;
          if ($this->canAct($this->view->sOperation,$aFake,$qItem))
            $this->view->aItem = $qItem;
          else {
            $this->flashSession->notice(Hl::$t->_("You can not edit this record"));
            $this->view->bError = true;
          }
        } else {
          $this->flashSession->notice(Hl::$t->_("Record not found") .
            ' '.$sTable.
            ' (' . $id . ')');
          $this->view->bError = true;
        }
      }
    }
    else
      $this->view->aItem = $qItem;
    $this->view->aTable = Hl::tables($sTable);
    $this->view->disableLevel([View::LEVEL_ACTION_VIEW => true,View::LEVEL_MAIN_LAYOUT => true,]);
    $this->view->setLayoutsDir('admin/');
    $this->view->setLayout('admin_edit');
  }

  public function clearPost($aPost) { //Видалити з посту усі пусті значення
    foreach ($aPost as $kp=>$vp) {
      if (strlen($vp) == 0)
        unset($aPost[$kp]);
    }
    return $aPost;

  }

  public function editgoAction()
  {

    if (!$this->request->isPost()) {
      $this->goPage();
      return;
    }

    $id = $this->request->getPost("id");
    $curModel = $this->autoNames('curModel');
    $editedModel = $curModel::findFirstByid($id);
    if (!$editedModel) {
      $this->flashSession->error($curModel . ' '.Hl::$t->_("does not exist") . ' '. $id);
      $this->goPage();
      return;
    }

    $aPost = $this->clearPost($this->request->getPost());


    $sOperation = 'editgo';
    if (!$this->canAct($sOperation,$aPost,$editedModel)) { //Перевірка на можливість запису даних
      $this->flashSession->error(
        Hl::$t->_("Not enough rights to %act% record",
          ['act'=>Hl::$t->_($sOperation)])
      );
      $this->goPage();
      return;
    }

    $editedModel->setByPost($aPost);

    if (!$editedModel->save()) {
      foreach ($editedModel->getMessages() as $message) {
        $this->flashSession->error($message);
      }
      $this->editAction($id,$this->request->getPost());
      return;
    }
    $recordName = $id;
    if (isset($aPost['name']))
      $recordName = $aPost['name'];
    $this->flashSession->error($curModel.' '. $recordName.' '.Hl::$t->_("successfull saved"));
    if (isset($aPost['tags'])) {
      $aTable = Hl::tables($this->autoNames('curTable'));
      Tags::saveTags($aPost['tags'],$id,
        isset($aPost['attr_id'])
          ?$aPost['attr_id']:$aTable['options']['attr_id']);
      
    }
    $this->goPage();
  }

  public function autoNames($ind=null) //Отримати назву моделі та назву таблиці(без префіксу) біжучого контроллера
  {
    $curModel = str_replace('Controller','',get_called_class());
    $aRes = ['curTable' => strtolower($curModel),'curModel' => $curModel];
    if (is_null($ind))
      return $aRes;
    else
      return $aRes[$ind];
  }

  public function viewAction($id=null) //Стандартний показ даних з таблиці
  {
    if ($id != 0) {
      $aNames = $this->autoNames();
      $qElement = HT::sel($aNames['curTable'],'id='.(int)$id);
      $this->view->aItem = $qElement[0];
      $this->view->disableLevel([View::LEVEL_ACTION_VIEW => true,]);
      $this->view->setLayoutsDir('common/');
      $this->view->setLayout('element');
    }
    else {
      $numberPage = 1;
      $aNames = $this->autoNames();
      $curModel = $aNames['curModel'];
      if ($this->request->isPost()) {
        $query = Criteria::fromInput($this->di, $curModel, $_POST);
        //$this->persistent->parameters = $query->getParams();
      } else {
        $numberPage = $this->request->getQuery("page", "int");
      }

      $parameters = $this->persistent->parameters;
      if (!is_array($parameters)) {
        $parameters = array();
      }
      $parameters["order"] = "id";

      $curTable = $aNames['curTable'];
      $mResults = $curModel::find($parameters);

      if (count($mResults) == 0) {
        $this->flashSession->notice(Hl::$t->_("The search did not find any"). ' ' . $curTable);

        /*
          $this->dispatcher->forward(array(
          "controller" => $curTable,
          "action" => "search"
        ));

        return;
        */
      }

      $paginator = new Paginator(array(
        'data' => $mResults,
        'limit' => Hl::opt('per_page'),
        'page' => $numberPage
      ));

      $this->view->aTable = Hl::tables($curTable);
      $this->view->page = $paginator->getPaginate();
      $this->view->disableLevel([View::LEVEL_ACTION_VIEW => true,]);
      $this->view->setLayoutsDir('common/');
      $this->view->setLayout('grid');
    }
  }

  public function indexAction($id=null) //Грід для адмінки
  {
    if (!$this->canAct('view'))
      return false;

    $numberPage = 1;
    $aNames = $this->autoNames();
    $curModel = $aNames['curModel'];
    if ($this->request->isPost()) {
        $query = Criteria::fromInput($this->di, $curModel, $_POST);
        $this->persistent->parameters = $query->getParams();
    } else {
        $numberPage = $this->request->getQuery("page", "int");
    }

    $parameters = $this->persistent->parameters;
    if (!is_array($parameters)) {
        $parameters = array();
    }
    $parameters["order"] = "id";

    $curTable = $aNames['curTable'];
    $mResults = $curModel::find($parameters);
    if (count($mResults) == 0) {
        $this->flashSession->notice(Hl::$t->_("The search did not find any".' '.$curTable));

        /*
          $this->dispatcher->forward(array(
          "controller" => $curTable,
          "action" => "search"
        ));

        return;
        */
    }

    $aFields = $curModel::fields();
    $aForeign = [];
    $aResults = $mResults->toArray();
    if(count($aResults) > 0)
    foreach ($aFields as $kf => $vf) { //Витягаєм усі зв'язани дані
      if (isset($vf['foreign']))
      if ($vf['foreign'] != 'attrtypes') { //Крім атрибутів, які є в пам'яті
        $aVals = implode(',',Hl::array_column($aResults,$kf));
        if(strlen($aVals) > 0) {
          $qFields = HT::sel($vf['foreign'],
            'id in('.$aVals.')'
          );
          $aForeign[$vf['foreign']] = Hl::q2a($qFields);
        }
      }
    }

    $paginator = new Paginator(array(
      'data' => $mResults,
      'limit'=> Hl::opt('per_page'),
      'page' => $numberPage
    ));

    if (count($aForeign) > 0)
      $this->view->aForeign = $aForeign;

    $this->view->aTable = Hl::tables($curTable);
    $this->view->page = $paginator->getPaginate();
    //$this->view->disableLevel([View::LEVEL_ACTION_VIEW => true,]);
    $this->view->setLayoutsDir('admin/');
    $this->view->setLayout('admin_grid');
  }

  protected function _getTransPath()
  {
      $translationPath = '../app/messages/';
      $language = $this->session->get("language");
      if (!$language) {
          $this->session->set("language", "en");
      }
      if ($language === 'es' || $language === 'en') {
          return $translationPath.$language;
      } else {
          return $translationPath.'en';
      }
  }

  /**
   * Loads a translation for the whole site
   */
  public function loadMainTrans()
  {
      $translationPath = $this->_getTransPath();
      require $translationPath."/main.php";

      //Return a translation object
      $mainTranslate = new Phalcon\Translate\Adapter\NativeArray(array(
          "content" => $messages
      ));

      //Set $mt as main translation object
      $this->view->setVar("mt", $mainTranslate);
    }

    /**
     * Loads a translation for the active controller
     */
  public function loadCustomTrans($transFile)
  {
      $translationPath = $this->_getTransPath();
      require $translationPath.'/'.$transFile.'.php';

      //Return a translation object
      $controllerTranslate = new Phalcon\Translate\Adapter\NativeArray(array(
          "content" => $messages
      ));

      //Set $t as controller's translation object
      $this->view->setVar("t", $controllerTranslate);
  }

  public function initialize()
  {
    $aNames = $this->autoNames();
    if (isset($aNames['curTable'])) {
      $aTable = Hl::tables($aNames['curTable']);
      if ($aTable) {
        $sName = $aTable['note'.Hl::$pstfx];
        Phalcon\Tag::prependTitle($sName.' - '); //Встановлюєм назву
      }
    }

    $this->loadMainTrans();
  }

  public function deleteAction($id)
  {
    $this->view->sOperation = 'delete';
    $aNames = $this->autoNames();
    $curModel = $aNames['curModel'];
    $aTable = Hl::tables($aNames['curTable']);
    $deletedModel = $curModel::findFirstByid($id);
    
    if (!$deletedModel) {
      $this->flashSession->error(Hl::$t->_("Record %id% of %table% was not found",
        ['id'=>$id,'table'=>$aTable['single'.Hl::$pstfx]]));
      $this->goPage();
      return;
    }


    $aFake = null;
    if (!$this->canAct($this->view->sOperation,$aFake,$deletedModel)) {
      $this->flashSession->error(
        Hl::$t->_("Not enough rights to %act% record %id%",
          ['act'=>Hl::$t->_($this->view->sOperation),'id'=>$id])
      );
      $this->goPage();
      return;
    }


    if (!$deletedModel->delete()) {

      foreach ($deletedModel->getMessages() as $message) {
        $this->flashSession->error($message);
      }
      $this->goPage();
      return;
    }

    $this->flashSession->success($aTable['single'.Hl::$pstfx] . ' '.Hl::$t->_("was deleted successfully"));
    $this->persistent->parameters = null;

    $this->goPage();
  }
}
