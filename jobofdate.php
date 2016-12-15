<?php
/*
Log file
270714 1117 : เพิ่มให้การใช้งาน feture เพิ่มค่าเดินทาง ให้เช็ค jobindex ด้วย สำหรับกรณีได้งานทั้ง ถอดและติด
171014 1052 : ขยับ jobofdate() จาก function.php เข้ามาไว้ในนี้เองแล้ว
171014 1130 : แก้ไข query ไม่ใช้ between แต่เปลี่ยนเป็น < until ดีกว่า
301214 1149 : เพิ่มให้ permission 1 ยกเลิกงานแล้วเอา sn คืนเจ้าของเดิมด้วย
*/
include('cookies.php');
include("functions/function.php");
include("headmenu.php");
include("showstockeng.php");
?>
<script>
$(document).ready(function(){
	$("#newJob").click(function(){
		ncir = $("#newcircuit").val();
		nsn = $("#newseries").val();

		$.ajax({
		   type: "POST",
		   url: "cutstock.php",
		   cache: false,
		   data: "cir="+ncir+"&sn="+nsn,
		   success: function(msg){
			 if(msg==1){
				openAlert('ตัดสต๊อกและลงบันทึกงานเรียบร้อย');
				window.location.reload();
			 }else{
				openAlert('ติดขัดบางประการ ไม่สามารถตัดสต๊อกและบันทึกงานได้อย่างสมบูรณ์\nกรุณาแจ้งพี่หนึ่งโดยด่วน');
			 }
		   }
		});
	});


	$("#newseries").blur(function(){
		nsn = $(this).val();
		$.ajax({
		   type: "POST",
		   url: "checksnengstock.php",
		   cache: false,
		   data: "nsn="+nsn,
		   success: function(msg){
			/*if(msg!=0){
				$("#newJob").disabled = "disabled";
			}*/
		   }
		});
	});

	$('[name="travelprice"]').click(function(){
		alert('มีการกดเพิ่มค่าเดินทาง ไม่ถูกต้องบ่อยครั้ง จึงยกเลิกรายการนี้ครับ');
	});

});
</script>
<?php
$m = $_GET['m'];

if($_COOKIE['permission'] == '4'){
	showjobofdate($_COOKIE[uid],$m);
	showStockWaitSN($_COOKIE[uid]);
}else{
	/*
	$strTable = "employee";
	$strCondition = "permission=4";
	$engList = fncSelectConditionRecord($strTable,$strCondition);*/

	$engId = $_GET['engId'];

	if($engId <>''){
			//echo "รายการงานของ ".nameofengineer($engId);
			showjobofdate($engId,$m);
			showStockWaitSN($engId);
	}
}
function showjobofdate($eid,$m){

	$m = $_GET['m'];
	$y = $_GET['y'];

	$mth = nextnprevMonth($m,$y);
	$query = $mth['query'];
	$link = $mth['link'];


	$y = date(Y);
	$py = date(Y);
	if($m==1){
		$py = $y-1;
	}
	if($m==12){
		$y = $py+1;
	}
	$strTable = "closedjob,tidnet_common.typeofjob";
	$strCondition = "emp_id=".$eid." and series<>'' and typejob=id and (closeddate>='".$query['since']."' and closeddate<'".$query['until']."') ORDER BY closeddate DESC";
	//$strCondition = "emp_id=".$eid." and series<>'' and typejob=id ORDER BY closeddate DESC";
	$jobList = fncSelectConditionRecord($strTable,$strCondition);
	?>
	<table border=1>
		<?php
		/*
		$pvm = $m-1;
		$nxm = $m+1;
		$curLink = '';
		$fAdmin = '';
		if($m <> date(n)){
			if($_cookie['permission']<>'4') $fAdmin = "?engId=".$eid;
				$curLink = "<a href=\"jobofdate.php".$fAdmin."\">เดือนปัจจุบัน</a>";
		}*/
		?>
		<tr class="label"><td colspan="4">-:- งานปิดแล้ว<?php echo "ของ ".nameofengineer($eid)?></td><td class="right" colspan="5"><?php echo linkMonth($link,"engId=".$eid);?></td></tr>
		<tr class="header">
			<td>ปิดงาน</td>
			<td>ยกเลิก</td>
			<td>Circuit</td>
			<td>ชื่อลูกค้า</td>
			<td>series</td>
			<td>ประเภทงาน</td>
			<td colspan="2">ระยสาย</td>
			<td>ค่าติดตั้ง</td>
		</tr>
	<?php
		$st = 1;
		while($job = mysql_fetch_array($jobList)){

			if($job[series]=='xxxxxxxxxx'){
				$jobseries = '';
			}else{
				$jobseries = $job[series];
			}

			if($job[typeof]=='True visions') $jtstyle = "tvisions";
			if($job[typeof]=='True Online') $jtstyle = "tonline";

			$travelButton = "";
			$revertTxt = "<span title=\"ยกเลิกกรณีปิดงานผิด\">ยกเลิก</span>";
			if(diffDateMysql($job[closeddate])<=constant('DATEALLOWCUTSTOCK') and $job['payhireheader_id']==0){
				if(($job[typejob]==6) or ($job[typejob]==7)){
					$travelButton = "<input type=\"submit\" id=\"travelprice_".$row."\" name=\"travelprice\" indexjob=\"".$job[indexjob]."\" circuit=\"".$job[circuit]."\" value=\"เพิ่มค่าเดินทาง\">";
				}
				if(($job[typejob]==12) or ($job[typejob]==13)){
					$travelButton = "<input type=\"submit\" id=\"travelprice_".$row."\" name=\"travelprice\" indexjob=\"".$job[indexjob]."\" circuit=\"".$job[circuit]."\" value=\"ยกเลิกค่าเดินทาง\">";
				}
				if($_COOKIE["permission"]==1){
					$moreLink = "engId=".$_GET['engId']."&";
				}
				$revertTxt = "<a href=\"revertjobsn.php?".$moreLink."c=".$job[circuit]."&sn=".$jobseries."\" title=\"ยกเลิกกรณีปิดงานผิด\">ยกเลิก</a>";
			}

			echo "<tr class=\"".$jtstyle."\">";
			echo "<td>".convdateMini($job[closeddate],0)."</td>";

			//if(checkAllow('sit_overtime_cancel_job')){
			//	echo "<td><a href=\"revertjobsn.php?c=".$job[circuit]."&sn=".$jobseries."\" title=\"ยกเลิกกรณีปิดงานผิด\">ยกเลิก</a></td>";
			//}else{
				echo "<td class=center>".$revertTxt."</td>";
			//}
			echo "<td>".$job[circuit]."</td>";
			echo "<td>".$job[cust_name]."</td>";
			echo "<td>".$jobseries."</td>";
			echo "<td><span id=\"tjob_".$row."\" title=\"".$job[typeof]."/".$job[description]."\">".$job[ename]."</span> </td>";
			if(in_array($job[id],array('8','9','10','11','14','15'))){
				echo "<td>".$job[bcable]." / ".$job[wcable]."</td><td>เกิน : ".$job[dwov70]."</td>";
			}else if(in_array($job[id],array(24,26,27,28))){
				echo "<td colspan=\"2\" class=\"right\">".$job[bcable]."</td>";
			}else{
				echo "<td colspan=\"2\"2>".$travelButton."</td>";
			}
			$newPrice = $job['price'];
			if($newPrice==0) $newPrice = $job['price_ivr'];

			echo "<td class=\"right\">".($newPrice+$job[overrange])."</td>";
			echo "<tr>";

			$st +=1;
		}
	?>
</table>
<?php
}

	?>
