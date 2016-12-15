<?php
/*
Log file
150714 1146 : เพิ่มเติมในส่วนของ ตรวจเช็ค modem ว่าสามารถใช้กับงานที่ปิดได้หรือไม่
*/
include('cookies.php');

include("db_function/phpMySQLFunctionDatabase.php");
$sn = $_POST['sn'];
$eid = $_COOKIE['uid'];
$jtstyle = $_POST['jtstyle'];

if((strlen($sn)==6) and ((substr($sn, 0,2) == '30') or (substr($sn, 0,2) == '99')))   {
	$strTable = "employee";
	$strCondition = "code='".$sn."' and id='".$eid."' and permission='4' and status='1'";
	$nameEng = fncSelectSingleRecord($strTable,$strCondition);
	echo $nameEng['name'];
	//echo "SELECT * FROM $strTable WHERE $strCondition ";
	die();
}

$strTable = "eqm_sn,eqm_model";
$strCondition = "eqm_sn.id_eqm=eqm_model.id and responcible=$eid and sn='".$sn."'";
$stkList = fncSelectConditionRecord($strTable,$strCondition);
$snInTable = mysql_fetch_array($stkList);
//echo "SELECT * FROM $strTable WHERE $strCondition  $strSort";

if($snInTable['forjob']<>$jtstyle and $snInTable['forjob']<>''){
	die('2'); //modem ไม่ตรงกับงาน
}
if(($snInTable['sn']!='') and (isset($snInTable['sn']))){
	echo '1';
}else{
	echo '0';
}
?>
