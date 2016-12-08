<div class="row">
  <div class="col-sm-5">
    <div class="dataTables_info" id="datatable-fixed-header_info"
         role="status" aria-live="polite"
         title="<?=Hl::$t->_('Total').' '.$page->total_items?>">
      <?php echo Hl::$t->_("Showing").' '.$page->current, "/", $page->total_pages,' ',Hl::$t->_("entries");
        $pagesLink = strlen($aTable['adm_link'])>0?$aTable['adm_link']:$aTable['name'];
      ?>
    </div>
  </div>
  <div class="col-sm-7">
    <div class="dataTables_paginate paging_simple_numbers" id="datatable-fixed-header_paginate">
      <ul class="pagination">
        <li class="paginate_button previous <?=($page->current > 1)?'':'disabled'?>"
            id="datatable-fixed-header_previous">
          <?=$this->tag->linkTo( [Hl::url( $pagesLink."?page=" . $page->before ),
            Hl::$t->_("Previous"),
            'aria-controls'=>'datatable-fixed-header', 'data-dt-idx'=>"0",
            'tabindex'=>"0",
          ])?>
        </li>
        <?php
        $i=1;
        while ($i<=$page->total_pages) {
          echo '<li class="paginate_button '.($page->current==$i?'active':'').'">'.
            $this->tag->linkTo( [Hl::url( $pagesLink."?page=" . $i ),
              $i,
              'aria-controls'=>'datatable-fixed-header', 'data-dt-idx'=>$i,
              'tabindex'=>$i,
            ]);
          $i++;
        }
        ?>
        <li class="paginate_button next <?=($page->current < $page->total_pages)?'':'disabled'?>"
            id="datatable-fixed-header_next">
          <?=$this->tag->linkTo( [Hl::url( $pagesLink."?page=" . $page->next ),
            Hl::$t->_("Next"),
            'aria-controls'=>'datatable-fixed-header', 'data-dt-idx'=>"0",
            'tabindex'=>"0",
            'title'=>Hl::$t->_('Total').' '.$page->total_items,
          ])?>
        </li>
      </ul>
    </div>
  </div>
</div>
