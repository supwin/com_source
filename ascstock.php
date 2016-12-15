<?php
/*
Log file
250814 0854 : เพิ่มส่่วนจำกัดสิทธิ์ ในการนำสต๊อกอะไหล่เข้า
020914 0902 : แก้ bug เรื่อง between
200914 1816 : เพิ่ม feature ในการบันทึก min stock และ  temp order
220914 1227 : เพิ่มความสามารถให้ ตรวจสอบการใส่ temp order ไม่ได้ หากไม่มี supplier รองรับ
290914 1350 : แก้ไขหัวข้อ คอรัมภ์ ให้เป็น "สั่งของเข้า"
021014 0850 : เพิิ่่มความสามารถในการจดจำผู้ทำรายการสั่งซื้อได้  หากมีการกดปุ่ม "ยืนยันการสั่ง" ถึงแม้ session หมดอายุก็ตาม
151014 2239 : แก้ไขวิธีการเช็คสต๊อกปัจจุบันด้วย class แทนจำนวนคอรัมภ์
151014 2252 : เพิ่มเติมให้ตรวจสอบตัวเลขเท่านั้นในช่องสั่งเข้า
061114 1719 : แก้ไขให้นำ desciption มาใช้ให้ถูกต้อง
140115 1303 : เพิ่มเติมให้มี field available เพื่อแยกอะไหล่ที่ไม่มีจำหน่ายออกจากหน้ารายการอะไหล่
*/

include('cookies.php');
include('functions/function.php');
include("headmenu.php");
?>
<script>
$(document).ready(function(){
	$('tr[name="item"]:even').css('background-color', '#DFFBED');
	$('tr[name="item"]:odd').css('background-color', '#ffffff');

	$("span.intostock").click(function(){
		//var acsId = $(this).val();
		var acsId = $(this).attr('value');
		var qty2stk = $("#intostock_"+acsId).val();
		var namerek = $("#remk_"+acsId).val();
		$.ajax({
		   type: "POST",
		   url: "intoaccstock.php",
		   cache: false,
		   data: "acsId="+acsId+"&qty2stk="+qty2stk+"&namerek="+namerek,
		   success: function(msg){
			 if(msg==1){
				window.location.reload();
			 }else{
				openAlert("ไม่สามารถนำเข้าสต๊อกได้");
			 }
		   }
		});
	});

	$("input.num").keyup(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A
            (e.keyCode == 65 && e.ctrlKey === true) ||
             // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

	$("input.ordernum").focus(function(){
		$("#temporder").val($(this).val());
	});


	$("input.ordernum").blur(function(){
		qty = $(this).val();

		if(qty==''){ // ควรจะแก้ให้เป็นยกเลิกการสั่งซื้อ Item นี้
			$(this).val($("#temporder").val());
			return false;
		}

		if(!$.isNumeric($(this).val())){
			openAlert('ใส่ได้เฉพาะตัวเลขดิ');
			$(this).val($("#temporder").val());
			return false;
		}

		if((qty<=0) && ($("#dofor").text()=='เบิก')){
			$(this).val($("#temporder").val());
			return false;
		}

		acsid = parseInt($(this).attr('id'),10);
		//acsstock = parseInt($(this).parents('tr#'+acsid).find('td:eq(6)').html(),10);  // คอรัมภ์ จำนวนปัจจุบัน "สต๊อก"
		acsstock = parseInt($("td.qty_"+acsid).html(),10);
		//idrow = $('#bill tr td').find(':last-child:eq(0)').text();
		//alert(acsstock);
		//return false;
		if((qty > acsstock) && ($("#dofor").text()=='เบิก')){
			openAlert('ไม่สามารถเบิก เกินจำนวนสต๊อกที่มีอยู่ได้');
			$(this).val($("#temporder").val());
			return false;
		}

		if($("#dofor").text()=='คืน'){
			qty = qty*-1;
			//$("input#"+acsid).val() = qty;
		}
		acsname = $("#desc_"+acsid).text();
		//acsname = $(this).parents('tr#'+acsid).find('td:eq(1)').html();
		//price = $(this).parents('tr#'+acsid).find('td:eq(5)').html();
		price = $("input#p_"+acsid).val();
		totalprice = parseInt(qty*price,10);

		seqbill = parseInt($("input#seqbill").val(),10) + 1;
		$("#bill tr:contains('ยังไม่มีรายการสั่ง')").remove();
		existRow = $('#bill tbody').find('tr#'+acsid+' td:eq(2)').text();
		if(existRow==''){
			$('#bill tbody').append('<tr class="child" id="'+acsid+'"><td class=center>'+seqbill+'</td><td>'+acsname+'</td><td style=\"text-align:right\">'+qty+'</td><td>'+totalprice+' บาท [@'+price+' บาท]</td></tr>');
			$("input#seqbill").val(seqbill);
			gSumtotal = parseInt($("#sumtotal").val(),10);
			$("#sumtotal").val(gSumtotal+totalprice);
		}else{
			$('#bill tbody').find('tr#'+acsid+' td:eq(2)').text(qty);
			$('#bill tbody').find('tr#'+acsid+' td:eq(3)').text(totalprice+' บาท [@'+price+' บาท]');
			$("#sumtotal").val(parseInt($("#sumtotal").val(),10) - (parseInt(existRow,10)*price) + (qty*price));
		}

		return false;
	});

	$('a.orderdetail').click(function(){
		var id = $(this).attr("id");
		var billto = $(this).attr("billto");
		$.ajax({
		   type: "POST",
		   url: "showordering.php",
		   cache: false,
		   data: "id="+id+"&billto="+billto,
		   success: function(msg){
				$("div.showbill").remove();
				$( "div#showorderpending").append( "<div class=\"showbill\">"+msg+"</div>" );
		   }
		});
	});

	$('a.billdetail').click(function(){
		var id = $(this).attr("id");
		var billto = $(this).attr("billto");
		$.ajax({
		   type: "POST",
		   url: "showbilling.php",
		   cache: false,
		   data: "id="+id+"&billto="+billto,
		   success: function(msg){
			$("div.showbillget").remove();
			$( "div#showorderget").append( "<div class=\"showbillget\">"+msg+"</div>" );
		   }
		});
	});

	$('a.cancelOrder').click(function(){
		var id = $(this).attr("id");
		$.ajax({
		   type: "POST",
		   url: "cancelorder.php",
		   cache: false,
		   data: "id="+id,
		   success: function(msg){
			if(msg=='1'){
				openAlert('ยกเลิกใบเบิกเรียบร้อย');
			}else if(msg=='closed'){
				openAlert('ใบงานนี้ปิดไปแล้ว ไม่สามารถยกเลิกได้');
			}else{
				openAlert('ใบงานนี้มีสถานะ "'+msg+'" ให้จดไว้แล้วแจ้งพี่หนึ่งด่วน');
			}
			window.location = 'ascstock.php';
		   }
		});
	});



	$("#dofor").click(function(){
		var txt = $("#dofor").text();
		if(txt=='เบิก'){
			$("#dofor").text('คืน');
			$("input.typeget").val(-1);
			$(".ordernum").prop('disabled', false);
		}
		if(txt=='คืน')	location.reload();
	});


	$("span.intostock").click(function(){
		$("span.intostock").fadeOut( "slow" );
	});



	$("input.chgcstSup").focus(function(){
		$("#temporder").val($(this).val());
	});

	$("input.chgcstSup").blur(function(){
		cost = $(this).val();

		if($("#temporder").val() == cost) return false;

		//if( == $("#temporder").val()) return false;

		if(!$.isNumeric($(this).val())){
			openAlert('ใส่ได้เฉพาะตัวเลขดิ');
			$(this).val($("#temporder").val());
			return false;
		}

		supplier = $(this).attr("supplier");
		acccodename = $(this).attr("for");
		//alert(accid+' '+supplier+' '+cost);
		$.ajax({
		   type: "POST",
		   url: "changecostsup.php",
		   cache: false,
		   data: "acccodename="+acccodename+"&cost="+cost+"&supplier="+supplier,
		   success: function(msg){
			if(msg.indexOf("login_frm.php") > -1){
				window.location.replace("login_frm.php");
				return false;
			}
			if(msg=='0'){
				openAlert('ไม่สามารถปรับราคาได้ กรุณาตรวจสอบ');
				return false;
			}
			//$(this).val($.number(cost,2));
			$("#show").html(msg);
		   }
		});
	});


	$("input.clikckupdate").focus(function(){
		$("#temporder").val($(this).val());
	});

	$("input.clikckupdate").blur(function(){
		if(!$.isNumeric($(this).val())){
			openAlert('ใส่ได้เฉพาะตัวเลขดิ');
			$(this).val($("#temporder").val());
			return false;
		}

		accid = $(this).attr("id");
		qty = $(this).val();
		forf = $(this).attr("for");
		//alert(accid+' '+qty+' '+forf);
		$.ajax({
		   type: "POST",
		   url: "updatedb.php",
		   cache: false,
		   data: "accid="+accid+"&qty="+qty+"&forf="+forf,
		   success: function(msg){
			if(msg.indexOf("login_frm.php") > -1){
				window.location.replace("login_frm.php");
				return false;
			}
		   }
		});
	});

	/*
	$("#orderSubm").click(function(){
		if(parseInt($("#sumtotal").val(),10)<=0) return false;
		var round =supselect 0;
		$('#bill tr').each(function() {
			var item = {};
			item[round] = [$(this).attr('id'), $(this).find('td:eq(2)').text()];
			round = round +1;
		});
	});	*/

	$(".supselect").change(function(){
		//cf = confirm("แน่ใจว่าต้องการเปลี่ยน supplier");
		//if(!cf) return false;
		cdname = $(this).attr('for');
		sup = $(this).val();
		$.ajax({
		   type: "POST",
		   url: "changesupplier.php",
		   cache: false,
		   data: "cdname="+cdname+"&sup="+sup,
		   success: function(msg){
			if(msg.indexOf("login_frm.php") > -1){
				window.location.replace("login_frm.php");
				return false;
			}
			$("#show").html(msg);
			/*if(msg==1){
				alert('เปลี่ยน supplier เรียบร้อย');
			}else{
				alert('ไม่สามารถเปลี่ยน supplier ได้');
			}*/
		   }
		});
	});

  });
 </script>

<style>
#preview{
	position:absolute;
	border:3px solid #ccc;
	background:#333;
	padding:5px;
	display:none;
	color:#fff;
	box-shadow: 4px 4px 3px rgba(103, 115, 130, 1);
}
</style>


<?php

$strTable = "stock_acs";
$strCondition = "available=1";
if(checkAllow('sit_viewall')){
	$strCondition = " 1";
	$strSort = "order by available desc";
}else{
	$strSort = "order by sequence asc";
}
$accList = fncSelectConditionRecord($strTable,$strCondition,$strSort);
?>
<div id="show"></div>
<?php if(checkAllow('sit_viewstockvalue')){?>
	<div id="sumstockvalue"></div>
<?php }?>
<table class="noneborder"><input type="hidden" id="temporder" value="">
	<tr>
		<td class="noneborder" style="vertical-align:text-top;">
	<table border=1>
	<form action="orderingacs.php" method="post">
		<tr class="header"><input type="hidden" name="typeget" class="typeget" value="1">
			<?php
			if(checkAllow('sit_changeavailable')){?>
				<td>ใช้งาน</td>
			<?php
			}
			?>
			<td>ลำดับ</td>  <!--ถ้าจะมีการขยับ คอรัมภ์ จะต้องแก้ตัวเลขคอรัมภ์สต๊อกให้ด้วย เพราะมีการเช็คจำนวสต๊อกปัจจุบันจาก คอรัภม์นั้น -->
			<!--<td>ชื่อ</td>
			<td>update</td>	-->
			<td>ภาพ</td>
			<td>รายการ</td>
			<?php

			mysql_select_db("tidnet_accounting");
			$strTableAcc = "partners";
			$strConditionAcc = "showat like '%@vendor@%' and showat not like '%@cancleonstock@%'";
			$strSortAcc = "order by id";
			$allSup = fncSelectConditionRecord($strTableAcc,$strConditionAcc,$strSortAcc);
			while($supp = mysql_fetch_array($allSup)){
				if(checkAllow('sit_changecost') and $_GET['chgcst']==1)	echo "<td>".$supp['short_thname']."</td>";
				$supplierObj_id[] = $supp['id'];
				$supplierObj_short_thname[] = $supp['short_thname'];

			}
			mysql_select_db("tidnet_".ABVT);

			if(!$_GET['chgcst']){
				if(checkAllow('sit_minstock') and checkAllow('sit_temporder')){?>
					<td>ต่ำสุด</td>
				<?php }
				if(checkAllow('sit_changesupplier')){
					if($_GET['chgcst']==1){
						echo "<td><a href=\"ascstock.php\">supplier</a></td>";
					}else{
						echo "<td><a href=\"ascstock.php?chgcst=1\">supplier</a></td>";
					}
				}
				if(checkAllow('sit_temporder')){?>
					<td>สั่งเข้า</td>
					<td>รอเข้า</td>
				<?php
				}
				?>
				<td colspan="2">สต๊อก</td>
				<td>ราคา</td>
				<?php
				if($_COOKIE['permission']=='4' or $_COOKIE['permission']=='20'){
					?>
					<td><span id="dofor">เบิก</span></td>
					<?php
				}
				?>
				<?php if(($_COOKIE[permission]==1) or ($_COOKIE[uid]==1) or(checkAllow('sit_stockin'))){?>
				<td>ยอดเข้า / หมายเหตุ</td>
				<?php }
			}?>
		</tr>

<?php
		$row = 1;
		$strTableSupp = "supplier_cost";
		$strSortSupp = "order by supplier_id";

		while($acc = mysql_fetch_array($accList)){
			if($acc[qty]<=0) $disabled = "disabled";
			echo "<tr id=\"".$acc[id]."\" name=\"item\">";
			//echo "<td class=center>$acc[sequence]</td>";
			if(checkAllow('sit_changeavailable')){
				$checked = "";
				if($acc[available]){
					$checked = "checked";
				}
				echo "<td class=center><input type=\"checkbox\" ".$checked."></td>";
			}
			echo "<td class=center>$row</td>";
			//echo "<td>".convdateMini($acc[date_update],0)."</td>";
			//echo "<td>$acc[name]</td>";
			echo "<td><a href=\"http://tidnet.co.th/com_source/img/asc/".$acc[code_name]."_R.jpg\" class=\"preview\" title=\"".$acc[description]."\"><img src=\"http://tidnet.co.th/com_source/img/asc/".$acc[code_name].".jpg\" width=\"30\" height=\"30\" align=\"middle\"></a></td>";
			echo "<td><span title=\"".$acc[name]."\" id=\"desc_".$acc[id]."\">$acc[description]</span></td>";

			$sumCost += $acc['qty']*$acc['cost'];
			$sumPrice += $acc['qty']*$acc['price'];

			if(checkAllow('sit_changecost') and $_GET['chgcst']==1){
				$strConditionSupp = "acs_codename='".$acc['code_name']."' and supplier_id in (".join(',',$supplierObj_id).")";
				$allSuppCost = fncSelectConditionRecord($strTableSupp,$strConditionSupp,$strSortSupp);
				//echo "SELECT * FROM $strTableSupp WHERE $strConditionSupp  $strSortSupp";
				while($suppCost = mysql_fetch_array($allSuppCost)){
					if($suppCost['cost']<=0){
						$costTxt = '...';
					}else{
						$costTxt = $suppCost['cost'];
					}
					echo "<td><input type=\"text\" value=\"".$costTxt."\" for=\"".$acc['code_name']."\" supplier=\"".$suppCost['supplier_id']."\" class=\"chgcstSup minstockbox\" style=\"width:50px;text-align:right;\"></td>";
				}

			}else if(!$_GET['chgcst']){

				if(checkAllow('sit_minstock') and checkAllow('sit_temporder')){
					echo "<td class=center><input type=\"text\" for=\"minstock\" class=\"clikckupdate minstockbox\" id=\"".$acc[id]."\" value=\"".$acc[minstock]."\" size=\"1\" style=\"text-align:right;\"></td>";
				}

				if(checkAllow('sit_changesupplier')){
					?>
					<td>
						<select id="sup_<?php echo $acc['id'];?>" for="<?php echo $acc['code_name'];?>" class="supselect">
							<option value='0' <?php echo $sel_1;?>>เลือกผู้ขาย</option>
						<?php
							for($i=0; $i<count($supplierObj_id); $i++){
								$selectedSupp = "";
								if($supplierObj_id[$i]==$acc['supplier']) $selectedSupp = "selected";
								echo "<option value='".$supplierObj_id[$i]."' ".$selectedSupp.">".$supplierObj_short_thname[$i]."</option>";
							}
						?>
						</select>
					</td>
				<?php
				}
				if(checkAllow('sit_temporder')){
					$dis = '';
					if($acc[supplier]==0) $dis = 'disabled';

					$colorbg = '';
					$titleTxt = "ใส่จำนวนที่ต้องการสั่งซื้อเข้า";
					if($acc[minstock]==$acc[qty] and $acc[minstock]>0){
						$colorbg = "yellow";
						$titleTxt = "จำนวนเหลือน้อย ควรสั่ง";
					}
					if($acc[minstock]>$acc[qty]){
						$colorbg = "orange";
						$titleTxt = "จำนวนน้อยกว่าที่ควร ต้องรีบสั่ง";
					}
					if($acc[qty]<=0 and $acc[minstock]>0){
						$colorbg = "red";
						$titleTxt = "จำนวนน้อยจนวิกฤต ต้องสั่งทันที";
					}
					echo "<td class=center><input type=\"text\" ".$dis." title=\"".$titleTxt."\" style=\"background-color : ".$colorbg.";text-align:right;\" for=\"temporder\" class=\"clikckupdate temporderbox\" id=\"".$acc[id]."\" value=\"".$acc[temporder]."\" size=\"1\"></td>";
					$waiting = (int)getwaitinginstock($acc[id]);
					if($waiting==0) $waiting = '';
					echo "<td class=\"right\">".$waiting."</td>";
				}

				echo "<td class=\"right qty_".$acc[id]."\">".$acc['qty']."</td><td><span title=\"".$acc['unit_description']."\">".$acc['unit']."</span></td>";
				echo "<input type=\"hidden\" id=\"p_".$acc[id]."\" value=\"".$acc[price]."\">";
				echo "<td class=right>".number_format($acc[price],2)."</td>";
				if($_COOKIE['permission']=='4' or $_COOKIE['permission']=='20'){
					echo "<td class=center><input type=text id=\"".$acc[id]."\" name=getacc[".$acc[id]."]  size=3 ".$disabled." class=\"num ordernum\"></td>";
				}
				if(($_COOKIE[permission]==1) or ($_COOKIE[uid]==1) or(checkAllow('sit_stockin'))){
					echo "<td><input type=text size=3 id=intostock_".$acc[id]." class=num> / <input type=text id=remk_".$acc[id]."> <span class=\"button intostock\" id=b_".$acc[id]."  value=".$acc[id].">นำเข้า</span></td>"; //<button id=b_".$acc[id]."  value=".$acc[id].">นำเข้า</button>
				}
			}
			echo "</tr>";
			$disabled = "";
			$row+=1;
		}
?>
	</table>
<script>
	$("#sumstockvalue").html('สรุปยอดเงินในสต๊อก จำนวนเงินต้นทุน <span style=\"color:red\"><?php echo number_format($sumCost,2);?></span> บาท / จำวนเงิน <span style=\"color:green\"><?php echo number_format($sumPrice,2);?></span> บาท');
</script>

<script type="text/javascript" language="javascript">

// Kick off the jQuery with the document ready function on page load
$(document).ready(function(){
	imagePreview();
});

// Configuration of the x and y offsets
this.imagePreview = function(){
		xOffset = -20;
		yOffset = 40;

    $("a.preview").hover(function(e){
        this.t = this.title;
        this.title = "";
	     var c = (this.t != "") ? "<br/>" + this.t : "";
         $("body").append("<p id='preview'><img src='"+ this.href +"' alt='Image preview' />"+ c +"</p>");
         $("#preview")
            .css("top",(e.pageY - xOffset) + "px")
            .css("left",(e.pageX + yOffset) + "px")
						.fadeIn("slow");
    },

    function(){
        this.title = this.t;
        $("#preview").remove();

    });

    $("a.preview").mousemove(function(e){
        $("#preview")
            .css("top",(e.pageY - xOffset) + "px")
            .css("left",(e.pageX + yOffset) + "px");
    });
};

</script>


		</td>
		<td class="noneborder" style="vertical-align:text-top;">
		<?php
		if($_COOKIE['permission']=='4' or $_COOKIE['permission']=='20'){
		?>
		<div style="vertical-align:text-top;">

			<table id="bill">
				<tbody>
				<tr class="header">
					<td>ลำดับ</td>
					<td>รายการ</td>
					<td>จำนวนเบิก</td>
					<td>ราคา [@ต่อหน่วย]</td>
				</tr>
				<tr>
					<td colspan="4">ยังไม่มีรายการสั่ง</td>
				</tr>
				</tbody>
			</table><input type="hidden" value="0" id="seqbill">
			<div align="right">รวมราคา <input type="text" value="0.00" size='5' id="sumtotal" style="text-align:right;"> บาท</div>
			<input type="hidden" value="<?php echo $_COOKIE[uid]?>" name="orderer">
			<div align="right"><input type="submit" id="orderSubm" value="ยืนยันรายการเบิก"></div>
		</div>
		<div id="showorderpending">
			<p class="header">รายการเบิก รอรับของ</p>
			<hr style="margin-bottom:10px;">
			<?php
				$strTable = "ordering_acs_header";
				$strCondition = "billtoemp_id='".$_COOKIE[uid]."' and status='new'";
				$headerBill = fncSelectConditionRecord($strTable,$strCondition);
				while($hBill = mysql_fetch_array($headerBill)){
					echo "<p id=\"".$hBill[id]."\" style=\"padding-left:15px;\">ใบเบิกวันที่ ".convdate($hBill[createddatetime])." <a href=\"#\" class=\"cancelOrder\" billto=\"".$hBill[billtoemp_id]."\" id=\"".$hBill[id]."\">ยกเลิก</a> | <a href=\"#\" class=\"orderdetail\" billto=\"".$hBill[billtoemp_id]."\" id=\"".$hBill[id]."\">รายละเอียด</a></p>";
				}
			?>
		</div>
		<div id="showorderget">
			<p class="header">รายการเบิกแล้ว</p>
			<hr style="margin-bottom:10px;">
			<?php
				$y = date(Y);
				$m = date(n);
				$toY = $y;
				$toM = $m+1;
				if($m==12){
					$toY = $y+1;
					$toM = 1;
				}
				$strTable = "acs_billheader";
				$strCondition = "billtoemp_id='".$_COOKIE[uid]."' and billdatetime BETWEEN  '".$y."-".$m."-01 00:00:00' and '".$toY."-".$toM."-1 00:00:00'";
				$BillEQM = fncSelectConditionRecord($strTable,$strCondition);
				while($EBill = mysql_fetch_array($BillEQM)){
					echo "<p id=\"".$EBill[id]."\" style=\"padding-left:15px;\" title=\"".$EBill[comment]."\">[".$EBill[id]."] รับของวันที่ ".convdate($EBill[billdatetime])." <a href=\"#\" class=\"billdetail\" billto=\"".$EBill[billtoemp_id]."\" id=\"".$EBill[id]."\">รายละเอียด</a></p>";
				}
			?>
		</div>
		<?php
		}
		?>
		</td>
	</tr>
	</form>
</table>
