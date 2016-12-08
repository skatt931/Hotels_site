                            <li class="col-sm-3 no-padding">
                              <h2><?= Hl::$t->_('Stars')?></h2>
                              <div class="stars">
                                <?php for($i=1;$i<6;$i++) { ?>
                                <p class="wrapper-star-label">
                                  <label>
                                    <input type="checkbox" name="star<?=$i?>">
                                    <span class="checkboxx"></span>
                                    <i
                                      <?=Hl::stars($i,5,
                                        '<i class="fa fa-star truestar" aria-hidden="true"></i>',
                                        '<i class="fa fa-star" aria-hidden="true"></i>')?>
                                  </label>
                                </p>
                                <?php } ?>
                              </div>
                            </li>
