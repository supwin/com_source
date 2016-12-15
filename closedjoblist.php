<?php
/*
Log file
220814  1445 : เพิ่มเติมการออก INV แบบใหม่ผ่าน พันธวณิช
*/

include('cookies.php');
include("functions/function.php");
include("headmenu.php");
?>
<script>
$(document).ready(function(){
	$("button.printpayment").click(function(){
		var eid = $(this).attr('id');
		var m = $("input#m").val();
		var y = $("input#y").val();
		window.open("printing/prtpayment.php?eid="+eid+"&m="+m+"&y="+y,"_blank");
	});
	$('.polist').click(function(){
		var potype = $(this).attr('id');
		var m = $("#m").val();
		var y = $("#y").val();
		$("#closedlist").html('');
		$("#fromtuc").remove();
		$.ajax({
		   type: "POST",
		   url: "getclosedjotaspotype.php",
		   cache: false,
		   data: "potype="+potype+"&m="+m+"&y="+y,
		   success: function(msg){
			if((msg.indexOf("window.location ='login_frm.php';") > -1) && (msg.indexOf("> -1") <= -1)){
				window.location.replace("login_frm.php");
				return false;
			}

			var arr = $.parseJSON(msg);
			//$("#serialLst").val(arr['sn']+','+arr['result']);
			$.each(arr, function(i,v) {
				//$("#serialLst").val(v['result']+','+v['sn']);
				cir = v['cir'];
				price = $.trim(v['price']);
				$("#closedlist").append("<p id='"+cir+"'>"+cir+" "+price+"</p>");
			});
			$("#closedlist").after("<div style=\"background-color:#888;float:left\" id=\"fromtuc\"><textarea style=\"width:300px;height:500px;\"/></div>");
		   }

		});
	})
});
</script>
<?php
$strTable = "closedjob";

$header = "<tr style=\"background-color:#5CACEE;color:#ffffff;\"><td class=\"center\">ประเภทงาน</td><td class=\"center\">จำนวนงาน</td><td class=\"center\">จำนวนเงิน</td></tr>";

$m = $_GET['m'];
$y = $_GET['y'];

if($m=='') $m = date('n');
if($y=='') $y = date('Y');

$mth = nextnprevMonth($m,$y);
$query = $mth['query'];
$link = $mth['link'];



?>

<input type="hidden" id="m" value="<?php echo $m?>">
<input type="hidden" id="y" value="<?php echo $y?>">

<?php
if($_COOKIE[permission]==1){

	$strTable = "closedjob";
	$strCondition = "closeddate>='".$query['since']."' and closeddate<'".$query['until']."'";
	//die("select count(*) as numOfRow from ".$strTable." where ".$strCondition);
	$allJob = fncCountRow($strTable,$strCondition);
	?>
	<table>
		<tr style="background-color:#4F94CD;color:#ffffff;font-weight:900;">
	<?php
	echo "<td>TUC TO Tidnet Limited Partnership. </td><td colspan=\"2\"> งานทั้งหมด ".$allJob." รายการ</td>";
	?>
		</tr>
		<?php echo linkMonth($link);?>
		<?php echo $header?>
	<?php
	$jtTable = "tidnet_common.typeofjob";
	$jtCondition = "1 order by typeof";
	$jt = fncSelectConditionRecord($jtTable,$jtCondition);
	$sum = 0;

	while($j = mysql_fetch_array($jt)){
		echo "<tr>";
		$star = " count(*) as allrec, sum(dwov70) as totaldwov70 ";
		$strTable = "closedjob";
		$strCondition = "typejob='".$j[id]."' and closeddate>='".$query['since']."' and closeddate<'".$query['until']."'";
		$recjob = fncSelectStarConditionRecord($star,$strTable,$strCondition);

		$cjob = mysql_fetch_array($recjob);

		//var_dump($cjob);
		$countjob = $bathJob = '';
		if($cjob['allrec'] > 0){
			$countjob = $cjob['allrec']." รายการ";
			$totalB = ($cjob['allrec']*$j['tuc2tidnet'])+(25*$cjob['totaldwov70']);
			$sum += $totalB;
			$bathJob = number_format($totalB,2)." บาท";
		}
		echo "<td style=\"background-color:#B0E2FF;\">งาน".$j[tname]."</td><td>".$countjob."</td><td class=\"right\">".$bathJob."</td>";
		echo "</tr>";

	}
	echo "<tr><td align=\"center\">true Online Invoice : <a target=\"_blank\" href=\"preinv.php?tof=1&po=1&m=".$m."&y=".$y."\">1</a>, <a target=\"_blank\" href=\"preinv.php?tof=1&po=2&m=".$m."&y=".$y."\">2</a>, <a target=\"_blank\" href=\"preinv.php?tof=1&po=3&m=".$m."&y=".$y."\">3</a>
	|
	 true Visions Invoice <a target=\"_blank\" href=\"preinv.php?tof=2&po=1&m=".$m."&y=".$y."\">1</a>, <a target=\"_blank\" href=\"preinv.php?tof=2&po=2&m=".$m."&y=".$y."\">2</a> </td><td class=\"right\">ยอดสุทธิ : </td><td>".number_format($sum,2)." บาท</td></tr>";
	/*
	$txtCreatINVLink = "<tr><td align=\"center\">";
	$invEO = invCreated('1',$m,$branch);
	if($invEO[month_no]==$m){
		$txtCreatINVLink .= "true Online Invoice";
	}else{
		$txtCreatINVLink .= "<a target=\"_blank\" href=\"preinv.php?tof=1&m=".$m."&y=".$y."\">true Online Invoice</a>";
	}

	$txtCreatINVLink .= " | ";

	$invEV = invCreated('2',$m,$branch);
	if($invEV[month_no]==$m){
		$txtCreatINVLink .= "true Visions Invoice";
	}else{
		$txtCreatINVLink .= "<a target=\"_blank\" href=\"preinv.php?tof=2&m=".$m."&y=".$y."\">true Visions Invoice</a>";
	}
	$txtCreatINVLink .= "</td></tr>";

	echo $txtCreatINVLink;
	*/

	//sumTRMListEachOne();
	?>
	</table>
<?php
}
?>

<br>
<?php
$strTable = "employee";
$strCondition = "permission='4'";
$eng = fncSelectConditionRecord($strTable,$strCondition);
while($e = mysql_fetch_array($eng)){

	if(($e[id]==$_COOKIE['uid']) or ($_COOKIE['permission']==1)){
		$strTable = "closedjob";
		$strCondition = "emp_id='".$e[id]."' and series<>'' and closeddate>='".$query['since']."' and closeddate<'".$query['until']."'";
		$allJob = fncCountRow($strTable,$strCondition);
		?>
		<table>
			<tr style="background-color:#4F94CD;color:#ffffff;font-weight:900;">
		<?php
		echo "<td>Tidnet TO ".$e[name]."</td><td colspan=\"2\"> งานทั้งหมด ".$allJob." รายการ</td>";
		?>
			</tr>
			<?php echo linkMonth($link);?>
			<?php echo $header?>
		<?php
		$jtTable = "tidnet_common.typeofjob";
		$jtCondition = "1 order by typeof";
		$jt = fncSelectConditionRecord($jtTable,$jtCondition);
		$sum = 0;

		while($j = mysql_fetch_array($jt)){
			echo "<tr>";
			$strTable = "closedjob";
			$star = "COUNT( * ) AS c, SUM( price ) AS sumprice";
			$strCondition = "emp_id='".$e[id]."' and typejob='".$j[id]."' and closeddate>='".$query['since']."' and closeddate<'".$query['until']."'  and series<>''";
			if($_GET['debug']) echo "SELECT $star FROM $strTable WHERE $strCondition";
			$cjob = @mysql_fetch_array(fncSelectStarConditionRecord($star,$strTable,$strCondition));
			//$cjob = fncCountRow($strTable,$strCondition);

			//var_dump($cjob);
			$countjob = $bathJob = '';
			if($cjob['c'] > 0){
				$countjob = $cjob['c']." รายการ";
				$sum += $cjob['sumprice'];
				$bathJob = number_format($cjob['sumprice'],2)." บาท";
			}
			echo "<td style=\"background-color:#B0E2FF;\">งาน".$j[tname]."  </td><td>".$countjob."</td><td class=\"right\">".$bathJob."</td>";
			echo "</tr>";

		}
		echo "<tr><td colspan=\"2\" class=\"right\">ยอดสุทธิ : </td><td>".number_format($sum,2)." บาท</td></tr>";
		//sumTRMListEachOne($e[id]);
		echo "<tr><td colspan=\"3\" class=\"center\"><button id=\"".$e[id]."\" class=\"printpayment\" style=\"padding:5px 20px;\">พิมพ์รายการตั้งเบิกของ ".$e[name]."</button></td></tr>";
		echo "</table>";
		echo "<br>";
	}
}



$strTable = "closedjob";
$strCondition = "emp_id='0' and closeddate>='".$query['since']."' and closeddate<'".$query['until']."'";
$allJob = fncCountRow($strTable,$strCondition);
?>
<table>
	<tr style="background-color:#4F94CD;color:#ffffff;font-weight:900;">
<?php
echo "<td>งานยังไม่มีใครรับเป็นเจ้าของ </td><td colspan=\"2\"> งานทั้งหมด ".$allJob." รายการ</td>";
?>
	</tr>
	<?php echo $header?>
<?php
$jtTable = "tidnet_common.typeofjob";
$jtCondition = "1 order by typeof";
$jt = fncSelectConditionRecord($jtTable,$jtCondition);
$sum = 0;

while($j = mysql_fetch_array($jt)){
	echo "<tr>";
	$strTable = "closedjob";
	$strCondition = "series=''  and typejob='".$j[id]."' and closeddate>='".$query['since']."' and closeddate<'".$query['until']."'";
	$cjob = fncCountRow($strTable,$strCondition);

	//var_dump($cjob);
	$countjob = $bathJob = '';
	if($cjob > 0){
		$countjob = $cjob." รายการ";
		$totalB = $cjob*$j['tidnet2sub'];
		$sum += $totalB;
		$bathJob = number_format($totalB,2)." บาท";
	}
	echo "<td style=\"background-color:#B0E2FF;\">งาน".$j[tname]." </td><td>".$countjob."</td><td class=\"right\">".$bathJob."</td>";
	echo "</tr>";
}
echo "<tr><td colspan=\"2\" class=\"right\">ยอดสุทธิ : </td><td>".number_format($sum,2)." บาท</td></tr>";
//sumTRMListEachOne('0');
?>
</table>

<?php
if(checkAllow('sit_provepo')){
	?>
	<div style="position:absolute; left:650px; top:150px; padding:10px; overflow:hidden;">
	<span>true online </span>
	<p><span class="polist button" id="1">[PO. type-1]</span> | <span class="polist button" id="2">[PO. type-2]</span> | <span class="polist button" id="3">[PO. type-3]</span></p>
	 <div style="float:left;margin-right:20px;" id="closedlist"></div>
	</div>
<?php
}
?>
