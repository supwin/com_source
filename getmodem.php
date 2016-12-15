<?php
/*
log file
200614 1623 : เพิ่มความสามารถในการโอนย้าย อุปกรณ์ไปยังบริษัทข้างเคียงเพื่อยืมคืนกันได้ 
200614 1727 : แก้ไข bug intput ของบันทัดที่ 69-70
230614 2310 : แก้ไข bug แสดงชื่อช่างที่เบิก modem ไปแล้ว
240614 0945 : พบ bug แบบเดียวกันกับ 200614 1727 เช่นเดียวกับบันทัดที่ 69-70 ที่ในส่วนของ else ใน if เดียวกัน
180814 1608 : พบ bug ที่ไม่มี permission path ใน linux จึงเปลี่ยน path ของ file ที่จะ upload นิดหน่อย
021014 0730 : เพิิ่มความสามารถให้ช่างสามารถเบิกของเองได้ โดยไม่ต้องมี scan barcode
*/


include("cookies.php");
include("functions/function.php");
include("headmenu.php");
?>
<script>
$(document).ready(function(){
  $("#confget").click(function(){
		emp = $("select#emp").val();
		if(emp==0){
			openAlert('กรุณาระบุชื่อช่างผู้เบิกอุปกรณ์ด้วย');
			return false;
		}
  });
});
</script>
  ยินดีต้อนรับ <?php echo $_COOKIE['name'];?>
<?php
include("objform.php");

$sn = '';
if(isset($_POST['empup'])){	
	move_uploaded_file($_FILES["fileCSV"]["tmp_name"],"csveqm/".$_FILES["fileCSV"]["name"]); // Copy/Upload CSV
	$objCSV = fopen("csveqm/".$_FILES["fileCSV"]["name"], "r");
	$snno = 0;
	$modemsn = array();
	while (($objArr = fgetcsv($objCSV, 1000, ",")) !== FALSE) {
		if(in_array($objArr[0],$modemsn)){
			echoError($objArr[0]." มีซ้ำอยู่ในรายการเบิกชุดเดียวกัน (แจ้งให้ทราบเท่านั้น ระบบจะทำการคัดออกให้)");
		}else{
			$modemsn[$snno] = $objArr[0];
			if($sn<>'') $sn .= ",";
			$sn .= "'".$objArr[0]."'";
			$snno += 1;
		}
	}	
	$emp = $_POST['empup'];
}else{
	$modemsn = $_POST['modemsn'];
	$emp = $_POST['emp'];
	$sn = "'".$modemsn[0]."'";
	$totalModem = count($modemsn);
	for($m=1; $m<$totalModem; $m++){
		if($sn<>'') $sn .= ",";
		$sn .="'".$modemsn[$m]."'";
	}
}



$strTable = "eqm_sn,eqm_model";
//$strCondition = "sn in ($sn) and id_eqm=id and responcible=0";
$strCondition = "sn in ($sn) and id_eqm=id";


$modemList = fncSelectConditionRecord($strTable,$strCondition)
?>
<form action="getmodemconfirm.php" method="post" enctype="multipart/form-data">
รายการ Modem  
<?php
if(checkPermmissionNo($emp)=='20'){
	echo "ที่จะทำการโอนย้ายไป ".nameofengineer($emp);
	echo "<input type=\"hidden\"  id=\"emp\" name=\"emp\" value=\"".$emp."\"/>";
	echo "<input type=\"hidden\"  id=\"typemovement\" name=\"typemovement\" value=\"moveout\"/>";
}else{
	$dis = '';
	if($_COOKIE[permission]>=4){
		$dis = 'disabled';
		echo "<input type=\"hidden\" value=\"".$emp."\" name=\"emp\">";
	}	
	echo "ที่จะทำการเบิกโดย ".employeeList('4',$emp,$dis);
	echo "<input type=\"hidden\"  id=\"typemovement\" name=\"typemovement\" value=\"out2eng\">";
}
?>

<table border='1'>
	<tr class="header">
		<td>ลำดับ</td>
		<td>ประเภท</td>
		<td>ยี่ห้อ</td>
		<td>รุ่น</td>	
		<td>series</td>
	</tr>
	
<?php

$row = 1;
while($objModem = @mysql_fetch_array($modemList)){
	echo "<tr>";
	echo "<td align=\"center\">$row</td>";
	echo "<td>$objModem[type]</td>";
	echo "<td>$objModem[brand]</td>";
	echo "<td>$objModem[model]</td>";
	if($objModem['responcible']=='9099'){
		echo "<td><div class=\"errort\">".$objModem[sn]." ถูกใช้ติดตั้งไปแล้ว</div></td>";
	}else if($objModem['responcible']=='0'){
		echo "<td><input type=text name=gsn[] value=$objModem[sn]></td>";
		$row +=1;
	}else{
		echo "<td><div class=\"errort\">".$objModem[sn] ." ถูกเบิกไปแล้วด้วย  ".nameofengineer($objModem['responcible'])."</div></td>";
	}
	echo "</tr>";
	$sn = str_replace("'".$objModem[sn]."'","",$sn);
	$mid = $objModem[id];
}
$snArr = explode(",",$sn);
for($a=0;$a<=count($snArr);$a++){
	if($snArr[$a]<>'')$snListTxt .= " ".$snArr[$a];
}
?><input type="hidden" name="modelId" value="<?php echo $mid?>">
</table>
</br>
<?php
if($row>1){
	?>
	<input type="submit" id="confget" value="พิมพ์ใบเบิกและยืนยันการเบิก"/> <input type="button" value="ยกเลิก" onclick="index.php">
	<?php
}
?>
</form>
<p>
<?php
if($snListTxt<>'') echoError("รายการ S/n ที่ไม่เคยมีอยู่ในสต๊อก<br>".$snListTxt);
?>
</p>