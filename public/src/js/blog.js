function blogReload(data) {
  var oSearch = $('#search');
  oSearch.attr('data-progress','executed');
  if (oSearch.attr('data-word') != oSearch.val()) {//Якщо слово встигло змінитись протягом роботи
    blogChange(oSearch[0]); //Повторно завантажити
    return;
  }
  cloneSearch(JSON.parse(data),$('#publication-row'));
}

function cloneSearch(aData,oMain) {
  var oElem = $('#elem_tmpl_search');
  if (Object.keys(aData).length > 0) {
    oMain.html('');
    for (var d in aData) {
      newObject = oElem.clone();
      newObject.removeAttr('style');
      newObject.attr("id", "elem_" + aData[d]['attr_id'] + '_' + d);
      newObject.find('img').attr('src', aData[d]['picture']);
      newObject.find('.info_partition').html(aData[d]['partition']);
      newObject.find('.info_date').html(aData[d]['created']);
      newObject.find('.info_head').html(aData[d]['name']);
      newObject.find('.info_body').html(aData[d]['small_note']);
      newObject.attr("onclick", newObject.attr("onclick") + "/" + d + "'");
      oMain.append(newObject);
      //sText += ' id'+d;
      //sText += ' attr_id'+aData[d]['attr_id'];
    }
  }
}

function blogChange(oThis) {
  if ($(oThis).attr('data-progress') != 'in-progress')
  if (oThis.value.length > 5) {
    $(oThis).attr('data-progress','in-progress');
    $(oThis).attr('data-word',oThis.value);
    ajaxIt('/blog',{'word':oThis.value},blogReload);
  }
}

function tagClick(oTab) {
  var pData = {
    'attr_id': $(oTab).attr("attr_id"),
    'tag_id': $(oTab).attr("tag_id")
  };
  $.ajax({
    type: 'get',
    url: '/blog',
    data: pData,
    response: 'text',
    success: function (data) {
      var aData = JSON.parse(data);
      var sText = '';
      var oElem = $('#elem_tmpl');
      var oMain = $('#' + $(oTab).attr("panel_id"));
      var newObject;
      oMain.html('');
      for (var d in aData) {
        newObject = oElem.clone();
        newObject.removeAttr('style');
        newObject.attr("id", "elem_" + aData[d]['attr_id'] + '_' + d);
        newObject.find('img').attr('src', aData[d]['picture']);
        newObject.find('.info_head').html(aData[d]['name']);
        newObject.find('.info_body').html(aData[d]['small_note']);
        newObject.attr("onclick", newObject.attr("onclick") + "/" + d + "'");
        oMain.append(newObject);
        //sText += ' id'+d;
        //sText += ' attr_id'+aData[d]['attr_id'];
      }
      //[0].innerHTML = sText;
    },
    error: function (data) {
      $('#' + $(oTab).attr("panel_id"))[0].innerHTML = data.responseText;
    }
  });
  return true;
}