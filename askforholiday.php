<?php
include('cookies.php');
include("functions/function.php");
include("headmenu_mobile.php");
?>
<html>
<head>
  <title>แบบฟอร์มขออนุมัติลาหยุด</title>
  <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
<script src="http://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" href="css/style.css">
<script>
/*
      $(document).ready(function() {
         $(function(){
        $("#datepicker2").datepicker({ dateFormat: 'yy-mm-dd' });
        $("#datepicker1").datepicker({ dateFormat: 'yy-mm-dd',
                            minDate:+3 }).bind("change",function(){
            var minValue = $(this).val();
            minValue = $.datepicker.parseDate("yy-mm-dd", minValue);
            minValue.setDate(minValue.getDate()+1);
            $("#datepicker2").datepicker( "option", "minDate", minValue );
        })
    });
});
*/
$(document).ready(function () {
    $("#dt1").datepicker({
        dateFormat:'yy-mm-dd' ,
        minDate:+3,
        onSelect: function () {
            var dt2 = $('#dt2');
            var startDate = $(this).datepicker('getDate');
            //add 7 days to selected date
            startDate.setDate(startDate.getDate() + 7);
            var minDate = $(this).datepicker('getDate');
            //minDate of dt2 datepicker = dt1 selected day
            dt2.datepicker('setDate', minDate);
            //sets dt2 maxDate to the last day of 7 days window
            dt2.datepicker('option', 'maxDate', startDate);
            //first day which can be selected in dt2 is selected date in dt1
            dt2.datepicker('option', 'minDate', minDate);
            //same for dt1
            $(this).datepicker('option', 'minDate', minDate);
        }
    });
    $('#dt2').datepicker({
       dateFormat:'yy-mm-dd'
    });
});
  </script>
</head>
<body>
<div class="container">
  <div class="page-header">
    <h3>แบบฟอร์มขออนุมัติลาหยุด</h3>      
  </div>
  <form action="insertholiday.php" class="form-horizontal" method="post">
    <div class="form-group">
      <label class="control-label col-sm-2" for="dstart" >จากวันที่ : </label>
      <div class="col-sm-10">
        <input type="text" class="form-control" id="dt1" name="dstart" required>
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" for="dend">ถึงวันที่ :</label>
      <div class="col-sm-10">          
        <input type="text" class="form-control" id="dt2"  name="dend" required>
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" for="dend">เหตุผลการลาหยุด :</label>
      <div class="col-sm-10">          
        <input type="text" class="form-control" id="memo"  name="memo" required>
      </div>
    </div>
    <div class="alert alert-danger" align="center">
        <strong>สำคัญ!</strong> ให้โทรแจ้งพี่หนึ่งทราบสำหรับการลาหยุดอีกทางหนึ่งจึงจะได้รับอนุมัติหยุด
    </div>
    <div class="form-group">        
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-warning" name="submit">ส่งแบบฟอร์ม</button>
      </div>
    </div>
  </form>
</div>
</body>
</html>
