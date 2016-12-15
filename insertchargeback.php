<?php

include('cookies.php');
include('functions/function.php');
include('db_function/phpMySQLFunctionDatabase.php');

if($_POST['submitbut']<>'' and checkAllow('sit_insertchargeback')){

	$jobname = $_POST['jname'];
	$description = $_POST['description'];
	$circuit = $_POST['circuit'];
	$cbt = $_POST['cbt'];
	$chargedby = $_POST['chargedby'];
	$emp = $_POST['emp'];
	$cost = $_POST['cost'];
	$status = $_POST['status'];

  $strTable = "tidnet_common.chargebackjob_header";
	$strField = "jobname,createddatetime,circuit,complaintype,description,chgbck_from,chgbaked_who,cost,status,who_key";
	$strValue = "'".$jobname."',".tidnetNow().",'".$circuit."','".$cbt."','".$description."','".$chargedby."','".$emp."','".$cost."','".$status."','".$_COOKIE['uid']."'";
	if(!fncInsertRecord($strTable,$strField,$strValue)){
    echo "INSERT INTO $strTable ($strField) VALUES ($strValue) ";
    die('ไม่สามารถบันทึก charge back ได้1111');
  }else{
    ?>
      <script>
      window.location = "chargeback.php";
      </script>
    <?php
  }
	//echo "INSERT INTO $strTable ($strField) VALUES ($strValue) ";
}


?>
