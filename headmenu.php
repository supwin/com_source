<?php
/*
Log file
250614 0816 : ได้เพิ่มสิทธิ์ในการเข้าถึงเมนู "สต๊อกขาเข้า" ให้เฉพาะสมาชิกที่มีสิทธิ์เท่านั้น
200714 1654 : เพิ่มส่วน error message แบบ sexy แล้ว
081014 1418 : เพิ่มการแสดง nofify เมื่อมี modem รอเข้าสต๊อก
101014 1716 : เพิ่มสิทธิ์ในการเข้าถึงเมนู ตรวจสอบสต๊อก
030115 1238 : เพิ่มให้ list รายชื่อพนักงานแสดง title เป็น email
030115 1253 : เพิ่มให้ list รายชื่อพนักงานและสาขาอื่นๆ จัดกลุ่มและลำดับให้เป็นกลุ่ม
*/

include("db_function/phpMySQLFunctionDatabase.php");
include("../com_source/config.php");
if(!isset($_GET['debug'])) $_GET['debug']='';
header ("Last-Modified: " . gmdate ("D, d M Y H:i:s") . " GMT");
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
	<meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1' />
	<link rel="stylesheet" type="text/css" href="css/mystyle.css">
	<link rel='stylesheet' type='text/css' href='css/headmenu.css' />
	<link href="/favicon/favicon.ico" rel="shortcut icon" type="image/x-icon" />
	<script language="JavaScript" type="text/javascript" src="jquery/jquery.js"></script>
	<script language="JavaScript" type="text/javascript">

		function closeAlert() {
		  $('.notification').slideUp('slow');
		}

		function openAlert(msg){
			$('.notification').text('<<- '+msg+' ->>');
			$('.notification').slideDown('fast');
			window.setTimeout(closeAlert,8000);
			return false;
		}
		$(document).ready(function(){

			$("select#engineer").change(function(){
				var pathname = window.location.pathname;
				var eng = $("select#engineer").val();
				var url = 'changeperm.php?url='+pathname+'&eng='+eng;
				$(location).attr('href',url);
			});
			$("body").append('<div class=\"footer\"><p style=\"height:100px;\"></p></div>');
	    $(".nuberOnly").keydown(function (e) {
				if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9
									 || event.keyCode == 27 || event.keyCode == 13
									 || (event.keyCode == 65 && event.ctrlKey === true)
									 || (event.keyCode >= 35 && event.keyCode <= 39)){
											 return;
				 }else {
						 // If it's not a number stop the keypress
						 if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
								 event.preventDefault();
						 }
				 }
	    });

		});
	</script>
</head>
<body>

<div class="notification">Error Message</div>
<?php
include('namebranch.php');
?>
<table style="border:0px;"><tr style="border:0px;"><td width="350" style="border:0px;"><p style="size:18px;color:brown;margin-top:10px;">หจก.ติดเน็ต <?php echo $branch?></p></td> <td style="border:0px;text-align:right;" width="350"> ยินดีต้อนรับ <?php echo $_COOKIE['name'];?> <?php if($_COOKIE['uid']=='1') echo "7y05gJKE";?></td>
<?php
if($_COOKIE['superuser']==1){
	$option = "";
	if($_COOKIE['uid']<>$_COOKIE['superuseruid']){
		$option = "<option value='".$_COOKIE['superuseruid']."'>".$_COOKIE['superusername']."</option>";
	}else{

		$strTable = "tidnet_common.master_employee";
		$strCondition = "permission >'".$_COOKIE['permission']."' and dontshowat not like '%@chgperm@%' order by permission DESC";
		$emp = fncSelectConditionRecord($strTable,$strCondition);
		while($em = mysql_fetch_array($emp)){
				$nickname = '';
				if($em['nickname']<>''){
					$nickname = "(".$em['nickname'].")";
				}
				$option = $option."<option value=".$em['id']." title=\"".$em['email']."\">".$em['name']." ".$nickname."</option>";
		}
	}
	?>
	<td width="350" style="border:0px;">
		<select id="engineer">
			<option value="0">เลือกผู้ใช้งาน</option>

	<?php
		echo $option;
	?>
		</select>
	</td>
	<?php
}
?>
</tr></table>
<?php
if(checkAllow('sit_confstkin')){
	$notifyModem = notificationModemIn();
	if($notifyModem>=1){
		$notfModemMenu = "<span style=\"color:red\">(".$notifyModem.")</span>";
		$notifyModemTxt = "<p><img src=\"img/new.gif\">  มี Modem รอเข้าสต๊อก <b>".$notifyModem."</b> ตัว <a href=\"stockeqmconfirmin.php\">คลิ๊ก!!</a></p>";
	}
}
?>
<div id='cssmenu'>
<ul>
   <li class='active has-sub'><a href='index.php'><span>หน้าแรก</span></a>
      <ul>
	<li class='last'><a href='ticket.php'><span>ticket</span></a></li>
      </ul>
   </li>
   <?php
   if($_COOKIE['permission']==4){ ?>
	<li><a href='foajobassign.php'><span>G-Sys</span></a></li>
 <?php }
   ?>
   <li class='has-sub'><a href='#'><span>Modem / CATV</span></a>
      <ul>
         <li class='has-sub'><a href='#'><span>สต๊อก</span></a>
            <ul>
							<li class='last'><a href='engstocktotallist.php'>จำนวนทุกช่าง</a></li>
			   <?php if($_COOKIE['permission']==4){

					 $strTableSnTrans = "eqm_sn";
					 $strConditionSnTrans = "oldowner='".$_COOKIE['uid']."' and responcible='9092'";
					 $countOldowner = fncCountRow($strTableSnTrans,$strConditionSnTrans);

					 $strConditionSnTrans = "requestor='".$_COOKIE['uid']."' and responcible='9092'";
					 $countRequestor = fncCountRow($strTableSnTrans,$strConditionSnTrans);

					 if($countOldowner>=1){
								$oldownerSnTrans = "<p><img src=\"img/new.gif\">  มี SN โอนออก รอปลายทางรับจำนวน <b>".$countOldowner."</b> รายการ <a href=\"sntransferlist.php\">คลิ๊ก!!</a></p>";
						 ?>
               <li class='last'><a href='sntransferlist.php'><span> SN โอนออก <span style="color:red">(<?php echo $countOldowner?>)</span></span></a></li>
					<?php
					 }
 					 if($countRequestor>=1){
								 $requestorSnTrans = "<p><img src=\"img/new.gif\">  มี SN รอรับเข้าจำนวน <b>".$countRequestor."</b> รายการ <a href=\"sntransferlist.php\">คลิ๊ก!!</a></p>";
							?>
                <li class='last'><a href='sntransferlist.php'><span> SN รอรับเข้า <span style="color:red">(<?php echo $countRequestor?>)</span></span></a></li>
 					<?php
 					 }
				 ?>
               <li class='last'><a href='engstock.php'><span><?php echo nameofengineer($_COOKIE['uid'])?></span></a></li>
				<?php
				 }else{
					 ?>
				   <li class='has-sub'><a href='#'><span>ช่าง</span></a>
					<ul>
					<?php

						$strTable = "employee";
						$strCondition = "permission=4 and status=1";
						$engList = fncSelectConditionRecord($strTable,$strCondition);
						$strTableSnCount = eqm_sn;
						while($engineer = mysql_fetch_array($engList)){
							$strConditionSnCount = "oldowner='".$engineer[id]."' or responcible='".$engineer[id]."'";
							$totalSn=0;
							$totalSnTxt='';
							$totalSn = fncCountRow($strTableSnCount,$strConditionSnCount);
							if($totalSn>0) $totalSnTxt = "[<span style='color:red;'>".$totalSn."</span>]";
							?>
							<li><a href='engstock.php?engId=<?php echo $engineer[id]?>'><span title="<?php echo $totalSn;?>"><?php echo $engineer[name];?></span></a></li>
					<?php	}?>
						</ul>
						</li>
					<?php
				 }?>
               <li class='has-sub'><a href='stockeqm.php'><span>บริษัท</span></a>
			<ul>
				<li><a href='more60d.php'>สต๊อกกลาง > 60 วัน</a></li>
				<li><a href='eachengmore30d.php'>สต๊อกช่าง > 30 วัน</a></li>
			</ul>
		</li>
			   <?php if(checkAllow('sit_confstkin')){
					 	echo "<li><a href='stockeqmconfirmin.php'><span>ยืนยันสต๊อกเข้า".$notfModemMenu."</span></a></li>";
				 }?>
               <li><a href='returnwh.php'><span>ส่งคืน W/H</span></a></li>
               <li><a href='catvworkno.php'><span>เลข work CATV</span></a></li>
		<?php
		 if(checkAllow('sit_createcomming')){
              echo " <li><a href='eqmwaitinglist.php'><span>รอเข้า</span></a></li>";
		}?>
            </ul>
         </li>


					<?php
					if($_COOKIE['permission']!=4) { ?>
					<li class='has-sub'><a href='#'><span>งานใหม่</span></a>
					<ul>
 	               	<li class='last'><a href='jobassign.php'><span>นัดงาน</span></a></li>
  	            	<li class='last'><a href='jobassign.php?due=<?php echo date("Y-m-d")?>'><span>งานวันนี้</span></a></li>
  	            	<li class='last'><a href='realtimemonitor.php'><span>Real-Time Monitor</span></a></li>
               		<li class='last'><a href='fttx_map.php'><span>แผนที่ FTTH</span></a></li>
               		</ul>
               		</li>
					<?php
					} else {

					}
					?>

	<?php
		if(checkAllow('sit_viewclosedjob')){
         ?>
         <li class='has-sub'><a href='#'><span>ติดตั้งแล้ว</span></a>
            <ul>

            <li class='last' style="background-color:orange;"><a href='closedjob.php'><span>รายงานจาก IVR</span></a></li>
            <hr>
			   <?php if($_COOKIE['permission']==4){?>
					<li><a href='jobofdate.php'><span><?php echo $_COOKIE['name']?></span></a></li>
			   <?php }else{
					$strTable = "employee";
					$strCondition = "permission=4 and status=1";
					$engList = fncSelectConditionRecord($strTable,$strCondition);
					while($engineer = mysql_fetch_array($engList)){?>
						<li><a href='jobofdate.php?engId=<?php echo $engineer[id]?>'><span><?php echo $engineer['name']?></span></a></li>
				<?php	}
			   }
				 ?>
			   <?php if(checkAllow('sit_pendingstockeng')){?>
               <li class='last'><a href='pendingstockeng.php'><span>งานไม่ถูกตัดสต๊อก</span></a></li>
			   <?php }?>
               <li class='last'><a href='returnlist.php'><span>งานเก็บ/คืน</span></a></li>
            </ul>
         </li>
	<?php
		}
	if(checkAllow('sit_modemtranf')){
	?>
		 <li class='has-sub'><a href='#'><span>โอนย้าย</span></a>
			<ul>
			<?php
				$strTable = "tidnet_common.master_employee";
				$strCondition = "permission=20 and workat like '%@".$abvt."@%'";
				$engList = fncSelectConditionRecord($strTable,$strCondition);
				while($engineer = mysql_fetch_array($engList)){?>
					<li><a href='engstock.php?engId=<?php echo $engineer[id]?>'><span><?php echo $engineer[name]?></span></a></li>
			<?php	}?>
			</ul>
		</li>
	<?php
	}
	if(checkAllow('sit_checkstock')){
	?>
        <li class='last'><a href='checkstock.php'><span>เช็คสต๊อกบริษัท</span></a></li>
	<?php
	}

	if(checkAllow('sit_returnbackstock')){
	?>
        <li class='last'><a href='returnbackstock.php'><span>ตัดสต๊อกทรู</span></a></li>
	<?php
	}
	if(checkAllow('sit_reportstockin_view')){
	?>
	<li class='has-sub'><a href='checkstatusSN.php'><span>ตรสอจสอบสถานะ Serial</span></a></li>
	<?php
	}
	?>
      </ul>
   </li>
   <li class='has-sub'><a href='#'><span>เบิก / จ่ายอะไหล่</span></a>
      <ul>
         <li class='last'><a href='ascstock.php'><span>สต๊อกอะไหล่</span></a></li>
	         <?php
					 if(checkAllow('sit_stock_1')){
						$notify = notificationOrder();
						if($notify>=1){
							$notfMenu = "<span style=\"color:red\">(".$notify.")</span>";
							$notifyOrderTxt = "<p><img src=\"img/new.gif\">  มีออเดอร์เบิกของรอจ่าย <b>".$notify."</b> ออเดอร์ <a href=\"transacs.php\">คลิ๊ก!!</a></p>";
						}
				    ?>
						<li class='last'><a href='transacs.php'><span>รอจ่ายอะไหล่ <?php echo $notfMenu?></span></a>
		 <?php }?>
         <li class='has-sub'><a href='#'><span>รายการเบิก</span></a>
            <ul>
				<?php
					$strTable = "tidnet_common.master_employee";
					$strCondition = "permission=4 and workat like '%@".$abvt."@%'";
					$engList = fncSelectConditionRecord($strTable,$strCondition);
					while($engineer = mysql_fetch_array($engList)){?>
						<li><a href='getasclist.php?engId=<?php echo $engineer[id]?>'><span><?php echo $engineer[name]?></span></a></li>
				<?php	}?>
            </ul>
         </li>
				<?php
		 		if(checkAllow('sit_reportstockin_view')){
		 		?>
		          <li class='has-sub'><a href='#'><span>ตรวจสอบสต๊อก</span></a>
		             <ul>
		 				<li class='last'><a href='stocktransdetail.php'><span>สต๊อกเคลื่อนไหว</span></a>
		 				<li class='last'><a href='incomingstockhistory.php'><span>สต๊อกขาเข้า</span></a>
		             </ul>
		          </li>
		 	<?php
		}
		if(checkAllow('sit_borrow_view')){
		?>
	         <li class='has-sub'><a href='#'><span>ยืม / คืน</span></a>
	            <ul>
					<?php
						$strTable = "employee";
						$strCondition = "permission=20";
						$engList = fncSelectConditionRecord($strTable,$strCondition);
						while($engineer = mysql_fetch_array($engList)){?>
							<li><a href='getasclist.php?engId=<?php echo $engineer[id]?>'><span><?php echo $engineer[name]?></span></a></li>

					<?php	}?>
	            </ul>
	         </li>
		<?php
		}
	?>
      </ul>
   </li>
	 <?php
	 if(checkAllow('sit_accounting')){
					?>
			   <li class='has-sub'><a href='#'><span>ระบบบัญชี</span></a>
			   <ul>
				<li class='last'><a href='trmlist.php'><span>เงินประกัน CATV</span></a></li>
				<?php
					if(checkAllow("sit_viewpo")){?>
						<li class='last'><a href='po.php'><span>ใบสั่งซื้อ (P.O.)</span></a></li>
						<?php
					}
					if(checkAllow('sit_accounting') and ABVT=='ryg'){
					?>
						<li class='has-sub'><a href='#'><span>ใบแจ้งหนี้ (Inv)</a></span>
							<ul>
										 <li><a href='invoicelist.php'><span>รายการ Invoice</span></a></li>
										 <li><a href='preinv.php'><span>สร้าง Invoice</span></a></li>
							</ul>
						</li>
					<?php
					}
					if(checkAllow('sit_paidrecording') and ABVT=='ryg'){
					?>
						<li class='has-sub'><a href='#'><span>บันทึกใช้จ่าย</span></a>
							<ul>
							       <li><a href='cash.php'><span>เงินสดย่อย</span></a></li>
						 				 <li><a href='generalbuymaterialjournal.php'><span>สมุดรายวันซื้อวัสดุ</span></a></li>
							       <li><a href='generalbuyjournal.php'><span>สมุดรายวันซื้อทั่วไป</span></a></li>
							       <li><a href='generalhirejournal.php'><span>สมุดรายวันจ่ายค่าแรง</span></a></li>
							       <li><a href='liabilities.php'><span>ชำระหนี้</span></a></li>
							       <li><a href='moneymovement.php'><span>โยกย้ายเงิน</span></a></li>
							</ul>
						</li>
					<?php
					}
					if(checkAllow('sit_create_paymentvoucher') and ABVT=='ryg'){
					?>
						<li class='has-sub'><a href='paymentvoucherfrm.php'><span>วิเคราะห์รายการ</span></a>
							<ul>
							       <li><a href='generalledger.php'><span>สมุดรายวันทั้วไป</span></a></li>
							</ul>
						</li>
						<li class='last'><a href='vtaxinlist.php'><span>ใบกำกับภาษี (ซื้อ)</span></a></li>
						<li class='last'><a href='whtaxinlist.php'><span>หัก ณ ที่จ่าย (ค้างจ่าย)</span></a></li>
					<?php
					}
					if(checkAllow('sit_accounting') and ABVT=='ryg'){
					?>
						<li class='last'><a href='worksheet.php'><span>สรุปงบบัญชี</span></a></li>
					<?php
					}
					if(checkAllow('sit_catvcover_view')){
					?>
						<li class='last'><a href='catvreport.php'><span>ใบปะหน้า CATV</span></a></li>
					<?php
					}
					if(checkAllow('sit_viewclosedjob')){
					?>
					<li class='last'><a href='closedjoblist.php'><span>ตั้งเบิก</span></a></li>
					<?php
					}
				?>
				<li class='last'><a href='catvrangecable.php'><span>ระยะสาย CATV</span></a></li>
				<li class='last'><a href='report_cablerange.php'><span>ระยะสาย FTTx</span></a></li>
			   </ul>
			   </li>
				 <?php
 }




	  ?>
   <li class='has-sub'><a href='#'><span>พนักงาน</span></a>
	   <ul>
			<li class='last'><a href='member.php'><span>ข้อมูลพนักงาน</span></a></li>
			<?php
				if(checkAllow('sit_viewcaltopay')){
					?>
					<li class='has-sub'><a href='caltopay.php'><span>ตั้งเบิก</span></a>
						 <ul>
							<li class='last'><a href='payhirelist.php'><span>รอจ่าย</span></a></li>
 							<li class='last'><a href='payhirelist.php?all=1'><span>ทั้งหมด</span></a></li>
						</ul>
					</li>
					<?php
				}else{
					?>
					<li class='last'><a href='checkcaltopay.php'><span>ตรวจสอบตั้งเบิก</span></a></li>
					<?php
			}
			?>
			<li class='last'><a href='historypayhire.php'><span>ประวัติยอดตั้งเบิก</span></a></li>
			<li class='last'><a href='advancelist.php'><span>รายการเบิกเงิน</span></a></li>
			<li class='last'><a href='chargeback.php'><span>รายการปรับ</span></a></li>
			<li class='last'><a href='askforholiday.php'><span>ขอวันหยุด</span></a></li>
	   </ul>
   </li>
	 <?php
	 if(checkAllow('sit_report')){
			  ?>
		   <li class='has-sub'><a href='chartreport.php'><span>รายงาน</span></a></li>
			 <?php
		 }
	  ?>
   <li class='has-sub'><a href='#'><span>ระบบ</span></a>
	   <ul>
			 <li class='has-sub'><a href='#'><span>ฟอรั่ม</span></a>
				<ul>
					<li><a href='installerforum.php'><span>ฟอรั่มช่าง</span></a></li>
					<!--<li><a href='installerforum.php'><span>ฟอรั่มแอดมิน</span></a></li>
					<li><a href='installerforum.php'><span>ฟอรั่มไอที</span></a></li>-->
				</ul>
			 </li>
			<li class='last'><a href='#'><span>คู่มือ</span></a></li>
			<?php
			if(checkAllow('permission')){
			?>
			<li class='last'><a href='confpermission.php'><span>ตั้งค่าสิทธิ์</span></a></li>
			<?php
			}
			?>
			<li class='last'><a href='reasoncode.php'><span>Code คืนงาน</span></a></li>
			<li class='last'><a href='logout.php'><span>ออกจากระบบ</span></a></li>
	   </ul>
   </li>
<?php
$strTTicket = "tidnet_common.notification";
$strCTicket = "who_notified='".$_COOKIE['id_ticket']."' and read_first='0000-00-00 00:00:00'";
$notis = fncCountRow($strTTicket,$strCTicket);

if($_GET['debug']) echo "select count(*) as numOfRow from $strTTicket where $strCTicket";
$noty = '';
if($notis>=1) $noty = "<a href=\"ticket.php\"  style=\"color:red\">คุณมี ".$notis." tickets <<คลิ๊ก</a>";
?>
<span id="ticketnoty"><?php echo $noty?></span>
</ul>
</div>
