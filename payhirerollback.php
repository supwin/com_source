<?php
include('cookies.php');
include("functions/function.php");
include("db_function/phpMySQLFunctionDatabase.php");
include("../com_source/config.php");



if(!checkAllow('sit_rollbackpayhire') and $_COOKIE['uid']==1){
  echoError('คุณไม่มีสิทธิ์ยกเลิก ใบจ่ายค่าติดตั้งได้');
  die();
}

$hid = $_GET['hid'];
$empid = $_GET['empid'];

if(isset($_POST['save'])){
    $note = $_POST['note'];

    $strTablePhh = "tidnet_common.payhireheader";
    $strConditionPhh = "id='".$hid."'";
    $strCommandPhh = "confirmstatus=-9999, whorollback='".$_COOKIE['uid']."',note='".$note."', confirmedorrollbackeddatetime=".tidnetNow();
    mysql_query("SET NAMES UTF8");
    mysql_query('BEGIN');

    fncUpdateRecord($strTablePhh,$strCommandPhh,$strConditionPhh);

    if(mysql_affected_rows()<=0){
      echoError("ไม่สามารถ update payhireheader ได้ UPDATE $strTablePhh SET $strCommandPhh WHERE $strConditionPhh");
      mysql_query('ROLLBACK');
      die();
    }
    echoSuccf( "<p>$strTablePhh SET $strCommandPhh WHERE $strConditionPhh</p>");

    $starD = "price";
    $strTableD = "tidnet_common.payhiredetail";
    $strConditionD = "header_id='".$hid."' and typedetail='3401'";

    $waranty = fncSelectSingleStarRecord($starD,$strTableD,$strConditionD);
    if($waranty['price']<0){
      mysql_query("update tidnet_common.master_employee set paiddepositwaranty=paiddepositwaranty+".$waranty['price']." where id='".$empid."'");
      if(mysql_affected_rows()<=0){
        echoError("ไม่สามารถ update master_employee ได้ UPDATE update tidnet_common.master_employee set paiddepositwaranty=paiddepositwaranty+".$waranty['price']." where id='".$empid."'");
        mysql_query('ROLLBACK');
        die();
      }
    }

    $sql = "delete from tidnet_common.payhiredetail where header_id='".$hid."'";
    if(!fncFullSQL($sql)){
      echoError("ไม่สามารถ ลบ payhiredetail ได้ $sql");
      mysql_query('ROLLBACK');
      die();
    }
    echoSuccf( "<p>".$sql."</p>");

    $strCommandUpdateDetail = " payhireheader_id=0";
    $strConditionUpdateDetail = " payhireheader_id='".$hid."'";

    fncUpdateRecord("tidnet_common.advance_money",$strCommandUpdateDetail,$strConditionUpdateDetail);

    if(mysql_affected_rows()<0){
      echoError("ไม่สามารถ update advance_money ได้ UPDATE tidnet_common.advance_money SET $strCommandUpdateDetail WHERE $strConditionUpdateDetail");
      mysql_query('ROLLBACK');
      die();
    }

    foreach ($allBranch as $key => $value) {
      /*
      fncUpdateRecord("tidnet_".$key.".advance_money",$strCommandUpdateDetail,$strConditionUpdateDetail);

      if(mysql_affected_rows()<0){
        echoError("ไม่สามารถ update advance_money ได้ UPDATE tidnet_".$key.".advance_money SET $strCommandUpdateDetail WHERE $strConditionUpdateDetail");
        mysql_query('ROLLBACK');
        die();
      }*/


      fncUpdateRecord("tidnet_".$key.".acs_billheader",$strCommandUpdateDetail,$strConditionUpdateDetail);

      if(mysql_affected_rows()<0){
        echoError("ไม่สามารถ update acs_billheader ได้ UPDATE tidnet_".$key.".acs_billheader SET $strCommandUpdateDetail WHERE $strConditionUpdateDetail");
        mysql_query('ROLLBACK');
        die();
      }


      fncUpdateRecord("tidnet_".$key.".closedjob",$strCommandUpdateDetail,$strConditionUpdateDetail);

      if(mysql_affected_rows()<0){
        echoError("ไม่สามารถ update closedjob ได้ UPDATE tidnet_".$key.".closedjob SET $strCommandUpdateDetail WHERE $strConditionUpdateDetail");
        mysql_query('ROLLBACK');
        die();
      }


    }
    mysql_query('COMMIT'); ?>
          <script language="Javascript" type="text/javascript">
          alert('Rollback เสร็จเรียบร้อย');
          window.close();
          </script>
  <?php
}
?>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
<meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1' />
<script src="jquery/jquery.js">
</script>
<script language="Javascript" type="text/javascript">
    $(document).ready(function(){
      $('#cancel').click(function() {
        window.close();
      });
    });
</script>
<?php





      $star = "tidnet_common.payhireheader.id as hid, tidnet_common.master_employee.id as empid, tidnet_common.payhireheader.*, tidnet_common.master_employee.*";
      $strTable= "tidnet_common.payhireheader join tidnet_common.master_employee on tidnet_common.payhireheader.emp_id=tidnet_common.master_employee.id";
      $strCondition = "tidnet_common.payhireheader.id='".$hid."'";
      //echo "SELECT $star FROM $strTable WHERE $strCondition ";
      $phhd_id= fncSelectSingleStarRecord($star,$strTable,$strCondition);
?>
<p style="background-color:#FF7F00;color:#ffffff;font-weight:450;vertical-align: middle;text-align:center;height:20px;">บันทึกการ Rollback [PH-ID:<?php echo $phhd_id['hid']; ?>] ตั้งเบิกของ<?php echo $phhd_id['name']."(".$phhd_id['nickname'].") ".$phhd_id['headername']?> </p>
 <form method="post">
<table>
  <tr>
    <td>บันทึก : <input type="text" name="note" required> </td>
  </tr>
</table>
<hr>
<p>กดปุ่ม "ยืนยัน" เพื่อยืนยันการ Rollback </p>
<input type="button" id="cancel" value="ยกเลิก">
<button type="submit" id="btn" name="save" >ยืนยัน</button>
</form>
