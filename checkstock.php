<?php
/*
Log file 
200714 1836 : just created.
210714 1311 : update ให้เมื่อสแกนบาร์โค้ดและประมวลผล เรียบร้อยให้ทำให้ input text box เป็นค่าว่างด้วย
161014 2053 : เพิ่ม $.trim() ให้กับ serial
*/

include('cookies.php');
include("functions/function.php");
include("headmenu.php");
if(!checkAllow('sit_checkstock')){
	die(echoError('คุณไม่มีสิทธิ์ใช้งานหน้า'));
}
$strTable = "eqm_sn,eqm_model";
$strCondition = "responcible=0 and circuit='' and id_eqm=id";
$strSort = " order by type";
$stkList = fncSelectConditionRecord($strTable,$strCondition,$strSort);
?>
<script>
$(document).ready(function(){
	$('tr[class="snlst"]:even').css('background-color', '#DFFBED');
	$('tr[class="snlst"]:odd').css('background-color', '#ffffff');	

	$('<audio id="chatAudio"><source src="sounds/Air Plane Ding-SoundBible_com-496729130_2.ogg" type="audio/ogg"><source src="sounds/Air Plane Ding-SoundBible_com-496729130_2.mp3" type="audio/mpeg"><source src="sounds/Air Plane Ding-SoundBible_com-496729130_2.wav" type="audio/wav"></audio>').appendTo('body');
	$('<audio id="mistAudio"><source src="sounds/Bike Horn-SoundBible_com-602544869.ogg" type="audio/ogg"><source src="sounds/Bike Horn-SoundBible_com-602544869.mp3" type="audio/mpeg"><source src="sounds/Bike Horn-SoundBible_com-602544869.wav" type="audio/wav"></audio>').appendTo('body');
	$('<audio id="dupiAudio"><source src="sounds/Banana_Slap-AngryFlash-2001109808.ogg" type="audio/ogg"><source src="sounds/Banana_Slap-AngryFlash-2001109808.mp3" type="audio/mpeg"><source src="sounds/Banana_Slap-AngryFlash-2001109808.wav" type="audio/wav"></audio>').appendTo('body');
	
	$('input#txtSearch').change(function() {
		sn = $('input#txtSearch').val();
		if(sn.lenght<9){
			return false;
		}
		$.ajax({
		   type: "POST",
		   url: "checkseriesstock.php",
		   cache: false,
		   data: "sn="+sn,
		   success: function(msg){
			   if(msg.indexOf("login_frm.php") > -1){
					window.location.replace("login_frm.php");
					return false;
				}
				if(msg==1){
					var row = $('tr#row_'+sn).html();
					$('tr#row_'+sn).fadeOut('slow');
					$('#chatAudio')[0].play();
					$('#tblresult tbody').append('<tr class=\"snlst\">'+row+'<td><?php echo convdateMini(date("Y-m-d"),1)?></td><td><?php echo nameofengineer($_COOKIE['uid'])?></td></tr>');
					$('tr[class="snlst"]:even').css('background-color', '#DFFBED');
					$('tr[class="snlst"]:odd').css('background-color', '#ffffff');
					$('input#txtSearch').val('');
				}else if(msg==0){
					openAlert('ไม่มี serial หมายเลข '+sn+' นี้ในสต๊อกของบริษัทนะ');
					$('#mistAudio')[0].play();
				}else if(msg==2){
					openAlert('serial หมายเลข '+sn+' อยู่ในรายการ ตีเสีย / ส่งคืน');
					$('#mistAudio')[0].play();
				}else if(msg==3){
					openAlert('serial หมายเลข '+sn+' ได้ถูกใช้ติดตั้งให้กับลูกค้าไปแล้ว');
					$('#mistAudio')[0].play();
				}else{
					openAlert('serial หมายเลข '+sn+' เป็นอุปกรณ์ที่ถูกเบิกโดย '+msg+' ให้นำส่งด้วย');
					$('#mistAudio')[0].play();
				}
		   }				   
		 });
	});
	
	$("#btncheckstock").click(function(){
		serialLst = $("#serialLst").val();		
		$.ajax({
		   type: "POST",
		   url: "checkserielist.php",
		   cache: false,
		   data: "snlst="+serialLst,
		   success: function(msg){
				var arr = $.parseJSON(msg);
				//$("#serialLst").val(arr['sn']+','+arr['result']);
				$.each(arr, function(i,v) {
					//$("#serialLst").val(v['result']+','+v['sn']);
					retxt = v['result'];
					sntxt = $.trim(v['sn']);
					if(retxt==1){
						var row = $('tr#row_'+sntxt).html();
						$('tr#row_'+sntxt).fadeOut('slow');
						$('#tblresult tbody').append('<tr class=\"snlst\">'+row+'<td><?php echo convdateMini(date("Y-m-d"),1)?></td><td><?php echo nameofengineer($_COOKIE['uid'])?></td></tr>');
						//$('tr[class="snlst"]:even').css('background-color', '#DFFBED');
						//$('tr[class="snlst"]:odd').css('background-color', '#ffffff');
					}else if(retxt==0){				
						txtshow = 'ไม่มี serial หมายเลข '+sntxt+' นี้ในสต๊อกของบริษัทนะ';
						$('#tblresult tbody').append('<tr><td style=\"background-color:red;\" colspan=\"6\">'+txtshow+'</td></tr>');
					}else if(retxt==2){	
						txtshow = 'serial หมายเลข '+sntxt+' อยู่ในรายการ ตีเสีย / ส่งคืน'
						$('#tblresult tbody').append('<tr><td style=\"background-color:red;\" colspan=\"6\">'+txtshow+'</td></tr>');
					}else if(retxt==3){	
						txtshow = 'serial หมายเลข '+sntxt+' ได้ถูกใช้ติดตั้งให้กับลูกค้าไปแล้ว'
						$('#tblresult tbody').append('<tr><td style=\"background-color:red;\" colspan=\"6\">'+txtshow+'</td></tr>');
					}else{	
						txtshow = 'serial หมายเลข '+sntxt+' เป็นอุปกรณ์ที่ถูกเบิกโดย '+retxt+' ให้นำส่งด้วย'
						$('#tblresult tbody').append('<tr><td style=\"background-color:red;\" colspan=\"6\">'+txtshow+'</td></tr>');
					}
				});
		   }
		});	
	});
	
});
</script>
<table class="noneborder">
	<tr><td class="noneborder" >	
	<table border=1 style="margin-top:10px;" id="tblSearch">
		<tr class="label"><td colspan="4">-:- รายการสต๊อกของบริษัท (ในระบบ)</td><td colspan="2" align="right">ค้นหา s/n <input type="text" name="txtSearch" id="txtSearch" /></td></tr>
		<tr class="header">
			<td>ลำดับ</td>
			<td>Lot-ID</td>
			<td>วันที่เข้าสต๊อก </td>
			<td>ประเภท</td>
			<td>รุ่น</td>
			<td>series</td>		
			<!--<td>ประเภทงาน</td>-->
		</tr>
	<?php
		$c = 1;
		$st=1;
		while($stk = mysql_fetch_array($stkList)){		
			echo "<tr id=\"row_".$stk['sn']."\" class=\"snlst\">";
			echo "<td class=center>$st</td>";
			echo "<td>$stk[lot_id]</td>";
			echo "<td>".convdateMini($stk[date_created])."</td>";
			echo "<td>$stk[type]</td>";
			echo "<td>$stk[model]</td>";
			echo "<td>$stk[sn]</td>";		
			echo "<tr>";
			
			$st +=1;
		}
	?>
	</table>
	</td>
	<td>
		<form action="difftucstock.php" method="post" enctype="multipart/form-data">
			<label for="file"> S/N จาก TUC ที่ต้องการค้นหา</label>
			<input name="fileCSV" type="file" id="fileCSV">
			<input name="btnSubmit" type="submit" id="btnSubmit" value="Submit">
		</form>
	<table border=1 style="margin-top:10px;" id="tblresult">
		<tr class="label"><td colspan="3">-:- รายการอุปกรณ์ที่เช็คแล้ว</td><td colspan="3" align="right"></td></tr>
		<tr class="header">
			<td>ลำดับ</td>
			<td>ประเภท</td>
			<!--<td>ยี่ห้อ</td>-->
			<td>รุ่น</td>
			<td>series</td>		
			<td>วันที่เช็คสต๊อก</td>	
			<td>ผู้ตรวจเช็ค</td>		
		</tr>
	</table>
	<hr>
	<div style="margin-top:45px;">
	<p>ยิงบาร์โค้ดทั้งหมดทีนี่แล้วกดปุ่ม "เช็คสต๊อก"</p>
	<textarea id="serialLst" rows="20" cols="35"></textarea>
	<br>
	<button id="btncheckstock"> เช็คสต๊อก </button>
	</div>
	</td>
</table>
