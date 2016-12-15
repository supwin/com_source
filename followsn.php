<?php
/*
Log file
270614 0006 : เพิ่มฟอร์มที่ไว้สำหรับ โยก / โอนย้ายอุปกรณ์ ระหว่างช่าง
280614 2029 : แก้ permission กรณีสิทธิ์ในการสั่งโยกอุปกรณ์
050814 1219 : เพิ่มให้สามารถค้นข้อมูลจาก serial ที่ไม่ตัดสต๊อกได้ นั่นคือ responcible = 97 ด้วยนั่นเอง
181014 0914 : เพิ่มแสดงผล note จาก status 90
*/
include('cookies.php');
include('functions/function.php');
include('objform.php');
include("db_function/phpMySQLFunctionDatabase.php");
?>
<script>
$(document).ready(function(){
	$("#emp").change(function(){
		newResp = $("#emp").val();
		curResp = $("#curresp").val();
		if(newResp == curResp){
			openAlert('เลือกผู้รับ อุปกรณ์ผิดคนรึเปล่า');
			$("#transSerial").attr('disabled', 'disabled');
			return false;
		}else{
			$("#transSerial").removeAttr('disabled');
		}
	});

	$(".gotconfirm").click(function(){
			sn = $(this).attr('for');
			$.ajax({
			   type: "POST",
			   url: "confgotsnbyrequestor.php",
			   cache: false,
			   data: "sn="+sn,
			   success: function(msg){
				if(msg.indexOf("login_frm.php") > -1){
					window.location.replace("login_frm.php");
					return false;
				}
				 if(msg==1){
				 		$("#transSnTable").html(msg);
						$("snf").focus();
				 }else{
				 		$("#transSnTable").html(msg);
				 		openAlert('ติดขัดบางประการ ไม่สามารถขอรับ '+sn+'\nกรุณาแจ้งพี่หนึ่งโดยด่วน');
				 }
			  }
			});
	});

});
</script>
<?php
$sn = trim($_POST['sn']);

$strTable = "eqm_sn,eqm_model";
$strCondition = "eqm_sn.sn='".$sn."' and eqm_sn.id_eqm=eqm_model.id";
//die("select * from ".$strTable." where ".$strCondition);
$resultModel = fncSelectConditionRecord($strTable,$strCondition);

$mdhead = mysql_fetch_array($resultModel);
if(!isset($mdhead[brand])) die(echoError('ไม่ปรากฎ s/n '.$sn.' นี้ในระบบ<br>กรุณาตรวจสอบเลขหมาย s/n อีกครั้ง'));
?>
<table width="500" style="margin-top:10px;background-color:#348781;color:#ffffff;">
	<tr>
		<td>Modem / CATV : <span class="valuehead"><?php echo $mdhead[brand]?></span></td>
		<td>Model : <span class="valuehead"><?php echo $mdhead[model]?></span></td>
	</tr>
	<tr>
		<td>Type : <span class="valuehead"><?php echo $mdhead[type]?></span></td>
		<td rowspan="2"><img src=""></td>
	</tr>
	<?php
		$snofset = $mdhead[sn];

		$strTable = "card";
		$strCondition = "std='".$sn."'";
		$card = fncSelectSingleRecord($strTable,$strCondition);
		if(isset($card[smc])) $snofset = "STD : ".$mdhead[sn]." / SMC : ".$card[smc];
	?>
	<tr>
		<td>Serial : <span class="valuehead"><?php echo $snofset?></span> [status : <?php echo $mdhead[responcible]?>]</td>
	</tr>
</table>
<?php
$strTable = "eqm_trans,eqm_model";
$strCondition = "sn='".$sn."' and eqm_trans.model=eqm_model.id order by eqm_trans.date_time ASC";
$resultSn = fncSelectConditionRecord($strTable,$strCondition);

$round = '1';
while($md = mysql_fetch_array($resultSn)){
	$idEmpResp = $md[responcible];
	if($idEmpResp=='0'){
		$responcible = 'สต๊อกบริษัท';
		$snStatus = "0"; // status ของ s/n 0=พึ่งเข้าสต๊อกบริษัท, 1=ช่างเบิกไปแล้ว, 2=ติดตั้งไปแล้ว
	}else{
		$responcible = nameofengineer($idEmpResp);
		$snStatus = "1"; // status ของ s/n 0=พึ่งเข้าสต๊อกบริษัท, 1=ช่างเบิกไปแล้ว, 2=ติดตั้งไปแล้ว
	}


	// จะทำการเพิ่ม sn_id เข้าช่วย ในการที่ sn กลับเข้าระบบอีกครั้ง แต่ไม่สามารถทำได้เนื่องจากผลของการค้นหา ไม่สามารถระบุได้ว่าต้องการค้นตัวเก่าหรือตัวใหม่ที่เข้ามา  10 ส.ค. 16


?>
	<table width="500" style="margin-top:10px;">
		<tr style="line-height: 10px; ">
			<td height="10" style="height:25px;font-size:12px;background-color:#348781;color:#ffffff;padding-top:10px;">เคลื่อนไหวครั้งที่ <?php echo $round;?> : <span class="valuehead"><?php echo convdate($md[date_time])?></span></td>
		</tr>
		<tr>
			<td><span class="label">อุปกรณ์อยู่กับ : </span><?php echo $responcible?></td>
		</tr>
		<tr>
			<td><span class="label">ผู้ทำรายการ : </span> <?php echo nameofengineer($md[id_employee_did])?></td>
		</tr>
	</table>
<?php
	$round +=1;
	$roundOut = $round;
}

$strTable = "eqm_sn";
$strCondition = "sn='".$sn."' and responcible>=9000";

//die("select * from ".$strTable." where ".$strCondition);

$cust = fncSelectSingleRecord($strTable,$strCondition);
if($cust[responcible] == '9092'){
		$snStatus = "4"; // status ของ s/n 0=พึ่งเข้าสต๊อกบริษัท, 1=ช่างเบิกไปแล้ว, 2=ติดตั้งไปแล้วหรือตีเสียส่งคืนไปแล้ว, 3=ลบออกจากระบบ, 4=อยู่ระหว่างโยกส่ง
		?>
			<div id="transSnTable">
				<table width="500" style="margin-top:10px;" >
					<tr style="line-height: 10px; ">
						<td height="10" style="height:25px;font-size:12px;background-color:orange;color:#ffffff;padding-top:10px;">เคลื่อนไหวครั้งที่ <?php echo $roundOut;?> : <span class="valuehead">โยกส่ง <?php echo convdate($cust[date_movement])?></span></td>
					</tr>
					<tr>
						<td><span class="label">ช่างที่ต้องยืนยันรับ : </span><?php echo nameofengineer($cust[requestor])?></td>
					</tr>
					<tr>
						<td><span class="label">ผู้เจ้าของเดิม : </span> <?php echo $responcible?></td>
					</tr>
					<?php
					if($cust[requestor]==$_COOKIE['uid']){
					?>
					<tr>
						<td><span class="label button gotconfirm" for="<?php echo $sn?>">ยืนยันรับ : </span></td>
					</tr>
					<?php } ?>
				</table>
			</div>
		<?php
}else if($cust[responcible] == '9099'){

	$snStatus = "2"; // status ของ s/n 0=พึ่งเข้าสต๊อกบริษัท, 1=ช่างเบิกไปแล้ว, 2=ติดตั้งไปแล้วหรือตีเสียส่งคืนไปแล้ว, 3=ลบออกจากระบบ, 4=อยู่ระหว่างโยกส่ง
	$strTable = "closedjob";
	$strCondition = "series='".$sn."'";
	$cJob = fncSelectSingleRecord($strTable,$strCondition);
	?>
		<table width="500" style="margin-top:10px;">
			<tr style="line-height: 10px; ">
				<td height="10" style="height:25px;font-size:12px;background-color:#348781;color:#ffffff;padding-top:10px;">เคลื่อนไหวครั้งที่ <?php echo $roundOut;?> : <span class="valuehead">ปิดงานเมื่อ <?php echo convdate($cJob[closeddate])?></span></td>
			</tr>
			<tr>
				<td><span class="label">Circuit ที่รับการติดตั้ง : </span><?php echo $cJob[circuit]?></td>
			</tr>
			<tr>
				<td><span class="label">ผู้ติดตั้ง: </span> <?php echo nameofengineer($cJob[emp_id])?></td>
			</tr>
			<tr>
				<td><span class="label">Note: </span> <?php echo $cust[note]?></td>
			</tr>
		</table>
	<?php
}else if($cust[responcible] == '9098'){
	$snStatus = "2"; // status ของ s/n 0=พึ่งเข้าสต๊อกบริษัท, 1=ช่างเบิกไปแล้ว, 2=ติดตั้งไปแล้วหรือตีเสียส่งคืนไปแล้ว, 3=ลบออกจากระบบ, 4=อยู่ระหว่างโยกส่ง
	$strTable = "eqm_return";
	$strCondition = "series='".$sn."'";
	$retMd = fncSelectSingleRecord($strTable,$strCondition);
	?>
		<table width="500" style="margin-top:10px;">
			<tr style="line-height: 10px; ">
				<td height="10" style="height:25px;font-size:12px;background-color:#330;color:#ffffff;padding-top:10px;">เคลื่อนไหวครั้งที่ <?php echo $roundOut;?> : <span class="valuehead">ทำรายการคืนเมื่อ <?php echo convdate($retMd[date])?></span> [<span style="color:red;font-weight:bold;"><?php echo $cust[responcible]?></span>]</td>
			</tr>
			<tr>
				<td><span class="label">ตีเสีย / ส่งคืน : </span><?php echo $retMd[status]?></td>
			</tr>
			<tr>
				<td><span class="label">ผู้ทำรายการ: </span> <?php echo nameofengineer($retMd[whodid])?></td>
			</tr>
			<tr>
				<td><span class="label">Note: </span> <?php echo $cust[note]?> </td>
			</tr>
			<tr>
				<td><span class="label">Description: </span> <?php echo $retMd[description]?> </td>
			</tr>
		</table>
	<?php
}else if($cust[responcible] == '9097'){
	$snStatus = "2"; // status ของ s/n 0=พึ่งเข้าสต๊อกบริษัท, 1=ช่างเบิกไปแล้ว, 2=ติดตั้งไปแล้วหรือตีเสียส่งคืนไปแล้ว, 3=ลบออกจากระบบ, 4=อยู่ระหว่างโยกส่ง
	?>
		<table width="500" style="margin-top:10px;">
			<tr style="line-height: 10px; ">
				<td height="10" style="height:25px;font-size:12px;background-color:#006;color:#ffffff;padding-top:10px;">เคลื่อนไหวครั้งที่ <?php echo $roundOut;?> : <span class="valuehead">ตัดสต๊อกเมื่อ <?php echo convdate($cJob[closeddate])?></span> [<span style="color:red;font-weight:bold;"><?php echo $cust[responcible]?></span>]</td>
			</tr>
			<tr>
				<td><span class="label">Circuit ที่รับการติดตั้ง : </span><?php echo $cust[closedcircuit]?></td>
			</tr>
			<tr>
				<td><span class="label">ผู้ติดตั้ง: </span> --</td>
			</tr>
			<tr>
				<td><span class="label">Note: </span> <?php echo $cust[note]?></td>
			</tr>
		</table>
	<?php
}else if($cust[responcible] == '9090'){
	$snStatus = "3"; // status ของ s/n 0=พึ่งเข้าสต๊อกบริษัท, 1=ช่างเบิกไปแล้ว, 2=ติดตั้งไปแล้วหรือตีเสียส่งคืนไปแล้ว, 3=ลบออกจากระบบ, 4=อยู่ระหว่างโยกส่ง
	?>
		<table width="500" style="margin-top:10px;">
			<tr style="line-height: 10px; ">
				<td height="10" style="height:25px;font-size:12px;background-color:#999;color:#ffffff;padding-top:10px;">เคลื่อนไหวครั้งที่ <?php echo $roundOut;?> : <span class="valuehead">ปิดงานเมื่อ <?php echo convdate($cJob[closeddate])?></span> [<span style="color:red;font-weight:bold;"><?php echo $cust[responcible]?></span>]</td>
			</tr>
			<tr>
				<td><span class="label">Circuit ที่รับการติดตั้ง : </span><?php echo $cust[closedcircuit]?></td>
			</tr>
			<tr>
				<td><span class="label">ผู้ติดตั้ง: </span> --</td>
			</tr>
			<tr>
				<td><span class="label">Note: </span> <?php echo $cust[note]?></td>
			</tr>
		</table>
	<?php
}else if($cust[responcible] == '9100'){
	$snStatus = "2"; // status ของ s/n 0=พึ่งเข้าสต๊อกบริษัท, 1=ช่างเบิกไปแล้ว, 2=ติดตั้งไปแล้วหรือตีเสียส่งคืนไปแล้ว, 3=ลบออกจากระบบ, 4=อยู่ระหว่างโยกส่ง
	?>
		<table width="500" style="margin-top:10px;">
			<tr style="line-height: 10px; ">
				<td height="10" style="height:25px;font-size:12px;background-color:#999;color:#ffffff;padding-top:10px;">เคลื่อนไหวครั้งที่ <?php echo $roundOut;?> : <span class="valuehead">ปิดงานเมื่อ <?php echo convdate($cJob[closeddate])?></span> [<span style="color:red;font-weight:bold;"><?php echo $cust[responcible]?></span>]</td>
			</tr>
			<tr>
				<td><span class="label">Circuit ที่รับการติดตั้ง : </span><?php echo $cust[closedcircuit]?></td>
			</tr>
			<tr>
				<td><span class="label">ผู้ติดตั้ง: </span> --</td>
			</tr>
			<tr>
				<td><span class="label">Note: </span> <?php echo $cust[note]?></td>
			</tr>
		</table>
	<?php
}else if($cust[responcible] == '9096'){

	$snStatus = "2"; // status ของ s/n 0=พึ่งเข้าสต๊อกบริษัท, 1=ช่างเบิกไปแล้ว, 2=ติดตั้งไปแล้วหรือตีเสียส่งคืนไปแล้ว, 3=ลบออกจากระบบ, 4=อยู่ระหว่างโยกส่ง
	?>
		<table width="500" style="margin-top:10px;">
			<tr style="line-height: 10px; ">
				<td height="10" style="height:25px;font-size:12px;background-color:#999;color:#ffffff;padding-top:10px;">เคลื่อนไหวครั้งที่ <?php echo $roundOut;?> : <span class="valuehead">ปิดงานเมื่อ <?php echo convdate($cJob[closeddate])?></span></td>
			</tr>
			<tr>
				<td><span class="label">Circuit ที่รับการติดตั้ง : </span><?php echo $cust[closedcircuit]?></td>
			</tr>
			<tr>
				<td><span class="label">ผู้ติดตั้ง: </span>--</td>
			</tr>
			<tr>
				<td><span class="label">Note: </span> <?php echo $cust[note]?></td>
			</tr>
		</table>
	<?php
}else if($cust[responcible] == '9093'){

	$snStatus = "2"; // status ของ s/n 0=พึ่งเข้าสต๊อกบริษัท, 1=ช่างเบิกไปแล้ว, 2=ติดตั้งไปแล้วหรือตีเสียส่งคืนไปแล้ว, 3=ลบออกจากระบบ, 4=อยู่ระหว่างโยกส่ง
	?>
		<table width="500" style="margin-top:10px;">
			<tr style="line-height: 10px; ">
				<td height="10" style="height:25px;font-size:12px;background-color:#999;color:#ffffff;padding-top:10px;">เคลื่อนไหวครั้งที่ <?php echo $roundOut;?> : <span class="valuehead">ติดตั้งเมื่อ <?php echo convdate($cust[date_movement])?></span></td>
			</tr>
			<tr>
				<td><span class="label">Circuit ที่รับการติดตั้ง : </span><?php echo $cust[circuit]?></td>
			</tr>
			<tr>
				<td><span class="label">ผู้ติดตั้ง: </span> <?php echo nameofengineer($cust[oldowner])?></td>
			</tr>
			<tr>
				<td><span class="label">Note: </span> <?php echo $cust[note]?></td>
			</tr>
		</table>
	<?php
}else if($cust[responcible] == '9198'){
	$snStatus = "2"; // status ของ s/n 0=พึ่งเข้าสต๊อกบริษัท, 1=ช่างเบิกไปแล้ว, 2=ติดตั้งไปแล้วหรือตีเสียส่งคืนไปแล้ว, 3=ลบออกจากระบบ, 4=อยู่ระหว่างโยกส่ง
	$strTable = "eqm_return";
	$strCondition = "series='".$sn."'";
	$retMd = fncSelectSingleRecord($strTable,$strCondition);
	?>
		<table width="500" style="margin-top:10px;">
			<tr style="line-height: 10px; ">
				<td height="10" style="height:25px;font-size:12px;background-color:#330;color:#ffffff;padding-top:10px;">เคลื่อนไหวครั้งที่ <?php echo $roundOut;?> : <span class="valuehead">ทำรายการคืนเมื่อ <?php echo convdate($retMd[date])?></span> [<span style="color:red;font-weight:bold;"><?php echo $cust[responcible]?></span>]</td>
			</tr>
			<tr>
				<td><span class="label">ตีเสีย / ส่งคืน : รอรับของ</span></td>
			</tr>
			<tr>
				<td><span class="label">เจ้าของล่าสุด: </span> <?php echo nameofengineer($cust[oldowner])?></td>
			</tr>
			<tr>
				<td><span class="label">Note: </span> <?php echo $cust[note]?> </td>
			</tr>
		</table>
	<?php
}else if($cust[responcible] == '9199'){
	$snStatus = "3"; // status ของ s/n 0=พึ่งเข้าสต๊อกบริษัท, 1=ช่างเบิกไปแล้ว, 2=ติดตั้งไปแล้วหรือตีเสียส่งคืนไปแล้ว, 3=ลบออกจากระบบ, 4=อยู่ระหว่างโยกส่ง
	?>
		<table width="500" style="margin-top:10px;">
			<tr style="line-height: 10px; ">
				<td height="10" style="height:25px;font-size:12px;background-color:#999;color:#ffffff;padding-top:10px;">เคลื่อนไหวครั้งที่ <?php echo $roundOut;?> : <span class="valuehead">ปิดงานเมื่อ <?php echo convdate($cJob[closeddate])?></span> [<span style="color:red;font-weight:bold;"><?php echo $cust[responcible]?></span>]</td>
			</tr>
			<tr>
				<td><span class="label">Status : ช่างไม่สามารถใช้ Serial ปิดงานได้ </span></td>
			</tr>
			<tr>
				<td><span class="label">Circuit ที่รับการติดตั้ง : </span><?php echo $cust[closedcircuit]?></td>
			</tr>
			<tr>
				<td><span class="label">ผู้ติดตั้ง: </span><?php echo nameofengineer($cust[oldowner])?></td>
			</tr>
			<tr>
				<td><span class="label">Note: </span> <?php echo $cust[note]?></td>
			</tr>
		</table>
	<?php
} else if($cust[responcible] == '9111'){
	$snStatus = "3"; // status ของ s/n 0=พึ่งเข้าสต๊อกบริษัท, 1=ช่างเบิกไปแล้ว, 2=ติดตั้งไปแล้วหรือตีเสียส่งคืนไปแล้ว, 3=ลบออกจากระบบ, 4=อยู่ระหว่างโยกส่ง
	?>
		<table width="500" style="margin-top:10px;">
			<tr style="line-height: 10px; ">
				<td height="10" style="height:25px;font-size:12px;background-color:#999;color:#ffffff;padding-top:10px;">เคลื่อนไหวครั้งที่ <?php echo $roundOut;?> : <span class="valuehead">ปิดงานเมื่อ <?php echo convdate($cJob[closeddate])?></span> [<span style="color:red;font-weight:bold;"><?php echo $cust[responcible]?></span>]</td>
			</tr>
			<tr>
				<td><span class="label">Status : ผ่อนผัน </span></td>
			</tr>
			<tr>
				<td><span class="label">ช่าง: </span><?php echo nameofengineer($cust[oldowner])?></td>
			</tr>
			<tr>
				<td><span class="label">Note: </span> <?php echo $cust[note]?></td>
			</tr>
		</table>
	<?php
}


if($snStatus==1 and checkallow('sit_serialtranfer')){
?>
			<form action="serialtransfering.php" method="post">
		<table width="500" style="margin-top:10px;">
			<tr style="line-height: 10px; ">
				<td height="10" colspan="2" style="height:25px;font-size:14px;weight:bold;background-color:orange;color:#555555;padding-top:10px;">โยก / โอนอุปกรณ์ ระหว่างช่าง</span></td>
			</tr><input type="hidden" name="frompage" value="followsn">
			<input type="hidden" name="sn" id="sn" value="<?php echo $sn?>"><input type="hidden" name="curresp" id="curresp" value="<?php echo $idEmpResp?>">
			<tr>
				<td style="background-color:#ffffff"><span class="label">ผู้รับโยก / โอนอุปกรณ์: </span> <?php echo employeeList('4')?> <input type="submit" id="transSerial" disabled value=" โอน/ย้าย "></td>
			</tr>
		</table>
			</form>
<?php
}else if($snStatus==1 and $_COOKIE[uid]==$idEmpResp){  //
?>
			<form action="serialtransferingbyowner.php" method="post">
		<table width="500" style="margin-top:10px;">
		<tr style="line-height: 10px; ">
			<td height="10" colspan="2" style="height:25px;font-size:14px;weight:bold;background-color:brown;color:#fff;padding-top:10px;">โยก / โอนอุปกรณ์ ระหว่างช่าง</span></td>
		</tr><input type="hidden" name="frompage" value="followsn">
		<input type="hidden" name="sn" id="sn" value="<?php echo $sn?>"><input type="hidden" name="curresp" id="curresp" value="<?php echo $idEmpResp?>">
		<tr>
			<td style="background-color:#ffffff"><span class="label">ต้องการโยกให้กับ >>> </span> <?php echo employeeList('4')?> <input type="submit" id="transSerial" disabled value=" โอน/ย้าย ">
				<br><span style="color:red;">**คำเตือน ความรับผิดชอบยังเป็นของเจ้าของเก่า<br>จนกว่าผู้รับจะยืนยันในระบบ </span>
			</td>
		</tr>
		</table>
			</form>
<?php
}/*else if($snStatus==1 and $_COOKIE[uid]<>$idEmpresp and $_COOKIE[permission]==4){
?>
			<form action="serialtransferingbyreqter.php" method="post">
		<table width="500" style="margin-top:10px;">
		<tr style="line-height: 10px; ">
			<td height="10" colspan="2" style="height:25px;font-size:14px;weight:bold;background-color:yellow;color:#000;padding-top:10px;">โยก / โอนอุปกรณ์ ระหว่างช่าง</span></td>
		</tr><input type="hidden" name="frompage" value="followsn">
		<input type="hidden" name="sn" id="sn" value="<?php echo $sn?>"><input type="hidden" name="curresp" id="curresp" value="<?php echo $idEmpResp?>">
		<tr>
			<td style="background-color:#ffffff"><span class="label"> <input type="submit" value=" ขอรับโอน/ย้าย "></td>
		</tr>
		</table>
			</form>
<?php
}*/

?>
