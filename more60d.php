<?php
include('cookies.php');
include('functions/function.php');
include("headmenu.php");

if(!checkAllow('sit_returnbackstock')){
	echo "คุณไม่มีสิทธิ์ใช้งานหน้านี้";
}
$strStar = "brand,model,sn,date_created,responcible,lot_id,DATEDIFF(now(),date_created) AS datedepend";
$strTable = "eqm_sn join eqm_model on id_eqm=eqm_model.id";
$strCondition = "responcible=0 and back_lotid=0 and date_created>'2015-11-01' order by date_created";
$modlst = fncSelectStarConditionRecord($strStar,$strTable,$strCondition);
//echo "SELECT $strStar FROM $strTable WHERE $strCondition ";
		?>
<script>
$(document).ready(function(){

	$("select").change(function(){
		val = $(this).val();
		id = $(this).attr('selid');
		selectedindexnum = $(this).prop('selectedIndex');
		$('select').prop('selectedIndex',0);
		$('input').attr('disabled',true);
		$('button').attr('disabled',true);


		$("#citcuittxt_"+id).removeAttr('disabled');
		$("#notetxt_"+id).removeAttr('disabled');
		$("#but_"+id).removeAttr('disabled');
		$(this).prop('selectedIndex',selectedindexnum);
	});

	$("button").click(function(){
		id = $(this).attr('butid');
		oldresp = $(this).attr('oldresp');
		cir = $("#citcuittxt_"+id).val();
		note = $("#notetxt_"+id).val();
		sn = $("#sntxt_"+id).text();
		reasonoption = $("#reasonoption_"+id).val();

		$.ajax({
			type: "POST",
			url: "changesnstatus.php",
			cache: false,
			data: "sn="+sn+"&reason="+reasonoption+"&cir="+cir+"&note="+note+"&oldresp="+oldresp,
			success: function(msg){
				//alert("msg="+msg);
				//return false;
				if(msg.indexOf("login_frm.php") > -1){
					window.location.replace("login_frm.php");
					return false;
				}
				if(msg==1){
					$("#tr_"+id).fadeOut('xslow', function(){
						$("#tr_"+id).remove();
					});
				}else{
					openAlert(msg);
				}
			}
		});

	});

});
</script>
รายการสต๊อกบริษัทมีอายุมากกว่า 60 วัน
			<table>
				<tr class="header">
					<td>Lot-ID</td>
					<td>ยี่ห้อ/รุ่น</td>
					<td>Date In Stock</td>
					<td>Serial</td>
					<td>จำนวนวัน</td>
					<?php
					if(checkAllow('sit_confstkin')){
					?>
						<td>ตัดเพื่อ</td>
						<td>Circuit</td>
						<td>บันทึกช่วยจำ</td>
					<?php
					}
					?>
				</tr>
			<?php
			while($md = mysql_fetch_array($modlst)){
				$idr++;
				$color = "red";
				if($md['datedepend']<60) $color = "";
				?>

				<tr id="tr_<?php echo $idr?>" style="background-color:<?php echo $color;?>">
					<td><?php echo $md['lot_id']?></td>
					<td><?php echo $md['brand']?>/<?php echo $md['model']?></td>
					<td><?php echo convdateMini($md['date_created'])?></td>
					<td><span id="sntxt_<?php echo $idr?>" title="<?php echo $md['brand']?>/<?php echo $md['model']?>"><?php echo $md['sn']?></span></td>
					<td><?php echo $md['datedepend'];?></td>
					<?php
					if(checkAllow('sit_confstkin')){
					?>
						<td>
							<select id="reasonoption_<?php echo $idr?>" selid="<?php echo $idr?>">
								<option value="0"><?php echo $md['sn']?></option>
								<?php if($boss=='1' OR $boss=='4' OR $boss=='122') { ?>
								<option value="9091">9091=หักค่าปรับไว้แล้ว</option>
								<?php } else {} ?>
								<option value="9096">9096=ตัดสต๊อก ADSL,AP</option>
								<option value="9097">9097=ตัดสต๊อกไม่ปิดงาน</option>
								<option value="9098">9098=ของเสีย/ส่งคืน</option>
								<option value="9100">9100=ตัดสต๊อกจุดเสริม</option>
								<option value="9111">9111=ผ่อนผัน</option>
								<option value="9198">9198=ของเสีย/ส่งคืน(รอรับ)</option>
								<option value="9199">9199=ปิดงานไม่ได้</option>
							</select>
						</td>
						<td><input type="text" disabled="true" name="citcuittxt_<?php echo $idr?>" id="citcuittxt_<?php echo $idr?>"></td>
						<td><input type="text" size="35" disabled="true" name="notetxt_<?php echo $idr?>" id="notetxt_<?php echo $idr?>"><button oldresp="<?php echo $md['responcible'];?>" disabled="true" id="but_<?php echo $idr?>" butid="<?php echo $idr?>">บันทึก</button></td>
					<?php
					}
					?>
				</tr>
			<?php
			}
			?>
			</table>
