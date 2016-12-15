<?php
/*
Log file

070714 2109 : แก้ไขเรื่อง warning ของ cookie เพื่อใน index.php งงทำไมมันเป็นอยู่แค่ file เดียว file อื่นไม่เป็น
081014 1418 : เพิ่มการแสดง nofify เมื่อมี modem รอเข้าสต๊อก
090115 1447 : แก้ไขให้สามารถคำนวณยอดเงินได้ในช่วงเดือนต่ำกว่าเดือน 10
*/

if($_COOKIE['user']==""){
	?>
	<script>
		window.location ='login_frm.php';
	</script>
	<?php
}

if(preg_match('/android|avantgo|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)) and $_COOKIE['permission']==4 and $_GET['orgver']<>1)
{
// ทำการเขียนโปรแกรมต่อที่นี่ กรณีเป็นการดูเว็บเพจจากโทรศัพท์เคลื่อนที่ ?>
 <script>
    window.location='foajobassign.php'
  </script>
<?php  //echo "เป็นการดูจากโทรศัพท์เคลื่อนที่";
}

include('functions/function.php');
include("../com_source/headmenu.php");
?>
<script>
$(document).ready(function(){
	$('.changepass').click(function(){
		var pa = $('input#passa').val();
		var pb = $('input#passb').val();
		if(pa != pb || pa==''){
			$("div.showtxt").remove();
			$("div#showchgpass").append( "<div class=\"showtxt\"><p class=\"errort\">รหัสผ่านใหม่ไม่ตรงกัน หรือไม่ถูกต้อง</p></div>" );
			$('input#passa').val('');
			$('input#passb').val('');
			return false;
		}
		$.ajax({
		   type: "POST",
		   url: "changepassemp.php",
		   cache: false,
		   data: "p="+pa,
		   success: function(msg){
				$("div.showtxt").remove();
				$("div#showchgpass").append( "<div class=\"showtxt\">"+msg+"</div>" );
		   }
		});
	});

	$('input#passa').keydown(function(){
		$("div.showtxt").remove();
	});

	$('input#passb').keydown(function(){
		$("div.showtxt").remove();
	});
});
</script>
<table border='0' style="border:0px solid #ffffff;">
	<tr>
		<td>
			<form action="searchjobassignhistory.php" method="post"><span style="text-indent: 5em;">ค้นประวัตินัดหมาย : <input type="text" name="searchtxt"></span>&nbsp;<input type="submit" value=" search... "> <span style="color:red; font-size:10px;">*ค้นได้จากบางส่วนของ circuit หรือ จากชื่อ-สกุล</span></form>
		</td>
	</tr>
	<tr style="border:0px;">
		<td style="border:0px;vertical-align:top;">
		<?php
		if(checkAllow('sit_proveorderacs') and $notifyOrderTxt<>'') echoError($notifyOrderTxt);
		if(checkAllow('sit_provemodemin') and $notifyModemTxt<>'') echoError($notifyModemTxt);
		if(checkAllow('sit_cancelsntrans') and $notifySnTrans<>'') echoError($notifySnTrans);
		if($countOldowner>=1) echoError($oldownerSnTrans);
		if($countRequestor>=1) echoError($requestorSnTrans);

		$strPayHeaderTable = "tidnet_common.payhireheader";
		$strPayHeaderCondition = "emp_id='".$_COOKIE['uid']."' and confirmstatus='9999'";

		//echo "SELECT * FROM $strPayHeaderTable WHERE $strPayHeaderCondition";
		$PH = fncSelectSingleRecord($strPayHeaderTable,$strPayHeaderCondition);
		if($PH['confirmstatus']=='9999'){
			echoError("<img src=\"img/new.gif\"> มีรายการตั้งเบิกรอยืนยัน ".$PH['headername']." : สถานะ ".$statusConfirm." >>> <a href=\"http://".$abvt.".tidnet.co.th/payhireconfirmation.php?urlkey=".$PH['urlkey']."&hid=".$PH['id']."\">คลิ๊กเพื่อดูรายละเอียด..</a>");
		}
		include('../com_source/news.php');
		?>
		</td>
		<td style="border:0px;vertical-align:top;">
<?php
	if(checkAllow("sit_viewincome")){
		$m = date('n');
		if($m<10) $m = "0".$m;
		$y = date('Y');

		$strTable = "closedjob";//,typeofjob";
		$strCondition = "closeddate like '".$y."-".$m."%'";// and typejob=id";
		$strStar = "sum(truepay)as sumtidnet, sum(price)as sumsub";

		$incomelst = fncSelectStarConditionRecord($strStar,$strTable,$strCondition);

		$income = mysql_fetch_array($incomelst);

?>
		<table>
			<tr class="label"> <?php $thisM = convmonth(date(n))?>
				<td>ค่าติดตั้งเดือน <?php echo $thisM?></td>
			</tr>
			<tr>
				<td>
					<span>รายรับ</span><br>
					<span style="padding-left:50px;"><span style="color:#CD3700;font-weight:900;"> <?php echo number_format($income[sumtidnet])?></span> บาท</span><br>
					<span>จ่าย sub</span><br>
					<span style="padding-left:50px;"><span style="color:#CD3700;font-weight:900;"> <?php echo number_format($income[sumsub])?></span> บาท</span><br>
					<span>รับสุทธิ</span><br>
					<span style="padding-left:50px;"><span style="color:#CD3700;font-weight:900;"> <?php echo number_format($income[sumtidnet]-$income[sumsub])?></span> บาท</span>
				</td>
			</tr>
		</table>
<?php
	}else{
?>
		<table>
			<tr><?php $thisM = convmonth(date(n))?>
				<td width="200">
					<span>ค่าติดตั้งเดือน <?php echo $thisM?></span><br>
					<span style="padding-left:50px;"><span style="color:#CD3700;font-weight:900;"> <?php echo number_format(sumIncome())?></span> บาท</span><br>

					<span>ยอดอุปกรณ์</span><br>
					<span style="padding-left:50px;"><span style="color:#CD3700;font-weight:900;"> <?php echo number_format(sumBuyacs(date('m'),date('Y')))?></span> บาท</span>
				</td>
			</tr>
		</table>
<?php
	}
?>
		<div id="passchange" style="margin-top:25px;">
		<div id="showchgpass"></div>
		<span>กำนหดรหัสผ่านใหม่</span>
		<table>
			<tr>
				<td><input type="password" id="passa" style="width:100px;"></td><td rowspan="2"><span class="button changepass" style="height:50px;width:50px;display: inline-block;zoom: 1;*display: inline;text-align:center;line-height:50px;font-weight:900" >เปลี่ยน</span></td>
			</tr>
			<tr>
				<td><input type="password" id="passb" style="width:100px;"></td>
			</tr>
		</table>
		</div>
</td>
</tr>
</table>
</body>
</html>
