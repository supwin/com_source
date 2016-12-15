<?php
/*
Log file
070814 1305 : just created
*/

include('cookies.php');
include("functions/function.php");
include("headmenu.php");
if(!checkAllow('sit_pendingstockeng')){
	die(echoError('คุณไม่มีสิทธิ์เข้าใช้งานหน้านี้   <a href="index.php">กลับหน้าแรก</a>'));
}
$strTable = "closedjob";
$strCondition = "series=''";
$objlist = fncSelectConditionRecord($strTable,$strCondition);
?>
<script>
$(document).ready(function(){
	$(".smbmit").click(function(){
		
		var row = $(this).attr('id');
		var sn = $("input#sn_"+row).val();
		var note = $("input#note_"+row).val();

		if(sn=='' || note==''){
			alert('ไม่สามารถปล่อยให้ sn หรือ note เป็นข้อมูลว่างได้');
			return false;
		}
		var closeddate = $("td#cd_"+row).html();
		var indexjob = $("td#idx_"+row).html();
		var cir = $("td#cc_"+row).html();
		var typejob = $("td#tj_"+row).html();
		$.ajax({
		   type: "POST",
		   url: "cutstockpending.php",
		   cache: false,
		   data: "sn="+sn+"&note="+note+"&cir="+cir+"&indexjob="+indexjob+"&closeddate="+closeddate+"&typejob="+typejob,
		   success: function(msg){
			   if(msg.indexOf("login_frm.php") > -1){
					window.location.replace("login_frm.php");
					return false;
				}		
				if(msg==1){
					$("tr#tr_"+row).fadeOut("slow");
				}else if(msg==2){
					openAlert('ไม่สามารถบันทึกฐานข้อมูลได้');
				}else if(msg==0){
					openAlert('ไม่พบ Serial นี้ในฐานข้อมูล');
				}
			}			
		});
	});
});
</script>
<table class="noneborder">
	<tr><td class="noneborder" >				
	<table border='1'>
		<tr class="label"><td colspan="4">-:- รายการงานที่ไม่ได้ปิด</td><td colspan="2" class="right">ตรวจสอบงาน <input type="text" id="cirSearch"><button id="search">ค้นหา</button></td></tr>
		<tr class=header>
			<td>วันที่ปิด</td>
			<td>Job No.</td>
			<td>circuit/สมาชิก</td>	
			<td>ชื่อลูกค้า</td>	
			<td>ประเภทงาน</td>
			<td>Serial No.</td>
			<td>Note</td>
		</tr>
<?php
while($list = mysql_fetch_array($objlist)){
		$r++;
?>
		<tr id='tr_<?php echo $r;?>'>
			<td id="cd_<?php echo $r?>"><?php echo $list['closeddate']?></td>
			<td id="idx_<?php echo $r?>"><?php echo $list['indexjob']?></td>
			<td id="cc_<?php echo $r?>"><?php echo $list['circuit']?></td>
			<td><?php echo $list['cust_name']?></td>
			<td id="tj_<?php echo $r?>"><?php echo $list['typejob']?></td>
			<td><input type="text"class="serialcut" id="sn_<?php echo $r?>"></td>
			<td><input type="text" id="note_<?php echo $r?>" class="notecut"> <button class="smbmit" id="<?php echo $r?>" >บันทึก</button></td>
		</tr>
<?php
}
?>
