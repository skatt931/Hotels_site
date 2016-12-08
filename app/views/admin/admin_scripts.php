    <!-- jQuery -->
    <script src="../vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="../vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="../vendors/nprogress/nprogress.js"></script>
    <!-- Chart.js -->
    <script src="../vendors/Chart.js/dist/Chart.min.js"></script>
    <!-- gauge.js -->
    <script src="../vendors/gauge.js/dist/gauge.min.js"></script>
    <!-- bootstrap-progressbar -->
    <script src="../vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <!-- iCheck -->
    <script src="../vendors/iCheck/icheck.min.js"></script>
    <!-- Skycons -->
    <script src="../vendors/skycons/skycons.js"></script>
    <!-- Flot -->
    <script src="../vendors/Flot/jquery.flot.js"></script>
    <script src="../vendors/Flot/jquery.flot.pie.js"></script>
    <script src="../vendors/Flot/jquery.flot.time.js"></script>
    <script src="../vendors/Flot/jquery.flot.stack.js"></script>
    <script src="../vendors/Flot/jquery.flot.resize.js"></script>
    <!-- Flot plugins -->
    <script src="/js/flot/jquery.flot.orderBars.js"></script>
    <script src="/js/flot/date.js"></script>
    <script src="/js/flot/jquery.flot.spline.js"></script>
    <script src="/js/flot/curvedLines.js"></script>
    <!-- JQVMap -->
    <script src="../vendors/jqvmap/dist/jquery.vmap.js"></script>
    <script src="../vendors/jqvmap/dist/maps/jquery.vmap.world.js"></script>
    <script src="../vendors/jqvmap/examples/js/jquery.vmap.sampledata.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="/js/moment/moment.min.js"></script>
    <script src="/js/datepicker/daterangepicker.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>

    <!-- Flot -->
    <script>
      $(document).ready(function() {
        var data1 = [
          [gd(2012, 1, 1), 17],
          [gd(2012, 1, 2), 74],
          [gd(2012, 1, 3), 6],
          [gd(2012, 1, 4), 39],
          [gd(2012, 1, 5), 20],
          [gd(2012, 1, 6), 85],
          [gd(2012, 1, 7), 7]
        ];

        var data2 = [
          [gd(2012, 1, 1), 82],
          [gd(2012, 1, 2), 23],
          [gd(2012, 1, 3), 66],
          [gd(2012, 1, 4), 9],
          [gd(2012, 1, 5), 119],
          [gd(2012, 1, 6), 6],
          [gd(2012, 1, 7), 9]
        ];
        $("#canvas_dahs").length && $.plot($("#canvas_dahs"), [
          data1, data2
        ], {
          series: {
            lines: {
              show: false,
              fill: true
            },
            splines: {
              show: true,
              tension: 0.4,
              lineWidth: 1,
              fill: 0.4
            },
            points: {
              radius: 0,
              show: true
            },
            shadowSize: 2
          },
          grid: {
            verticalLines: true,
            hoverable: true,
            clickable: true,
            tickColor: "#d5d5d5",
            borderWidth: 1,
            color: '#fff'
          },
          colors: ["rgba(38, 185, 154, 0.38)", "rgba(3, 88, 106, 0.38)"],
          xaxis: {
            tickColor: "rgba(51, 51, 51, 0.06)",
            mode: "time",
            tickSize: [1, "day"],
            //tickLength: 10,
            axisLabel: "Date",
            axisLabelUseCanvas: true,
            axisLabelFontSizePixels: 12,
            axisLabelFontFamily: 'Verdana, Arial',
            axisLabelPadding: 10
          },
          yaxis: {
            ticks: 8,
            tickColor: "rgba(51, 51, 51, 0.06)",
          },
          tooltip: false
        });

        function gd(year, month, day) {
          return new Date(year, month - 1, day).getTime();
        }
      });
    </script>
    <!-- /Flot -->

    <!-- JQVMap -->
    <script>
      $(document).ready(function(){
        $('#world-map-gdp').vectorMap({
            map: 'world_en',
            backgroundColor: null,
            color: '#ffffff',
            hoverOpacity: 0.7,
            selectedColor: '#666666',
            enableZoom: true,
            showTooltip: true,
            values: sample_data,
            scaleColors: ['#E6F2F0', '#149B7E'],
            normalizeFunction: 'polynomial'
        });
      });
    </script>
    <!-- /JQVMap -->

    <!-- Skycons -->
    <script>
      $(document).ready(function() {
        var icons = new Skycons({
            "color": "#73879C"
          }),
          list = [
            "clear-day", "clear-night", "partly-cloudy-day",
            "partly-cloudy-night", "cloudy", "rain", "sleet", "snow", "wind",
            "fog"
          ],
          i;

        for (i = list.length; i--;)
          icons.set(list[i], list[i]);

        icons.play();
      });
    </script>
    <!-- /Skycons -->

    <!-- Doughnut Chart -->
    <script>
      $(document).ready(function(){
        var options = {
          legend: false,
          responsive: false
        };

        new Chart(document.getElementById("canvas1"), {
          type: 'doughnut',
          tooltipFillColor: "rgba(51, 51, 51, 0.55)",
          data: {
            labels: [
              "Symbian",
              "Blackberry",
              "Other",
              "Android",
              "IOS"
            ],
            datasets: [{
              data: [15, 20, 30, 10, 30],
              backgroundColor: [
                "#BDC3C7",
                "#9B59B6",
                "#E74C3C",
                "#26B99A",
                "#3498DB"
              ],
              hoverBackgroundColor: [
                "#CFD4D8",
                "#B370CF",
                "#E95E4F",
                "#36CAAB",
                "#49A9EA"
              ]
            }]
          },
          options: options
        });
      });
    </script>
    <!-- /Doughnut Chart -->
    
    <!-- bootstrap-daterangepicker -->
    <script>
      $(document).ready(function() {

        var cb = function(start, end, label) {
          console.log(start.toISOString(), end.toISOString(), label);
          $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        };

        var optionSet1 = {
          startDate: moment().subtract(29, 'days'),
          endDate: moment(),
          minDate: '01/01/2012',
          maxDate: '12/31/2015',
          dateLimit: {
            days: 60
          },
          showDropdowns: true,
          showWeekNumbers: true,
          timePicker: false,
          timePickerIncrement: 1,
          timePicker12Hour: true,
          ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          opens: 'left',
          buttonClasses: ['btn btn-default'],
          applyClass: 'btn-small btn-primary',
          cancelClass: 'btn-small',
          format: 'MM/DD/YYYY',
          separator: ' to ',
          locale: {
            applyLabel: 'Submit',
            cancelLabel: 'Clear',
            fromLabel: 'From',
            toLabel: 'To',
            customRangeLabel: 'Custom',
            daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
            monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            firstDay: 1
          }
        };
        $('#reportrange span').html(moment().subtract(29, 'days').format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));
        $('#reportrange').daterangepicker(optionSet1, cb);
        $('#reportrange').on('show.daterangepicker', function() {
          console.log("show event fired");
        });
        $('#reportrange').on('hide.daterangepicker', function() {
          console.log("hide event fired");
        });
        $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
          console.log("apply event fired, start/end dates are " + picker.startDate.format('MMMM D, YYYY') + " to " + picker.endDate.format('MMMM D, YYYY'));
        });
        $('#reportrange').on('cancel.daterangepicker', function(ev, picker) {
          console.log("cancel event fired");
        });
        $('#options1').click(function() {
          $('#reportrange').data('daterangepicker').setOptions(optionSet1, cb);
        });
        $('#options2').click(function() {
          $('#reportrange').data('daterangepicker').setOptions(optionSet2, cb);
        });
        $('#destroy').click(function() {
          $('#reportrange').data('daterangepicker').remove();
        });
      });
    </script>
    <!-- /bootstrap-daterangepicker -->

    <!-- gauge.js -->
    <script>
      var opts = {
          lines: 12,
          angle: 0,
          lineWidth: 0.4,
          pointer: {
              length: 0.75,
              strokeWidth: 0.042,
              color: '#1D212A'
          },
          limitMax: 'false',
          colorStart: '#1ABC9C',
          colorStop: '#1ABC9C',
          strokeColor: '#F0F3F3',
          generateGradient: true
      };
      var target = document.getElementById('foo'),
          gauge = new Gauge(target).setOptions(opts);

      gauge.maxValue = 6000;
      gauge.animationSpeed = 32;
      gauge.set(3200);
      gauge.setTextField(document.getElementById("gauge-text"));
    </script>
    <!-- /gauge.js -->
<script>
function setAsFrame() {
  $("div.right_col").find("form").
                     bind('submit',function() { return getBySubmit(this); });
  $("div.right_col").find("a").each( //Усім посиланням по кліку змінити виклик
      function() {
        if (this.href.length > 3)
          this.onclick = function () {
            return getContent(this);
          };
        }
    );
}

function backAction() {
  var history = $("#history")[0].href;
  var aHistory = history.split(';');
  aHistory.pop();
  var lasturl = aHistory.pop();
  if (aHistory.length == 0)
    aHistory.push(lasturl);
  if (lasturl.length > 0) {
    $("#history")[0].href = aHistory.join(';');
    $("#curUrl")[0].href = lasturl;
    getContent($("#curUrl")[0]);
  }
}

function getContent(objA) {
  var pData;
  pData={};
  $("#curUrl")[0].href = objA.href;
  if ($("#history")[0].href.length > 0)
    $("#history")[0].href += ';';
  $("#history")[0].href += objA.href;
  $.ajax({
    type:'get',//тип запроса: get,post либо head
    url:objA.href,//url адрес файла обработчика
    data:pData,//параметры запроса
    response:'text',//тип возвращаемого ответа text либо xml
    beforeSend:function () {
      $("div.right_col")[0].innerHTML = 'Loading...';
      return true;
    },
    success:function (data) {//возвращаемый результат от сервера
      if (data.substring(0,12) == 'json_result=') {
        var jsonData = JSON.parse(data.substring(12));
        if (jsonData.action == 'back')
          backAction();
      }
      else
      {
        $("div.right_col")[0].innerHTML = data;
        setAsFrame();
      }
    },
    error:function (data) {//возвращаемый результат от сервера
      $("div.right_col")[0].innerHTML = data.responseText;
    }
  });
  return false;
}

function getBySubmit(objForm) {
  var pData;
  pData=$(objForm).serialize();
  $.ajax({
    type:'post',//тип запроса: get,post либо head
    url:objForm.action,//url адрес файла обработчика
    data:pData,//параметры запроса
    response:'text',//тип возвращаемого ответа text либо xml
    beforeSend:function () {
      $("div.right_col")[0].innerHTML = 'Sending...';
      return true;
    },
    success:function (data) {//возвращаемый результат от сервера
      $("div.right_col")[0].innerHTML = data;
      setAsFrame();
    },
    error:function (data) {//возвращаемый результат от сервера
      $("div.right_col")[0].innerHTML = data.responseText;
    }
  });
  return false;
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

function setPerPage(oSel) {
  ajaxIt(oSel.getAttribute('href')+oSel.value);
  getContent($("#curUrl")[0]);
  return false;
}

function tagClick(oTag) {
  $('#fieldTags')[0].value += ','+oTag.innerText;
}

function loadObjByAttr(url,fieldName,attr_id) { //Завантажити
  ajaxIt(url,{'type':'post','field':fieldName,'attr_id':attr_id},ajaxAfter);
}

function ajaxAfter(data) {
  var aData = JSON.parse(data);
  $('#'+aData.field)[0].outerHTML = aData.result;
}

function uploadPicture(oThis,objId,attrId) {
  if (oThis.files && oThis.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {
      ajaxIt('pictures/upload',{'type':'post','obj_id':objId,'attr_id':attrId
        ,'picture':e.target.result //JSON.stringify(
      });
      /*
       $('#blah')
       .attr('src', e.target.result)
       .width(150)
       .height(200);*/
    };

    reader.readAsDataURL(oThis.files[0]);
  }
}

function deletePicture(pictureId) {
  var result = confirm("Do you realy want to delete the picture?");
  if (result) {
    ajaxIt('pictures/unlink',{'type':'post','id':pictureId});
  }
}

window.onload = function() {
  $('#menu_hotels').click();
};

</script>
