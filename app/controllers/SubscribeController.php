<?php

class SubscribeController extends ControllerBase
{
  public function indexAction($id=null)
  {
    $sEmail = $_POST['email'];
    if (!filter_var($sEmail,FILTER_VALIDATE_EMAIL)) {
      echo 'Wrong E-mail';
      return false;
    }
    $qEmail = HT::sel('users',['email'=>$sEmail]);
      if (count($qEmail) == 0) {
        HT::ins('users',['email'=>$_POST['email']]);
        echo Hl::$t->_('Subscribed successfull');
      }
      else
        echo Hl::$t->_('E-mail allready subscribed');
  }
}
