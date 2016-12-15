<?php
/*
Log file
150714 1100 : ออกแบบมาเพื่อ ทำ list สต๊อกให้แสดงเพื่อตัดสต๊อกงาน
*/
include('cookies.php');
include("db_function/phpMySQLFunctionDatabase.php");
include("functions/function.php");

$jjid = $_POST['jjid'];
$eid = $_COOKIE['uid'];

$strTable = "jobassign";
$strCondition = "jid = '".$jjid."'";
$stkList = fncSelectSingleRecord($strTable,$strCondition);
$workaction = $stkList['work_action'];
$ordertype = $stkList['SO_CCSS_ORDER_TYPE'];
$chgaddflg = $stkList['SO_CHG_ADDR_FLG'];
$catvflg = $stkList['CATV_FLG'];
$jobname = $stkList['jobname'];
$doctype = $stkList['sodoctype'];
$bundle = $stkList['bundle'];

	if($workaction=='F'){
		$typejob = "Dis";
	}else if($workaction=='T' and $ordertype=='I' and 	$chgaddflg=='N'){
		$typejob = "Net";
		if($catvflg=='Y'){
			$typejob .= "+TV";
		}
	}else if($workaction=='T' and $ordertype=='C' and 	$chgaddflg=='N'){
		$typejob = "Chg Mod";
	}else if($workaction=='T' and $ordertype=='C' and 	$chgaddflg=='Y'){
		$typejob = "Chg Addr";
	}else if($jobname=='FTTX' and $ordertype=='I' and   $doctype=='HSI'){
		$typejob = "FTTx";
		if($bundle <> ""){
			$typejob .= "+TV";
		}
	}else if ($jobname=='FTTX' and $ordertype=='D' and   $doctype=='HSI'){
		$typejob = "Dis FTTx";
	}else if ($jobname=='FTTX' and $ordertype=='C' and   $doctype=='FIBERTV'){
		$typejob = "Dis/New FIBERTV";
	}else if ($jobname=='FTTX' and $ordertype=='C' and   $doctype=='FLP'){
		$typejob = "Chg Add FTTx";
	}

echo $typejob;

?>
