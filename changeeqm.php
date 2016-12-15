<?php
include('cookies.php');
include("db_function/phpMySQLFunctionDatabase.php");
include('functions/function.php');


if(!checkAllow('sit_returnsnbacktostockby')){
	echo "0";
	die('');
}
$sn = $_POST['sn'];
$typereturn = $_POST['typereturn'];
$description = $_POST['description'];

if($typereturn=='1'){

	$newEng = $_POST['newEng'];

	$strTable = "eqm_trans";
	$strCondition = "sn='".$sn."' and id=(SELECT MAX(id) FROM TABLE)";
	$md = fncSelectSingleRecord($strTable,$strCondition);


	mysql_query("BEGIN");

	$strTable = "eqm_trans";
	$strField = "date_time,sn,model,responcible,status,id_employee_did";
	$strValues = "ADDTIME(now(), '00:00:00'),'".$sn."','".$md[model]."','".$newEng."','out2eng','".$_COOKIE['uid']."'";
	if(!fncInsertRecord($strTable,$strField,$strValues)) $resultQuery = false;

	if($resQuery){
		mysql_query(COMMIT);
		echo "ok";
	}else{
		mysql_query(ROLLBACK);
		echo "notok";
	}
}else{

	$lastowner = getEngFrmSN($sn);

	mysql_query("BEGIN");
	$resultQuery = true;

	$strTable = "eqm_return";
	$strField = "date,last_owner,series,type_id,description,status,whodid";
	$strValues = "ADDTIME(now(), '00:00:00'),'".$lastowner."','".$sn."','".$typereturn."','".$description."','0','".$_COOKIE['uid']."'";
	if(!fncInsertRecord($strTable,$strField,$strValues)) $resultQuery = false;


	$strTable = "eqm_sn";
	$strCommand = "responcible='9098',date_movement=ADDTIME(now(), '00:00:00'), note='".$_COOKIE['uid']." เป็นผู้ทำรายการ'";
	$strCondition = "sn = '".$sn."'";
	if(!fncUpdateRecord($strTable,$strCommand,$strCondition)) $resultQuery = false;


	if($resultQuery){
		mysql_query("COMMIT");
		echo "1";
	}else{
		mysql_query("ROLLBACK");
		echo "0";
	}

}
?>
