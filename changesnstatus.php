<?php
include('cookies.php');
include("db_function/phpMySQLFunctionDatabase.php");
include("functions/function.php");


if(!checkAllow('sit_changesnstatus')){
	die('คุณไม่มีสิทธิ์ใช้งานส่วนนี้');
}

$sn = $_POST['sn'];
$reason = $_POST['reason'];
$oldresp = $_POST['oldresp'];
$cir = $_POST['cir'];
$note = $_POST['note']." [".$oldresp."] [บันทึกโดย ".nameofengineer($_COOKIE['uid'])."]";

$strTable = "eqm_sn";
$strCondition = "sn='".$sn."'";
$query = fncSelectSingleRecord($strTable,$strCondition);
$emp_id = $query['responcible'];
if($reason=='9111'){
$strCommand = "closedcircuit='".$cir."', oldowner='".$emp_id."' , responcible='".$reason."', note='".$note."'";	
} else {
$strCommand = "closedcircuit='".$cir."', oldowner='".$emp_id."' , responcible='".$reason."' , date_movement=".tidnetNow().", note='".$note."'";	
}

//echo "update $strTable set $strCommand where $strCondition";

if(!fncUpdateRecord($strTable,$strCommand,$strCondition)){
	die('ติดขัดบางประการ ไม่สามารถตัด SN และบันทึกงานได้อย่างสมบูรณ์\nกรุณาแจ้งพี่หนึ่งโดยด่วน');
}

echo '1';
?>
