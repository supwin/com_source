<?php
include('cookies.php');
header ("Last-Modified: " . gmdate ("D, d M Y H:i:s") . " GMT");
include("functions/function.php");
include("headmenu_mobile.php");
?>
<html>
<head>
<title>Reject Reason Code</title>
<script type="text/javascript">
function copyToClipboard(element) {
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($(element).text()).select();
  document.execCommand("copy");
  alert("คัดลอกข้อความเรียบร้อย");
  $temp.remove();
}
</script>
</script>
</head>
<body>
<div class="container">
  <h4>ปัญหาและCode ในการ Reject งาน</h4>
   <div class="alert alert-danger" align="center">
    <strong>ขอความร่วมมือทีมช่างทุกทีม</strong> ให้รับทราบและปฏิบัติตามอย่างเคร่งครัด
  </div> 
  <!--         
  <table class="table table-striped table-bordered">
    <thead>
      <tr>
        <th>No.</th>
        <th>ปัญหาที่พบ</th>
        <th>Code ในการ Reject (คืนงาน)</th>
        <th>ตัวอย่างการ Remark ในระบบ FOA</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><span class="badge">1.</span></td>        
        <td>1.1  ลูกค้าไม่สะดวกให้เข้าติดตั้ง<br>
            1.2 ลูกค้าอยู่ต่างจังหวัด<br>
            1.3 ลูกค้าจะไปต่างประเทศ<br>
            1.4  ลูกค้าขอตัดสินใจรอการตัดสินใจ<br>
            1.5 ลูกค้ายังไม่พร้อมเปลี่ยนระบบ
        </td>
        <td><code>กรณีลูกค้าขอเลื่อนนัดหมาย</code></td>
        <td>ติดต่อลูกค้าก่อนวันนัดหมาย : ลูกค้าคุณ xx  Tel ติดต่อ xx ไม่สะดวกเนื่องจาก xxx  code คืนงานจาก IDC xxxxxxx</td>
      </tr>

      <tr>
        <td>2.</td>
        <td>2.1 เบอร์ติดต่อลูกค้าผิด <br>
            2.2 ไม่มีเบอร์ติดต่อลูกค้า <br>
            2.3 โทรหาลูกค้า สายว่างไม่รับ และหรือเข้าบริการรับฝากข้อความ <br>
            2.4 โทรลูกค้าเบอร์ติดต่อไม่มีคนรู้จัก<br>
        </td>
        <td>ติดต่อลูกค้าไม่ได้</td>
        <td>ติดต่อลูกค้า  ลูกค้าคุณ xx  Tel ติดต่อ xx   ผลการติดต่อ xxx<br>
            ส่ง SMS แจ้งลุกค้าที่เบอร์ xx  code คืนงานจาก IDC  xxxx <br>
            ติดต่อ sale  : คุณ xxxx  Tel ผลการติดต่อ Sale  ติดไม่ได้ xx<br>
        </td>
      </tr>

      <tr>
        <td>3.</td>
        <td>3.1 ลูกค้าไม่ได้อยู่บ้านนี้แล้ว  ย้ายไปแล้ว<br>
            3.2   คนในครอบครัวไม่ให้ติดตั้ง<br>
            3.3 ลูกค้าจะย้ายไปอยู่ต่างจังหวัด<br>
            3.4 ช่างไม่เข้าตามนัดหมาย<br>
            3.5  ลูกค้าติดตั้งของค่ายอื่นไปแล้ว<br>
            3.6  ลูกค้าแจ้งหมดความจำเป็นในการใช้งาน<br>
            3.7  ลูกค้าแจ้งติดสัญญากับผู้ให้บริการรายอื่น<br>
            3.8 ลูกค้าแจ้งต้องการประหยัดค่าใช้จ่าย<br>
            3.9 ลูกค้าแจ้งยังไม่ให้ติดตั้งเพราะเปลี่ยนชื่อผู้ขอ  <br>
            3.10 ลูกค้าแจ้งไม่ได้ขอบริการ<br>
            3.11 ลูกค้าไม่ต้องการติดตั้งแล้ว <br>
            3.12  ลูกค้าย้ายบ้านและหรือย้ายไปอยู่ต่างจังหวัด<br>
        </td>
        <td>ลูกค้าขอยกเลิก</td>
        <td>ติ ลูกค้าคุณ xx  Tel ติดต่อ xx   ลูกค้าขอยกเลิก  xxx<br>
            Code คืนงานจาก IDC  xxxx<br>
        </td>
      </tr>

      <tr>
        <td>4.</td>
        <td>4.1  กรณี case Non standardและทำงานต่อเนื่องอันเนื่องจากวันเดิม<br>
          ทำแล้วไม่เสร็จต้องมาทำต่อในวันรุ่งขึ้น
        </td>
        <td>เปลี่ยนวันนัดหมาย</td>
        <td>ติทำงานต่อเนื่องจากวันที่ xx/xx/xx หน้างานเป็น case Non standard
        </td>
      </tr>

      <tr>
        <td>5.</td>
        <td>5.1 New  ระยะสายเกิน  500 เมตร ไม่ต้องเก็บเงินค่าสายเกินกับลูกค้า ถึงสิ้นเดือนธันวาคม 2016  หากมีเปลี่ยนแปลงจะมีประกาศแจ้งอีกครั้ง<br>
            5.2 New  ระยะสายเกินมากกว่า   500 เมตร  แจ้งปัญหา(คืนงาน) <br>
            5.3 Migration ระยะสายเกิน 1000 เมตร ไม่ต้องเก็บเงินลูกค้า<br>
            5.4 Migration ระยะสายเกิน 1000 เมตร แจ้งปัญหา (คืนงาน)<br>
        </td>
        <td>ติดตั้งให้ลูกค้าได้เลย<br>
            ทางสายเกินมากกว่ามาตรฐานที่กำหนด<br>
            ติดตั้งให้ลูกค้าได้เลย<br>
            ทางสายเกินมากกว่ามาตรฐานที่กำหนด<br>
        </td>
        <td>(5.2)ทางสายเกินมากกว่าที่กำหนดระยะสาย xx เมตรแจ้งzone คุณ xxxx  รับทราบ<br>
            (5.4)ทางสายเกินมากกว่าที่กำหนดระยะสาย xx เมตรแจ้งzone คุณ xxxx  รับทราบ
        </td>
      </tr>

      <tr>
        <td>6.</td>
        <td>6.1 ข้ามถนน 4 เลนทีมีรถวิ่งตลอดเดินไม่ได้<br>
            6.2 ไม่มีเสารองรับ<br>
            6.3 ติดแนวกันสาดไม่มีแนวในการเดิน และหรือข้างบ้านไม่ให้เดินผ่าน<br>
            6.4 มีต้นไม้เล็กใหญ่ตลอดทั้งแนวเดินไม่ได้<br>
            6.5 แนวสายที่ผ่านหม้อแปลงไม่มีแนวในการเดิน<br>
            6.6 ตลาดมีสิ่งวางขวางตลอดแนวเดินไม่ได้<br>
            6.7 ผ่านคลองซึ่งมีขนาดกว้างเดินไม่ได้
        </td>
        <td>มีอุปสรรคในการติดตั้ง</td>
        <td> ไม่มีเสารองรับ แจ้งลูกค้าคุณ xx  แจ้ง zone คุณ xxx<br>
              ติดแนวกันสาดไม่มีแนวในการเดิน และหรือข้างบ้านไม่ให้เดินผ่าน แจ้งลูกค้า xx   แจ้ง Zone คุณ xx <br><br>
              มีต้นไม้เล็กใหญ่ตลอดทั้งแนวแจ้งลูกค้าคุณ xx  แจ้ง zone<br>
              แนวสายที่ผ่านหม้อแปลงไม่มีแนวในการเดินแจ้งลูกค้าคุณ xx  แจ้ง zone คุณ xx<br>
              ตลาดมีสิ่งวางขวางตลอดแนวแจ้งลูกค้าคุณ xx  แจ้ง Zone คุณ xx<br>
              ผ่านคลองซึ่งมีขนาดกว้าง แจ้งลูกค้าคุณ xx  แจ้ง Zone คณ xx<br>
        </td>
      </tr>

      <tr>
        <td>7.</td>
        <td>7.1 Test ก่อนการติดตั้งค่าสัญญาณไม่ได้มาตราฐาน  </td>
        <td>ค่าสัญญาณไม่ได้มาตรฐาน Test ก่อนการติดตั้ง</td>
        <td>ติค่าสัญญาณไม่ได้มาตฐานแจ้งลูกค้าคุณ xx    แจ้ง Zone คุณxx</td>
      </tr>
      <tr>

        <td>8.</td>
        <td>   - สร้างซ้ำ<br>
           -  Package ไม่ตรงกับที่ขอ
        </td>
        <td>ปัญหาการสร้าง Order</td>
        <td>ติลูกค้าคุณ xx  แจ้ง : ขอเปลี่ยน Package เป็น xx แจ้ง Sale คุณxx<br>
            สร้าง orde ซ้ำกับ Cirucit xxx แจ้ง Sale คุณxx
        </td>
      </tr>

      <tr>
        <td>9.</td>
        <td>    - อาคารไม่อนุญาต<br>  
          - รอขอนุญาตนิติก่อน
        </td>
        <td>เจ้าของพื้นที่ไม่อนุญาติให้ติดตั้ง</td>
        <td>เจ้าของที่คุณ xx ไม่อนุญาตให้ติดตั้ง แจ้งลูกค้าคุณ xx   แจ้ง zone คุณxx</td>
      </tr>

      <tr>
        <td>10.</td>
        <td>ฝนตก (หน้างานฝนต้องตกจริงเท่านั้น ณ บ้านลูกค้า)</td>
        <td>ฝนตก</td>
        <td>ฝนตก แจ้งลูกค้าคุณ xx  แจ้ง zone คุณ xx</td>
      </tr>

       <tr>
        <td>11.</td>
        <td>Code ยังไม่ได้มีการประกาศใช้แต่เป็นการขึ้นเตรียมไว้ หากอนาคตต้องให้ผู้รับเหมาไปเก็บเงินหน้างาน</td>
        <td>ลูกค้าไม่พร้อมชำระเงิน</td>
        <td>ไม่ได้ให้ใช้</td>
      </tr>

       <tr>
        <td>12.</td>
        <td>Code นี้เดิมกำหนดขึ้นในช่วงที่จะให้เดินสายรอไว้ด้านนอก ยังไม่ต้องเข้าบ้านลูกค้า สำหรับงาน Migration  แต่ได้ยกเลิกการให้เดินสายรอไว้ไปก่อนหน้านี้แล้ว</td>
        <td>เดินสายรอ</td>
        <td>ไม่ได้ให้ใช้</td>
      </tr>
      
    </tbody>
  </table>
  -->
  <table class="table table-striped">
    <tbody>
      <tr>
        <td>
          <span class="badge">1.</span> <b>กรณีลูกค้าขอเลื่อนนัดหมาย</b><br>
            <span class="label label-danger">ปัญหาที่พบ</span><br>&nbsp;&nbsp;&nbsp;&nbsp;
            1.1  ลูกค้าไม่สะดวกให้เข้าติดตั้ง<br>&nbsp;&nbsp;&nbsp;&nbsp;
            1.2 ลูกค้าอยู่ต่างจังหวัด<br>&nbsp;&nbsp;&nbsp;&nbsp;
            1.3 ลูกค้าจะไปต่างประเทศ<br>&nbsp;&nbsp;&nbsp;&nbsp;
            1.4  ลูกค้าขอตัดสินใจรอการตัดสินใจ<br>&nbsp;&nbsp;&nbsp;&nbsp;
            1.5 ลูกค้ายังไม่พร้อมเปลี่ยนระบบ<br>
            <span class="label label-success">ตัวอย่างการ Remark ใน FOA</span>
              <ul class="list-unstyled">
                <ul>
                    <li><span id="p1">ติดต่อลูกค้าก่อนวันนัดหมาย : ลูกค้าคุณ xx  Tel ติดต่อ xx ไม่สะดวกเนื่องจาก xxx  code คืนงานจาก IDC xxxxxxx</span> <button type="button" class="btn btn-info btn-xs" onclick="copyToClipboard('#p1')">คลิกเพื่อคัดลอก</button></li>

                </ul>
             </ul>
        </td>
      </tr>

      <tr>
        <td>
          <span class="badge">2.</span> <b>ติดต่อลูกค้าไม่ได้</b><br>
            <span class="label label-danger">ปัญหาที่พบ</span><br>&nbsp;&nbsp;&nbsp;&nbsp;
              2.1 เบอร์ติดต่อลูกค้าผิด <br>&nbsp;&nbsp;&nbsp;&nbsp;
              2.2 ไม่มีเบอร์ติดต่อลูกค้า<br>&nbsp;&nbsp;&nbsp;&nbsp;
              2.3 โทรหาลูกค้า สายว่างไม่รับ และหรือเข้าบริการรับฝากข้อความ<br>&nbsp;&nbsp;&nbsp;&nbsp;
              2.4 โทรลูกค้าเบอร์ติดต่อไม่มีคนรู้จัก<br>
            <span class="label label-success">ตัวอย่างการ Remark ใน FOA</span>
              <ul class="list-unstyled">
                <ul>
                    <li><span id="p2">ติดต่อลูกค้า  ลูกค้าคุณ xx  Tel ติดต่อ xx   ผลการติดต่อ xxx</span> <button type="button" class="btn btn-info btn-xs" onclick="copyToClipboard('#p2')">คลิกเพื่อคัดลอก</button></li>
                    <li><span id="p3">ส่ง SMS แจ้งลุกค้าที่เบอร์ xx  code คืนงานจาก IDC  xxxx</span> <button type="button" class="btn btn-info btn-xs" onclick="copyToClipboard('#p3')">คลิกเพื่อคัดลอก</button></li>
                    <li><span id="p4">ติดต่อ sale  : คุณ xxxx  Tel ผลการติดต่อ Sale  ติดไม่ได้ xx</span> <button type="button" class="btn btn-info btn-xs" onclick="copyToClipboard('#p4')">คลิกเพื่อคัดลอก</button></li>
                </ul>
             </ul>
        </td>
      </tr>

      <tr>
        <td>
          <span class="badge">3.</span> <b>ลูกค้าขอยกเลิก</b><br>
            <span class="label label-danger">ปัญหาที่พบ</span><br>&nbsp;&nbsp;&nbsp;&nbsp;
              3.1 ลูกค้าไม่ได้อยู่บ้านนี้แล้ว  ย้ายไปแล้ว<br>&nbsp;&nbsp;&nbsp;&nbsp;
              3.2   คนในครอบครัวไม่ให้ติดตั้ง<br>&nbsp;&nbsp;&nbsp;&nbsp;
              3.3 ลูกค้าจะย้ายไปอยู่ต่างจังหวัด<br>&nbsp;&nbsp;&nbsp;&nbsp;
              3.4 ช่างไม่เข้าตามนัดหมาย<br>&nbsp;&nbsp;&nbsp;&nbsp;
              3.5  ลูกค้าติดตั้งของค่ายอื่นไปแล้ว<br>&nbsp;&nbsp;&nbsp;&nbsp;
              3.6  ลูกค้าแจ้งหมดความจำเป็นในการใช้งาน<br>&nbsp;&nbsp;&nbsp;&nbsp;
              3.7  ลูกค้าแจ้งติดสัญญากับผู้ให้บริการรายอื่น<br>&nbsp;&nbsp;&nbsp;&nbsp;
              3.8 ลูกค้าแจ้งต้องการประหยัดค่าใช้จ่าย<br>&nbsp;&nbsp;&nbsp;&nbsp;
              3.9 ลูกค้าแจ้งยังไม่ให้ติดตั้งเพราะเปลี่ยนชื่อผู้ขอ <br>&nbsp;&nbsp;&nbsp;&nbsp;
              3.10 ลูกค้าแจ้งไม่ได้ขอบริการ<br>&nbsp;&nbsp;&nbsp;&nbsp;
              3.11 ลูกค้าไม่ต้องการติดตั้งแล้ว <br>&nbsp;&nbsp;&nbsp;&nbsp;
              3.12  ลูกค้าย้ายบ้านและหรือย้ายไปอยู่ต่างจังหวัด<br>
            <span class="label label-success">ตัวอย่างการ Remark ใน FOA</span>
              <ul class="list-unstyled">
                <ul>
                    <li ><span id="p5">ลูกค้าคุณ xx  Tel ติดต่อ xx   ลูกค้าขอยกเลิก  xxx Code คืนงานจาก IDC  xxxx</span> <button type="button" class="btn btn-info btn-xs" onclick="copyToClipboard('#p5')">คลิกเพื่อคัดลอก</button></li>
                </ul>
             </ul>
                   
        </td>
      </tr>

      <tr>
        <td>
          <span class="badge">4.</span> <b>เปลี่ยนวันนัดหมาย</b><br>
            <span class="label label-danger">ปัญหาที่พบ</span><br>&nbsp;&nbsp;&nbsp;&nbsp;
              4.1  กรณี case Non standardและทำงานต่อเนื่องอันเนื่องจากวันเดิมทำแล้วไม่เสร็จต้องมาทำต่อในวันรุ่งขึ้น<br>
            <span class="label label-success">ตัวอย่างการ Remark ใน FOA</span>
              <ul class="list-unstyled">
                <ul>
                    <li ><span id="p6" > ทำงานต่อเนื่องจากวันที่ xx/xx/xx หน้างานเป็น case Non standard</span> <button type="button" class="btn btn-info btn-xs" onclick="copyToClipboard('#p6')">คลิกเพื่อคัดลอก</button></li>
                </ul>
             </ul>
        </td>
      </tr>

      <tr>
        <td>
          <span class="badge">5.</span> <b>ระยะสาย</b><br>
          <span class="label label-warning">กรณี</span> <b>ติดตั้งให้ลูกค้าได้เลย</b><br>
            <span class="label label-danger">ปัญหาที่พบ</span><br>&nbsp;&nbsp;&nbsp;&nbsp;
             5.1 New  ระยะสายเกิน  500 เมตร ไม่ต้องเก็บเงินค่าสายเกินกับลูกค้า ถึงสิ้นเดือนธันวาคม 2016  หากมีเปลี่ยนแปลงจะมีประกาศแจ้งอีกครั้ง<br>&nbsp;&nbsp;&nbsp;&nbsp;
             5.3   Migration ระยะสายเกิน 1000 เมตร ไม่ต้องเก็บเงินลูกค้า<br> 
          <span class="label label-warning">กรณี</span> <b>ทางสายเกินมากกว่ามาตรฐานที่กำหนด</b><br>
            <span class="label label-danger">ปัญหาที่พบ</span><br>&nbsp;&nbsp;&nbsp;&nbsp;
             5.2 New  ระยะสายเกินมากกว่า   500 เมตร  แจ้งปัญหา(คืนงาน)<br>&nbsp;&nbsp;&nbsp;&nbsp;
             5.4   Migration ระยะสายเกิน 1000 เมตร แจ้งปัญหา (คืนงาน)<br>
            <span class="label label-success">ตัวอย่างการ Remark ใน FOA</span>
              <ul class="list-unstyled">
                <ul>
                    <li ><span id="p7" > ทางสายเกินมากกว่าที่กำหนดระยะสาย xx เมตรแจ้งzone คุณ xxxx  รับทราบ</span> <button type="button" class="btn btn-info btn-xs" onclick="copyToClipboard('#p7')">คลิกเพื่อคัดลอก</button></li>
                </ul>
             </ul>  
        </td>
      </tr>

      <tr>
        <td>
          <span class="badge">6.</span> <b>มีอุปสรรคในการติดตั้ง</b><br>
            <span class="label label-danger">ปัญหาที่พบ</span><br>&nbsp;&nbsp;&nbsp;&nbsp;
              6.1 ข้ามถนน 4 เลนทีมีรถวิ่งตลอดเดินไม่ได้<br>&nbsp;&nbsp;&nbsp;&nbsp;
              6.2 ไม่มีเสารองรับ<br>&nbsp;&nbsp;&nbsp;&nbsp;
              6.3 ติดแนวกันสาดไม่มีแนวในการเดิน และหรือข้างบ้านไม่ให้เดินผ่าน<br>&nbsp;&nbsp;&nbsp;&nbsp;
              6.4 มีต้นไม้เล็กใหญ่ตลอดทั้งแนวเดินไม่ได้<br>&nbsp;&nbsp;&nbsp;&nbsp;
              6.5 แนวสายที่ผ่านหม้อแปลงไม่มีแนวในการเดิน<br>&nbsp;&nbsp;&nbsp;&nbsp;
              6.6  ตลาดมีสิ่งวางขวางตลอดแนวเดินไม่ได้<br>&nbsp;&nbsp;&nbsp;&nbsp;
              6.7  ผ่านคลองซึ่งมีขนาดกว้างเดินไม่ได้<br>
            <span class="label label-success">ตัวอย่างการ Remark ใน FOA</span>
              <ul class="list-unstyled">
                <ul>
                    <li><span id="p8" >ไม่มีเสารองรับ แจ้งลูกค้าคุณ xx  แจ้ง zone คุณ xxx</span> <button type="button" class="btn btn-info btn-xs" onclick="copyToClipboard('#p8')">คลิกเพื่อคัดลอก</button></li>
                    <li><span id="p9" >ติดแนวกันสาดไม่มีแนวในการเดิน และหรือข้างบ้านไม่ให้เดินผ่าน แจ้งลูกค้า xx   แจ้ง Zone คุณ xx </span> <button type="button" class="btn btn-info btn-xs" onclick="copyToClipboard('#p9')">คลิกเพื่อคัดลอก</button></li>
                    <li><span id="p11" >มีต้นไม้เล็กใหญ่ตลอดทั้งแนวแจ้งลูกค้าคุณ xx  แจ้ง zone</span> <button type="button" class="btn btn-info btn-xs" onclick="copyToClipboard('#p11')">คลิกเพื่อคัดลอก</button></li>
                    <li><span id="p12" >แนวสายที่ผ่านหม้อแปลงไม่มีแนวในการเดินแจ้งลูกค้าคุณ xx  แจ้ง zone คุณ xx</span> <button type="button" class="btn btn-info btn-xs" onclick="copyToClipboard('#p12')">คลิกเพื่อคัดลอก</button></li>
                    <li><span id="p13">ตลาดมีสิ่งวางขวางตลอดแนวแจ้งลูกค้าคุณ xx  แจ้ง Zone คุณ xx</span> <button type="button" class="btn btn-info btn-xs" onclick="copyToClipboard('#p13')">คลิกเพื่อคัดลอก</button></li>
                    <li><span id="p14"> ผ่านคลองซึ่งมีขนาดกว้าง แจ้งลูกค้าคุณ xx  แจ้ง Zone คณ xx</span> <button type="button" class="btn btn-info btn-xs" onclick="copyToClipboard('#p14')">คลิกเพื่อคัดลอก</button></li>
                </ul>
             </ul>
        </td>
      </tr>

      <tr>
        <td>
          <span class="badge">7.</span> <b>ค่าสัญญาณไม่ได้มาตรฐาน Test ก่อนการติดตั้ง</b><br>
            <span class="label label-danger">ปัญหาที่พบ</span><br>&nbsp;&nbsp;&nbsp;&nbsp;
             7.1 Test ก่อนการติดตั้งค่าสัญญาณไม่ได้มาตราฐาน <br>
            <span class="label label-success">ตัวอย่างการ Remark ใน FOA</span>
              <ul class="list-unstyled">
                <ul>
                    <li><span id="p15">ค่าสัญญาณไม่ได้มาตฐานแจ้งลูกค้าคุณ xx แจ้ง Zone คุณxx</span> <button type="button" class="btn btn-info btn-xs" onclick="copyToClipboard('#p15')">คลิกเพื่อคัดลอก</button></li>
                </ul>
             </ul>
        </td>
      </tr>

      <tr>
        <td>
          <span class="badge">8.</span> <b>ปัญหาการสร้าง Order</b><br>
            <span class="label label-danger">ปัญหาที่พบ</span><br>&nbsp;&nbsp;&nbsp;&nbsp;
               - สร้างซ้ำ<br>&nbsp;&nbsp;&nbsp;&nbsp;
               -  Package ไม่ตรงกับที่ขอ<br>
            <span class="label label-success">ตัวอย่างการ Remark ใน FOA</span>
              <ul class="list-unstyled">
                <ul>
                    <li><span id="p16">ลูกค้าคุณ xx  แจ้ง : ขอเปลี่ยน Package เป็น xx แจ้ง Sale คุณxx</span> <button type="button" class="btn btn-info btn-xs" onclick="copyToClipboard('#p16')">คลิกเพื่อคัดลอก</button></li>
                    <li><span id="p17"> สร้าง order ซ้ำกับ Cirucit xxx แจ้ง Sale คุณxx</span> <button type="button" class="btn btn-info btn-xs" onclick="copyToClipboard('#p17')">คลิกเพื่อคัดลอก</button></li>
                </ul>
             </ul>
        </td>
      </tr>

      <tr>
        <td>
          <span class="badge">9.</span> <b>เจ้าของพื้นที่ไม่อนุญาตให้ติดตั้ง</b><br>
            <span class="label label-danger">ปัญหาที่พบ</span><br>&nbsp;&nbsp;&nbsp;&nbsp;
                 - อาคารไม่อนุญาต<br>&nbsp;&nbsp;&nbsp;&nbsp;
                 - รอขออนุญาตนิติก่อน<br>
            <span class="label label-success">ตัวอย่างการ Remark ใน FOA</span>
              <ul class="list-unstyled">
                <ul>
                    <li><span id="p18">เจ้าของที่คุณ xx ไม่อนุญาตให้ติดตั้ง แจ้งลูกค้าคุณ xx   แจ้ง zone คุณxx</span> <button type="button" class="btn btn-info btn-xs" onclick="copyToClipboard('#p18')">คลิกเพื่อคัดลอก</button></li>
                </ul>
             </ul>
        </td>
      </tr>

      <tr>
        <td>
          <span class="badge">10.</span> <b>ฝนตก</b><br>
            <span class="label label-danger">ปัญหาที่พบ</span><br>&nbsp;&nbsp;&nbsp;&nbsp;
             ฝนตก (หน้างานฝนต้องตกจริงเท่านั้น ณ บ้านลูกค้า) <br>
            <span class="label label-success">ตัวอย่างการ Remark ใน FOA</span>
              <ul class="list-unstyled">
                <ul>
                    <li><span id="p21">ฝนตก แจ้งลูกค้าคุณ xx  แจ้ง zone คุณ xx</span> <button type="button" class="btn btn-info btn-xs" onclick="copyToClipboard('#p21')">คลิกเพื่อคัดลอก</button></li>
                </ul>
             </ul>
        </td>
      </tr>

      <tr>
        <td>
          <span class="badge">11.</span> <b>ลูกค้าไม่พร้อมชำระเงิน</b><br>
            <span class="label label-danger">ปัญหาที่พบ</span><br>&nbsp;&nbsp;&nbsp;&nbsp;
             Code ยังไม่ได้มีการประกาศใช้แต่เป็นการขึ้นเตรียมไว้ หากอนาคตต้องให้ผู้รับเหมาไปเก็บเงินหน้างาน<br>
            <span class="label label-success">ตัวอย่างการ Remark ใน FOA</span>
              <ul class="list-unstyled">
                <ul>
                    <li><span id="p22">ไม่ได้ให้ใช้</span> <button type="button" class="btn btn-info btn-xs" onclick="copyToClipboard('#p22')">คลิกเพื่อคัดลอก</button></li>
             </ul>
        </td>
      </tr>

      <tr>
        <td>
          <span class="badge">12.</span> <b>เดินสายรอ</b><br>
            <span class="label label-danger">ปัญหาที่พบ</span><br>&nbsp;&nbsp;&nbsp;&nbsp;
             Code นี้เดิมกำหนดขึ้นในช่วงที่จะให้เดินสายรอไว้ด้านนอก ยังไม่ต้องเข้าบ้านลูกค้า สำหรับงาน Migration  แต่ได้ยกเลิกการให้เดินสายรอไว้ไปก่อนหน้านี้แล้ว<br>
            <span class="label label-success">ตัวอย่างการ Remark ใน FOA</span>
              <ul class="list-unstyled">
                <ul>
                    <li><span id="p23">ไม่ได้ให้ใช้</span> <button type="button" class="btn btn-info btn-xs" onclick="copyToClipboard('#p23')">คลิกเพื่อคัดลอก</button></li>
             </ul>
        </td>
      </tr>
    </tbody>
  </table>
    <div class="alert alert-info" align="center">
    หากมีข้อสงสัยสอบถามติดต่อได้ <a href="foa_contact.php">คลิกที่นี่</a>
  </div>
</div>
</body>
</html>
