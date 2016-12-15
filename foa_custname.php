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
$output = $stkList['circuit']."<br>".$stkList['cust_name'];
echo $output;

?>
