<?php
include('cookies.php');
include('functions/function.php');
include('headmenu.php');


if(isset($_GET['edit_id'])){
  $id = $_GET['edit_id'];
  $condition = "SELECT * FROM tidnet_common.master_employee WHERE id =".$id;
  $member = mysql_query($condition);
}

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
  $salary = $_POST['salary'];

  $pic_ci = $_FILES["file_ci"]["name"];
  $pic_ac = $_FILES["file_ac"]["name"];

  $Str_file_ci = explode(".",$pic_ci);
  $namePic_ci = $citizenid.".".$Str_file_ci['1'];
  move_uploaded_file($_FILES["file_ci"]["tmp_name"],"img/citizencard/".$namePic_ci);

  $Str_file_ac = explode(".",$pic_ac);
  $namePic_ac = $account_number.".".$Str_file_ac['1'];
  move_uploaded_file($_FILES["file_ac"]["tmp_name"],"img/account/".$namePic_ac);

  $resultCondition = "UPDATE employee SET name ='$name', nickname ='$nickname', code = '$code' , email ='$email', tel ='$tel', citizenid ='$citizenid', account_number ='$account_number', account_name ='$account_name', account_branch ='$account_branch', user ='$user', password ='$password', car_licence ='$car_licence', car_no ='$car_no', car_detail ='$car_detail', salary ='$salary' WHERE id = $id";
  $result = mysql_query($resultCondition);
	if($result){
  ?>
    <script>
        window.location='editmember.php?edit_id=<?php echo $id ?>'
    </script>
  <?php
  }else{
  ?>
    <script>
    alert('แก้ไขผิดพลาด');
        window.location='editmember.php'
    </script>
  <?php
   }
}
?>
<?php
    if(isset($_POST['btn-update2'])){
    $email = $_POST['email'];
    $tel = $_POST['tel'];
    $citizenid = $_POST['citizenid'];
    $car_licence = $_POST['car_licence'];
    $car_no= $_POST['car_no'];
    $car_detail = $_POST['car_detail'];
    $resultCondition = "UPDATE employee SET email ='$email',tel ='$tel', citizenid ='$citizenid', car_licence ='$car_licence', car_no ='$car_no', car_detail ='$car_detail'
    WHERE id =$id";
	$result = mysql_query($resultCondition);
	if($result){
  ?>
    <script>
        alert('แก้ไขเสร็จสิ้น');
        window.location='member.php'
    </script>
  <?php
    }
    else
    {
  ?>
  <script>
 		alert('แก้ไขผิดพลาด');
        window.location='editmember.php'
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


  <h3>แก้ไขข้อมูลพนักงาน <?php echo "$abvt"; ?></h3>
  <br>

<?php
  while($row=mysql_fetch_array($member)){
if(($_COOKIE['permission']==1) or (checkAllow('sit_importclosedjob'))){
  if($row['permission'] ==4){
?>
<table>
<form id="tryitForm" method="post" enctype="multipart/form-data">
        <tr>
            <td width="250">ชื่อ-นามสกุล :</td>
            <td><input type="text" name="name" style="height:19px" value="<?php  echo $row['name']; ?>"></input></td>
            <td rowspan="17"><img src="img/citizencard/<?php echo $row['citizenid']; ?>.jpg" width="470"  height="280"><br>
        <img src="img/account/<?php echo $row['account_number']; ?>.jpg" width="470"  height="280"></td>
        </tr>
        <tr>
            <td width="250">ชื่อเล่น :</td>
            <td><input type="text" name="nickname" style="height:19px"  value="<?php  echo $row['nickname']; ?>"></input></td>
        </tr>
        <tr>
            <td width="250">เลขประจำตัวบัตรประชาชน :</td>
            <td><input type="text" name="citizenid" id="citizenid" style="height:19px"   value="<?php  echo $row['citizenid']; ?>" onkeyup="countTextJs1()" /></td>
        </tr>
        <tr>
            <td width="250">Code :</td>
            <td>
                <input type="text" name="code" style="height:19px" size="10" value="<?php  echo $row['code']; ?>"></input>&nbsp;&nbsp;|&nbsp;&nbsp;
                <span id="rs_citizenid" style="color:red"> 0 </span></input>&nbsp;&nbsp;
                <span class="button" onclick="myFunction()">ขอ Code</span>&nbsp;&nbsp;<a id="demo" style="color:green" ></a></td>
        </tr>
        <tr>
            <td width="250">อีเมล์ :</td>
            <td><input type="text" name="email" style="height:19px" value="<?php  echo $row['email']; ?>"></input></input></td>
        </tr>
        <tr>
            <td width="250">เบอร์โทรศัพท์ :</td>
            <td><input type="text" name="tel" style="height:19px"  value="<?php  echo $row['tel']; ?>"></input></input></td>
        </tr>
        <tr>
            <td width="250">เลขที่บัญชี :</td>
            <td><input type="text" name="account_number" style="height:19px"  value="<?php  echo $row['account_number']; ?>"></input></input></td>
        </tr>
        <tr>
            <td width="250">ชื่อบัญชี :</td>
            <td><input type="text" name="account_name" style="height:19px"  value="<?php  echo $row['account_name']; ?>"></input></td>
        </tr>
        <tr>
            <td width="250">ธนาคาร :</td>
            <td><input type="text" name="account_branch" style="height:19px" value="<?php  echo $row['account_branch']; ?>"></input></td>
        </tr>
        <tr>
            <td width="250">Username :</td>
            <td><input type="text" name="user" style="height:19px" value="<?php  echo $row['user']; ?>"></input></td>
        </tr>
        <tr>
            <td width="250">Password :</td>
            <td><input type="text" name="password" style="height:19px" value="<?php  echo $row['password']; ?>"></input></td>
        </tr>
        <tr>
            <td width="250">ทะเบียนรถ จังหวัด :</td>
            <td><input type="text" name="car_licence" style="height:19px" size="15" value="<?php  echo $row['car_licence']; ?>"></input></td>
        </tr>
        <tr>
            <td width="250">รหัสสติ๊กเกอร์ติดรถ :</td>
            <td><input type="text" name="car_no" style="height:19px" size="10" value="<?php  echo $row['car_no']; ?>"></input></td>
        </tr>
        <tr>
            <td width="250">ยี่ห้อ รุ่น สี ของรถ :</td>
            <td><input type="text" name="car_detail" style="height:19px" size="30" value="<?php  echo $row['car_detail']; ?>"></input></td>
        </tr>
        <tr>
            <td width="250">รูปหน้าบัตรประชาชน :</td>
            <td><input type="file" name="file_ci" id="file_ci"></input></td>
        </tr>
        <tr>
            <td width="250">รูปหน้าสมุดชัญชี :</td>
            <td><input type="file" name="file_ac" id="file_ac"></input></td>
        </tr><!--
        <tr>
            <td colspan="2" align="center"><button type="submit" style="width:90px;" name="btn-update">บันทึก</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <button onclick="window.location.href='member.php'" style="width:90px;" type="button">กลับ</button></td>
        </tr >-->
</table>
</form>
<?php
  }else if ($row['permission']==3){ ?>
  <table>
    <form id="tryitForm" method="post" enctype="multipart/form-data">
        <tr>
            <td width="250">ชื่อ-นามสกุล :</td>
            <td><input type="text" name="name" style="height:19px" value="<?php  echo $row['name']; ?>"></input></td>
            <td rowspan="14"><img src="img/citizencard/<?php echo $row['citizenid']; ?>.jpg" width="470"  height="280"><br>
        <img src="img/account/<?php echo $row['account_number']; ?>.jpg" width="470"  height="280"></td>
        </tr>
        <tr>
            <td width="250">ชื่อเล่น :</td>
            <td><input type="text" name="nickname" style="height:19px"  value="<?php  echo $row['nickname']; ?>"></input></td>
        </tr>
        <tr>
            <td width="250">เลขประจำตัวบัตรประชาชน :</td>
            <td><input type="text" name="citizenid" id="citizenid" style="height:19px"   value="<?php  echo $row['citizenid']; ?>"/></td>
        </tr>
        <tr>
            <td width="250">อีเมล์ :</td>
            <td><input type="text" name="email" style="height:19px" value="<?php  echo $row['email']; ?>"></input></input></td>
        </tr>
        <tr>
            <td width="250">เบอร์โทรศัพท์ :</td>
            <td><input type="text" name="tel" style="height:19px"  value="<?php  echo $row['tel']; ?>"></input></input></td>
        </tr>
        <tr>
            <td width="250">เลขที่บัญชี :</td>
            <td><input type="text" name="account_number" style="height:19px"  value="<?php  echo $row['account_number']; ?>"></input></input></td>
        </tr>
        <tr>
            <td width="250">ชื่อบัญชี :</td>
            <td><input type="text" name="account_name" style="height:19px"  value="<?php  echo $row['account_name']; ?>"></input></td>
        </tr>
        <tr>
            <td width="250">ธนาคาร :</td>
            <td><input type="text" name="account_branch" style="height:19px" value="<?php  echo $row['account_branch']; ?>"></input></td>
        </tr>
        <tr>
            <td width="250">เงินเดือน :</td>
            <td><input type="text" name="salary" style="height:19px"  value="<?php  echo $row['salary']; ?>"></input></td>
        </tr>
        <?php if($_COOKIE['permission']==1) { ?>
        <tr>
            <td width="250">Username :</td>
            <td><input type="text" name="user" style="height:19px" value="<?php  echo $row['user']; ?>"></input></td>
        </tr>
        <tr>
            <td width="250">Password :</td>
            <td><input type="text" name="password" style="height:19px" value="<?php  echo $row['password']; ?>"></input></td>
        </tr>
        <?php } ?>

        <tr>
            <td width="250">รูปหน้าบัตรประชาชน :</td>
            <td><input type="file" name="file_ci" id="file_ci"></input></td>
        </tr>
        <tr>
            <td width="250">รูปหน้าสมุดชัญชี :</td>
            <td><input type="file" name="file_ac" id="file_ac"></input></td>
        </tr><!--
        <tr>
            <td colspan="3"><button type="submit" style="width:90px;" name="btn-update">บันทึก</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <button onclick="window.location.href='member.php'" style="width:90px;" type="button">กลับ</button></td>
        </tr >-->
    </form>
  </table>
<?php
  }
}
if($_COOKIE['permission']==4){
?>
<table>
<form id="tryitForm2" method="post">
        <tr>
            <td width="250">ชื่อ-นามสกุล :</td>
            <td><input type="text" name="name" style="height:19px" value="<?php  echo $row['name']; ?>"></input></td>
            <td rowspan="14"><img src="img/citizencard/<?php echo $row['citizenid']; ?>.jpg" width="470"  height="280"><br>
        <img src="img/account/<?php echo $row['account_number']; ?>.jpg" width="470"  height="280"></td>
        </tr>
        <tr>
            <td width="250">ชื่อเล่น :</td>
            <td><input type="text" name="nickname" style="height:19px"  value="<?php  echo $row['nickname']; ?>"></input></td>
        </tr>
        <tr>
            <td width="250">เลขประจำตัวบัตรประชาชน :</td>
            <td><input type="text" name="citizenid" id="citizenid" style="height:19px"   value="<?php  echo $row['citizenid']; ?>" onkeyup="countTextJs1()" /></td>
        </tr>
        <tr>
            <td width="250">Code :</td>
            <td>
                <input type="text" name="code" style="height:19px" size="10" value="<?php  echo $row['code']; ?>"></input></td>
        </tr>
        <tr>
            <td width="250">อีเมล์ :</td>
            <td><input type="text" name="email" style="height:19px" value="<?php  echo $row['email']; ?>"></input></input></td>
        </tr>
        <tr>
            <td width="250">เบอร์โทรศัพท์ :</td>
            <td><input type="text" name="tel" style="height:19px"  value="<?php  echo $row['tel']; ?>"></input></input></td>
        </tr>
        <tr>
            <td width="250">เลขที่บัญชี :</td>
            <td><input type="text" name="account_number" style="height:19px"  value="<?php  echo $row['account_number']; ?>"></input></input></td>
        </tr>
        <tr>
            <td width="250">ชื่อบัญชี :</td>
            <td><input type="text" name="account_name" style="height:19px"  value="<?php  echo $row['account_name']; ?>"></input></td>
        </tr>
        <tr>
            <td width="250">ธนาคาร :</td>
            <td><input type="text" name="account_branch" style="height:19px" value="<?php  echo $row['account_branch']; ?>"></input></td>
        </tr>
        <tr>
            <td width="250">Username :</td>
            <td><input type="text" name="user" style="height:19px" value="<?php  echo $row['user']; ?>"></input></td>
        </tr>
        <tr>
            <td width="250">Password :</td>
            <td><input type="text" name="password" style="height:19px" value="<?php  echo $row['password']; ?>"></input></td>
        </tr>
        <tr>
            <td width="250">ทะเบียนรถ จังหวัด :</td>
            <td><input type="text" name="car_licence" style="height:19px" size="15" value="<?php  echo $row['car_licence']; ?>"></input></td>
        </tr>
        <tr>
            <td width="250">รหัสสติ๊กเกอร์ติดรถ :</td>
            <td><input type="text" name="car_no" style="height:19px" size="10" value="<?php  echo $row['car_no']; ?>"></input></td>
        </tr>
        <tr>
            <td width="250">ยี่ห้อ รุ่น สี ของรถ :</td>
            <td><input type="text" name="car_detail" style="height:19px" size="30" value="<?php  echo $row['car_detail']; ?>"></input></td>
        </tr>
        <!--
        <tr>
            <td colspan="3"><button type="submit" style="width:90px;" name="btn-update">บันทึก</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <button onclick="window.location.href='member.php'" style="width:90px;" type="button">กลับ</button></td>
        </tr >-->
</table>
</form>
<?php
}
}
?>
</div>
</body>
</html>
