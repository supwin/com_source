<?php
/*
Log file
150714 1100 : ออกแบบมาเพื่อ ทำ list สต๊อกให้แสดงเพื่อตัดสต๊อกงาน
*/
include('cookies.php');
include("db_function/phpMySQLFunctionDatabase.php");
include("functions/function.php");

$sn = $_POST['sn'];
$eid = $_COOKIE['uid'];

if($sn==''){
echo "0";
die();
} else {
	$strTable = "eqm_sn";
	$strCondition = "sn = '".$sn."'";
	$stkList = fncSelectSingleRecord($strTable,$strCondition);
	//echo "SELECT * FROM $strTable WHERE $strCondition  $strSort";
	if($stkList['sn']==$sn and $stkList['responcible']==$eid){
		echo "1";
	} else if ( $stkList['sn']==$sn and $stkList['requestor']==$eid ) {
		echo "2";
	} else {
		echo "3";
	}
}
