<div class="modal fade book_modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">&times;</span></button>
        <h4 class="modal-title text-center" id="myModalLabel"><?= Hl::$t->_('You have') ?></h4>
        <div class="coin center-block"></div>
        <p class="bonus text-center">5000</p>
        <p class="text-center"><?= Hl::$t->_('points') ?></p>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6">
            <div class="main_form_wrap first">
              <div class="book_title_wrap">
                <h3 class="text-center"><?= Hl::$t->_('Quick booking') ?></h3>
              </div>
              <div class="quick_form">
                <form class="form-horizontal">
                  <input type="hidden" id="fullinfo" name="fullinfo">
                  <div class="form-group">
                    <label for="name" class="col-sm-3 control-label text-left"><?= Hl::$t->_('Name') ?>
                      <br> <?= Hl::$t->_('Surname') ?></label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="phone" class="col-sm-3 control-label text-left"><?= Hl::$t->_('Mobile phone') ?></label>
                    <div class="col-sm-9">
                      <input type="tel" class="form-control" id="phone" name="phone" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-3 control-label text-left"><?= Hl::$t->_('E-mail') ?></label>
                    <div class="col-sm-9">
                      <input type="email" class="form-control" id="inputEmail3" name="inputEmail3" required>
                    </div>
                  </div>
                  <div class="col-sm-offset-3 col-sm-9"><?= Hl::$t->_('Fields marked * is required') ?></div>
                  <div class="form-group">
                    <div class="text-center">
                      <button type="submit" class="btn btn-default quick_book_btn" data-toggle="modal"
                              name="book" data-target="#myModal" onclick="sendBook(this)">
                        <?= Hl::$t->_('Book') ?>
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="main_form_wrap last">
              <div class="book_title_wrap">
                <h3 class="text-center"><?= Hl::$t->_('Register for save your points') ?></h3>
              </div>
              <div class="bonus_form">
                <form class="form-horizontal">
                  <div class="form-group">
                    <label for="name" class="col-sm-3 control-label text-left"><?= Hl::$t->_('Name') ?>
                      <br> <?= Hl::$t->_('Surname') ?></label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="phone" class="col-sm-3 control-label text-left"><?= Hl::$t->_('Mobile phone') ?></label>
                    <div class="col-sm-9">
                      <input type="tel" class="form-control" id="phone" name="phone" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-3 control-label text-left"><?= Hl::$t->_('E-mail') ?></label>
                    <div class="col-sm-9">
                      <input type="email" class="form-control" id="inputEmail3" name="inputEmail3" required>
                    </div>
                  </div>
                  <div class="col-sm-offset-3 col-sm-9"><?= Hl::$t->_('Fields marked * is required') ?></div>
                  <div class="form-group">
                    <div class="text-center">
                      <button type="submit" class="btn btn-default book_register_btn"
                              name="quick" onclick="sendBook(this)">
                        <?= Hl::$t->_('Register and book') ?>
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--<div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
      </div>-->
    </div>
  </div>
  
</div>
<script>
  function sendBook(bttn) {
    var pData = $(bttn.form).serialize();
    var oStorage = JSON.parse(sessionStorage.getItem('search'));
    pData += '&action=' + bttn.name;
    if (typeof oStorage['checkIn'] != 'undefined')
      pData += '&checkIn=' + oStorage['checkIn'];
    if (typeof oStorage['checkOut'] != 'undefined')
      pData += '&checkOut=' + oStorage['checkOut'];
    $.ajax({
      type: 'post',
      url: '/hotels/book',
      data: pData,
      response: 'text',
      success: function (data) {
        let $modal = $('#modalBooking');
        if (data) {
          $modal.modal('show');
          $modal.find('.modal-title').html(data);
        }
      },
      error: function (data) {
        //$('#' + $(oTab).attr("panel_id"))[0].innerHTML = data.responseText;
      }
    });
    return true;
  }
</script>