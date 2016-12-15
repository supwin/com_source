<?php
include('cookies.php');
include("functions/function.php");
include("headmenu.php");
 
$jid = $_GET['jid'];
$ncf = $_GET['ncf'];

mysql_query("BEGIN");

$strTable = "jobassign";
$strCondition = "jid='".$jid."'";
$strCommand = "conf_date='".$ncf."', new_confdate='0000-00-00'";
$job = fncSelectSingleRecord($strTable,$strCondition);

$strTableM = "memo_appointment";
$strField = "memo_date_time,jid,due_date,emp_id,result,return_status,memotxt,who_did";
$strValue = tidnetNow().",'".$jid."','".$ncf."','".$job['assigned_eng']."','40','0','update due จาก ".$job['conf_date']." เป็น ".$ncf." ','".$_COOKIE['uid']."'";

if(!fncInsertRecord($strTableM,$strField,$strValue)){
	mysql_query("ROLLBACK");
	die("ไม่สามารถบันทึก ประวัตินัดได้");
}

if(!fncUpdateRecord($strTable,$strCommand,$strCondition)){
	mysql_query("ROLLBACK");
	die("ไม่สามารถ update due-date ได้");
}

mysql_query("COMMIT");
?>
<script>
	window.location = "jobassign.php";
</script>
