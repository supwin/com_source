<?php
include('cookies.php');
include("db_function/phpMySQLFunctionDatabase.php");

$txt = $_POST['txt'];

$strTable = "jobassign";
$strCondition = "jobname='CATV' and circuit = '".$txt."'";

echo $txt;

$wk = fncSelectSingleRecord($strTable,$strCondition);
echo "SELECT * FROM $strTable WHERE $strCondition ";
var_dump($wk);

if($wk['circuit']==$txt){
	echo $wk['cust_name'];
}else{
	echo "0";
}
