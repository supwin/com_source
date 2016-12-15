
<?php

include('cookies.php');
include('namebranch.php');
include('functions/function.php');
include('db_function/phpMySQLFunctionDatabase.php');
$start = $_POST['dstart'];
$dstart = $_POST['dstart'];
$dend = $_POST['dend'];
$memo = $_POST['memo'];
$emp_id = $_COOKIE['uid'];

$strTable = "tidnet_common.holiday";
$strField = "dateholiday,branch,emp_id,memo,status";

while(($dstart<=$dend) and $round<7){
  $strSQL = "INSERT INTO ".$strTable." (".$strField.") VALUES ('".$dstart."','".$abvt."','".$emp_id."','".$memo."',0);";
  $dstart = date ("Y-m-d", strtotime("+1 day", strtotime($dstart)));
  $round++;
  $check = mysql_query($strSQL);
  if($check){
    echo '<script language="javascript">';
    echo 'if(confirm("ส่งแบบฟอร์มขออนุมัติลาหยุดเรียบร้อย\nลาหยุดวันที่ '.$start.' \nถึงวันที่ '.$dend.'\n***ให้โทรแจ้งพี่หนึ่งทราบสำหรับการลาหยุดอีกทางหนึ่งจึงจะได้รับอนุมัติหยุด")==true)
		{
			window.location = "askforholiday.php";
		}';
    echo '</script>';
}else{
    echo "false";
  }
}
