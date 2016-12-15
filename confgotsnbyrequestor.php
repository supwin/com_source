<?php
include('cookies.php');
include('functions/function.php');
include("db_function/phpMySQLFunctionDatabase.php");
include('namebranch.php');

$sn = $_POST['sn'];


mysql_query('BEGIN');

  $strTable = "eqm_sn";
  $strCondition = "sn='".$sn."' and requestor='".$_COOKIE['uid']."' and responcible='9092'";
  $strCommand = "oldowner='', responcible='".$_COOKIE['uid']."', requestor=''";

  //$eqmid = fncSelectSingleStarRecord("id_eqm",$strTable,$strCondition);
  $eqm = fncSelectSingleRecord($strTable,$strCondition);
  $oldOwner = $eqm['oldowner'];

  if(!fncUpdateRecord($strTable,$strCommand,$strCondition)){
    mysql_query('ROLLBACK');
    die(echoError('ยกเลิกการทำรายการ เนื่องด้วยไม่สามารถแก้ไขเจ้าของเก่าเป็นใหม่ได้ <div>UPDATE '.$strTable.' SET  '.$strCommand.' WHERE '.$strCondition.' </div>'));
  }


	$table = "eqm_trans";
	$field = "date_time,sn,model,responcible,status,id_employee_did";
	$values = tidnetNow().",'".$sn."','".$eqm['id_eqm']."','".$_COOKIE['uid']."','out2eng','".$_COOKIE['uid']."'";
	if(!fncInsertRecord($table,$field,$values)){
		mysql_query('ROLLBACK');
		die(echoError('ยกเลิกการทำรายการ เนื่องด้วยไม่สามารถ สร้างข้อมูล Transaction ได้ <div>INSERT INTO $strTable ($strField) VALUES ($strValue) </div>'));
	}

mysql_query('COMMIT');


$msg .= "<tr>
<td style=\"border:1px solid #bbbbbb;border-collapse:collapse;\" align=\"center\">".$sn."</td>
<td style=\"border:1px solid #bbbbbb;border-collapse:collapse;\" align=\"center\">".nameofengineer($_COOKIE['uid'])."</td></tr>";


$msg = "<table style=\"border:1px solid #bbbbbb;border-collapse:collapse;\">
	<tr style=\"background-color:#088A08;text-align:center;color:#ffffff;border:1px solid #bbbbbb;border-collapse:collapse;\">
	<td style=\"border:1px solid #bbbbbb;border-collapse:collapse;\">Serial</td>
	<td style=\"border:1px solid #bbbbbb;border-collapse:collapse;\">ผู้รับผิดชอบใหม่</td></tr>".$msg."</table>";



$msg = "<p style=\"font-size:12px;\">รายงานการรับโยก/โอน ".nameofengineer($_COOKIE['uid'])." จากสาขา : ".$branch."</p>".$msg;
$msg .= "<p>Remark : <span style=\"color:red;\">หากพบรายการไม่ถูกต้องให้โต้แย้งให้ได้ภายใน 3 วันนับจากวันที่รับเมล์ฉบับน้ี มิฉนั้นจะยืนยันตามนี้</span><p>";
$msg .= "<p>ดูรายละเอียดได้ที่ <a href=\"".$_SERVER['SERVER_NAME']."\">http://".$_SERVER['SERVER_NAME']."</a></p>";

$strHeader = "Content-type: text/html; charset=UTF-8\r\n"; // or UTF-8 //
$strHeader .= "From: Mr.Tidnet system<admin@tidnet.co.th>\r\n";
$strHeader .= "Reply-To: supwin@gmail.com\r\n";
$strHeader .= "cc: tidnet.true@gmail.com\r\n";
$strHeader .= "cc: supwin@gmail.com\r\n";
$strHeader .= "cc:".getmail($oldOwner)."\r\n";

@mail(getmail($_COOKIE['uid']),"โยก/โอน Modem/CATV ".$sn." ".nameofengineer($oldOwner)." >> ".nameofengineer($_COOKIE['uid']),$msg,$strHeader);


echo "1";

?>
