<?php

include('cookies.php');
include('db_function/phpMySQLFunctionDatabase.php');
include("functions/function.php");

$hid = $_POST['ticketno'];

mysql_query('BEGIN');

$strTable = "tidnet_common.ticket_header";
$strCommand = "ticket_status=1";
$strCondition = "hid='".$hid."' and assigned='".$_COOKIE['id_ticket']."'";
//echo "1111UPDATE $strTable SET  $strCommand WHERE $strCondition ";
fncUpdateRecord($strTable,$strCommand,$strCondition);
if(mysql_affected_rows()<=0){
	mysql_query('ROLLBACK');
	echo "UPDATE $strTable SET  $strCommand WHERE $strCondition ";
	die('<br>ไม่สามารถบันทึกรับงานได้');
}


$strTable = "tidnet_common.ticket_reply";
$strField = "hid,reply_detail,assigned_who,who_reply";
$strValue = "'".$hid."','ยืนยันรับงาน','".$_COOKIE['id_ticket']."','".$_COOKIE['id_ticket']."'";

if(!fncInsertRecord($strTable,$strField,$strValue)){
	mysql_query('ROLLBACK');
	echo "INSERT INTO $strTable ($strField) VALUES ($strValue) ";
	die('<br>ไม่สามารถบันทึก reply ticket ได้');
}



mysql_query('COMMIT');
echo "รับงานเรียบร้อย";
