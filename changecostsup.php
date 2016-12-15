<?php
include('cookies.php');
include("db_function/phpMySQLFunctionDatabase.php");


$acccodename = $_POST['acccodename'];
$supplier = $_POST['supplier'];
$cost = $_POST['cost'];

$strTable = "supplier_cost";
$strCondition = "supplier_id='".$supplier."' and acs_codename='".$acccodename."'";
$strCommand = "cost='".$cost."'";

echo "UPDATE $strTable SET  $strCommand WHERE $strCondition ";

if(fncUpdateRecord($strTable,$strCommand,$strCondition)){
	echo "ปรับราคา code_name ".$acccodename." ของ supplier id ".$supplier." เป็นราคาใหม่เรียบร้อย";
}else{
	echo "0";
}
?>
