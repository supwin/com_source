<?php
include('cookies.php');
include('db_function/phpMySQLFunctionDatabase.php');
include("functions/function.php");
include("../com_source/config.php");

$key = $_POST['key']; 

foreach($allBranch as $i=>$v){
	$strTable = "tidnet_".$i.".jobassign";
	$strCondition = "circuit='".$key."'";
	$jb = '';
	$jb = fncSelectSingleRecord($strTable,$strCondition);
	//echo "SELECT * FROM $strTable WHERE $strCondition ";
	//var_dump($jb);
	if($jb['circuit']==$key){
		$js = array($jb['cust_name']=>$i);
		echo json_encode($js);
		break;
	}

	$strTable = "tidnet_".$i.".closedjob";
	$cj = '';
	$cj = fncSelectSingleRecord($strTable,$strCondition);
	if($cj['circuit']==$key){
		$js = array($cj['cust_name']=>$i);
		echo json_encode($js);
		break;
	}
}


