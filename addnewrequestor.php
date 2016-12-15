<?php
/*
Log file
040215 1238 : Just created
*/  

include('cookies.php');
include('functions/function.php');
include('db_function/phpMySQLFunctionDatabase.php');
mysql_select_db('tidnet_accounting');

$reqname = $_POST['reqname'];
$reqphone = $_POST['reqphone'];
$reqtype = $_POST['reqtype'];

$strTable = "tidnet_common.requestor";
$strField = "name,phone,type";
$strValue = "'".$reqname."','".$reqphone."','".$reqtype."'";

if(fncInsertRecord($strTable,$strField,$strValue)){
	$strCondition = "name = '".$reqname."'";
	$p = fncSelectSingleRecord($strTable,$strCondition);
	$reqJ[$p['req_id']] = array('name'=>$p['name'],'phone'=>$p['phone'],'type'=>$p['type']);
	echo json_encode($reqJ);
}
