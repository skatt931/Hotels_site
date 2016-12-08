function parseFloatDef(val) {
  var fVal = parseFloat(val);
  if (isNaN(fVal)) fVal = 0;
  return fVal;
}

function sfObj(oCheck,aIndexes,uDefault) { //Безпечна перевірка значення об'екту
  var oTemp;
  var oResult;
  var bTillEnd = true;
  if (typeof uDefault != 'undefined')
    oResult = uDefault;
  else
    oResult = false;

  if (typeof oCheck != 'undefined') {
    oTemp = oCheck;
    for(propName in aIndexes) {
      if (typeof oTemp[aIndexes[propName]] != 'undefined')
        oTemp = oTemp[aIndexes[propName]];
      else {
        bTillEnd = false;
        break;
      }
    }
    if (bTillEnd)
      oResult = oTemp;
  }
  return oResult;
}

function ajaxIt(sURL,oData,fSuccess,fError) {
  var sType = 'get';
  if (sfObj(oData,['type'])) {
    sType = oData['type'];
    delete oData['type'];
  }
  $.ajax({
    type:sType,
    url:sURL,
    data:oData,//параметры запроса
    response:'text',//тип возвращаемого ответа text либо xml
    success:fSuccess,
    error:fError
  });
  return true;
}

function updateGoogle(Data) {
  if (Data.status = 'OK') {
    var eGoogleInfo = $('#googleInfo');
    var sAddress = sfObj(Data,['results',0,'formatted_address']);
    eGoogleInfo.html(sAddress);
    eGoogleInfo.css('display','block');
  }
}

function padLeft(sStr,iLength,sSymb) {
  var iCount = iLength-sStr.length;
  var sResult = sStr;
  while(iCount > 0) {
    sResult = sSymb + sResult;
    iCount--;
  }
  return sResult;
}

function fillCalendar(oResponse) {
  var oCalendar = {},oCurCalendar; //Календарі з цінами для кожного номеру
  var oTemplCalendar; //Календар шаблон з днями місяця
  var iObj, oCurPrices;
  var aTD;
  var td;
  var aDate,sDateFrom,sDateEnd;
  var fPrice,fBasePrice=55;
  if (typeof oResponse['prices'] !== 'undefined') {
    oPrices = oResponse['prices'];
    for (var iPrice in oPrices) { //Об'єднання цін в календарі
      oCurPrices = oPrices[iPrice];
      iObj = oCurPrices['obj_id'];
      fBasePrice = $('#room_'+iObj).attr('data-price');
      if (typeof oCalendar[iObj] === 'undefined')
        oCalendar[iObj] = {}; //Додаємо новий об'ект
      oCurCalendar = oCalendar[iObj];
      if (typeof oTemplCalendar === 'undefined') {
        oTemplCalendar = {};
        aTD = $('#RangeCalendar_' + iObj).find('td');
        var i = 0;
        while(typeof aTD[i] !== 'undefined') {
          td = $(aTD[i]);
          if (td.find('.number-day').html().length > 0) {
            sDateP =
              td.attr('data-year') +
              padLeft(td.attr('data-month'), 2, '0') +
              padLeft(td.find('.number-day').html(), 2, '0');
            oTemplCalendar[i] = sDateP;
          }
          i++;
        }
      }
      aDate = oCurPrices['date_f'].split('.');
      sDateFrom = aDate[2]+aDate[1]+aDate[0];
      aDate = oCurPrices['date_e'].split('.');
      sDateEnd = aDate[2]+aDate[1]+aDate[0];
      for(var iCalendar in oTemplCalendar) {
        if (oTemplCalendar[iCalendar] >= sDateFrom &&
            oTemplCalendar[iCalendar] <= sDateEnd )
          fPrice = oCurPrices['price'];
        else
          fPrice = fBasePrice;
        oCurCalendar[oTemplCalendar[iCalendar]] = fPrice;
      }
    }
    var aRooms = $('.list-room-item');
    var curRoom,iRoom,j,bHasCalendar;
    i = 0;
    while(typeof aRooms[i] !== 'undefined') {
      curRoom = $(aRooms[i]);
      iRoom = curRoom.attr('id').substring(5);
      fBasePrice = curRoom.attr('data-price');
      bHasCalendar = (typeof oCalendar[+iRoom] !== 'undefined');
      aTD = curRoom.find('td');
      j = 0;
      while (typeof aTD[j] !== 'undefined') {
        td = $(aTD[j]);
        if (td.find('.number-day').html().length > 0) {
          sDateP =
            td.attr('data-year') +
            padLeft(td.attr('data-month'), 2, '0') +
            padLeft(td.find('.number-day').html(), 2, '0');
          if (bHasCalendar)
            fPrice = oCalendar[+iRoom][sDateP];
          else
            fPrice = fBasePrice;
          fPrice = parseInt(fPrice);
          td.find('.price').html(fPrice);
        }
        j++;
      }
      i++;
    }
    //console.log(oCalendar);
  }
}

function calcSumCalendar(oThis,date) {
  var startDate, endDate, dDate;
  dDate = oThis.state.startDate;
  if (dDate == 0)
    dDate = date;
  startDate = dDate['year']+dDate['month']+dDate['day'];

  dDate = oThis.state.endDate;
  if (dDate == 0)
    dDate = date;
  endDate = dDate['year']+dDate['month']+dDate['day'];

  var curRoom = $('#room_'+oThis.props.id);
  var j,aTD,fullSum=0;
  aTD = curRoom.find('td');
  j = 0;
  curRoom.attr('data-start',startDate);
  curRoom.attr('data-end',endDate);
  while (typeof aTD[j] !== 'undefined') {
    td = $(aTD[j]);
    if (td.find('.number-day').html().length > 0) {
      sDateP =
        td.attr('data-year') +
        padLeft(td.attr('data-month'), 2, '0') +
        padLeft(td.find('.number-day').html(), 2, '0');
      if (sDateP >= startDate && sDateP <= endDate)
        fullSum += +td.find('.price').html();
    }
    j++;
  }
  curRoom.attr('data-selected',fullSum);
  totalChanged(curRoom);
}

function guestChanged(oThis)
{
  var curRoom = $(oThis.closest('.list-room-item'));
  var iMulty = oThis.value;
  if (iMulty == 0) iMulty = 1;
  curRoom.attr('data-guests',iMulty);
  totalChanged(curRoom);
}

function addiChanged(oThis) {
  var curRoom = $(oThis.closest('.list-room-item'));
  var fAddi = 0, sAddi = '';
  var i = 0;
  while(typeof oThis.selectedOptions[i] !== 'undefined') {
    fAddi += +$(oThis.selectedOptions[i]).attr('data-price');
    sAddi += ','+$(oThis.selectedOptions[i]).html();
    i++;
  }
  curRoom.attr('data-addi-list',sAddi);
  curRoom.attr('data-addi',fAddi);
  totalChanged(curRoom);
}

function totalChanged(curRoom)
{
  var fSelected = parseFloatDef(curRoom.attr('data-selected'));
  if (fSelected == 0)
    fSelected = parseFloatDef(curRoom.attr('data-price'));
  var fAddi = parseFloatDef(curRoom.attr('data-addi'));
  var iMulty = parseInt(curRoom.attr('data-guests'));
  if (iMulty == 0 || isNaN(iMulty)) iMulty = 1;
  curRoom.attr('data-total',(fSelected+fAddi)*iMulty);
  curRoom.find('.money span').html((fSelected+fAddi)*iMulty);
  calcTotal(curRoom);
}

function calcTotal(curRoom) {
  var aRooms = $('.list-room-item');
  var i = 0,fTotal = 0,cntRooms = 0, oRoom;
  var sFullInfo = '';
  while(typeof aRooms[i] !== 'undefined') {
    fTotal += parseFloatDef($(aRooms[i]).attr('data-total'));
    if ($(aRooms[i]).attr('data-guests') > 0) {
      oRoom = $(aRooms[i]);
      cntRooms += oRoom.attr('data-guests');
      sFullInfo +=
        ',room='+oRoom.attr('id') +
        ',guests='+oRoom.attr('data-guests')+
        ',start='+oRoom.attr('data-start')+
        ',end='+oRoom.attr('data-end')+
        ',addi='+curRoom.attr('data-addi-list')
      ;
    }
    i++;
  }
  if (cntRooms == 0) {
    if (typeof curRoom !== 'undefined') {
      fTotal = parseFloatDef(curRoom.attr('data-total'));
      if (fTotal == 0)
        fTotal = curRoom.attr('data-price');
      sFullInfo =
        ',room='+curRoom.attr('id') +
        ',guests='+curRoom.attr('data-guests')+
        ',start='+curRoom.attr('data-start')+
        ',end='+curRoom.attr('data-end')+
        ',addi='+curRoom.attr('data-addi-list')
      ;
    }
  }

  sFullInfo += ',fullsumm='+fTotal;
  $('.wr-button-reserve i').html(fTotal);
  $('#fullinfo').val(sFullInfo);
}

function bookIt(oThis) {
  var curRoom = $(oThis.closest('.list-room-item'));
  calcTotal(curRoom);

}