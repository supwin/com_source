<?php
include('cookies.php');
include('functions/function.php');
include('headmenu.php');


  mysql_select_db("tidnet_".$abvt);
  $starCome = "*";
  $strTableCome = "tidnet_common.master_employee";
  $strConditionCome = "permission = '4' and workat like '%@".$abvt."@%'";
  $lstsCome = fncSelectStarConditionRecord($starCome,$strTableCome,$strConditionCome);
?>


<html lang="en">
<div class="container">
    <script type="text/javascript">
    function edit_id(id){
        window.location='editmember.php?edit_id='+id
    }
    </script>

  <h3>รายชื่อช่างสาขา <?php echo "$abvt"; ?></h3>
  <?php if(($_COOKIE['permission']==1) or (checkAllow('sit_editemployee'))) { ?>
  <div align="center">
  <button onclick="window.location.href='insertEngineer.php'" style="width:100px;" type="button">เพิ่มข้อมูล ช่าง</button>
  <button onclick="window.location.href='insertAdmin.php'" style="width:130px;" type="button">เพิ่มข้อมูล แอดมิน</button>
  </div>
  <br>
  <?php } ?>
 <table style="background-color:white;" >
  <tr>
          <th style="background-color:#93FFE8;">ID</th>
          <th style="background-color:#93FFE8;">โค้ดช่าง</th>
          <th style="background-color:#93FFE8;">ชื่อ - สกุล</th>
          <th style="background-color:#93FFE8;">ชื่อเล่น</th>
          <th style="background-color:#93FFE8;">อีเมล์</th>
          <th style="background-color:#93FFE8;">เบอร์โทร</th>
          <th style="background-color:#FFC0CB;">หมายเลขบัตรปชช.</th>
          <th style="background-color:#FFC0CB;">ทะเบียนรถ/จังหวัด</th>
          <th style="background-color:#FFC0CB;">ยี่ห้อ-รุ่น-สีรถ</th>
          <th style="background-color:#FFC0CB;">สติกเกอร์</th>
              <?php if(($_COOKIE['permission']==1) or (checkAllow('sit_editemployee'))) {
              echo "<th style=\"background-color:#99C68E\">เลขที่บัญชี</th>";
              echo "<th style=\"background-color:#99C68E\">ชื่อบัญชี</th>";
              echo "<th style=\"background-color:#99C68E\">ธนาคาร</th>";
              //echo "<th style=\"background-color:#99C68E\">Username</th>";
              //echo "<th style=\"background-color:#99C68E\">Password</th>";
              } ?>
  </tr>
<?php while($come = mysql_fetch_array($lstsCome)){ ?>
  <tr>
          <td align="center" style="background-color:#93FFE8;"><?php  echo $come[id]; ?></td>
          <td style="background-color:#93FFE8;"><?php  echo $come['techcode_number']; ?></td>
      <?php if($_COOKIE['uid']==$come['id'] and $_COOKIE['permission']==4) { ?>
          <td align="center" style="background-color:#93FFE8;"><a href="javascript:edit_id(<?php  echo $come[0]; ?>)"><?php  echo $come['name']; ?></td>
      <?php } elseif (($_COOKIE['permission']==1) or (checkAllow('sit_editemployee'))) {?>
          <td align="center" style="background-color:#93FFE8;"><a href="javascript:edit_id(<?php  echo $come[0]; ?>)"><?php  echo $come['name']; ?></td>
      <?php } else {?>
          <td style="background-color:#93FFE8;"><?php  echo $come['name']; ?></td>
      <?php } ?>
          <td align="center" style="background-color:#93FFE8;"><?php  echo $come['nickname']; ?></td>
      <?php
          $check_1 = strpos($come['email'],",");
          if($check_1>0){
            $email = explode(",",$come['email']); ?>
            <td style="background-color:#93FFE8;"><?php echo $email[0]; ?><br>
                <?php echo $email[1]; ?><br>
                <?php echo $email[2]; ?></td>
   <?php  } else { ?>
            <td style="background-color:#93FFE8;"><?php  echo $come['email']; ?></td>
   <?php  } ?>

    <?php
          $check_2 = strpos($come[tel],",");
          if($check_2>0){
            $tel = explode(",",$come[tel]); ?>
            <td style="background-color:#93FFE8;"><?php echo $tel[0]; ?><br>
                <?php echo $tel[1]; ?><br>
                <?php echo $tel[2]; ?></td>
   <?php  } else { ?>
            <td style="background-color:#93FFE8;"><?php  echo $come[tel]; ?></td>
   <?php  } ?>
          <td style="background-color:#FFC0CB;"><?php  echo $come['citizenid']; ?></td>
          <td style="background-color:#FFC0CB;"><?php  echo $come['car_licence']; ?></td>
          <td style="background-color:#FFC0CB;"><?php  echo $come['car_detail']; ?></td>
          <td style="background-color:#FFC0CB;"><?php  echo $come['car_no']; ?></td>
              <?php if(($_COOKIE['permission']==1) or (checkAllow('sit_editemployee'))) { ?>
                      <td style="background-color:#99C68E;"><?php  echo $come['account_number']; ?></td>
                      <td style="background-color:#99C68E;"><?php  echo $come['account_name']; ?></td>
                      <td style="background-color:#99C68E;"><?php  echo $come['account_branch']; ?></td>
                      <?php /*
                      <td style="background-color:#99C68E;"><?php  echo $come[user]; ?></td>
                      <td style="background-color:#99C68E;"><?php  echo $come[password]; ?></td>
                      */?>
              <?php } ?>

  </tr>
<?php } ?>
</table>
</div>

  <?php  $strConditionCome = "permission = '3'";
  $lstsEmp = fncSelectStarConditionRecord($starCome,$strTableCome,$strConditionCome);
  if(($_COOKIE['permission']==1) or (checkAllow('sit_editemployee'))) { ?>
  <br><br>
 <h3>รายชื่อพนักงานสาขา <?php echo "$abvt"; ?></h3>

<div id ="emp">
   <table style="background-color:white;" >
  <tr>
          <th style="background-color:#FFF5EE;">ID</th>
          <th style="background-color:#FFF5EE;">ชื่อ - สกุล</th>
          <th style="background-color:#FFF5EE;">ชื่อเล่น</th>
          <th style="background-color:#FFF5EE;">อีเมล์</th>
          <th style="background-color:#FFF0F5;">เบอร์โทร</th>
          <th style="background-color:#FFF0F5;">หมายเลขบัตรปชช.</th>
          <th style="background-color:#F0FFF0;">เลขที่บัญชี</th>
          <th style="background-color:#F0FFF0;">ชื่อบัญชี</th>
          <th style="background-color:#F0FFF0;">สาขาบัญชี</th>
          <th style="background-color:#F0FFF0;">เงินเดือน</th>

  </tr>
  <?php while($emp = mysql_fetch_array($lstsEmp)){ ?>
  <tr>
          <td align="center" style="background-color:#FFF5EE;"><?php  echo $emp[id]; ?></td>
          <td align="center" style="background-color:#FFF5EE;"><a href="javascript:edit_id(<?php  echo $emp['id']; ?>)"><?php  echo $emp['name']; ?></td>
          <td style="background-color:#FFF5EE;"><?php  echo $emp['nickname']; ?></td>
          <td style="background-color:#FFF5EE;"><?php  echo $emp['email']; ?></td>
          <td style="background-color:#FFF0F5;"><?php  echo $emp['tel']; ?></td>
          <td style="background-color:#FFF0F5;"><?php  echo $emp['citizenid']; ?></td>
          <td style="background-color:#F0FFF0;"><?php  echo $emp['account_number']; ?></td>
          <td style="background-color:#F0FFF0;"><?php  echo $emp['account_name']; ?></td>
          <td style="background-color:#F0FFF0;"><?php  echo $emp['account_branch']; ?></td>
          <td style="background-color:#F0FFF0;"><?php  echo $emp['salary']; ?></td>
  </tr>
  <?php } ?>
  </table>
</div>
<?php } ?>
</html>
