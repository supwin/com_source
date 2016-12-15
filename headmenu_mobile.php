<?php
/*
Log file
250614 0816 : ได้เพิ่มสิทธิ์ในการเข้าถึงเมนู "สต๊อกขาเข้า" ให้เฉพาะสมาชิกที่มีสิทธิ์เท่านั้น
200714 1654 : เพิ่มส่วน error message แบบ sexy แล้ว
081014 1418 : เพิ่มการแสดง nofify เมื่อมี modem รอเข้าสต๊อก
101014 1716 : เพิ่มสิทธิ์ในการเข้าถึงเมนู ตรวจสอบสต๊อก
030115 1238 : เพิ่มให้ list รายชื่อพนักงานแสดง title เป็น email
030115 1253 : เพิ่มให้ list รายชื่อพนักงานและสาขาอื่นๆ จัดกลุ่มและลำดับให้เป็นกลุ่ม
*/

include("db_function/phpMySQLFunctionDatabase.php");
include("../com_source/config.php");
include('namebranch.php');
			$d = date('d');
			if(checkAllow('sit_importjobassign'))$d++;

			(isset($_GET['due'])? $_GET['due'] : $d);
			if(isset($_GET['due'])){
				$due = $_GET['due'];
				$expd = explode("-",$_GET['due']);
				$d = $expd[2];
			}else{
				$due = date('Y-m')."-".$d;
			}
			if($_GET['debug']) echo "due = ".$due;



	$table = "jobassign";
	$condition = "conf_date='".$due."' and assigned_eng='".$_COOKIE["uid"]."'";
	$start = "SELECT count(*) FROM $table WHERE $condition ";
	$query = mysql_query($start);
	$jbe = mysql_fetch_array($query);
?>
<!DOCTYPE html>
<html>
<head>


  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
   <meta name="csrf-token" content="XYZ123">
  <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="bootstrap/css/dropzone.css">
  <script src="bootstrap/js/jquery.min.js"></script>
  <script src="bootstrap/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="bootstrap/js/dropzone.js"></script>

</head>
<body>
<br>
<div class="col-sm-6" align="left">
        <p align="left" style="color:brown;font-size:14px;">หจก.ติดเน็ต <?php echo $branch; ?></p>
</div>
<div class="col-sm-6" align="right">
        <p align="right" style="font-size:14px;"><?php echo $_COOKIE['name']; ?> <span class="badge"><?php echo $jbe[0]; ?></span></p>
</div>
<div class="col-sm-12" align="center">

  <ol class="breadcrumb">
  <li><a href="index.php?orgver=1">หน้าหลัก</a></span></li>
  <li><a href="foajobassign.php">งานวันนี้</a></span></li>
  <li><a href="foa_contact.php">Contact Us</a></span></li>
  <li><a href="foa_rejreasoncode.php">Code คืนงาน</a></span></li>
</ol>

</div>

</body>
</html>
