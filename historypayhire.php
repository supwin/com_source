<?php
include('cookies.php');
include('functions/function.php');
include("headmenu.php");

if($_COOKIE['permission']<>4 and $_COOKIE['permission']<>1 and !checkAllow('sit_viewhistorypayhire')){
  echoError('คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
  die();
}

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
               msg = "<table width=\"100%\"><tr class='header' id=\"showtr\"><td class='center' colspan=\"2\">รายการ</td><td class='center'>ยอดเงิน</td></tr>"+msg+"</table>";
               $('td#showpayhirelist').html('');
               $('td#showpayhirelist').html(msg);
             }else{
              openAlert('ติดขัดบางประการ ไม่สามารถตัดสต๊อกและบันทึกงานได้อย่างสมบูรณ์\nกรุณาแจ้งพี่หนึ่งโดยด่วน'+msg);
             }
           }
        });
    });

    $("#enghis").change(function(){
        id = $('select[name=enghis]').val();
        window.location.replace("historypayhire.php?eid="+id);
    });

  });
</script>
<?php
$empId = $_GET['eid'];
if($_COOKIE['permission']==4){
  $empId = $_COOKIE['uid'];
}

$strTableEmp = "tidnet_common.master_employee";
$sttConditionEmp = "id='".$empId."'";
$empRec = fncSelectSingleRecord($strTableEmp,$sttConditionEmp);
?>
<table class="noneborder">
  <tr>
    <td>
      <p style="color:#ff6600;">ประวัติการทำจ่าย ค่าติดตั้งของ
        <?php
        if($_COOKIE['permission']==4){
          echo nameofengineerMast($_COOKIE['uid']);
        }else{
          getemplist('enghis',$empId,'เลือกช่าง');
        }
        ?>
      </p>
      <div style="padding-bottom:5px;">
        <table>
          <tr>
            <td>วงเงินประกัน</td><td class="right"><?php ifnotZeroisNumber($empRec['depositwaranty']);?> บาท</td>
              <td>งานแรก</td><td class="right"><?php echo convdate($empRec['firstclosedjobdate']);?></td>
          </tr>
          <tr>
            <td>เงินประกัน(ชำระแล้ว)</td><td class="right"><?php ifnotZeroisNumber($empRec['paiddepositwaranty']);?> บาท</td>
              <td>งานล่าสุด</td><td class="right"><?php echo convdate($empRec['lastclosedjobdate']);?></td>
          </tr>
          <tr>
            <td>เงินประกัน(หักขั้นต่ำ)</td><td class="right"><?php ifnotZeroisNumber($empRec['minimumdeposit']);?> บาท</td>
              <td></td><td class="right"><?php echo $empRec['xxx'];?></td>
          </tr>
        </table>
      </div>
      <table>
        <tr class="header">
          <td>ค่าติดตั้งรอบ</td>
          <td>ยอดเงิน</td>
          <td>สถานะ</td>
          <td>สร้างเมื่อ</td>
          <td>ยืนยันเมื่อ ^</td>
          <td>รายละเอียด</td>
        </tr>
      <?php
      $strTable = "tidnet_common.payhireheader";
      $strStar = "tidnet_common.payhireheader.id as phid,tidnet_common.payhireheader.*";
      $strcondition = "tidnet_common.payhireheader.emp_id='".$empId."' and confirmstatus != -9999";
      $strSort = " order by confirmedorrollbackeddatetime";

      $payhirelist = fncSelectStarConditionRecord($strStar,$strTable,$strcondition,$strSort);
      while($PH = mysql_fetch_array($payhirelist)){
        $color = "orange;color:#fff";
        $statustxt = "รอยืนยันรับเงิน";
        if($PH['confirmstatus']==0){
          $color = "#fff";
          $statustxt = "รอตรวจสอบ";
        }
        if($PH['confirmstatus']>0 and $PH['confirmstatus']<9999){
          $color = "green;color:#fff";
          $statustxt = "ยืนยันแล้ว";
        }

        ?>
        <tr style="background-color:<?php echo $color?>;">
          <td><?php echo $PH['headername']?></td>
          <td><?php echo ifnotZeroisNumber($PH['sumtotal'])?></td>
          <td><?php echo $statustxt?></td>
          <td><?php echo convdate($PH['createddatetime'])?></td>
          <td><?php echo $PH['confirmedorrollbackeddatetime']?></td>
          <td><span class="button link2checkcaltopay" for="<?php echo $PH['phid']?>" style="color:#000"> ดูรายละเอียด </span></td>
        </tr>
        <?php
      }

      ?>
      </table>
    </td>
    <td id="showpayhirelist">
    </td>
  </tr>
</table>
