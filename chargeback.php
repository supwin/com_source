<?php
include('cookies.php');
include("functions/function.php");
include("headmenu.php");

$strTable = "chargebackjob_header";

function selectJobname($n){
	$jobname = array('เลือกประเภท','DOCSIC','FTTx','CATV','VDSL','ADSL','MPLS');
	$selJobname = "<select name=\"".$n."\" id=\"".$n."\">";
	for($i=0; $i<count($jobname); $i++){
		$selJobname .= "<option value=\"".$jobname[$i]."\">".$jobname[$i]."</option>";
	}
	$selJobname .= "</select>";
	return $selJobname;
}

function selectChgbckType($n){
	$chkBckType = array('เลือกประเภท','OverDue','ร้องเรียน','Defect','เข้างานสาย');
	$selCkbType = "<select name=\"".$n."\" id=\"".$n."\">";
	for($i=0; $i<count($chkBckType); $i++){
		$selCkbType .= "<option value=\"".$chkBckType[$i]."\">".$chkBckType[$i]."</option>";
	}
	$selCkbType .= "</select>";
	return $selCkbType;
}


if(checkAllow('sit_postchargeback') or $_COOKIE['uid']==198){
?>
<form action="insertchargeback.php" method="post">
<table>
	<tr class="label"><td colspan="3"> -: บันทึกข้อมูลปรับ :-</td></tr>
	<tr>
		<td>ประเภทงาน</td> <td><?php echo selectJobname('jname');?></td>
		<td rowspan="6">
		รายละเอียด บันทึกปรับ<br>
		<textarea id="description" name="description" rows="10" cols="35"></textarea>
		</td>
	</tr>
	<tr>
		<td>Circuit</td> <td><input type="text" name="circuit" id="circuit"></td>
	</tr>
	<tr>
		<td>ประเภทปรับ</td> <td><?php echo selectChgbckType('cbt');?></td>
	</tr>
	<tr>
		<td>ข้อมูลปรับโดย</td> <td><input type="text" name="chargedby" id="chargedby"></td>
	</tr>
	<tr>
		<td>ผู้ถูกปรับ</td> <td><?php echo getemplist("emp")?></td>
	</tr>
	<tr>
		<td>ค่าปรับ</td> <td><input type="text" name="cost" id="cost" size="15"> บาท</td>
	</tr>
	<tr>
		<td colspan="3" align="right"><input name="submitbut" type="submit" value=" บันทึก "></td>
	</tr>
</table>
</from>
<br>
<?php }
/*
$strCondition = "parent=0";
$sort = "order by hid DESC";
$allpost = fncSelectConditionRecord($strTable,$strCondition);
while($post = mysql_fetch_array($allpost)){
	$buttonReply = "";
	//if($post['chgbaked_who']==$_COOKIE['uid']) $buttonReply = " <button for=\"".$post['hid']."\" class=\"reply\" id=\"reply_".$post['hid']."\"> ตอบ </button>";
	$postTxt .= "<a href=\"chargebackdetail.php?id=".$post['hid']."\"><span style=\"padding-right:30px;\">CB-ID:".$post['hid']."</span><span>".$post['description']."</span></a> ";
}

if($postTxt<>''){
	echo "<h3 style=\"padding-top:20px;\">-: หัวข้อบันทึกปรับ :-</h3>";
	//$postTxt = "<table class=\"nonborder\">".$postTxt."</table>";
}
echo $postTxt;
*/

include('chargebackheaderlist.php');

?>
