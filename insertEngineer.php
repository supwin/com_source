<?php
include('cookies.php');
include('functions/function.php');
include('headmenu.php');

    
  mysql_select_db("tidnet_".$abvt);


    if(isset($_POST['btn-update'])){
    $name = $_POST['name'];
    $nickname = $_POST['nickname'];
    $citizenid = $_POST['citizenid'];
    $code = $_POST['code'];
    $email = $_POST['email'];
    $tel = $_POST['tel'];
    $account_number = $_POST['account_number'];
    $account_name = $_POST['account_name'];    
    $account_branch = $_POST['account_branch'];
    $user = $_POST['user'];
    $password = $_POST['password'];
    $car_licence = $_POST['car_licence'];
    $car_no= $_POST['car_no'];
    $car_detail = $_POST['car_detail'];

    $pic_ci = $_FILES["file_ci"]["name"];
    $pic_ac = $_FILES["file_ac"]["name"];

      $Str_file_ci = explode(".",$pic_ci);
      $namePic_ci = $citizenid.".".$Str_file_ci['1'];
      move_uploaded_file($_FILES["file_ci"]["tmp_name"],"img/citizencard/".$namePic_ci);
    
      $Str_file_ac = explode(".",$pic_ac);
      $namePic_ac = $account_number.".".$Str_file_ac['1'];
      move_uploaded_file($_FILES["file_ac"]["tmp_name"],"img/account/".$namePic_ac);

    $resultCondition = "INSERT INTO `employee` (`id`, `id_ticket`, `code`, `name`, `email`, `nickname`, `car_licence`, `car_detail`, 
      `car_no`, `tel`, `citizenid`, `account_number`, `account_name`, `account_branch`, `user`, `password`, `permission`, `superuser`, `status`, `lat`, `lng`, `timeatgeo`, `sit_viewclosedjob`) 
      VALUES (null, 0, '$code', '$name', '$email', '$nickname', '$car_licence', '$car_detail', '$car_no', '$tel', '$citizenid', '$account_number', '$account_name', '$account_branch', '$user', '$password', '4', 0, '1', 0.000000, 0.000000, '0000-00-00 00:00:00', 1);";
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
        window.location='insertEngineer.php'
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
function myFunction() {
    var str = document.getElementById("citizenid").value;
    $.ajax({
       type: "POST",
       url: "setcitizenid.php",
       cache: false,
       data: "str="+str,
       success: function(msg){
        document.getElementById('demo').innerHTML=msg;
       }
   });
}
</script> 
<body>
<div class="container" align="center">


  <h3>เพิ่มข้อมูล ช่าง สาขา <?php echo "$abvt"; ?></h3>
  <br>

<form id="tryitForm" method="post" enctype="multipart/form-data">
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
            <td><input type="text" name="citizenid" id="citizenid" style="height:19px" onkeyup="countTextJs1()" /></td>
        </tr>
        <tr>
            <td width="250">Code :</td>
            <td>
                <input type="text" name="code" style="height:19px" size="10"></input>&nbsp;&nbsp;|&nbsp;&nbsp;
                <span id="rs_citizenid" style="color:red"> 0 </span></input>&nbsp;&nbsp;
                <span class="button" onclick="myFunction()">ขอ Code</span>&nbsp;&nbsp;<a id="demo" style="color:green" ></a></td>
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
            <td width="250">Username :</td>
            <td><input type="text" name="user" style="height:19px"></input></td>
        </tr>
        <tr>
            <td width="250">Password :</td>
            <td><input type="text" name="password" style="height:19px"></input></td>
        </tr>
        <tr>
            <td width="250">ทะเบียนรถ จังหวัด :</td>
            <td><input type="text" name="car_licence" style="height:19px" size="15"></input></td>
        </tr>
        <tr>
            <td width="250">รหัสสติ๊กเกอร์ติดรถ :</td>
            <td><input type="text" name="car_no" style="height:19px" size="10"></input></td>
        </tr>
        <tr>
            <td width="250">ยี่ห้อ รุ่น สี ของรถ :</td>
            <td><input type="text" name="car_detail" style="height:19px" size="30"></input></td>
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
            <td colspan="3" align="center"><button type="submit" style="width:90px;" name="btn-update">บันทึก</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <button onclick="window.location.href='member.php'" style="width:90px;" type="button">กลับ</button></td>
        </tr >
</table>
</form>

</div>
</body>
</html>
