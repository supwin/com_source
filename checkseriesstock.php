 <?php
/*
Log file
200714 2007 : just created
*/
include('cookies.php');
include("functions/function.php");
include("db_function/phpMySQLFunctionDatabase.php");
$sn = $_POST['sn'];
$eid = $_COOKIE['uid'];

$strTable = "eqm_sn";
$strCondition = "sn='".$sn."'";
$stkList = fncSelectConditionRecord($strTable,$strCondition);
$snInTable = mysql_fetch_array($stkList);
//echo "SELECT * FROM $strTable WHERE $strCondition  $strSort";

if($snInTable['responcible']==9098) die('2');

if($snInTable['responcible']==9099) die('3');

if($snInTable['responcible']>0){
	die(nameofengineer($snInTable['responcible']));
}

if($snInTable['sn']!='' and isset($snInTable['sn'])){
	$strT = "eqm_checkstock";
	$strF = "sn,checked_date,whodid";
	$strV = "'".$snInTable['sn']."',".tidnetNow().",'".$eid."'";
	fncInsertRecord($strT,$strF,$strV);
	echo '1';
}else{
	echo '0';
}
?>
