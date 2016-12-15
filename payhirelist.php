<?php
include('cookies.php');
include('functions/function.php');
include("headmenu.php");

if(!checkAllow('sit_caltopay')){
  echoError('คุณไม่มีสิทธิ์ใช้งานหน้านี้');
  die();
}
?>
<script>

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

  $strPayHeaderTable = "tidnet_common.payhireheader join tidnet_common.master_employee on tidnet_common.payhireheader.emp_id=tidnet_common.master_employee.id";

  if($_GET['all']==1){
    $strPayHeaderCondition = "confirmstatus !='-9999'";
  }else{
    $strPayHeaderCondition = "confirmstatus in ('0','9999') and confirmstatus<>'-9999'";
  }

  $strPayheaderStart = "tidnet_common.payhireheader.id as payhid, tidnet_common.payhireheader.confirmstatus as confirmstatus, tidnet_common.payhireheader.headername, tidnet_common.payhireheader.sumtotal, tidnet_common.master_employee.*";
  $strPayHeaderSort = " order by tidnet_common.payhireheader.headername,tidnet_common.payhireheader.id, tidnet_common.payhireheader.confirmstatus";

  $PHList = fncSelectStarConditionRecord($strPayheaderStart,$strPayHeaderTable,$strPayHeaderCondition,$strPayHeaderSort);

  //echo "SELECT * FROM $strPayHeaderTable WHERE $strPayHeaderCondition  $strPayHeaderSort";
  $headListTxt = "คลิ๊กที่รายการที่ต้องการตรวจสอบ";
  $grandSumTotal = 0;
  $listTxt = '';
  while($PH = mysql_fetch_array($PHList)){
    if($htmp<>$PH['headername']){
      $htmp = $PH['headername'];
      $listTxt .= "<p style=\"darkorange\">".$htmp."</p>";
    }

    if($PH['sumtotal']>0){
      $grandSumTotal += $PH['sumtotal'];
    }

    $statusConfirm = "<span style=\"color:#aaa\" class=\"button\">รอการตรวจสอบ</span>";
    if($PH['confirmstatus']>0 and $PH['confirmstatus']<9999){
      $statusConfirm = "<span style=\"green\" class=\"button\">ยืนยันยอดแล้ว</span>";
    }else if($PH['confirmstatus']==9999){
      $statusConfirm = "<span style=\"color:#111\" class=\"button\">รอยืนยันยอด</span>";
    }
    $listTxt .= " <p class=\"link2checkcaltopay\" style=\"color:#ff6600\" for=\"".$PH['payhid']."\">".$statusConfirm." PHID-".$PH['payhid']." : ".$PH['name']."(".$PH['nickname'].") [".ifnotZeroisNumberNotEcho($PH['sumtotal'])."]</p>";
  }
?>
<table class="noneborder">
  <tr>
    <td>
      <?php
        echo "<p>".$headListTxt." (<span style=\"color:red;\">";
        ifnotZeroisNumber($grandSumTotal);
        echo "</span>)</p> ".$listTxt;

      ?>
    </td>
    <td id="showpay">
    </td>
  </tr>
</table>
