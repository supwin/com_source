<?php
include('cookies.php');
include("functions/function.php");
include("headmenu.php");
?>
<script>
$(document).ready(function(){
	$("#prefixst").keyup(function(){
		$("#prefixen").val($("#prefixst").val());
	});

	/*$("#stno").keyup(function(){
		st = 
	});  สำหรับป้องกันกรณี en มากกว่า st*/

	$("#memberno").keyup(function(){
		txt = $(this).val();
		//alert(txt);
		
		$.ajax({
		   type: "POST",
		   url: "checkcatv.php",
		   cache: false,
		   data: "txt="+txt,
		   success: function(msg){
			alert(msg);
			if(msg.indexOf("login_frm.php") > -1){
				window.location.replace("login_frm.php");
				return false;
			}
			 if(msg != "0"){
				alert(msg);
			 }else{
				openAlert('ติดขัดบางประการ ไม่สามารถตัดสต๊อกและบันทึกงานได้อย่างสมบูรณ์\nกรุณาแจ้งพี่หนึ่งโดยด่วน');
			 }
		   }
		});
	});
});
</script>

กรุณาใส่หมายเลขสมาชิกงานที่ต้องการเลข WORK <br><select><option value='ถอด'>move ถอด</option><option value='ติด'>move ติด</option></select> 
<input type="text" id="memberno"> <div id="nameofmember">
<?php
if(checkAllow('sit_addcatvworkno')){
	
	if($_POST['startby']<>''){
		$st = $_POST['startby'];
		$en = $_POST['endby'];
		$prefix = $_POST['prefix'];
		$sql = "insert into tidnet_common.catv_workno (id,date_created,who_created,status) value ";
		//echo "st = ".$st;
		//echo "<br>en = ".$en;
		while($st<=$en){
			$sql .= "('".$prefix."-".$st."',".tidnetNow().",'".$_COOKIE['uid']."','new')";
			if($st==$en){		
				$sql .= ";";
			}else{
				$sql .= ",";
			}
			$st++;
			//echo "new st = ".$st."<br>";
		}
		fncSelectFullSQL($sql);
	}

?>
<hr style="margin-top:5px;"><br>
เพิ่มหมายเลขใบงาน
<form action="catvworkno.php" method="post">
Prefix <input id="prefixst" type="text" name="prefix" style="width:20px;"> - <input id="stno" type="text" name="startby" style="width:100px;"><br>
<dd><dd>ถึง</br>
Prefix <input id="prefixen" type="text" name="prefix" style="width:20px;"> - <input id="enno" type="text" name="endby" style="width:100px;"><br>

<input type="submit" value="บันทึก">
</form>
<hr style="margin-top:5px;">
<br>
<?php
}


if(checkAllow('sit_checkcatvworkno')){
	?>
	รายการหมายเลขใบงาน CATV
	<table>
		<tr>
			<td>หมายเลขใบงาน CATV</td>
			<td>สถานะ</td>
			<td>วันที่เก็บบันทึก</td>
			<td>วันที่นำไปใช้</td>
			<td>หมายเลขสมาชิกนำไปใช้ / ประเภท</td>
		</tr>
	<?php	

	$strTable = "tidnet_common.catv_workno" ;
	$strCondition = "1";
	$sort = " order by status,date_created,id";
	$lsts = fncSelectConditionRecord($strTable,$strCondition,$sort);

	//echo "SELECT * FROM $strTable WHERE $strCondition  $sort";
	while($wk = mysql_fetch_array($lsts)){
	?>
		<tr>
			<td><?php echo $wk['id']?></td>
			<td><?php echo $wk['status']?></td>
			<td><?php echo $wk['date_created']?></td>
			<td><?php echo $wk['date_used']?></td>
			<td><?php echo $wk['catv_memberno']." / ".$wk['type_job']?></td>	
		</tr>
	<?php
	}
}
?>
</table>
