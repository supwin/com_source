<?php
/*
Log file
071014 1446 : ทำให้สามารถรับของบางส่วนได้ถูกต้อง
101114 0645 : ทำให้สามารถ cancel po ได้เมื่อมีสถานะ sent โดยเฉพาะผู้มีสิทธิ์เท่านั้น
*/

include('cookies.php');
include('functions/function.php');
include("headmenu.php");

if(!checkAllow("sit_viewpo")){
	echoError("คุณไม่มีสิทธิ์ ใช้งานหน้านี้ <a href=\"index.php\">กลับหน้าแรก</a>");
	exit();
}

?>
<script>
$(document).ready(function(){
	$('tr[name="item"]:even').css('background-color', '#DFFBED');
	$('tr[name="item"]:odd').css('background-color', '#ffffff');
	//$("span#getdraftno").click(function(){
	$(".createpobut").click(function(){
		supplierno = $(this).attr('supplierno');
		//alert(supplierno);
		$.ajax({
		   type: "POST",
		   url: "createpo.php",
		   cache: false,
		   data: "supplierno="+supplierno,
		   success: function(msg){
				alert(msg);
				location.reload();
		   }
		});
	});


	$('.cancelOrder').click(function(){
		if(!confirm("ยืนยันการยกเลิกใบสั่งซื้อกด \"yes\"")) return false;
		var id = $(this).attr("id");
		$.ajax({
		   type: "POST",
		   url: "cancelpo.php",
		   cache: false,
		   data: "id="+id,
		   success: function(msg){
			if(msg=='1'){
				openAlert('ยกเลิกเรียบร้อย');
				window.location = 'po.php';
			}else{
				openAlert('ไม่สามารถยกเลิกได้');
			}
		   }
		});
	});

	<?php
		if($_GET['pono']<>''){?>
			var id = "<?php echo $_GET['pono']?>";
			$.ajax({
			   type: "POST",
			   url: "showpo.php",
			   cache: false,
			   data: "id="+id,
			   success: function(msg){
				$(".showpodiv").html(msg);
			   }
			});
		<?php
		}
	?>


	$('.orderdetail').click(function(){
		var id = $(this).attr("id");
		$.ajax({
		   type: "POST",
		   url: "showpo.php",
		   cache: false,
		   data: "id="+id,
		   success: function(msg){
			$(".showpodiv").html(msg);
		   }
		});

		$("html, body").animate({ scrollTop: 0 }, "slow");
		return false;
	});

});
</script>
<?php
$strTable = "stock_acs";
$strCondition = 'temporder<>0';
$strSort = "order by sequence asc";
$accList = fncSelectConditionRecord($strTable,$strCondition,$strSort);

	$row = 1;
	while($acc = mysql_fetch_array($accList)){
		$supNo = $acc['supplier'];
		$r[$supNo]++;
		$sup[$supNo] .= "<tr><td class=\"center\">".$r[$supNo]."</td><td>".$acc[name]."</td><td>".$acc[temporder]."</td><td align=\"right\">".$acc[cost]."</td><td align=\"right\">".number_format($acc[cost]*$acc[temporder],2)."</td></tr>";
		$sumSup[$supNo] += $acc['cost']*$acc['temporder'];

		if(!in_array($supNo,$spArray)){
			$spArray[] = $supNo;
		}
	}


?>
<form action="createpo.php" method="post"><input type="hidden" id="temporder" value="">
<table class="noneborder">
	<tr>
		<td class="noneborder" style="vertical-align:text-top;">
		<?php
		if(checkAllow('sit_confirmpo')){

			mysql_select_db("tidnet_accounting");
			$strTableAcc = "partners";
			$strConditionAcc = "showat like '%@vendor@%' and showat not like '%@cancleonstock@%'";
			$strSortAcc = "order by id";
			$allSup = fncSelectConditionRecord($strTableAcc,$strConditionAcc,$strSortAcc);
			while($supp = mysql_fetch_array($allSup)){
				$supplierObj_short_thname[$supp['id']] = $supp['short_thname'];
			}
			mysql_select_db("tidnet_".ABVT);

			for($spi = 0; $spi<count($spArray); $spi++){
				if($sup[$spArray[$spi]]<>''){
				?>
					<div style="vertical-align:text-top;">
						<table id="bill">
							<tbody>
							<tr class="header">
								<td>ลำดับ</td>
								<td>รายการ</td>
								<td>จำนวน</td>
								<td>ราคา</td>
								<td>รวมราคา</td>
							</tr>
								<?php echo $sup[$spArray[$spi]];?>
							</tbody>
						</table><input type="hidden" value="0" id="seqbill">
						<span class="button createpobut" style="padding-top:5px;" <?php echo "supplierno=".$spArray[$spi];?>> สร้าง PO. <?php echo $supplierObj_short_thname[$spArray[$spi]]?></span><span style="color:#fff">.....................</span><span><?php echo number_format($sumSup[$spArray[$spi]],2);?> บาท</span>
					</div>
				<?php
				}
			}
		}
		$strTable = "po_header";
		$strCondition = "1";
		$sort = "order by pono DESC";
		$polist = fncSelectConditionRecord($strTable,$strCondition,$sort);
		?>
		<div style="margin-top:10px;">
			<p class="header">รายการ PO</p>
			<hr style="margin-bottom:10px;">
				<table>
					<tr class="header">
						<td align="center">หมายเลข</td><td align="center">วันที่สร้าง</td><td align="center" width="100">Vendor</td><td align="center">สถานะ</td><td align="center">ปุ่มควบคุม</td>
					</tr>
			<?php
			while($po = mysql_fetch_array($polist)){
				$aCancel = '';
				$statusPO = detailStatus($po[status]);
				$sentPO = '';
				if($po[status]=='new'){
					$aCancel = "<span class=\"cancelOrder button\" id=\"".$po[pono]."\">ยกเลิก</span> |";
					if(checkAllow("sit_sentpo")) $sentPO = " <a href=\"printing/prtpo.php?pono=".$po[pono]."\" target=\"_blank\" class=\"createpdf\" id=\"".$po[pono]."\">ส่งเมล์สั่งซื้อ</a> |";
				}

				if(checkAllow('sit_cancelsentpo') and $po[status]=='sent'){
					$aCancel = "<span class=\"cancelOrder button\" id=\"".$po[pono]."\">ยกเลิก</span> |";
				}
				?>
					<tr>
						<td><?php echo strtoupper(constant("ABVT"))."_PO-No.-".$po[pono]?></td><td align="right"><?php echo convdateMini($po[createddate],0) ?></td><td><?php echo $supplierObj_short_thname[$po['supplier']] ?></td><td><?php echo $statusPO ?> <a href="printing/prtpo.php?pono=<?php echo $po[pono]?>" target="_blank" class="createpdf" id="<?php echo $po[pono]?>">ใบสั่งซื้อ</a></td><td align="right"><?php echo $aCancel ?><?php echo $sentPO?> <span class="orderdetail button" id="<?php echo $po[pono]?>">รายละเอียด</span></td>
					</tr>
				<?php
			}
			?>
			</table>
		</div>
		</td>
		<td>
			<div class="showpodiv"></div>
		</td>
	</tr>
</table>
</form>
