<?php
include('cookies.php');
include('functions/function.php');
include('headmenu.php');

    
  mysql_select_db("tidnet_".$abvt);


    if(isset($_POST['btn-update'])){
    $name = $_POST['name'];
    $nickname = $_POST['nickname'];
    $citizenid = $_POST['citizenid'];
    $email = $_POST['email'];
    $tel = $_POST['tel'];
    $user = $_POST['user'];
    $password = $_POST['password'];
    $pic_ci = $_FILES["file_ci"]["name"];
    $pic_ac = $_FILES["file_ac"]["name"];

      $Str_file_ci = explode(".",$pic_ci);
      $namePic_ci = $citizenid.".".$Str_file_ci['1'];
      move_uploaded_file($_FILES["file_ci"]["tmp_name"],"img/citizencard/".$namePic_ci);
    
      $Str_file_ac = explode(".",$pic_ac);
      $namePic_ac = $account_number.".".$Str_file_ac['1'];
      move_uploaded_file($_FILES["file_ac"]["tmp_name"],"img/account/".$namePic_ac);

    $resultCondition = "INSERT INTO `employee` (`id`, `id_ticket`, `code`, `name`, `email`, `nickname`, `car_licence`, `car_detail`, 
      `car_no`, `tel`, `citizenid`, `user`, `password`, `permission`, `superuser`, `status`, `lat`, `lng`, `timeatgeo`,
      `sit_viewclosedjob`, `sit_importjobassign`, `sit_importclosedjob`, `sit_assignconftime`) 
       VALUES (null, 0, '', '$name', '$email', '$nickname', '', '', '', '$tel', '$citizenid', '$user', '$password', '3', 0, '1', 0.000000, 0.000000, '0000-00-00 00:00:00', 1, 1, 1, 1);";
       
	$result = mysql_query($resultCondition);
	if($result){
  ?>
    <script>
        alert('เพิ่มข้อมูลเสร็จสิ้น');
        window.location='member.php'
    </script>
  <?php
    }
    else
    {
  ?>
  <script>
 		alert('แก้ไขผิดพลาด');
        window.location='insertAdmin.php'
    </script>
  <?php
   }
}
?>
<html lang="en">
<script> 
 function countTextJs1(){//ฟังก์ชั่นนับจำนวนตัวอักษรรวมช่องว่าง
  var txtForJs1=document.getElementById('citizenid').value;
  var countTxt=txtForJs1.length;
    document.getElementById('rs_citizenid').innerHTML=countTxt;
 }
</script> 
<body>
<div class="container">


  <h3>เพิ่มข้อมูล Admin สาขา <?php echo "$abvt"; ?></h3>
  <br>

<form id="tryitForm" method="post">
<table>
        <tr>
            <td width="250">ชื่อ-นามสกุล :</td>
            <td><input type="text" name="name" style="height:19px"></input></td>
        </tr>
        <tr>
            <td width="250">ชื่อเล่น :</td>
            <td><input type="text" name="nickname" style="height:19px"></input></td>
        </tr>
        <tr>
            <td width="250">เลขประจำตัวบัตรประชาชน :</td>
            <td><input type="text" name="citizenid" id="citizenid" style="height:19px"></td>
        </tr>
        <tr>
            <td width="250">อีเมล์ :</td>
            <td><input type="text" name="email" style="height:19px"></input></input></td>
        </tr>
        <tr>
            <td width="250">เบอร์โทรศัพท์ :</td>
            <td><input type="text" name="tel" style="height:19px"></input></input></td>
        </tr>
        <tr>
            <td width="250">เลขที่บัญชี :</td>
            <td><input type="text" name="account_number" style="height:19px"></input></input></td>
        </tr>
        <tr>
            <td width="250">ชื่อบัญชี :</td>
            <td><input type="text" name="account_name" style="height:19px"></input></td>
        </tr>
        <tr>
            <td width="250">ธนาคาร :</td>
            <td><input type="text" name="account_branch" style="height:19px"></input></td>
        </tr>
        <tr>
            <td width="250">เงินเดือน :</td>
            <td><input type="text" name="salary" style="height:19px"></input></td>
        </tr>
        <tr>
            <td width="250">รูปหน้าบัตรประชาชน :</td>
            <td><input type="file" name="file_ci" id="file_ci"></input></td>
        </tr>
        <tr>
            <td width="250">รูปหน้าสมุดชัญชี :</td>
            <td><input type="file" name="file_ac" id="file_ac"></input></td>
        </tr>
        <tr>
            <td colspan="3"><button type="submit" style="width:90px;" name="btn-update">บันทึก</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <button onclick="window.location.href='member.php'" style="width:90px;" type="button">กลับ</button></td>
        </tr>
</table>
</form>

</div>
</body>
</html>
