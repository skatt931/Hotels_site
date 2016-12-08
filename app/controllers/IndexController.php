<?php

use Phalcon\Translate\Adapter\NativeArray;

class IndexController extends ControllerBase
{

  public function routingAction() {
    // Get entered language.
    // Load global config.
    $config = $this->config;

    Hl::$u = 'http://'.$_SERVER['SERVER_NAME'];
    Hl::$url = $this->url;

    Hl::$aScriptList = [
      'https://code.jquery.com/jquery-2.2.0.min.js',
      '/public/src/vendor/jquery-ui/jquery-ui.min.js',
      '/public/src/vendor/owl.carousel.2.1.0/owl.carousel.min.js',
      '/public/src/vendor/bootstrap/dist/js/bootstrap.min.js',
      '/public/src/vendor/jquery-ui/locale/datepicker-uk.js',
      '/public/src/vendor/jquery-ui/locale/datepicker-ru.js',
      '/public/src/vendor/bootstrap-multiselect/bootstrap-multiselect.js',
      '/public/src/vendor/thumb-carousel/jquery.simple.thumbchanger.js',
      'https://use.fontawesome.com/369bda4469.js',
      '/public/src/vendor/slick/slick/slick.min.js',
      '/public/src/js/bundle.js',
    ];

    //Виставляємо мови за пріорітетом
    Langs::load(); //Завантажуємо з бази мови

    $language = $this->dispatcher->getParam('redirectLanguage'); //Отримуєм мови з стрічки запиту

    if (strlen($language) == 0)  //Спроба знати найліпшу мову у випадку, якщо її не має в стрічці
    if (!isset($_SESSION['language']))
      $language = substr($this->request->getBestLanguage(),0,2);

    if (!in_array($language, (array) $config->languages->list)) //якщо такої мови немає в списку
      $language = $config->languages->emptyLang;

    if (isset($_SESSION['language']))
    if ($_SESSION['language'] != $language)
      $_SESSION['language'] = $language;

    Langs::setVars($language); //Встановити всі змінні згідно обраної мови
    Phalcon\Tag::prependTitle(Hl::opt('site_name'.Hl::$pstfx));

    //Підключаю перекладач
    $fileLang = __DIR__."/../messages/" . $language . ".php";
    if (file_exists($fileLang)) {
      require $fileLang;
    } else {
      // Переключение на язык по умолчанию
      require __DIR__."/../messages/en.php";
    }

    // Возвращение объекта работы с переводом
    Hl::$t = new NativeArray(
      array(
        "content" => $messages
      )
    );


    // Get entered controller.
    $controller   = $this->dispatcher->getParam('redirectController');

    // Get entered action.
    $action = $this->dispatcher->getParam('redirectAction');

    // Get entered id.
    $id = $this->dispatcher->getParam('redirectId');
    
    if (is_null($controller) &&
      is_null($action) &&
      is_null($id) &&
      strlen($_SERVER['REQUEST_URI']) > 4 ) {
      $controller = 'page404';
      goto go_label;
    }

    if (is_null($controller) )
      $controller = 'default';

    $aSpecials = [ //Спеціальні шляхи до розшифровки
      'login' => ['user','login'],
      'register' => ['user','register'],
      'restore' => ['user','forgotPassword'],
      'option' => ['user','option'],
      'about' => ['default','about'],
      'contacts' => ['default','contacts'],
      //'' => ['user',''],
    ];

    if (isset($aSpecials[$controller])) {
      $id = $action;
      $action = $aSpecials[$controller][1];
      $controller = $aSpecials[$controller][0];
    }
    elseif ($controller == 'admin') {
      $id = $action;
      $action = null;
    }

    if (is_numeric($action) && $id == 0) { //Якщо немає третього параметру а другий є цифровим
      $id = $action;
      $action = 'index';
    }

go_label:
    $aGo = array(
      'controller' => $controller,
      'action'     => $action
    );

    if (!is_null($id) )
      $aGo['params'] = [$id];

    $this->dispatcher->forward($aGo);

  }

  public function indexAction($id=null)
  {
      $language = $this->session->get('language');

      $exists = $this->view->getCache()->exists($language.'index');
      $exists = false;
      if (!$exists) {

          $news = News::find(array("language='$language'", "limit" => 5, "order" => "published desc"));
          if (count($news) === 0) {
              $news = News::find(array("language='en'", "limit" => 5, "order" => "published desc"));
          }

          //Query the last 5 news
          $this->view->setVar("news", $news);

      }

      $this->view->cache(array("lifetime" => 86400, "key" => $language.'index'));
  }

  public function setLanguageAction($language='')
  {
      //Change the language, reload translations if needed
      if ($language == 'en' || $language == 'es') {
          $this->session->set('language', $language);
          $this->loadMainTrans();
          $this->loadCustomTrans('index');
      }

      //Go to the last place
      $referer = $this->request->getHTTPReferer();
      if (strpos($referer, $this->request->getHttpHost()."/")!==false) {
          return $this->response->setHeader("Location", $referer);
      } else {
          return $this->dispatcher->forward(array('controller' => 'index', 'action' => 'index'));
      }
  }
}
