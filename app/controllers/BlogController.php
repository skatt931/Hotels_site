<?php

use Phalcon\Mvc\View;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class BlogController extends ControllerBase
{
  public function commentAction() {
    if ($this->request->isAjax() && $this->request->isPost()) {
      $user_id = null;
      if (isset($_POST['fbUserInfo'])) {
        $fbInfo = $_POST['fbUserInfo'];
        if (isset($fbInfo['id'])) {
          $qUser = HT::sel('users', ['facebook_id' => $fbInfo['id'],]);
          if (count($qUser) == 0) {
            HT::ins('users', [
                'name' => $fbInfo['first_name'],
                'email' => $fbInfo['email'],
                'facebook_id' => $fbInfo['id'],
                'facebook_name' => $fbInfo['last_name'],
              ]
            );
            $qUser = HT::sel('users', ['facebook_id' => $fbInfo['id'],]);
          }
          $user_id = $qUser[0]['id'];
        }
      }
      $this->view->disable();
      if (strlen($_POST['text']) > 10) {
        if ($user_id == 0 && (Hl::user('id') > 0))
          $user_id = Hl::user('id');
        $sText = strip_tags($_POST['text']);
        $qFeeds = HT::sel('feedbacks', ['note' => $sText]);
        if (count($qFeeds) == 0) {
          $qBlog = HT::sel('blog', ['id' => (int)$_POST['postId']]);
          if (count($qBlog) > 0) {
            $qBlog = $qBlog[0];
            HT::ins('feedbacks', ['user_id' => $user_id, 'obj_id' => $qBlog['id'],
              'attr_id' => $qBlog['attr_id'],
              //'note' . Hl::$pstfx => $_POST['text'],
              'note' => $sText,
              'note_uk' => $sText,
            ]);
            echo Hl::$t->_('Comment successfuly added');
          } else
            echo Hl::$t->_('Blog not found');
        } else
          echo Hl::$t->_('This comment allready added');
      } else
        echo Hl::$t->_('Text is to short');
    }
  }

  public function canAct($sOper='',&$aNewItem=null,&$aOldItem=null) {
    $bResult = parent::canAct($sOper,$aNewItem,$aOldItem);
    if ($bResult) {
      $curModel = $this->autoNames('curModel');
      $curModel::fields();
      if ($this->view->sOperation == 'create') {
        $curModel::$fields['picture_id']['inaccessible'] = 'inaccessible';
      }
      if ($this->view->sOperation == 'edit' && !is_null($aOldItem)) {
        $curModel::$fields['hotel_id']['disabled'] = 'disabled';
        unset($curModel::$fields['picture_id']['inaccessible']);
        unset($curModel::$fields['picture_id']['inaccessible']);
        $sFilter = 'obj_id=' . $aOldItem['id'] . ' AND attr_id BETWEEN 6700 AND 6799';
        $curModel::$fields['picture_id']['foreign_filter'] = $sFilter;
        $aPictures = HT::sel('pictures', $sFilter);
        if (count($aPictures) < Hl::opt('rooms_pictures_max', 2))
          $sPictures .= '<div> <input type="file" name="file2upload" ' .
            'id="file2upload" accept="image/jpg, image/png"' .
            'onchange="uploadPicture(this,' . $aOldItem['id'] . ',' .
              $aOldItem['attr_id'] . ')">' .
            //'<a onclick="uploadPicture(this,'.$aOldItem['id'].',4600)">'.
            //Hl::$t->_('Upload').
            '</div>';
        else
          $sPictures = '';
        if (count($aPictures) > 0) {
          $sPictures .= '<div>';
          foreach ($aPictures as $p)
            $sPictures .= '<img width=100px height=100px src="' . $p['path'] . '" ' .
              'title="' . $p['path'] . '" alt="' . $p['id'] . '" ' .
              'onclick="deletePicture(' . $p['id'] . ')">';
          $sPictures .= '</div>';
        }
        $curModel::$fields['picture_id']['afterControl'] = $sPictures;
      }
    }
    return $bResult;
  }

  public function descriptionsAction($id=null)
  {
    if ($id > 0) {
      $qHotel = HT::sel('hotels',['id' => $id]);
      if (count($qHotel) > 0) {
        $qItems = ['table_id' => 15,'obj_id' => $id];

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

  public function viewAction($id=null) {
    $this->view->aButtons = ['descriptions','edit','delete'];
    parent::indexAction($id);
  }


  public function indexAction($id=null)
  {
    if ($this->request->isAjax()) {
      $aSelect =
        ['select' => 'blog.id,blog.attr_id,blog.name'.Hl::$pstfx.' name,'.
          'blog.small_note'.Hl::$pstfx.' small_note,'.
          'blog.created,pictures.path picture',
          'join' => ['pictures'=>'pictures.id=blog.picture_id'],
          'where' =>['coalesce(trash,0)=0'],
          'order' =>'created DESC',
          'limit' => 4,
          //'query' => 1,
        ];

      $iPictSize = 270;
      if ($this->request->get("attr_id", "int") > 0 &&
          $this->request->get("tag_id", "int")) { //Пошук за тегом
        $aSelect['join']['tag_links'] = 'tag_links.obj_id=blog.id AND '.
            'tag_links.attr_id='.$this->request->get("attr_id", "int");
        $aSelect['where']['blog.attr_id'] = $this->request->get("attr_id", "int");
        $aSelect['where']['tag_links.tag_id'] = $this->request->get("tag_id", "int");
        $iPictSize = 270;
      }

      if (strlen($this->request->get("word")) > 5) {//Пошук за входженням стрічки
        $aSelect['where'][] = '(blog.name'.Hl::$pstfx.' LIKE :word OR '.
          'blog.small_note'.Hl::$pstfx.' LIKE :word OR '.
          'blog.note'.Hl::$pstfx.' LIKE :word)';
        $aSelect['bind'][':word'] = '%'.$this->request->get("word").'%';
        $iPictSize = 260;
      }

      $aAttrs = Attrtypes::all();
      $qBlog = HT::sel('blog',$aSelect);
      $aBlogs = [];
      foreach ($qBlog as $a)
        $aBlogs[$a['id']] = [
          'picture'=>Pictures::pictSizeN($a['picture'],$iPictSize),
          'attr_id'=>$a['attr_id'],
          'partition'=>$aAttrs[$a['attr_id']]['name'],
          'name'=>$a['name'],
          'small_note'=>$a['small_note'],
          'created'=>Hl::df($a['created']),
        ];

      $this->view->disable();
      $this->response->setContent(json_encode($aBlogs));
      $this->response->send();
      goto end_action;
    }

    Hl::$aScriptList[] = '/public/src/js/common.js';
    Hl::$aScriptList[] = '/public/src/js/blog.js';
    $this->view->setLayoutsDir('default/');
    $this->view->disableLevel([View::LEVEL_ACTION_VIEW => true,]);
    $this->view->aAttrs=Attrtypes::all();
    if ($id <= 0) {
      $this->view->setLayout('blog');

      $aRubrics = [];
      foreach ($this->view->aAttrs as $ka=>$va)
        if ($va['parent_id'] == 6700)
          $aRubrics[] = $ka;
      $this->view->aRubrics = $aRubrics;

      $aSelect =
        ['select' => 'blog.id,blog.attr_id,blog.name'.Hl::$pstfx.' name,'.
          'blog.small_note'.Hl::$pstfx.' small_note,'.
          'blog.created,pictures.path picture',
          'join' => ['pictures'=>'pictures.id=blog.picture_id'],
          'where' =>['coalesce(trash,0)=0'],
          'order' =>'created DESC',
          'limit' => 13,
          //'query' => 1,
        ];
      if ($id < 0 && $id != -6701)
        $aSelect['where']['blog.attr_id'] = abs($id);

      $aSelAttrs =
        ['select' => 'tags.id,tags.name'.Hl::$pstfx.' name',
          'join' => ['tags'=>'tags.id=tag_links.tag_id'],
          'order' =>'tags.id',
          'group' =>'tag_id',
          'limit' => 6,
          //'query' => 1,
        ];

      $aBlogs = [];
      $aTags = [];
      foreach (array_merge(['all'],$aRubrics) as $attr_id) {
        if ($attr_id != 'all') {
          $aSelect['where'] = ['coalesce(trash,0)=0','blog.attr_id'=>$attr_id];
          $aSelect['limit'] = 4;

          $aSelAttrs['where'] = ['attr_id' => $attr_id];
          $tagsBlog = HT::sel('tag_links',$aSelAttrs);
          foreach ($tagsBlog as $a)
            $aTags[$attr_id][] =
              ['tag_id'=>$a['id'],
                'name'=>$a['name'],
              ];
        }
        $qBlog = HT::sel('blog',$aSelect);
        $aTemp = [];
        foreach ($qBlog as $a)
          $aTemp[$a['id']] =
            ['picture'=>$a['picture'],
              'attr_id'=>$a['attr_id'],
              'name'=>$a['name'],
              'small_note'=>$a['small_note'],
              'created'=>$a['created'],
            ];
        $aBlogs[$attr_id] = $aTemp;
      }
      $this->view->itemsBlog = $aBlogs;
      $this->view->aTags = $aTags;
      if ($id < 0)
        $this->view->curRubric = abs($id);
    }
    else { //Показ окремого запису блогу
      $this->view->setLayout('blog_page');
      $aSelect =
        ['select' => 'blog.id,blog.attr_id,blog.user_id,'.
          'blog.name'.Hl::$pstfx.' name,'.
          'blog.small_note'.Hl::$pstfx.' small_note,'.
          'blog.note'.Hl::$pstfx.' note,'.
          'blog.created,pictures.path picture',
          'join' => ['pictures'=>'pictures.id=blog.picture_id'],
          'where' =>['coalesce(trash,0)=0','blog.id' => $id],
        ];
      $qBlog = HT::sel('blog',$aSelect);
      if (count($qBlog) == 0) {
        $this->dispatcher->forward(["controller" => "page404",
          'action'=>'index',
          'params'=>[$id]]);
        return false;
      }

      $qBlog = $qBlog[0];

      $qMetaTags = HT::sel('descriptions',
        ['select' => 'note'.Hl::$pstfx.' note',
          'where' => ['obj_id'=>$qBlog['id'],'attr_id'=>12,'table_id'=>15],
        ]
      );

      if ($qMetaTags)
        Hl::$aMetaTags['description'] = $qMetaTags[0]['note'];

      $aSelFeeds =
        ['select' => 'users.name,profile.surname,pictures.path picture,'.
          'feedbacks.note'.Hl::$pstfx.' note,'.
          'feedbacks.likeit,feedbacks.created',
          'join' => ['profile'=>'feedbacks.user_id=profile.user_id',
            'users'=>'feedbacks.user_id=users.id',
            'pictures'=>'pictures.id=profile.picture_id',
          ],
          'where' =>['coalesce(feedbacks.trash,0)=0','feedbacks.obj_id' => $id,
            'feedbacks.attr_id' => $qBlog['attr_id']],
          'order' => 'created DESC',
          'limit' => 20,
        ];
      $qFeeds = HT::sel('feedbacks',$aSelFeeds);
      $qUserProfile = HT::sel('profile',
        ['select' => 'pictures.path picture,surname',
          'where' => [ 'profile.user_id' => $qBlog['user_id']],
          'join' => ['pictures'=>'pictures.id=profile.picture_id'],
        ]);
      $qUser = HT::sel('users',['select' => 'name', 'where' => ['id' => $qBlog['user_id']]]);
      $qUserProfile[0]['name'] = $qUser[0]['name'];
      if (strlen($qUserProfile[0]['picture']) == 0)
        $qUserProfile[0]['picture'] = '/public/src/images/user.png';

      $this->view->aSame = Hotels::getSame();

      $this->view->userData = $qUserProfile[0];
      $this->view->itemBlog = $qBlog;
      $this->view->itemFeeds = $qFeeds;
      Phalcon\Tag::prependTitle($qBlog['name'].' - ');
    }
    end_action:
  }

}
