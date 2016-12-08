<?php

class Tags extends PrfModel
{
  static function byObj($aObj_id,$aAttr_id) {
    if (!is_array($aObj_id)) $aObj_id = [(int)$aObj_id];
    if (!is_array($aAttr_id)) $aAttr_id = [(int)$aAttr_id];
    if (count($aObj_id) == 0 && count($aAttr_id) == 0 )
      return false;
    $aWhere = ['tag_links.obj_id in ('.implode(',',$aObj_id).')',
      'tag_links.attr_id in ('.implode(',',$aAttr_id).')'
    ];
    $aTags =
      Hl::q2a(
      HT::sel('tag_links',
        ['join' => ['tags'=>'tags.id=tag_links.tag_id'],
         'where' => $aWhere,
        ])
      );
    return $aTags;
  }

  static function saveTags($sTags,$obj_id,$attr_id) {
    $aWhere = ['obj_id' => $obj_id,
      'attr_id' => $attr_id,
    ];
    HT::del('tag_links',$aWhere);

    $aTags = explode(',',$sTags);
    foreach ($aTags as $kt=>$vt)
      $aTags[$kt] = '"'.htmlspecialchars($vt).'"';
    $sTags = implode(',',$aTags);

    $aTags =
      HT::sel('tags',
        ['where' => ['name'.Hl::$pstfx.' in ('.$sTags.')'],
        ]);

    foreach ($aTags as $t)
      HT::ins('tag_links',['obj_id' => $obj_id,'attr_id' => $attr_id,'tag_id' => $t['id']]);

    return $aTags;
  }
}
