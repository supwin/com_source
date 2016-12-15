<?php
include('cookies.php');
include("db_function/phpMySQLFunctionDatabase.php");
include('functions/function.php');

$strTable = "tidnet_common.master_employee";
$strCommand = "password='".$_POST['p']."'";
$strCondition = "id='".$_COOKIE['uid']."'";

//echo "UPDATE $strTable SET  $strCommand WHERE $strCondition ";


if(fncUpdateRecord($strTable,$strCommand,$strCondition)){
	echoSuccf('บันทึกการเปลี่ยนแปลงรหัสผ่านเรียบร้อย');
}else{
	echoError('ไม่สามารถบันทึกรหัสผ่านใหม่ได้ กรุณาติดต่อพี่หนึ่งโดยด่วน');
}
