<?php

class Toplists extends PrfModel
{
  static function initStatic($oModel=null) {
    parent::initStatic($oModel);
    static::$fields['attr_id']['foreign_filter'] = 'parent_id=4500 or id=6510';
    static::$fields['attr_id']['onChange'] =
'loadObjByAttr("'.Hl::url('default/objects').'","fieldObj_id",this.value)';
  }
}
