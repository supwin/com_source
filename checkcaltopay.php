<?php
include('cookies.php');
include('functions/function.php');
include("headmenu.php");


	$startdate = $_POST['startdate'];
	$enddate = $_POST['enddate'];
?>

<script language="Javascript" type="text/javascript">

	$(document).ready(function(){
    $(".link2checkcaltopay").click(function(){
        id = $(this).attr('for');
				//alert(id);
        $.ajax({
           type: "POST",
           url: "checkcaltopaylist.php",
           cache: false,
           data: "phid="+id,
           success: function(msg){
            if(msg.indexOf("login_frm.php") > -1){
              window.location.replace("login_frm.php");
              return false;
            }
             if(msg!=''){
               $('tr#showtr').after(msg);
             }else{
              openAlert('ติดขัดบางประการ ไม่สามารถตัดสต๊อกและบันทึกงานได้อย่างสมบูรณ์\nกรุณาแจ้งพี่หนึ่งโดยด่วน'+msg);
             }
           }
        });
    });

		$("tr#showtr").on('click', 'button',function(){
			alert('test');
		});

  });
</script>
<table width="80%">
  <tr>
    <td width="50%">
      <?php

      $strPayHeaderTable = "tidnet_common.payhireheader join tidnet_common.master_employee on tidnet_common.payhireheader.emp_id=tidnet_common.master_employee.id";
/*
      if(checkAllow('sit_confirmcaltopay')){
        $strPayHeaderCondition = "confirmstatus='0'";
      }else{
        $strPayHeaderCondition = "emp_id='".$_COOKIE['uid']."' and confirmstatus='9999'";
      }
      $strPayHeaderSort = " order by id";
*/
    if($_COOKIE['permission']==1){
      $strPayHeaderCondition = "confirmstatus in ('0','9999')";
		}else{
			$strPayHeaderCondition = "emp_id='".$_COOKIE['uid']."' and confirmstatus='9999'";
		}

			$strPayheaderStart = "tidnet_common.payhireheader.id as payhid, tidnet_common.payhireheader.confirmstatus as confirmstatus, tidnet_common.payhireheader.headername, tidnet_common.payhireheader.urlkey,  tidnet_common.master_employee.*";
      $strPayHeaderSort = " order by tidnet_common.payhireheader.id";

      $PHList = fncSelectStarConditionRecord($strPayheaderStart,$strPayHeaderTable,$strPayHeaderCondition,$strPayHeaderSort);

			//echo "SELECT * FROM $strPayHeaderTable WHERE $strPayHeaderCondition  $strPayHeaderSort";
			echo "<p>คลิ๊กที่รายการที่ต้องการตรวจสอบ และกดยืนยัน</p>";
      while($PH = mysql_fetch_array($PHList)){
        $statusConfirm = "<span style=\"color:#aaa\">รอการตรวจสอบ</span>";
        	if($PH['confirmstatus']>0 and $PH['confirmstatus']<9999){
          	$statusConfirm = "<span style=\"green\" class=\"button\">ยืนยันยอดแล้ว</span>";
          }else if($PH['confirmstatus']==9999){
            $statusConfirm = "<span style=\"color:#111\">รอยืนยันยอด</span>";
					}
        echo "<p class=\"link2checkcaltopay\" for=\"".$PH['payhid']."\">".$PH['name']."(".$PH['nickname'].") ".$PH['headername']." : สถานะ ".$statusConfirm." >>> <a href=\"http://".$abvt.".tidnet.co.th/payhireconfirmation.php?urlkey=".$PH['urlkey']."&hid=".$PH['payhid']."\">คลิ๊กเพื่อดูรายละเอียด..</a></p>";
      }
      ?>
      <table width="100%">
        <tr class='header' id="showtr">
          <td class='center' colspan="2">รายการ</td>
          <td class='center'>ยอดเงิน</td>
        </tr>
      </table>
    </td>
		<!--
    <td width="50%">
      <form action="" method="post">
      		เรียกดูจากวันที่ <input type="date" name="startdate" class="datejob" id="startdate" value="<?php echo $startdate; ?>" >
      		ถึงวันที่ <input type="date" name="enddate" class="datejob" id="enddate" value="<?php echo $enddate; ?>">
      		<button type="submit" id="btn" name="save" >ยืนยัน</button>
      </form>

      <table width="100%">
        <tr class='header'>
          <td class='center' colspan="2">รายการ</td>
          <td class='center'>ยอดเงิน</td>
        </tr>
        <tr>
          <td colspan="3" style="color:#fff;background-color:#ff8c00;" class="center">ข้อมูลนี้ยังไม่ได้รับส่งเมล์เพื่อยืนยัน</td>
        </tr>


      <?php

      if(isset($_POST['save'])){

        $sumTotal = 0;

      	$strSNStar = "count(*) as red";
      	$strSNTable = "eqm_sn";

        $strTable = "employee";
      	$strCondition = "id='".$_COOKIE['uid']."'";
        $emp = fncSelectSingleRecord($strTable,$strCondition);
        if($emp['lastpayment']<0){
          ?>
            <tr style="background-color:#fff;">
              <td>ยอดยกมา</td>
              <td class='center'><?php echo $emp['lastpayment'];?></td>
            </tr>
          <?php
          $sumTotal -= $CJ['price'];
        }

      	$strCJStar = "circuit,cust_name,price";
      	$strCJTable = "closedjob";
        $strCJCondition = "closedjob.series<>'' and closeddate>='".$startdate."' and closeddate<='".$enddate."' and emp_id='".$emp['id']."' and payhireheader_id=0";

        $cjList = fncSelectStarConditionRecord($strCJStar,$strCJTable,$strCJCondition);
        //if($_GET['debug']) echo "<p>SELECT $strCJStar FROM $strCJTable WHERE $strCJCondition</p>";
        while($CJ = mysql_fetch_array($cjList)){
          ?>
            <tr style="background-color:#fff;">
              <td><?php echo $CJ['circuit'];?></td>
							<td><?php echo $CJ['cust_name'];?></td>
              <td class='right'><?php echo number_format($CJ['price'],2);?></td>
            </tr>
          <?php
          $sumTotal += $CJ['price'];
        }



        $strACSStar = "sum(acs_billdetail.item_qty*acs_billdetail.item_price) as sumCost, billdatetime, acs_billdetail.header_id as header_id";
      	$strACSTable = "acs_billheader join acs_billdetail on acs_billheader.id=acs_billdetail.header_id";
        $strACSCondition = "billdatetime>='".$startdate."' and billdatetime<='".$enddate."' and billtoemp_id='".$emp['id']."' and acs_billheader.payhireheader_id=0 group by header_id";
        $acsList = fncSelectStarConditionRecord($strACSStar,$strACSTable,$strACSCondition);
        //if($_GET['debug']) echo "<p>SELECT $strACSStar FROM $strACSTable WHERE $strACSCondition</p>";
        //echo "SELECT $strACSStar FROM $strACSTable WHERE $strACSCondition";

        while($ACS = mysql_fetch_array($acsList)){
          ?>
            <tr style="background-color:#fff;">
              <td>ใบรับอะไหล่ <?php echo $ACS['header_id'];?></td>
							<td>เบิกเมื่อ <?php echo $ACS['billdatetime']."]";?></td>
              <td class='right'><?php echo number_format($ACS['sumCost'],2);?></td>
            </tr>
          <?php
          $sumTotal -= $ACS['sumCost'];
        }


      	$strADVStar = "sum(advance_money.total) as total, interestpercent";
      	$strADVTable = "advance_money";
        $strADVCondition = "advance_money.status=1 and date_advance>='".$startdate."' and date_advance<='".$enddate."' and emp_id='".$emp['id']."' and payhireheader_id=0";
        $advList = fncSelectStarConditionRecord($strADVStar,$strADVTable,$strADVCondition." and employee.id='".$emp['id']."'");
        while($ADV = mysql_fetch_array($advList)){
          ?>
            <tr style="background-color:#fff;">
              <td>เบิกล่วงหน้า </td>
							<td>เมื่อ <?php echo $ADV['date_advance'];?></td>
              <td class='right'><?php echo number_format($ADV['total'],2)." [".number_format($ADV['total']*$ADV['interestpercent'],2)."]";?></td>
            </tr>
          <?php
          $sumTotal -= ($ADV['total'] + ($ADV['total']*$ADV['interestpercent']));
        }


        if($emp['lastpayment']<0) $mustShow = 1;
        $depositMustPay = $emp['depositwaranty']-$emp['paiddepositwaranty'];
        $moreDeposit = 0;
        if($depositMustPay>0){
          $mustShow = 1;
          $tenPersent = $CJ['price']*0.1;
          if($tenPersent > 3000 and $tenPersent < $depositMustPay){
            $moreDeposit = ceil($tenPersent);
          }else if($tenPersent > 3000 and $tenPersent > $depositMustPay){
            $moreDeposit = ceil($depositMustPay);
          }else if($tenPersent < 3000 and $depositMustPay > 3000){
            $moreDeposit = 3000;
          }else if($tenPersent < 3000 and $depositMustPay < 3000){
            $moreDeposit = ceil($depositMustPay); //
          }
          $sumTotal -= $moreDeposit;  // หักจ่ายค่าประกันเพิ่ม
          ?>
            <tr style="background-color:#fff;">
              <td>หักเงินประกัน </td>
							<td>หักแล้ว <?php echo $emp['paiddepositwaranty'];?>)</td>
              <td class='right'><?php echo number_format($moreDeposit,2);?></td>
            </tr>
          <?php
        }


      /*
        $redSN = 0;
        $strSNCondition = "(responcible='".$emp['id']."' and DATEDIFF(now(),date_movement)>30)";// or (responcible='9092' and oldowner='".$emp['id']."')";
        $snList = fncSelectStarConditionRecord($strSNStar,$strSNTable,$strSNCondition);
        //echo "<p>SELECT $strSNStar FROM $strSNTable WHERE $strSNCondition</p>";
        $SN = mysql_fetch_array($snList);
        if($SN['red']>0){
          $redSN = $SN['red']*8000;
          $mustShow = 1;
          //$sumTotal -= $redSN;  //คิดเงินยังไม่ต้องหัก SN แดงในตอนนี้ แต่ถ้ายังมีแดงอยู่ ไม่ยอมให้ confirm การจ่ายเงิน
        }

      */



      }
      ?>
        <tr style="background-color:#fff;">
          <td class='right' colspan="2">รายรับสุทธิ</td>
          <td class='right'><?php echo number_format($sumTotal);?></td>
        </tr>
      </table>
    </td>-->
  </tr>
</table>
