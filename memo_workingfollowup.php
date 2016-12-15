<?php
include('cookies.php');
include('functions/function.php');
include("db_function/phpMySQLFunctionDatabase.php");

$brand = $_POST['brand'];
$jid = $_POST['jid'];
$note = $_POST['note'];
$engid = $_POST['engid'];


mysql_select_db("tidnet_".$brand);
$strTable = "workingfollowup";
$strField = "notetime, jid,	emp_id,	whodid,	note";
$strValue = "now(),'".$jid."','".$engid."','".nameofengineer($_COOKIE['uid'],1)."','".$note."'";
if(fncInsertRecord($strTable,$strField,$strValue)){
  echo "<div>".date('Y-m-d H:i')." ".$note." <span style=\"color:red;font-size:10px;\">*".nameofengineer($_COOKIE['uid'],1)." [ช่าง:".nameofengineer($engid,1)."]</span></div>";
  //echo "ok";
}else{
  echo "ไม่สามาถบันทึกข้อมูลได้ INSERT INTO ".$strTable." (".$strField.") VALUES (".$strValue.")" ;
}
?>
