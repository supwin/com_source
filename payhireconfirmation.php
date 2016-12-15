<?php
include('cookies.php');
include('functions/function.php');
include("headmenu.php");
?>
<script>
function validateForm() {
    var x = document.forms["confirmfrm"]["allred"].value;
    if (x >=1) {
        alert("มี SN เกิน 30 วันในสต๊อกของท่าน กรุณาตรวจสอบก่อนยืนยันค่ะ");
        return false;
    }
}
</script>
<?php


$datecash = date('Y-m-d');//$yy."-".$mm."-".$dd;

$phid = $_GET['hid'];
$uKey = $_GET['urlkey'];
$confirm = $_POST['confirm'];
mysql_query("BEGIN");

$strTable = "tidnet_common.payhireheader join tidnet_common.payhiredetail on tidnet_common.payhireheader.id= tidnet_common.payhiredetail.header_id";
$strCondition = "payhireheader.id='".$phid."'";
$strSort = " order by payhiredetail.id";
//ดecho "SELECT * FROM $strTable WHERE $strCondition  $strSort";
$PHList = fncSelectConditionRecord($strTable,$strCondition,$strSort);

$mailTxt = "<table width=\"800\">
  <tr class='header'>
    <td class='center' colspan=\"2\">รายการ</td>
    <td class='center'>ยอดเงิน</td>
  </tr>";

  while($PH = mysql_fetch_array($PHList)){
        $empId = $PH['emp_id'];

        if($PH['confirmstatus']!=0 and $PH['urlkey']<>'' and ($_COOKIE['permission']==1 and $_COOKIE['uid']<>$empId) ){
          if($PH['confirmstatus']==-9999){
            echoError('รายการนี้ยกเลิกไปแล้ว ไม่สามารถส่งเมล์ได้');
            die();
          }
          echoError('รอบเงินจ่ายนี้ได้มีการส่งเมล์ไปแล้ว ไม่อนุญาตให้ส่งซ้ำอีก');
          die();
        }

        if(++$i==1){
          $headnameTxt = $PH['headername'];
          $mailTxt .="<tr style=\"background-color:#fff;\">
            <td colspan=\"3\" class=\"center\" style=\"background-color:#3399ff;weight:900\">".$PH['headername']."</td>
          </tr>";
        }

        $sumTotalList += $PH['price'];
        $mailTxt .= "<tr style=\"background-color:#fff;\">
            <td>".$PH['cj_id']."</td>
            <td>".$PH['circuit']."</td>
            <td class='right'>".number_format($PH['price'],2)."</td>
          </tr>";

  }
  $mailTxt .= "<tr style=\"background-color:orange;color:#fff;\">
          <td colspan='2' class=\"right\">รวมยอด</td>
          <td class='right'> ".number_format($sumTotalList,2)."</td>
        </tr>
      </table>";


if($uKey<>''){
  //$allBranch = array('ask'=>'40-C200','non'=>'');

  $strTable = "tidnet_common.payhireheader";
  $strCondition = "urlkey='".$uKey."' and emp_id='".$_COOKIE['uid']."'";
  $strCommand = "confirmstatus='".$_COOKIE['uid']."'";
  $phl = fncSelectSingleRecord($strTable,$strCondition);

  if($phl['confirmstatus']>'0' and $phl['confirmstatus']<'9999'){
      echoError( "<div><p>รายการนี้ได้รับการ <span style=\"weight:900\">ตอบรับยืนยัน</span> ไปแล้ว</p></div>");
      mysql_query("ROLLBACK");
      die();
  }else if($phl['confirmstatus']=='-9999'){
      echoError( "<div><p>รายการนี้ได้รับการ <span style=\"weight:900\">ยกเลิก</span> ไปแล้ว</p></div>");
      mysql_query("ROLLBACK");
      die();
  }



  if($confirm<>1){

    echo $mailTxt;

    $allRed = 0;
    $listRedSn='';
    foreach ($allBranch as $key => $value) {
      $strSNTable = "tidnet_".$key.".eqm_sn";
      $strSNCondition = "responcible='".$_COOKIE['uid']."' and date_movement>='2015-11-01' and DATEDIFF(now(),date_movement)>=30";
      //$allRed += fncCountRow($strSNTable,$strSNCondition);
      //echo "<p>select * from $strSNTable where $strSNCondition</p>";
      $redEach = fncSelectConditionRecord($strSNTable,$strSNCondition);
      while($rSN = mysql_fetch_array($redEach)){
        $allRed +=1;
        $listRedSn .= "<p>สาขา ".$key." : ".$rSN['sn']."</p>";
      }
    }
    ?>
    <br>
    <form  name='confirmfrm' action="http://<?php echo $abvt;?>.tidnet.co.th/payhireconfirmation.php?urlkey=<?php echo $uKey;?>&hid=<?php echo $phid;?>" onsubmit="return validateForm()" method="post">
      <input type="hidden" value="<?php echo $allRed;?>" name="allred"><input type="hidden" value="1" name="confirm"> >> >> <input style="height:35px;" type="submit" value=" >> ยืนยันยอดถูกต้อง << "> << <<
    </form>

    <?php

    if($allRed>0){
      echoError( "<div style=\"color:red;\"> คุณมียอด modem/CATV ค้างสต๊อกเกิน 30 วันกรุณาตรวจสอบ</div>");
      echoError($listRedSn);
      //mysql_query("ROLLBACK");
    }
    die();

  }else if($confirm==1){

        fncUpdateRecord($strTable,$strCommand,$strCondition);
        if(mysql_affected_rows()<0){
          mysql_query("ROLLBACK");
          echoError('<p>[711]  ทำรายการไม่สำเร็จ ติดต่อฝ่ายไอทีทันทีค่ะ</p>');
        }
        //เอายอดเงินเข้า cash ของพี่แก้ม
        $table = "tidnet_common.payhireheader join tidnet_common.master_employee on tidnet_common.payhireheader.emp_id=tidnet_common.master_employee.id";
        $star = "tidnet_common.payhireheader.*, tidnet_common.master_employee.id as empid, tidnet_common.master_employee.name as empname, tidnet_common.master_employee.nickname as empnickname";
        $condition = "tidnet_common.payhireheader.id=".$phid;
        //echo "xxx = SELECT $star FROM $table WHERE $condition ";
        $query = fncSelectSingleStarRecord($star,$table,$condition);
        $empnametxt = $query['empname'];
        $empnickname = $query['empnickname'];
        $headnameTxt = $query['headername'];
        $list = $empnametxt."(".$empnickname.") ".$headnameTxt;
        $cash_plan = $query['sumtotal'];
        $ms_emp_id = $query['emp_id'];
        $empId = $query['empid'];
        if($cash_plan>0){
          $lastp = 0;
          $strTableC = "tidnet_ryg.cash";
          $strFieldC = "date_plan,list,cash_plan,whocreated";
          $strValueC = "'".$datecash."','".$list."','".$cash_plan."','999".$phid."'";
          //echo "INSERT INTO $strTableC ($strFieldC) VALUES ($strValueC)";
          if(!fncInsertRecord($strTableC,$strFieldC,$strValueC)){
            echoError( "<div><p>ไม่สามารถบันทึกเข้า เงินสดย่อยได้ กรุณาตรวจสอบ</p>INSERT INTO $strTableC ($strFieldC) VALUES ($strValueC)</div>");
            mysql_query("ROLLBACK");
            die();
          }
        }

        // ส่งเมล์อีกครั้ง เพื่อเป็นหลักฐานการยืนยันยอด
        $strHeader = "Content-type: text/html; charset=UTF-8\r\n"; // or UTF-8 //
        $strHeader .= "From: Mr.Tidnet system<admin@tidnet.co.th>\r\n";
        $strHeader .= "Reply-To: admin@tidnet.com\r\n";
        //$strHeader .= "cc: tidnet.true@gmail.com\r\n";
        $strHeader .= "cc: sukanya.jamp@gmail.com\r\n";
        $strHeader .= "cc: supwin@gmail.com\r\n";
        $strHeader .= "cc: aiyara.wina2532@gmail.com\r\n";
        $strHeader .= "cc: kaeangja@gmail.com\r\n";

        $mailTxt = "<div><p style=\"color:green;weight:900\">กรุณารอรับเงินเข้าบัญชี ตามที่แจ้งไว้ค่ะ</p></div>";

        $mailTxt = "<p style=\"font-size:12px;\">ยอดเงิน ".$headnameTxt." ของ : ".$empnametxt."(".$empnickname.")</p><br>".$mailTxt;
        $mailTxt .= "<p>Remark : <span style=\"color:red;\">ท่านได้รับเมล์ฉบับนี้แสดงว่าทางระบบได้รับคำยืนยันจากท่านแล้ว</span><p>";
        //$mailTxt .= "<p>คลิ๊กที่นี่เพื่อยืนยันยอดถูกต้อง <a href=\"".$_SERVER['SERVER_NAME']."/payhireconfirmation.php?hid=".$phid."&urlkey=".$urlKey."\">http://".$_SERVER['SERVER_NAME']."/payhireconfirmation.php?hid=".$phid."&urlkey=".$urlKey."</a></p>";

          @mail(getmailMast($empId),"ยอดเงิน ".$headnameTxt." ของ : ".$empnametxt."(".$empnickname.")",$mailTxt,$strHeader);

        echoSuccf( "<div><p>ได้ยืนยันยอดเงินของท่านและได้ทำการตั้งยอดจ่ายเรียบร้อยแล้ว รอเงินเข้าบัญชี....</p></div>");
        mysql_query("COMMIT");
        die();
    }
}

function RandomString(){
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randstring = '';
    for ($i = 0; $i < 20; $i++) {
        $randstring .= $characters[rand(0, strlen($characters))];
    }
    return $randstring;
}


$urlKey = RandomString();

$strTableUp = "tidnet_common.payhireheader";
$strConditionUp = "id='".$phid."'";
$strCommandUp = "urlkey='".$urlKey."', confirmstatus='9999', confirmedorrollbackeddatetime=".tidnetNow();
//echo "UPDATE $strTableUp SET  $strCommandUp WHERE $strConditionUp ";
fncUpdateRecord($strTableUp,$strCommandUp,$strConditionUp);
if(mysql_affected_rows()>0){
  $strHeader = "Content-type: text/html; charset=UTF-8\r\n"; // or UTF-8 //
  $strHeader .= "From: Mr.Tidnet system<admin@tidnet.co.th>\r\n";
  $strHeader .= "Reply-To: admin@tidnet.com\r\n";
  //$strHeader .= "cc: tidnet.true@gmail.com\r\n";
  $strHeader .= "cc: sukanya.jamp@gmail.com\r\n";
  $strHeader .= "cc: supwin@gmail.com\r\n";
  $strHeader .= "cc: aiyara.wina2532@gmail.com\r\n";
  $strHeader .= "cc: kaeangja@gmail.com\r\n";

  $empnametxt = nameofengineerMast($empId);
  $mailTxt = "<p style=\"font-size:12px;\">ยอดเงิน ".$headnameTxt." ของ : ".$empnametxt."(".nameofengineerMast($empId,1).")</p><br>".$mailTxt;
  $mailTxt .= "<p>Remark : <span style=\"color:red;\">หากพบยอดไม่ถูกต้องให้ติดต่อกลับโดยด่วน</span><p>";
  $mailTxt .= "<p>คลิ๊กที่นี่เพื่อยืนยันยอดถูกต้อง <a href=\"http://".$abvt.".tidnet.co.th/payhireconfirmation.php?hid=".$phid."&urlkey=".$urlKey."\">http://".$abvt.".tidnet.co.th/payhireconfirmation.php?hid=".$phid."&urlkey=".$urlKey."</a></p>";

    @mail(getmailMast($empId),"ยอดเงิน ".$headnameTxt." ของ : ".$empnametxt."(".nameofengineerMast($empId,1).")",$mailTxt,$strHeader);
    //@mail("supwin@gmil.com","ยอดเงิน ".$headnameTxt." ของ : ".$empnametxt."(".nameofengineerMast($emp,1),$mailTxt,$strHeader);
}else{
  echoError("<p>ส่งเมล์ไม่สำเร็จ กรุณาตรวจสอบ</p>");
  die();
}
mysql_query('COMMIT');
echoSuccf("<p>ส่งเมล์เรียบร้อย</p>");

echo $mailTxt;
