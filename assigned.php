<?php
/*
log file
180315 2309 : just created
*/
include('cookies.php');
include('db_function/phpMySQLFunctionDatabase.php');

$jid = $_POST['jid'];
$engid = $_POST['engid'];
$duedate = $_POST['duedate'];

$strTable = "jobassign";
$strCommand = "assigned_eng='".$engid."', due_date='".$duedate."'";
$strCondition = "jid in(".$jid.")";
echo "UPDATE $strTable SET  $strCommand WHERE $strCondition ";
fncUpdateRecord($strTable,$strCommand,$strCondition);

$typechange = 2;
$table = "jobassign";
$fields = "assigned_eng due_date";
$values = $strCommand." jid=".$jid;
logDB($typechange,$table,$fields,$values);
