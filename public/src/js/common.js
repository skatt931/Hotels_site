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
