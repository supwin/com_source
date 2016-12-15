<?php
/*
Log file
200614 1726 : update กรณี mysql rollback ให้ die ข้อมูล query ออกมาด้วย
280614 1719 : เพิ่ม http:// ใน link ที่ส่งไปกับเมล์
*/

include("cookies.php");
include('functions/function.php');
include("headmenu.php");

?>
  ยินดีต้อนรับ <?php echo $_COOKIE['name'];?>
<?php
$emp = $_POST['emp'];

$branch = fncSelectSingleRecord("tidnet_common.master_employee","id='".$emp."'");
//echo "<br> branch = ";
//var_dump($branch);
include("objform.php");

$strTable = "eqm_billheader";
$strField = "date_time,employee_id,employee_did";
$strValue = "ADDTIME(now(), '14:00:00'),'$emp','$_COOKIE[uid]'";
mysql_query("BEGIN");

$resultQuery = true;

if(!fncInsertRecord($strTable,$strField,$strValue)){
	mysql_query("ROLLBACK");
	die(echoError("ยกเลิกการบันทึกเนื่องจาก ไม่สามารถออกหัวใบเบิก modem ใน db ได้"));
}
// get max id of header bill
$strSQL = "SELECT max(id) FROM $strTable";
$objQuery = @mysql_query($strSQL);
$resultmax = @mysql_fetch_array($objQuery);
$lastId = $resultmax[0];
// get max id of header bill
$modelId = $_POST['modelId'];
$gsn = $_POST['gsn'];
$gsntotal = count($_POST['gsn']);
$detailId = 1;


$mrow = 1;
$typemovement = $_POST['typemovement'];
for($m=0; $m<$gsntotal; $m++){
	$sn = $gsn[$m];
	//$snMail .=" [".$sn."]";
	$modelId = getModemId($gsn[$m]);
	// create transaction
	$strTable = "eqm_trans";
	$strField = "date_time,sn,model,responcible,status,id_employee_did";
	$strValue = tidnetNow().",'".$sn."','".$modelId."','".$emp."','".$typemovement."','".$_COOKIE[uid]."'";
	//echo "<div>1.INSERT INTO $strTable ($strField) VALUES ($strValue)  </div>";
	if(!fncInsertRecord($strTable,$strField,$strValue)){
		mysql_query("ROLLBACK");
		die(echoError("ยกเลิกการบันทึกเนื่องจาก สร้าง transaction ของ modem ใน eqm_trans ไม่ได้ <div>INSERT INTO $strTable ($strField) VALUES ($strValue)  </div>"));
	}

	$strTable = "eqm_billdetail";
	$strField = "head_id,id,sn";
	$strValue = "$lastId,$detailId,'$sn'";
	//echo "<div>2.INSERT INTO $strTable ($strField) VALUES ($strValue)  </div>";
	if(!fncInsertRecord($strTable,$strField,$strValue)){
		mysql_query("ROLLBACK");
		die(echoError("ยกเลิกการบันทึกเนื่องจาก สร้างรายการใน eqm_billdetail ไม่ได้"));
	}
	$strTable = "eqm_sn,eqm_model";
	$strCommand = "eqm_sn.responcible=$emp, eqm_sn.date_firstmovement=".tidnetNow().",  eqm_sn.date_movement=".tidnetNow().", eqm_model.qty = eqm_model.qty-1, eqm_model.date_update=".tidnetNow();
	$strCondition = "eqm_sn.sn='$sn' and eqm_sn.id_eqm=eqm_model.id";
	//echo "<div>UPDATE $strTable SET  $strCommand WHERE $strCondition</div>";
	if(!fncUpdateRecord($strTable,$strCommand,$strCondition)){
		mysql_query("ROLLBACK");
		die(echoError("ยกเลิกการบันทึกเนื่องจาก ปรับลดจำนวนหลังการเบิกใน eqm_model ไม่ได้<div>UPDATE $strTable SET  $strCommand WHERE $strCondition</div>"));
	}
	$detailId +=1;


	//-- ทำ msg เพื่อส่งเมล์-----//
	$msg .= "	<tr>
			<td style=\"border:1px solid #bbbbbb;border-collapse:collapse;\" align=\"center\">".$mrow."</td>
			<td style=\"border:1px solid #bbbbbb;border-collapse:collapse;text-align:right\">".$sn."</td>
			</tr>";
	//-- ทำ msg เพื่อส่งเมล์-----//

	$mrow +=1;


	if($branch['permission']=='20'){  // ถ้าเป็นการโยกอุปกรณ์ข้ามสาขา a
		$mod = fncSelectSingleRecord("eqm_sn","sn='".$sn."'");
		//echo "<br> mod =";
		//var_dump($mod);


		$sql = "insert into tidnet_".$branch['techcode_number'].".serial_tmp (sn,mac,model_id,lot_coming,date_time,whodid) values ('".$mod['sn']."','".$mod['mac']."','".$mod['id_eqm']."','".strtoupper(constant('ABVT'))."-".$lastId."',".tidnetNow().",'".$_COOKIE['uid']."')";
		echo $sql."<br>";
		mysql_query($sql);
	}

}

if($resultQuery){
	//*** Commit Transaction ***//
	mysql_query("COMMIT");
	$strHeader = "Content-type: text/html; charset=UTF-8\r\n"; // or UTF-8 //
	$strHeader .= "From: Mr.Tidnet system<system@tidnet.co.th>\r\n";
	$strHeader .= "Reply-To: supwin@gmail.com\r\n";
	//$strHeader .= "cc: tidnet.true@gmail.com\r\n";
	$strHeader .= "cc: sukanya.jamp@gmail.com\r\n";
	$strHeader .= "cc: supwin@gmail.com\r\n";

	$msg = "<table style=\"border:1px solid #bbbbbb;border-collapse:collapse;\">
		<tr style=\"background-color:#088A08;text-align:center;color:#ffffff;border:1px solid #bbbbbb;border-collapse:collapse;\">
		<td style=\"border:1px solid #bbbbbb;border-collapse:collapse;\">ลำดับ</td>
		<td style=\"border:1px solid #bbbbbb;border-collapse:collapse;\">s/n</td>
		</tr>".$msg."</table>";
	$empnametxt = nameofengineer($emp);
	$msg = "<p style=\"font-size:12px;\">รายงานการเบิกอุปกรณ์ของ ".$empnametxt." จากสาขา : ".constant('BRANCH')."</p>".$msg;
	$msg .= "<p>Remark : <span style=\"color:red;\">หากพบยอดไม่ถูกต้องให้โต้แย้งให้ได้ภายใน 3 วันนับจากวันที่รับเมล์ฉบับนี มิฉนั้นจะยืนยันตามนี้</span><p>";
	$msg .= "<p>ดูรายละเอียดได้ที่ <a href=\"".$_SERVER['SERVER_NAME']."\">http://".$_SERVER['SERVER_NAME']."</a></p>";

	@mail(getmail($emp),"รายการเบิก Modem/CATV ของ".$empnametxt,$msg,$strHeader);
	/*
	$recievedEmail = getMailEmp($emp);
	$empname = getNameEmp($emp);
	$adminEmail = getMailEmp($_COOKIE['uid']);
	$adminName = getNameEmp($_COOKIE['uid']);
	$strMessage = "//ทดสอบ----///n/n<br><br>รายการ Modem และ CATV ที่เบิกตามรายการด้านล่างนี้ /n<br>".$snMail."/n/n<br><br>หมายเหตุ : ทำรายการโดย ".$adminName." [ทดลองใช้งานบันทึกการเบิก]/n/n<br><br>//ทดสอบ----//";
	sendmailTidnet($recievedEmail,$empname,$strMessage,$adminEmail);
	*/
	echo "<p class=\"\">บันทึกรายการเบิกเรียบร้อย <a href=\"engstock.php?engId=".$emp."\">กลับหน้าสต๊อก</a></p>";
}else{
	//*** RollBack Transaction ***//
	mysql_query("ROLLBACK");
	echo "<p class=\"error\">ไม่สามารถ ลงบันทึกรายการเบิกได้ กรุณาติดต่อพี่หนึ่งด่วนครับ</p>";
}

function sendmailTidnet($recievedEmail,$empname,$strMessage,$adminEmail){
	$adminEmail = $adminEmail.", ";
	$strSubject = "รายการเบิก Modem ".$empname;
	$strHeader = "From: admin@tidnet.co.th";
	$strHeader .= "Cc: ".$adminEmail;//."supwin@gmail.com,sukanya.jamp@gmail.com,tidnet.true@gmail.com\r\n";
	$flgSend = mail($recievedEmail,$strSubject,$strMessage,$strHeader);  // @ = No Show Error //
	if($flgSend)
	{
		echo "<br>".$strSubject."<br>".$strHeader."<br>".$strMessage;
	}
	else
	{
		echo "Email Can Not Send.";
	}
}
