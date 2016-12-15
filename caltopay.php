<?php
include('cookies.php');
include('functions/function.php');
include("headmenu.php");

if(!checkAllow('sit_caltopay')){
  echoError('คุณไม่มีสิทธิ์ใช้งานหน้านี้');
  die();
}

?>
<script language="Javascript" type="text/javascript">

$(document).ready(function(){
	$('tr[name="listrow"]:even').css('background-color', '#DFFBED');
	$('tr[name="listrow"]:odd').css('background-color', '#ffffff');

   $(".sentmailbut").click(function(){
     empid = $(this).attr('for');
     $(this).hide();

     $.ajax({
        type: "POST",
        url: "postpayhire.php",
        cache: false,
        data: "whosome="+empid+"&bch=<?php echo $_POST['bch']?>&typeofjob=<?php echo $_POST['typeofjob']?>&stdate=<?php echo $_POST['startdate']?>&endate=<?php echo $_POST['enddate']?>",
        success: function(msg){
           if(msg.indexOf("login_frm.php") > -1){
            window.location.replace("login_frm.php");
            return false;
           }
            if(msg!=''){
              if(msg=='no'){
                $(this).show();
                alert('ช่างทีมนี้มีเงินที่ออกแล้วแต่ยังไม่ยืนยันการรับเงิน ไม่สามารถออกเงินซ้ำซ้อนได้');
                return false;
              }
              $('div#show').html(msg);
              buttonMail = "<span class=\"button checkpay link2checkcaltopay\" style=\"background-color:green;color:#fff;\">  ดูเบิก  </span>";
              $("#cellbutton"+empid).html( buttonMail );
            }else{
              openAlert('ติดขัดบางประการ ไม่สามารถตัดสต๊อกและบันทึกงานได้อย่างสมบูรณ์\nกรุณาแจ้งพี่หนึ่งโดยด่วน'+msg);
            }
        }
     });

   });

   $(".checkpay").click(function(){
     id = $(this).attr('for');
     //alert(phId);

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
            msg = "<table width=\"100%\"><tr class='header' id=\"showtr\"><td class='center' colspan=\"2\">รายการ</td><td class='center'>ยอดเงิน</td></tr>"+msg+"</table>";
            $('td#showpay').html(msg);
          }else{
           openAlert('ติดขัดบางประการ ไม่สามารถตัดสต๊อกและบันทึกงานได้อย่างสมบูรณ์\nกรุณาแจ้งพี่หนึ่งโดยด่วน'+msg);
          }
        }
     });
   });

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
                msg = "<table width=\"100%\"><tr class='header' id=\"showtr\"><td class='center' colspan=\"2\">รายการ</td><td class='center'>ยอดเงิน</td></tr>"+msg+"</table>";
                $('td#showpay').html(msg);
              }else{
               openAlert('ติดขัดบางประการ ไม่สามารถตัดสต๊อกและบันทึกงานได้อย่างสมบูรณ์\nกรุณาแจ้งพี่หนึ่งโดยด่วน'+msg);
              }
            }
         });
     });

 });

</script>

<?php

if(!checkAllow('sit_viewcaltopay')){
 die("คุณไม่มีสิทธิ์ใช้งานหน้านี้");
}

if(isset($_POST['save'])){
 $startdate = $_POST['startdate'];
 $enddateadd1day = date('Y-m-d',strtotime($_POST['enddate']."+1 days"));  // แก้ bug ของ datetime
 $enddate = $_POST['enddate'];
 $OV = $_POST['typeofjob'];
 $selectedEmpId = $_POST['engineerid'];

 $strCJStar = "sum(closedjob.price) as price";
 $strACSStar = "sum(acs_billdetail.item_qty*acs_billdetail.item_price) as sumCost";
 $strSNStar = "count(*) as red";
 $strADVStar = "sum(advance_money.total+(advance_money.total*interestpercent)) as total";


 $strTable = "tidnet_common.master_employee";
 $strCondition = "permission<20 and permission>0 and dontshowat not like '%@caltopay@%'";
 if($selectedEmpId<10000000) $strCondition .= " and id='".$selectedEmpId."'";
 $empList = fncSelectConditionRecord($strTable,$strCondition);
 $report = Array();

 $allBranchforcal = $allBranch;

 if($_POST['bch']<>''){
    $allBranchforcal = array($_POST['bch']=>'');
  }

 while($emp = mysql_fetch_array($empList)){
  $minimumDeposit = $emp['minimumdeposit']; // index ด้วยตัวเลข จึงจได้ value ถ้า index ด้วยชื่อ ไม่ได้ value ยังงงงงงงงงๆๆๆๆ อยู่
  $mustShow = 0;
  $sumTotal = 0;

  $price = 0;
  $inputsumCost = 0;
  $total = 0;
  $interest = 0;
  $redSN = 0;



  foreach ($allBranchforcal as $key => $value) {
     $strCJTable = "tidnet_".$key.".closedjob";
     $strCJCondition = "series<>'' and closeddate>='".$startdate."' and closeddate<'".$enddateadd1day."' and emp_id='".$emp['id']."' and payhireheader_id=0";

     if($OV>0){
       $strCJTable .= " join tidnet_common.typeofjob on tidnet_".$key.".closedjob.typejob=tidnet_common.typeofjob.id";
       if($OV==1){
         $strCJCondition .= " and tidnet_common.typeofjob.typeof in ('True Online')";
       }else if($OV==2){
         $strCJCondition .= " and tidnet_common.typeofjob.typeof in ('True visions')";
       }
     }

     $cjList = fncSelectStarConditionRecord($strCJStar,$strCJTable,$strCJCondition);
     //echo "<p>SELECT $strCJStar FROM $strCJTable WHERE $strCJCondition</p>";
     $CJ = mysql_fetch_array($cjList);
     if($CJ['price']<>0){
      $price += $CJ['price'];
      $mustShow = 1;
      $sumTotal += $CJ['price'];
     }

    if($OV<2){
       $strACSTable = "tidnet_".$key.".acs_billheader join tidnet_".$key.".acs_billdetail on tidnet_".$key.".acs_billheader.id=tidnet_".$key.".acs_billdetail.header_id";
       $strACSCondition = "billdatetime>='".$startdate."' and billdatetime<'".$enddateadd1day."' and billtoemp_id='".$emp['id']."' and payhireheader_id='0'";
       $acsList = fncSelectStarConditionRecord($strACSStar,$strACSTable,$strACSCondition);
       //echo "<p>SELECT $strACSStar FROM $strACSTable WHERE $strACSCondition</p>";
       $ACS = mysql_fetch_array($acsList);
       if($ACS['sumCost']<>0){
        $inputsumCost += $ACS['sumCost'];
        $mustShow = 1;
        $sumTotal -= $ACS['sumCost'];  // หักค่าเบิกอุปกรณ์
       }
     }

     $strSNTable = "tidnet_".$key.".eqm_sn";
     $strSNCondition = "responcible='".$emp['id']."' and date_movement>='2015-11-01' and DATEDIFF(now(),date_movement)>=30";// or (responcible='9092' and oldowner='".$emp['id']."')";
     $snList = fncSelectStarConditionRecord($strSNStar,$strSNTable,$strSNCondition);

     //if($emp['id']==7) echo "<p>SELECT $strSNStar FROM $strSNTable WHERE $strSNCondition</p>";
     $SN = mysql_fetch_array($snList);
     if($SN['red']>0){
      $redSN += $SN['red']*8000;
      $mustShow = 1;
      //$sumTotal -= $redSN;  //คิดเงินยังไม่ต้องหัก SN แดงในตอนนี้ แต่ถ้ายังมีแดงอยู่ ไม่ยอมให้ confirm การจ่ายเงิน
      //echo "<p>".$SN['red']." ".$emp['id']." ".$redSN."</p>";
     }

     $strADVTable = "tidnet_".$key.".advance_money";
     $strADVCondition = "advance_money.status=1 and payhireheader_id='0' and emp_id='".$emp['id']."'";
     $advList = fncSelectStarConditionRecord($strADVStar,$strADVTable,$strADVCondition);
     $ADV = mysql_fetch_array($advList);
     if($ADV['total']<>0){
      $total += $ADV['total'];
      //$interest += $ADV['total']*$ADV['interestpercent'];
      $mustShow = 1;
      $sumTotal -= ($ADV['total']);  // หักเงินเบิก+ดอกเบี้ย
     }
    }

$strTableH = "tidnet_common.payhireheader";
$strConditionH = "emp_id='".$emp['id']."' and confirmstatus>0 and confirmstatus<9999 ORDER BY id DESC LIMIT 1";
$last = fncSelectSingleRecord($strTableH,$strConditionH);
$lastpaymentF=0;
if($last['sumtotal']<0){
  $mustShow = 1;
  $lastpaymentF = $last['sumtotal'];
  $sumTotal += $lastpaymentF;
}


//$minimumDeposit = $emp['minimumDeposit'];
if($enddate<='2016-10-31'){
  $minimumDeposit = 10000;
}
//echo "<p>id=".$emp['id']." minimumDeposit=".$emp['minimumdeposit']."</p>";
   $depositMustPay = $emp['depositwaranty']-$emp['paiddepositwaranty'];
   $moreDeposit = 0;
   if($depositMustPay>0){
    $mustShow = 1;
    $tenPersent = $price*0.1;
    if($tenPersent > $minimumDeposit and $tenPersent < $depositMustPay){
     $moreDeposit = ceil($tenPersent);
    }else if($tenPersent > $minimumDeposit and $tenPersent > $depositMustPay){
     $moreDeposit = ceil($depositMustPay);
   }else if($minimumDeposit > $tenPersent and $minimumDeposit < $depositMustPay){
     $moreDeposit = $minimumDeposit;
   }else if($minimumDeposit > $tenPersent and $minimumDeposit > $depositMustPay){
     $moreDeposit = ceil($depositMustPay); //
    }
    $sumTotal -= $moreDeposit;  // หักจ่ายค่าประกันเพิ่ม
   }

   //installment
   $cap = 0;
   $intrestPon = 0;
   $strTableInstallment = "tidnet_common.installment";
   $strConditioninstallment = "emp_id='".$emp['id']."' and payhireheader_id=0 and duedate<=".tidnetNow();

   $installmentlist = fncSelectConditionRecord($strTableInstallment,$strConditioninstallment);

   while($ins = mysql_fetch_array($installmentlist)){
     $cap += $ins['amount'];
     $intrestPon += $ins['interestamount'];
   }
   if($cap+$intrestPon<>0){
     $mustShow = 1;
     $sumTotal += -$cap-$intrestPon;
   }

   if($mustShow){
     $report[$emp['id']] = array('id'=>$emp['id'],'name'=>$emp['name'],'nickname'=>$emp['nickname'],'permission'=>$emp['permission'], 'lastpayment'=>$lastpaymentF, 'deposit'=>$moreDeposit, 'sumCost'=>$inputsumCost,'redsn'=>$redSN, 'total'=>$total,'interest'=>$interest,'price'=>$price,'pon'=>$cap+$intrestPon,'sumTotal'=>$sumTotal);
   }
 }
}
?>
    <form action="" method="post">
      <table class="noneborder">
        <tr>
          <td>เรียกดูจากวันที่ </td><td><input type="date" name="startdate" class="datejob" id="startdate" value="<?php echo $startdate; ?>" >
            <td>  ถึงวันที่ </td><td><input type="date" name="enddate" class="datejob" id="enddate" value="<?php echo $enddate; ?>">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;สาขา &nbsp;<select name="bch">
                    <option value=''>ทุกสาขา</option>
                  <?php
                    foreach ($allBranch as $key => $value) {
                      $selectedforbch = "";
                      if($_POST['bch']==$key){
                        $selectedforbch = "selected";
                      }
                      echo "<option value=\"".$key."\" ".$selectedforbch.">".$key."</option>";
                    }
                  ?>
                </select>

            </td>
              <td></td>
        </tr>
        <tr>
          <td>ทำจ่าย </td><td><select name="typeofjob">
                              <option value="0">ทั้งหมด</option>
                              <option value="1" <?php if($OV==1) echo "selected";?>>เฉพาะ true Online</option>
                              <option value="2" <?php if($OV==2) echo "selected";?>>เฉพาะ true visions</option>
                            </select>
                          </td>
          <td>เลือกช่าง </td><td>
            <select name="engineerid">
              <option value="10000000">ทุกช่าง</option>
            <?php

                $strTable = "tidnet_common.master_employee";
                $strCondition = "permission >'0' and dontshowat not like '%@caltopay@%' order by permission DESC";
                $emp = fncSelectConditionRecord($strTable,$strCondition);
                while($em = mysql_fetch_array($emp)){
                    $nickname = '';
                    if($em['nickname']<>''){
                      $nickname = "(".$em['nickname'].")";
                    }
                    $selectedOption = "";
                    if($selectedEmpId==$em['id']){
                      $selectedOption = "selected";
                    }
                    echo "<option value=".$em['id']." ".$selectedOption.">".$em['name']." ".$nickname."</option>";
                }

             ?>
           </select>
          </td>
          <td><button type="submit" id="btn" name="save" > ขอดู </button></td>
        </tr>
      </table>
    </form>
    <div id='show'></div>
    <table>
    <tr class='header'>
    <td class='center'>ID</td>
    <td class='center'>ชื่อ-นามสกุล</td>
    <td class='center'>ตั้งเบิก</td>
    <td class='center'>ยอดติดตั้ง</td>
    <td class='center'>เบิกอะไหล่</td>
    <td class='center'>ยอดยกมา</td>
    <td class='center'>เงินประกัน</td>
    <td class='center'>SN แดง</td>
    <td class='center'>เบิกล่วงหน้า</td>
    <td class='center'>ผ่อนชำระ</td>
    <td class='center'>รวม</td>
    </tr>
    <tr class="header"><td colspan="11"><span>สาขา : <?php echo strtoupper($_POST['bch'])?> รอบการจ่าย <?php echo $_POST['startdate']." ถึง ".$_POST['enddate']." [".$_POST['typeofjob']."]"?> </span></td></tr>
    <?php
    $whosome = "";
    $strTablepayheader = "tidnet_common.payhireheader";

    foreach ($report as $view) {
     if(floatval($report['price'])+floatval($report['sumCost'])+floatval($report['total'])+floatval($report['interest'])==0) continus;

     $buttonMail = "<span class=\"button sentmailbut\" for=\"".$view['id']."\"> ตั้งเบิก </span>";
     //$strCondition = "emp_id='".$view['id']."' and headername='[".strtoupper($abvt)."] รอบ ".$startdate." ถึง ".$enddate."'";

     $headerRound = "รอบ ".$startdate." ถึง ".$enddate." [".$OV."]";
     if($_POST['bch']<>''){
     	$headerRound .= " สาขา:".strtoupper($_POST['bch']);
     }
     $strCondition = "emp_id='".$view['id']."' and headername='".$headerRound."' and confirmstatus<>'-9999'";
     $p = fncSelectSingleRecord($strTablepayheader,$strCondition);
     if($p['id']){
      $buttonMail = "<span class=\"button checkpay link2checkcaltopay\" for=\"".$p['id']."\" style=\"background-color:green;color:#fff;\">  ดูเบิก  </span>";
     }
     ?>
     <tr  name="listrow">
      <td><?php echo $view['id'];?></td>
      <td><a href="historypayhire.php?eid=<?php echo $view['id'];?>" target="_blank" title="คลิ๊กเพื่อดูประวัติจ่ายค่าติดตั้ง"><?php echo $view['name']." (".$view['nickname'].")";?></a></td>
      <td class='center' id="cellbutton<?php echo $view['id'];?>"><?php echo $buttonMail;?></td>
      <td class='right'><?php ifnotZeroisNumber($view['price']);?></td>
      <td class='right'><?php ifnotZeroisNumber($view['sumCost']);?></td>
      <td class='right'><?php ifnotZeroisNumber($view['lastpayment']);?></td>
      <td class='right'><?php ifnotZeroisNumber($view['deposit']);?></td>
      <td class='right'><?php ifnotZeroisNumber($view['redsn'])?></td>
      <td class='right'><?php ifnotZeroisNumber($view['total']);?></td>
      <td class='right'><?php ifnotZeroisNumber($view['pon']);?></td>
      <td class='right'><?php ifnotZeroisNumber($view['sumTotal']);?></td>
     </tr>
     <?php
     if($whosome<>''){
      $whosome .= ",".$view['id'];
     }else{
      $whosome = $view['id'];
     }
    }
    ?>
    </table>
