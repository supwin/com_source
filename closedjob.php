<?php
/*
Log file
060714 1904 : ทำการกรอกรายงาน "งานปิดแล้ว จาก IVR" ให้โชว์งานที่ยังไม่ได้ตัดสต๊อกตั้งเบิก ของเดิอนก่อนไม่ให้เกินวันที่ 5 ของเดือนถัดไป
120714 2134 : ทำ form ที่สำหรับ jobtype 5 หรืองาน change package เรียบร้อย แต่อย่าลืมยกเลิกการใส่ code ช่างใน sn box เพื่อปิดงานด้วย
150714 0933 : ทำ sugestion ใน textbox และกำหนด type ของ usersn (ใช้อุปกรณ์ และการเก็บอุปกรณ์) มีการแก้ไข db ใน typejob ด้วย
150714 1146 : เพิ่มเติมในส่วนของ ตรวจเช็ค modem ว่าสามารถใช้กับงานที่ปิดได้หรือไม่
200714 1145 : แก้ไขเรื่อง catv ติดตังใม่ แจ้ง  "ต้องใส่ serial ที่ถูกต้องเท่านั้น
200714 1601 : แก้ bug กรณี change package แล้วไมไ่ด้ใส่ s/n เก่า แต่ก็สามารถ submit ได้
240714 2058 : แก้ bug กรณีปิดงาน อื่นๆ ที่ไม่มีระยะสาย มัน next remove ด้วย
020814 1122 : เพิ่มเติมให้ ข้อมูลวันที่ 1-4 ไม่มีข้อมูลของเดือน เก่าเกินกว่า 1 เดือนแสดงให้เห็น
030814 2038 : แก้ไข bug งานย้ายสถานที่ติดตั้ง ปุ่มไม่ยอมให้กด
030814 2114 : กรณีพิมพ์ 1-6 ตัวอักษรและเป็นงานที่ใช้ sn ใหม่ จึงจะไปค้นเท่านั้น
071014 2043 : เพิ่มความสามารถเปิด POPUP Window เพื่อให้มีการยืนยันข้อมูลก่อนบันทึก
081014 1041 : เพิ่มให้สามารถ monitor งานผิดปกติได้
081014 1326 : เพิ่มส่วนที่แสดง การแก้ไขข้อมูลการปิดงานของทีมติดตังด้วย
010115 2013 : เพิ่มให้สามารถเห็นข้อมูลเดือน 12 เมื่อปีที่แล้ว
120115 0924 : แก้ไขให้ validate sn เก่า สำหรับงานเปลี่ยนอุปกรณ์


*/

include('cookies.php');
include("functions/function.php");
include("headmenu.php");
?>
<script language="Javascript" type="text/javascript">
function clearbox(row){
	$("input#series_"+row).val('');
	$(".sugtxt").blur();
}

function submitCloseJob(row,bid){
	//var row = $(this).val();
	var oldSN = $("input.oldsn_"+row).val();
	var fortype = $("input#series_"+row).attr('for');
	var cir = $("#circuit_"+row).text();
	var cld = $("button#"+bid).attr('cld');
	var sn = $("#series_"+row).val();
	var tjob = $("#tjob_"+row).attr('jobnum');
	var rg = $("select#cbrg_"+row+" option:selected").text();
	var bc = $("select#bc_"+row+" option:selected").text();
	var wc = $("select#wc_"+row+" option:selected").text();
	var ofc = $("select#ofc_"+row+" option:selected").text();
	$("button#"+bid).fadeOut( "slow" );
	$.ajax({
	   type: "POST",
	   url: "cutstock.php",
	   cache: false,
	   data: "cir="+cir+"&sn="+sn+"&tjob="+tjob+"&oldSN="+oldSN+"&rg="+rg+"&bc="+bc+"&wc="+wc+"&ofc="+ofc+"&cld="+cld,
	   success: function(msg){
		if(msg.indexOf("login_frm.php") > -1){
			window.location.replace("login_frm.php");
			return false;
		}
		 if(msg==1){
			$("#tr_"+row).fadeOut('xslow', function(){
				if($.inArray(tjob,['8','9','10','11','14','15','24','26','27','28'])>=0) $("#tr_"+row).next("tr").remove();
				$("#tr_"+row).remove();
			});
		 }else{
			openAlert('ติดขัดบางประการ ไม่สามารถตัดสต๊อกและบันทึกงานได้อย่างสมบูรณ์\nกรุณาแจ้งพี่หนึ่งโดยด่วน');
		 }
	   }
	});
}

$(document).ready(function(){
	$(".sugtxt").click(function(){

		if($(this).attr('title')==$(this).val()){
			$("input#tmpSug").val($(this).val());
			$(this).val('');
			$(this).removeClass('colorb');
		}
	});

	$(".sugtxt").blur(function(){
		if($(this).val()==''){
			row = $(this).attr('name');
			$("#btn_"+row).attr("disabled", true);
			$(this).removeClass('bggreen bgred txtwhite');
			$(this).addClass('colorb');
			sugTxt = $(this).attr('title');
			$(this).val(sugTxt);
			$("div#stocklist").html('');
		}
	});

  $(".serieseq").keyup(function(){
	sn = $(this).val();
	if($(this).attr('for')=='tonline') jtstyle='1';
	if($(this).attr('for')=='tvisions') jtstyle='2';
	<?php
	$listObj = fncSelectRecord("usesn");
	while($list = mysql_fetch_array($listObj)){
		if($list['newsn']==1){
			if($newsn<>''){
				$newsn .= ",'".$list['id']."'";
			}else{
				$newsn = "'".$list['id']."'";
			}
		}
		if($list['oldsn']==1){
			if($oldsn<>''){
				$oldsn .= ",'".$list['id']."'";
			}else{
				$oldsn = "'".$list['id']."'";
			}
		}
		if($list['codeeng']==1){
			if($codeeng<>''){
				$codeeng .= ",'".$list['id']."'";
			}else{
				$codeeng = "'".$list['id']."'";
			}
		}
	}
	?>

	var newsn = [ <?php echo $newsn?> ];
	var oldsn = [ <?php echo $oldsn?> ];
	var codeeng = [ <?php echo $codeeng?> ];

	row = $(this).attr('name');
	usesnVal = $("span#tjob_"+row).attr("usesn");

	if(sn.length >=6){
		if(sn.length >=35) return false;  // ถ้า sn ยาวๆ ให้หยุดทำงานเลย
		$(".serieseq").removeClass('bggreen bgred txtwhite');
		$("#btn_"+row).attr("disabled", true);

		if((sn.length==6) && (sn.substr(0, 2)=='30' || sn.substr(0,2)=='99') && ($.inArray(usesnVal,codeeng)<0)){
			$(".serieseq").removeClass('bggreen bgred txtwhite');
			$(".btnsave").attr("disabled", true);
			$("input#series_"+row).val('');
			openAlert('งานชิ้นนี้ต้องปิดด้วยเลข Serial เท่านั้น');
			return false;
		}

		$.ajax({
		   type: "POST",
		   url: "checkseries.php",
		   cache: false,
		   data: "sn="+sn+"&jtstyle="+jtstyle,
		   success: function(msg){
		   //return false;
		   if(msg.indexOf("login_frm.php") > -1){
				window.location.replace("login_frm.php");
				return false;
		    }

			 if(msg==1){
				if($.inArray(usesnVal,newsn)<0){ // ไม่ใช่ในกลุ่ม newsn
					openAlert('Serial ไม่น่าจะถูกต้อง')
				}else{
					$("input#series_"+row).addClass('bggreen txtwhite');
					$("#btn_"+row).attr("disabled", false);
				}
			 }else if(msg==2){
				openAlert('Serial นี้ผิดประเภทของงานครับ')
				$("input#series_"+row).val('');
			 }else if(msg==0){
					if(($.inArray(usesnVal,newsn)<0) && ((sn.length==17 && (sn.substr(0,1)=='5' || sn.substr(0,1=='4'))) || (sn.length==9 && sn.substr(0,2)=='26') || (sn.length==14 && sn.substr(0,5=='000987')))){
						$("input#series_"+row).addClass('bggreen txtwhite');
						$("#btn_"+row).attr("disabled", false);
						//alert('1');
					}else if($.inArray(usesnVal,newsn)>=0 && $.inArray(usesnVal,codeeng)<0){  //เป็นกลุ่ม newsn  แก้ไขเรื่อง catv ติดตังใม่ แจ้ง  "ต้องใส่ serial ที่ถูกต้องเท่านั้น"
						msg;
						//alert('2');
					}else{
						//alert('3');
						if($.inArray(usesnVal,oldsn)<0){
							$(".serieseq").removeClass('bggreen bgred txtwhite');
							$(".btnsave").attr("disabled", true);
							$("input#series_"+row).val('');
							openAlert('ต้องใส่ serial ที่ถูกต้องเท่านั้น');
							return false;
						}
						$("#btn_"+row).attr("disabled", false);
						return false;
					}
			 }else{  // กรณีคืนค่าเป็นรายชื่อช่าง
				if($.inArray(usesnVal,codeeng)>=0){
					$("input#series_"+row).addClass('bggreen txtwhite');
					$("#btn_"+row).attr("disabled", false);
					$("input#series_"+row).val(msg);
				}else{
					$(".serieseq").removeClass('bggreen bgred txtwhite');
					$(".btnsave").attr("disabled", true);
					$("input#series_"+row).val('');
					openAlert('การบันทึกครั้งนี้ ไม่ได้รับการยอมรับ น่าจะมีอะไรผิดพลาด แจ้งพี่หนึ่งด่วน');
				}
				return false;
			}
		   }
		});
	}else if(sn.length >=1 && sn.length <6 && ($.inArray(usesnVal,newsn)>=0)){ // กรณีพิมพ์ 1-6 ตัวอักษรและเป็นงานที่ใช้ sn ใหม่ จึงจะไปค้นเท่านั้น
		row = $(this).attr('name');
		$.ajax({
		   type: "POST",
		   url: "stocklist.php",
		   cache: false,
		   data: "sn="+sn+"&jtstyle="+jtstyle,
		   success: function(txt){
		   if(txt.indexOf("login_frm.php") > -1){
				window.location.replace("login_frm.php");
				return false;
		    }
			var arr = $.parseJSON(txt);
			$("div#stocklist").html('');
			$("div#stocklist").html('<div style=\"margin:0px 3px;\"> -:- คลิ๊ก serial ที่ต้องการ</div>');
			$.each(arr, function(i,v) {
				if(v.indexOf("พบ") > -1){
					$("div#stocklist").append("<div class=\"snlist\" style=\"color:red;background-color:#ffffff;margin-bottom:2px;padding:0px 5px;\">"+v+"</div>");
					if($.inArray(usesnVal,newsn)>=0 && $.inArray(usesnVal,codeeng)<0){
						openAlert(v);
					}
					return false;
				}
				vrep = v.replace(sn, "<span style=\"color:red\">"+sn+"</span>");
				newdiv = "<div torow=\""+row+"\" value=\""+v+"\" class=\"snlist\" style=\"background-color:#ffffff;margin-bottom:2px;padding:0px 5px;cursor:pointer;\"><<< "+vrep+"</div>";
				$("div#stocklist").append(newdiv);
			});
		   }
		});
	}
  });

  $("div#stocklist").on('click', 'div',function(){
	row = $(this).attr('torow');
	value = $(this).attr('value').replace(/ /g,'');
	$("input#series_"+row).val(value);
	$("input#series_"+row).addClass('bggreen txtwhite');
	$("#btn_"+row).attr("disabled", false);
  });

	$("button.btnsave").click(function(){
		var row = $(this).val();
		//var oldSN = $("input#tmpSug").val();
		var oldSN = $("input.oldsn_"+row).val();
		var bid = $(this).attr('id');

		//var odsn = $("input.oldsn_"+row).val();
		if(($("button#"+bid).attr('typesn')=='4') && (oldSN=='' || oldSN.substring(0,3)=='ใส่')){
			$("#series_"+row).val('');
			$("#series_"+row).removeClass('bggreen');
			$("button.btnsave").attr("disabled", true);
			openAlert('ต้องระบุ serial ที่เก็บกลับมาลงสต๊อกด้วย จึงจะปิดงานได้');
			return false;
		}
		var cir = $("#circuit_"+row).text();
		var sn = $("#series_"+row).val();
		var ofcRange = $("#ofc_"+row).val();
		var bcRange = $("#bc_"+row).val();
		var wcRange = $("#wc_"+row).val();
		window.open("showinfojob.php?cir="+cir+"&sn="+sn+"&row="+row+"&oldSN="+oldSN+"&bid="+bid+"&ofcRange="+ofcRange+"&bcRange="+bcRange+"&wcRange="+wcRange,"List","scrollbars=no, resizable=no, width=500, height=400");
	});

	$("button#search").click(function(){
		var cir = $("input#cirSearch").val();

		$.ajax({
		   type: "POST",
		   url: "circuitsearcher.php",
		   cache: false,
		   data: "cir="+cir,
		   success: function(msg){
			if(msg.indexOf("login_frm.php") > -1){
				window.location.replace("login_frm.php");
				return false;
			}
			if(msg!==''){
			$("#stocklist").html(msg);
			} else {
			alert("ไม่พบข้อมูล Circuit "+cir);
			}
			
		   }
		});
	});
/*
   $("select.crange").change(function(){
		row = $(this).attr('row');
		selName = $(this).attr('name');
		cbrg = $("select#cbrg_"+row+" option:selected").text();
		thisval = $(this).val();
		oldPrice=$("span#price_"+row).text();
		if(cbrg==''){  //งาน fttx
			if(thisval<=300) price = 3033;
			if(thisval<=275) price = 2826;
			if(thisval<=250) price = 2619;
			if(thisval<=225) price = 2412;
			if(thisval<=200) price = 2002;
			if(thisval<=175) price = 1810;
			if(thisval<=150) price = 1606;
			if(thisval<=125) price = 1424;
			if(thisval<=100) price = 1229;
			if(thisval<=75) price = 1137;
			if(thisval<=50) price = 935;
			if(thisval<=25) price = 817;
			//alert(price+" "+oldPrice);
		}else{
			if($(this).attr('name')=='bc'){
				other = 'wc';
			}else{
				other = 'bc';
			}
			otherval = $("select#"+other+"_"+row).val();
			totalcab = parseInt(thisval, 10)+parseInt(otherval, 10);
			$("span#totalcable_"+row).text(totalcab);
			totc = $("span#totalcable_"+row).text();
			over=0;
			price = 1150;
			if(totc>=41) price= 1400;
			if(totc>70 && cbrg=='RG11') over=totc-70;
			oldOverPrice=$("span#overprice_"+row).text();
			$("span#overprice_"+row).text(over*20);
		}
		if(oldPrice != price){
			$("span#price_"+row).text(price);
		}
		setTimeout(function () {
			$("span#totalcable_"+row).css("color", "red");
			$("td#tdttl_"+row).css("background-color","#ffffff");
			if(over != oldOverPrice){
				$("span#overprice_"+row).css("color", "red");
				$("td#tdov_"+row).css("background-color","#ffffff");
			}
			if(oldPrice != price){
				$("span#price_"+row).css("color", "red");
				$("td#tdprc_"+row).css("background-color","#ffffff");
			}
		}, 100);
		setTimeout(function () {
			$("span#totalcable_"+row).css("color", "#999999");
			$("td#tdttl_"+row).css("background-color","");
			$("span#overprice_"+row).css("color", "#999999");
			$("td#tdov_"+row).css("background-color","");
			$("span#price_"+row).css("color", "#999999");
			$("td#tdprc_"+row).css("background-color","");
		}, 200);
		setTimeout(function () {
			$("span#totalcable_"+row).css("color", "red");
			$("td#tdttl_"+row).css("background-color","#ffffff");
			if(over != oldOverPrice){
				$("span#overprice_"+row).css("color", "red");
				$("td#tdov_"+row).css("background-color","#ffffff");
			}
			if(oldPrice != price){
				$("span#price_"+row).css("color", "red");
				$("td#tdprc_"+row).css("background-color","#ffffff");
			}
		}, 300);
		setTimeout(function () {
			$("span#totalcable_"+row).css("color", "#999999");
			$("td#tdttl_"+row).css("background-color","");
			$("span#overprice_"+row).css("color", "#999999");
			$("td#tdov_"+row).css("background-color","");
			$("span#price_"+row).css("color", "#999999");
			$("td#tdprc_"+row).css("background-color","");
		}, 400);
		setTimeout(function () {
			$("span#totalcable_"+row).css("color", "red");
			$("td#tdttl_"+row).css("background-color","#ffffff");
			if(over != oldOverPrice){
				$("span#overprice_"+row).css("color", "red");
				$("td#tdov_"+row).css("background-color","#ffffff");
			}
			if(oldPrice != price){
				$("span#price_"+row).css("color", "red");
				$("td#tdprc_"+row).css("background-color","#ffffff");
			}
		}, 500);
		setTimeout(function () {
			$("span#totalcable_"+row).css("color", "#999999");
			$("td#tdttl_"+row).css("background-color","");
			$("span#overprice_"+row).css("color", "#999999");
			$("td#tdov_"+row).css("background-color","");
			$("span#price_"+row).css("color", "#999999");
			$("td#tdprc_"+row).css("background-color","");
		}, 600);
	});*/

   $("select.cbt").change(function(){
		row = $(this).attr('row');
		cbrg = $("select#cbrg_"+row+" option:selected").text();
		oldOverPrice=$("span#overprice_"+row).text();
		oldPrice=$("span#price_"+row).text();
		if(cbrg !='RG11'){
			$("span#overprice_"+row).text(0);
		}else{
			bc = $("select#bc_"+row+" option:selected").text();
			wc = $("select#wc_"+row+" option:selected").text();
			overcable = ((parseInt(bc,10)+parseInt(wc,10))-70);
			if(overcable<=0){
				$("span#overprice_"+row).text(0);
			}else{
				$("span#overprice_"+row).text(overcable * 20);
			}
		}
		newOverPrice=$("span#overprice_"+row).text();
		newPrice=$("span#price_"+row).text();

		if(oldOverPrice != newOverPrice){
			setTimeout(function () {
				$("span#overprice_"+row).css("color", "red");
				$("td#tdov_"+row).css("background-color","#ffffff");
				if(oldPrice != newPrice){
					$("span#price_"+row).css("color", "red");
					$("td#tdprc_"+row).css("background-color","#ffffff");
				}
			}, 100);
			setTimeout(function () {
				$("span#overprice_"+row).css("color", "#999999");
				$("td#tdov_"+row).css("background-color","");
				$("span#price_"+row).css("color", "#999999");
				$("td#tdprc_"+row).css("background-color","");
			}, 200);
			setTimeout(function () {
				$("span#overprice_"+row).css("color", "red");
				$("td#tdov_"+row).css("background-color","#ffffff");
				if(oldPrice != newPrice){
					$("span#price_"+row).css("color", "red");
					$("td#tdprc_"+row).css("background-color","#ffffff");
				}
			}, 300);
			setTimeout(function () {
				$("span#overprice_"+row).css("color", "#999999");
				$("td#tdov_"+row).css("background-color","");
				$("span#price_"+row).css("color", "#999999");
				$("td#tdprc_"+row).css("background-color","");
			}, 400);
			setTimeout(function () {
				$("span#overprice_"+row).css("color", "red");
				$("td#tdov_"+row).css("background-color","#ffffff");
				if(oldPrice != newPrice){
					$("span#price_"+row).css("color", "red");
					$("td#tdprc_"+row).css("background-color","#ffffff");
				}
			}, 500);
			setTimeout(function () {
				$("span#overprice_"+row).css("color", "#999999");
				$("td#tdov_"+row).css("background-color","");
				$("span#price_"+row).css("color", "#999999");
				$("td#tdprc_"+row).css("background-color","");
			}, 600);
		}
   });

});
</script>
<?php
//if($_COOKIE['permission']=='4') $conMore = " and (emp_id='".$_COOKIE['uid']."' or emp_id='0')";

$yy = date('Y');
if(date('d')>constant('DATEALLOWCUTSTOCK')){
	$mm = date('m');  // หลังวันที่ 5 ไม่ให้เห็นข้อมูลเดือนก่อน
}else{
	$mm = date('m')-1; // วันที่ 1-4 ให้เห็นเฉพาะข้อมูลเดือนก่อนเพียง 1 เดือนเท่านั้น ก่อนหน้านั้นไม่ให้เห็นแล้ว\
	if($mm==0){
		$mm = 12;
		$yy = date('Y')-1;
	}
}

$prevMthinvisible = " and closeddate >= '".$yy."-".$mm."-01'";
//$prevMthinvisible = " and closeddate >= '".$yy."-08-01'";

$strTable = "closedjob,tidnet_common.typeofjob";
$strCondition = "series=''".$conMore." and payhireheader_id=0 and typejob=id".$prevMthinvisible;
if($_GET['debug']) echo "SELECT * FROM $strTable WHERE (".$strCondition.") or (circuit='9101106465' and typejob=id)";
$lstClosedJob = fncSelectConditionRecord($strTable,"(".$strCondition.") or (circuit in ('999999') and typejob=id".$conMore.") ORDER BY closeddate DESC");

?>
<?php	if(checkAllow('sit_importclosedjob')){?>
					<form action="insertclosedjob.php" method="post" enctype="multipart/form-data">
					<label for="file"> Closed Job List</label>
					<input name="fileCSV" type="file" id="fileCSV">
					<input name="btnSubmit" type="submit" id="btnSubmit" value="Submit">  <span  style="padding-left:50px;"><a href="#"><img src="img/csv.jpg" height="30"> ไฟล์ตัวอย่าง</a></span>
					</form>
<?php }?>
<table class="noneborder"><input type="hidden" id="tmpSug">
	<tr><td class="noneborder1" >
	<table border='1' width="1100">
		<tr class="label"><td colspan="3">-:- รายงานปิดแล้วจาก IVR</td><td colspan="3" class="right">ตรวจสอบงาน <input type="text" id="cirSearch"><button id="search">ค้นหา</button></td></tr>
		<tr class=header>
			<td>วันที่ปิด</td>
			<td>circuit/สมาชิก</td>
			<td>ชื่อลูกค้า</td>
			<td>ช่างติดตั้ง</td>
			<td>ประเภทงาน</td>
			<?php if($_COOKIE['permission']==4){?>
				<td width="250">Serial No.</td>
			<?php }?>
		</tr>
		<tr><td colspan="6" style="color:red;font-weight:bold;text-align:center;font-size:13px;">[[ -: ให้ตรวจสอบระยะสายและราคาที่จะได้รับ เพื่อเทียบกับข้อมูลของทรูให้ตรงกัน :- ]]</td></tr>
		<?php
		function optionnum($name,$row,$fix=1){
			$optnum = "<select name=\"".$name."\" id=\"".$name."_".$row."\" row=\"".$row."\" class=\"crange\">";

			if($name=='ofc'){
				$totalnum=500;
				$numincread = 25;
			}else{
				$totalnum=120;
				$numincread = 1;
			}

			for($num=0; $num<=$totalnum; $num=$num+$numincread){
				$selected = '';
				if($num==$fix) $selected = "selected";
				 $optnum .=  "<option value=\"".$num."\" ".$selected.">".$num."</option>";
			}
			$optnum .= "</select>";
			return $optnum;
		}
			$row = 1;
			while($objSelect = mysql_fetch_array($lstClosedJob)){

				$blackc = optionnum('bc',$row,$objSelect['bcable']);
				$whitec = optionnum('wc',$row,$objSelect['wcable']);
				$ofc = optionnum('ofc',$row,$objSelect['bcable']);  // เก็บข้อมูลระยะสาย fttx ไว้ที่ระยะสายดำ

				if($objSelect[typeof]=='True visions'){
					$jtstyle = "tvisions";
					$detailClosedjob = '';
					$borderTop = '';
				}
				if($objSelect[typeof]=='True Online'){
					$selected6 = '';
					$selected11 = '';
					$selectedOFC = '';
					if(trim($objSelect['rgcable'])=='RG6'){
						$selected6 = 'selected';
					}
					if(trim($objSelect['rgcable'])=='RG11'){
						$selected11 = 'selected';
					}
					$jtstyle = "tonline";
					$detailClosedjob = "<tr><td colspan=\"5\">";
					$detailClosedjob .= "<table class=\"noneborder2\"><tr style=\"color:#4682B4;font-weight:bold;\">";

					if(trim($objSelect['rgcable'])=='HSI'){
						$detailClosedjob .= "<td style=\"border-right:3px solid #aaaaaa \">ชนิดสาย : OFC</td>";
						$detailClosedjob .= "<td style=\"border-right:3px solid #aaaaaa\">ระยะสาย : ".$ofc." ม.</td>";
					}else{
						$detailClosedjob .= "<td style=\"border-right:3px solid #aaaaaa \">ชนิดสาย : <select row=\"".$row."\" id=\"cbrg_".$row."\" class=\"cbt closedjob\"><option value=\"RG6\" ".$selected6."/>RG6 <option value=\"RG11\" ".$selected11."/>RG11 <option value=\"OFC\" ".$selectedOFC."/>OFC</select>  </td>";
						$detailClosedjob .= "<td style=\"border-right:3px solid #aaaaaa\">ระยะสาย : ดำ/ขาว : ".$blackc." / ".$whitec." ม.</td>";
						//$detailClosedjob .= "<td style=\"border-right:3px solid #aaaaaa\" id=\"tdttl_".$row."\">รวม <span id=\"totalcable_".$row."\" style=\"color:#999999\">".$objSelect['dwsum']."</span> ม.</td>";
						$detailClosedjob .= "<td style=\"border-right:3px solid #aaaaaa\" id=\"tdttl_".$row."\"></td>";
						//$detailClosedjob .= "<td style=\"border-right:3px solid #aaaaaa\" id=\"tdov_".$row."\">ค่าสายเกิน <span id=\"overprice_".$row."\" style=\"color:#999999\">".$objSelect['overrange']."</span> บ.</td>";
					}
					//$detailClosedjob .= "<td id=\"tdprc_".$row."\">รวมค่าติดตั้ง <span id=\"price_".$row."\" style=\"color:#999999\">".$objSelect['price']."</span> บ.</td>";
					$detailClosedjob .= "<td id=\"tdprc_".$row."\"></td>";
					$detailClosedjob .= "</tr></table>";
					$detailClosedjob .= "</td></tr>";
					$borderTop = "style=\"border-top:8px solid #ffffff;\"";
				}
				echo "<tr id=\"tr_".$row."\" class=\"".$jtstyle."\" ".$borderTop.">";
				echo "<td>".convdateMini($objSelect[closeddate])."</td>";
				echo "<td><span id=\"circuit_".$row."\">".$objSelect[circuit]."</span></td>";
				echo "<td><span id=\"custname_".$row."\" title=\"".$objSelect[cust_addr]."\">".$objSelect[cust_name]."</span></td>";
				//if($_COOKIE['permission']==1){
					echo "<td>".nameofengineer($objSelect[emp_id])."</td>";
				//}

				$travelButton = "";
				if($_COOKIE['permission']=='1'){
					if(($objSelect[typejob]==6) or ($objSelect[typejob]==7)){
						$travelButton = "<input type=\"submit\" value=\"เพิ่มค่าเดินทาง\">";
					}
					if(($objSelect[typejob]==12) or ($objSelect[typejob]==13)){
						$travelButton = "<input type=\"submit\" value=\"ยกเลิกค่าเดินทาง\">";
					}
				}
				echo "<td><span id=\"tjob_".$row."\" usesn=\"".$objSelect[usesn]."\" jobnum=\"".$objSelect[typejob]."\" title=\"".$objSelect[typeof]."/".$objSelect[description]."\">".$objSelect[ename]."</span> ".$travelButton."</td>";
				$valueSug = '';
				switch($objSelect[usesn]){
					case 1 :
						$valueSug = 'ใส่ S/N อุปกรณ์ที่ติดตั้ง';
					break;
					case 2 :
						$valueSug = 'ใส่ code ช่าง';
					break;
					case 3 :
						$valueSug = 'ใส่ S/N และ CM MAC ที่เก็บมา';
					break;
					case 4 :
						$valueSug = 'ใส่ S/N';
					break;
					case 5 :
						$valueSug = 'ถ้าเก็บมากล่อง,การ์ด / ใส่ code ช่างถ้าไม่ได้เก็บมา';
					break;
					case 6 :
						$valueSug = 'ถ้าใช้ชุดใหม่ใส่ S/N / ใส่ code ช่างถ้าใช้ชุดเดิม';
					break;
				}
				if($objSelect[usesn]==4 and $_COOKIE['permission']==4){
					echo "<td>
					<input for=\"".$jtstyle."\" title=\"".$valueSug." เก่า\" size=\"7\" name=\"".$row."\" class=\"sugtxt chmodem colorb oldsn_".$row."\" type=\"text\" value=\"".$valueSug." เก่า\">
					<input for=\"".$jtstyle."\" title=\"".$valueSug." ใหม่\" size=\"7\" name=\"".$row."\" class=\"sugtxt serieseq chmodem colorb\" type=\"text\" id=\"series_".$row."\" value=\"".$valueSug." ใหม่\">
					<button cld=\"".$objSelect[closeddate]."\" id=\"btn_".$row."\" value=\"".$row."\" disabled=\"true\" class=\"btnsave\" typesn=\"".$objSelect[usesn]."\">บันทึก</button></td>";
				}else if($_COOKIE['permission']==4){
					echo "<td><input for=\"".$jtstyle."\" title=\"".$valueSug."\" name=\"".$row."\" class=\"colorb sugtxt serieseq\" type=\"text\" id=\"series_".$row."\" value=\"".$valueSug."\"> <button cld=\"".$objSelect[closeddate]."\" id=\"btn_".$row."\" value=\"".$row."\" disabled=\"true\" class=\"btnsave\">บันทึก</button></td>";
				}
				echo "</tr>";
				if(in_array($objSelect[id],array('8','9','10','11','14','15','27')))echo $detailClosedjob;
				$row +=1;
			}
		?>
	</table>
	<?php
	if(checkAllow('sit_viewabnomaljob')){
		$strTableABNormalJob = "closedjob";
		$strConditionABNormal = "typejob=0 or price=0 or price>2000  ORDER BY closeddate DESC";
		//echo "SELECT * FROM $strTableABNormalJob WHERE $strConditionABNormal";
		//$lstClosedABNormalJob = fncSelectConditionRecord($strTableABNormalJob,$strConditionABNormal);
		?>
		<br>
		<hr>
		<br>
		<table border='1'>
			<tr class="label"><td colspan="6">-:- รายงาน JOB ผิดปกติจาก IVR</td></tr>
			<tr class=header>
				<td>วันที่ปิด</td>
				<td>circuit/สมาชิก</td>
				<td>ชื่อลูกค้า</td>
				<?php if($_COOKIE['permission']==1){?>
					<td>ช่างติดตั้ง</td>
				<?php }?>
				<td>ประเภทงาน</td>
				<td>ค่าติดตั้ง</td>
			</tr>
			<?php
			while($jobObj = mysql_fetch_array($lstClosedABNormalJob)){?>
				<tr>
				<?php
				echo "<td>".convdate($jobObj[closeddate])."</td>";
				echo "<td><span id=\"circuit_".$row."\">".$jobObj[circuit]."</span></td>";
				echo "<td><span id=\"custname_".$row."\" title=\"".$jobObj[cust_addr]."\">".$jobObj[cust_name]."</span></td>";
				?>
				<?php if($_COOKIE['permission']==1){
					echo "<td>".nameofengineer($jobObj[emp_id])."</td>";
				}?>
				<td><?php echo $jobObj[typejob]?></td>
				<td><?php echo $jobObj[price]?></td>
			</tr>
			<?php
			}
			?>
		</table>
		<?php
	}
	?>
	</td>

	<td>
	<?php
	/*
	if(checkAllow('sit_viewdiffjob')){
		if(date('d')<=17){
			$period = "and closeddate >= '".date('Y')."-".date('m')."-01'";
		}else if(date('d')>17){
			$period = "and closeddate >= '".date('Y')."-".date('m')."-16'";
		}else if (date('d')==1 or date('d')==2){
			$prvm = date('m')-1;
			$period = "and closeddate >= '".date('Y')."-".$prvm."-16'";
		}
		$strTable = "closedjob";
		$strConditionDiff = "series<>'' and (rgcable<>rgcable_ivr or bcable<>bcable_ivr or wcable<>wcable_ivr or dwov70<>dwov70_ivr)".$period ;
		$diffObj = fncSelectConditionRecord($strTable,$strConditionDiff);
		//echo "SELECT * FROM $strTable WHERE $strConditionDiff";
		while($diff = mysql_fetch_array($diffObj)){
			if($diff[rgcable]<>$diff[rgcable_ivr])$rgcable = "ชนิดสาย [".$diff[rgcable].":".$diff[rgcable_ivr]."]";
			if($diff[bcable]<>$diff[bcable_ivr])$bcable = "สายขาว [".$diff[bcable].":".$diff[bcable_ivr]."]";
			if($diff[wcable]<>$diff[wcable_ivr])$wcable = "สายดำ [".$diff[wcable].":".$diff[wcable_ivr]."]";
			if($diff[dwov70]<>$diff[dwov70_ivr])$dwov70 = "ระยะสายเกิน [".$diff[dwov70].":".$diff[dwov70_ivr]."]";
			//echo "<p>".convdateMini($diff['closeddate'])." ".$diff[circuit]." ".$diff[cust_name]." ".$rgcable." ".$bcable." ".$wcable." ".$dwov70."</p>";
		}
	}*/

	?>
	<div style="margin-bottom:10px;">ลองพิมพ์ตัวเลข serial 4-5 ตัวท้ายดูซิ</div><div id="stocklist" style="margin-left: 0px !important;position: fixed;top: 100;"></div></td>
	<td class="noneborder3" id="cirShow"></td></tr></table>
