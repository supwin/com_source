<?php
/*
Log file
290914 1417 : just created
290914 2123 : เพิ่ม ชื่อย่อสาขาให้กับหมายเลข PO
071014 1446 : ทำให้สามารถรับของบางส่วนได้ถูกต้อง
030215 1404 : ทำให้คำนวณยอดเงิน cost ตอนเอาของเข้าด้วย
050315 0849 : ทำให้ไม่รับบันทึกรายการที่ qty เป็น 0
*/

include('cookies.php');
include('functions/function.php');
include("headmenu.php");
if(!checkAllow('sit_gotpo')){
	die('คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
}


mysql_query('BEGIN');

$pono = $_POST['pono'];
$gotValue = $_POST['gotValue'];
if($_POST['memo']==''){
	$memo = "...";
}else{
	$memo = $_POST['memo'];
}
//echo "pono = ".$pono."<br>gotValue = ".$gotValue."<br>";

$poNoTXT = $pono;
while(strlen($poNoTXT)<4){  // ทำหมายเลข po
	$poNoTXT = "0".$poNoTXT;
}
$msg = "<table border=\"1\">
			<tr style=\"background-color:pink\">
				<td>รายการ</td>
				<td>จำนวนก่อนหน้า</td>
				<td>จำนวนรับเข้า</td>
				<td>จำนวนปัจจุบัน</td>
			</tr>";

$strTblUpdate = "po_detail";

$strTblInsertacstrans = "stock_acs_trans";
$strFieldInsertacstrans = "date_time,name_billno,id_acs,qty,transition_type,id_employee_did,totalqty,cost";

$strTablestockacs = "stock_acs";
$poTXT = strtoupper($abvt)."_PO-No.".$poNoTXT;
foreach($_POST as $itemId=>$qty){
	if($itemId <> 'pono' and $itemId <> 'gotValue' and $qty<>0){
		$strComUpdate = "gotqty=gotqty+".$qty;
		$strCondUpdate = "poheaderno='".$pono."' and item_id='".$itemId."'";
		//echo '1 update '.$strTblUpdate.' ได้   "UPDATE '.$strTblUpdate.' SET  '.$strComUpdate.' WHERE '.$strCondUpdate.' <br>"';
		if(!fncUpdateRecord($strTblUpdate,$strComUpdate,$strCondUpdate)){
			mysql_query('ROLLBACK');
			die('ไม่สามารถ update '.$strTblUpdate.' ได้   "UPDATE '.$strTblUpdate.' SET  '.$strComUpdate.' WHERE '.$strCondUpdate.' "');
		}
		$prvqty = getqtyitem($itemId);
		$newqty = $prvqty+$qty;
		$strValueInsertacstrans =  tidnetNow().",'".$poTXT."','".$itemId."','".$qty."','in','".$_COOKIE[uid]."','".$newqty."',(select cost from stock_acs where id='".$itemId."')*$qty";
		//echo '2 insert '.$strTblInsertacstrans.' ได้   INSERT INTO '.$strTblInsertacstrans.' ('.$strFieldInsertacstrans.') VALUES ('.$strValueInsertacstrans.') <br>';
		if(!fncInsertRecord($strTblInsertacstrans,$strFieldInsertacstrans,$strValueInsertacstrans)){
			mysql_query('ROLLBACK');
			die('ไม่สามารถ insert '.$strTblInsertacstrans.' ได้   "INSERT INTO '.$strTblInsertacstrans.' ('.$strFieldInsertacstrans.') VALUES ('.$strValueInsertacstrans.') "');
		}

		$strCommandstockacs = "qty='".$newqty."',date_update=".tidnetNow();
		$strConditionstockacs = "id='".$itemId."'";
		//echo '3 update '.$strTablestockacs.' ได้   "UPDATE '.$strTablestockacs.' SET  '.$strCommandstockacs.' WHERE '.$strConditionstockacs.' "<br>';
		if(!fncUpdateRecord($strTablestockacs,$strCommandstockacs,$strConditionstockacs)){
			mysql_query('ROLLBACK');
			die('ไม่สามารถ update '.$strTablestockacs.' ได้   "UPDATE '.$strTablestockacs.' SET  '.$strCommandstockacs.' WHERE '.$strConditionstockacs.' "');
		}

		$msg .="<tr>
				<td>".getItemName($itemId)."</td>
				<td style=\"text-align:right\">".$prvqty."</td>
				<td style=\"text-align:right\">".$qty."</td>
				<td style=\"text-align:right\">".$newqty."</td>
			</tr>";
	}
}
$msg .= "</table><br>";

$msg .= "บันทึกช่วยจำ : ".$memo;

$strTable = "po_detail";
$strCondition = "poheaderno='".$pono."' and qty>gotqty";

$got = 'got';
if(fncCountRow($strTable,$strCondition)) $got = 'gotsome';

$strTable = "po_header";
$strCommand = "status='".$got."',gotdate=".tidnetNow();//$newqty."',date_update=".tidnetNow();
$strCondition = "pono='".$pono."'";
if(!fncUpdateRecord($strTable,$strCommand,$strCondition)){
	mysql_query('ROLLBACK');
	die('ไม่สามารถ update '.$strTable.' ได้   "UPDATE '.$strTable.' SET  '.$strCommand.' WHERE '.$strCondition.' "');
}

$strTableMemo = "po_getmemo";
$strFieldMemo = "datetime,pono,memo,whodid";
$strValueMemo = tidnetNow().",'".$pono."','".$memo."','".$_COOKIE['uid']."'";
if(!fncInsertRecord($strTableMemo,$strFieldMemo,$strValueMemo)) {
	mysql_query('ROLLBACK');
	die('ไม่สามารถ insert '.$strTableMemo.' ได้   INSERT INTO '.$strTableMemo.' ('.$strFieldMemo.') VALUES ('.$strValueMemo.') ');
}

if(mysql_query('COMMIT')){

	$strHeader = "Content-type: text/html; charset=UTF-8\r\n"; // or UTF-8 //
	$strHeader .= "From: Mr.Tidnet system<system@tidnet.co.th>\r\n";
	$strHeader .= "Reply-To: supwin@gmail.com\r\n";
	$strHeader .= "cc: admin@tidnet.co.th\r\n";
	$strHeader .= "cc: sukanya.jamp@gmail.com\r\n";
	$strHeader .= "cc: supwin@gmail.com\r\n";
	$strHeader .= "cc: aiyara.wina2532@gmail.com".getmaillistbysit('sit_stock_1')."\r\n";

	$msg = "มีการรับอะไหล่เข้าสต๊อกสาขา <span style=\"font-weight:900\">".$branch."</span> ด้วย ".$poTXT." ดังรายการดังต่อไปนี้".$msg;
	$msg .= "นำเข้าสต๊อกโดย ".nameofengineer($_COOKIE['uid']);
	$msg .= "<br>ท่านได้รับเมล์ฉบับนี้ ด้วยท่านมีความจำเป็นต้องรับทราบจำนวนสต๊อกเหล่านี้ <br><span style=\"color:red\">กรุณาตรวจสอบ หากมีความไม่ถูกต้อง ให้ทักท้วงทันที</span>";

	$mailto = getmail($_COOKIE['uid']);
	$mailsit = getmaillistbysit('sit_gotpo');
	if($mailist<>'') $mailto .= ",".$mailsit;

	@mail($mailto,"รายงานการนำอะไหล่เข้าสต๊อกสาขา ".$branch,$msg,$strHeader);
	echo $mailto."<br>";
	echo $msg;
	echo "<br><br><a href=\"po.php\">กลับหน้า PO</a>";
}
?>
<spcript type="javascript">
	window.location.href = "po.php?pono=<?php echo $pono?>";
</script>
