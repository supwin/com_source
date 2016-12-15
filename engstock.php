้<?php
/*
Log file
270614 1034 : เพิ่มเสียง alert ตอนยิงบาร์โค้ด เบิก modem
280614 1957 : แก้ไข  bug ของ followsn สำหรับกรณีช่างเบิก s/n ที่ไม่มีในระบบแล้วดันเบิกได้ เพราะไปเพิ่ม feature โอนย้ายอุปกรณ์ใน followsn.php
280614 2017 : แก้ไข  bug ของกรณี ช่างยิงบาร์โค้ดเบิก และมีการเช็คของในสต๊อกบริษัท และสต๊อกของช่างด้วยกัน เพื่อดูสถานะของ modem catv ว่าสามารถทำรายการได้หรือไม่
210714 1729 : แก้ไขให้ เจ้าของ serial คลิ๊กที่ serial เพื่อแจ้งเสียง/ส่งคืนได้
*/
include('cookies.php');
include("functions/function.php");
include("headmenu.php");
include("showstockeng.php");
?>

<script src="jquery/jquery.js"></script>
<script>
$(document).ready(function(){

	$('<audio id="chatAudio"><source src="sounds/Air Plane Ding-SoundBible_com-496729130_2.ogg" type="audio/ogg"><source src="sounds/Air Plane Ding-SoundBible_com-496729130_2.mp3" type="audio/mpeg"><source src="sounds/Air Plane Ding-SoundBible_com-496729130_2.wav" type="audio/wav"></audio>').appendTo('body');
	$('<audio id="mistAudio"><source src="sounds/Bike Horn-SoundBible_com-602544869.ogg" type="audio/ogg"><source src="sounds/Bike Horn-SoundBible_com-602544869.mp3" type="audio/mpeg"><source src="sounds/Bike Horn-SoundBible_com-602544869.wav" type="audio/wav"></audio>').appendTo('body');
	$('<audio id="dupiAudio"><source src="sounds/Banana_Slap-AngryFlash-2001109808.ogg" type="audio/ogg"><source src="sounds/Banana_Slap-AngryFlash-2001109808.mp3" type="audio/mpeg"><source src="sounds/Banana_Slap-AngryFlash-2001109808.wav" type="audio/wav"></audio>').appendTo('body');
	$('<audio id="emptyAudio"><source src="sounds/Banana_Slap-AngryFlash-2001109808.ogg" type="audio/ogg"><source src="sounds/Banana_Slap-AngryFlash-2001109808.mp3" type="audio/mpeg"><source src="sounds/Banana_Slap-AngryFlash-2001109808.wav" type="audio/wav"></audio>').appendTo('body');


	$("input.serial").change(function(){
		itemBC = $(this).val();
		if(itemBC=='' || itemBC.length <=5) return false;

		$("input.serial").val('');
		if($('td.snTXT').text().indexOf(itemBC) > -1){
			$('#dupiAudio')[0].play();
			openAlert('serial '+itemBC+' นี้ ได้ถูกสแกนเข้ามาก่อนแล้ว');
			return false;
		}

		//$("input.serial").attr('disabled','disabled');
		$.ajax({
		   type: "POST",
		   url: "followsn.php",
		   cache: false,
		   data: "sn="+itemBC,
		   success: function(msg){
				if(msg.indexOf("ไม่ปราก") > -1){
					$('#emptyAudio')[0].play();
					openAlert('serial '+itemBC+' ไม่พบอยู่ในสต๊อกปัจจุบัน กรุณาส่ง serial นี้ตรวจสอบ');
					return false;
					//$("input.serial").removeAttr('disabled');
				}else{
					if(msg.indexOf("[status : 0]") == -1){  // ถ้าไม่พบว่า responcible เป็น 0  จะไม่สามารถเบิกได้
					//if(msg.length>1000){
						$('#dupiAudio')[0].play();
						if(msg.indexOf("[status : 90]")>-1){
							openAlert('serial '+itemBC+' นี้ได้ถูกยกเลิกจากระบบแล้ว ไม่สามารถเบิกได้');
						}else if(msg.indexOf("[status : 97]")>-1){
							openAlert('serial '+itemBC+' นี้ได้ถูกใช้งานตัดสต๊อกไปแล้ว ไม่สามารถเบิกได้');
						}else if(msg.indexOf("[status : 98]")>-1){
							openAlert('serial '+itemBC+' นี้ ตีเสีย/ส่งคืน ไม่สามารถเบิกกได้');
						}else if(msg.indexOf("[status : 99]")>-1){
							openAlert('serial '+itemBC+' นี้ได้ถูกใช้งานตัดสต๊อกปิดงานไปแล้ว ไม่สามารถเบิกได้');
						}else if(msg.indexOf("[status : 100]")>-1){
							openAlert('serial '+itemBC+' นี้ได้ถูกใช้งานตัดสต๊อกปิดงานจุดเสริมไปแล้ว ไม่สามารถเบิกได้');
						}else{
							openAlert('serial '+itemBC+' นี้ได้ถูกเบิกเข้าสต๊อกช่างไปแล้ว ไม่สามารถเบิกได้ในตอนนี้');
						}
						return false;
					}
					//alert(gsn.length);
					$('#chatAudio')[0].play();
					//$("input.serial").removeAttr('disabled');
					$("#newGetBCTable tr:contains('ยังไม่มีรายการเบิก Serial')").remove();
					$("#savegot").removeAttr('disabled');
					$('#newGetBCTable tr.header').after('<tr><td style=\"background-color:#A7EBC0;\" class=\"snTXT\">'+itemBC+'</td><td style=\"background-color:#A7EBC0;\" class=\"center\">ลบ</td></tr>');
					$('#savegotfrm').append('<input type=\"hidden\" name=\"gsn[]\" value=\"'+itemBC+'"\">');
					$("span#countIn_"+modemID).html(count+1);
				}
			 }
		});
	});

	$("#savegot").click(function(){
		$( "#savegotfrm" ).submit();
	});

});
</script>
		<form id="savegotfrm" action="getmodemconfirm.php" method="post">
			<input type="hidden" id="emp" name="emp" value="<?php echo $_COOKIE['uid']?>">
			<input type="hidden"  id="typemovement" name="typemovement" value="out2eng">
		</form>
  <?php
  if($_COOKIE['permission'] == '4'){
  ?>
	<table class="noneborder">
		<tr><td valign="top">
  <?php
		showstockeng($_COOKIE['uid']);
		showStockWaitSN($_COOKIE['uid']);
  ?>
		</td>
		<td valign="top">
			<table id="newGetBCTable">
				<tr class="label"><td colspan="2">-:- รายการเบิก S/N <input type="text" class="serial" id="serial" name="serial"> <span id="sntotal">0</span></td></tr>
				<tr class="header"><td class="center">serial</td><td>ลบ</td></tr>
				<tr><td colspan="2">ยังไม่มีรายการเบิก Serial</td></tr>
				<tr><td colspan="2" align="right"><input type="submit" id="savegot" disabled value="บันทึกเบิก"></td></tr>
			</table>
		</td>
		</tr>
	</table>
  <?php
  }else{
	$engId = $_GET['engId'];

		if($engId <>''){
				echo "<table><tr style=\"border-color:#ffffff;\">";
				?>
					<form action="getmodem.php" method="post" enctype="multipart/form-data">
					<input type="hidden" name="empup" value="<?php echo $engId?>">
				<td style="border-color:#ffffff;"><label for="file"> S/N ที่เบิก</label>
					<input name="fileCSV" type="file" id="fileCSV">
					<input name="btnSubmit" type="submit" id="btnSubmit" value="Submit">
				</td>
					</form>
				</tr></table>
				<table class="noneborder">
					<tr>
						<td style="vertical-align:top;">
					<?php

					showstockeng($engId);
					showStockWaitSN($engId);
					?>
						</td>
						<form action="returnstockback.php" method="post" enctype="multipart/form-data">
						<input type="hidden" name="empup" value="<?php echo $engId?>">
						<td>
							serial ที่จะนำคืนเข้าสต๊อกบริษัท<br>
							<textarea rows="30" cols="60"id="returnstockback" name="returnstockback" ></textarea><br>
							<input type="submit" value=" ส่งคืน ">
						</td>
						</form>
					</tr>
				</table>
					<?php
		}

	}?>
