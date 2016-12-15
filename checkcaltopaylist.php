<?php
include('cookies.php');
include('namebranch.php');
include('functions/function.php');
include('db_function/phpMySQLFunctionDatabase.php');

$phid = $_POST['phid'];

$strStar = "tidnet_common.master_employee.id as uid, tidnet_common.master_employee.name as uname, tidnet_common.master_employee.nickname as nkname, tidnet_common.payhireheader.id as pid, tidnet_common.payhiredetail.*, tidnet_common.payhireheader.*";
$strTable = "tidnet_common.payhireheader join tidnet_common.payhiredetail on tidnet_common.payhireheader.id= tidnet_common.payhiredetail.header_id
 join tidnet_common.master_employee on tidnet_common.payhireheader.emp_id=tidnet_common.master_employee.id";
$strCondition = "payhireheader.id='".$phid."'";
$strSort = " order by tidnet_common.payhiredetail.typedetail, tidnet_common.payhiredetail.circuit, tidnet_common.payhiredetail.id";
//echo "SELECT * FROM $strTable WHERE $strCondition  $strSort";
$PHList = fncSelectStarConditionRecord($strStar,$strTable,$strCondition,$strSort);

?>
<script type="text/javascript">

$(document).ready(function(){
    $("#savemore").click(function(){
         phid = $('.hid').attr('for');
         data1 = $('input#data1').val();
         data2 = $('input#data2').val();
         data3 = $('input#data3').val();
         tax = $('select.tax').val();
         $.ajax({
            type: "POST",
            url: "addpayhiredetail.php",
            cache: false,
            data: "phid="+phid+"&data1="+data1+"&data2="+data2+"&data3="+data3+"&tax="+tax,
            datatype: 'json',
            success: function(msg){
              if(msg!=''){
                $(msg).insertBefore("tr#showpay");
                $("input#data1").val('');
                $("input#data2").val('');
                $("input#data3").val('');
              }else{
               openAlert('ติดขัดบางประการ ไม่สามารถตัดสต๊อกและบันทึกงานได้อย่างสมบูรณ์\nกรุณาแจ้งพี่หนึ่งโดยด่วน'+msg);
              }
            }
         });
     });

    $(".confRollback").click(function(){
    hid = $(this).attr("for");
    empid = $(this).attr("empid");
     window.open("payhirerollback.php?hid="+hid+"&empid="+empid,"List","scrollbars=no, resizable=no, width=450, height=350");
  });
});

</script>
<?php
while($PH = mysql_fetch_array($PHList)){
  $empId = $PH['uid'];
  $confSts = $PH['confirmstatus'];
  if(++$i==1){
  ?>
    <tr style="background-color:#fff;" class="hid" for="<?php echo $PH['pid']; ?>">
      <td colspan="3" class="center" style="background-color:#3399ff;weight:900"><?php echo $PH['uname']."(".$PH['nkname'].") [PH-ID:".$PH['pid']."] ".$PH['headername']?></td>
    </tr>
  <?php
  }
  $sumTotalList += $PH['price'];
  if($ccTmp == $PH['circuit']){  ///// ตรวจเช็ค circuit ซ้ำ
    $clrDuplicate = "red";
  }else{
    $ccTmp = $PH['circuit'];
    $clrDuplicate = "";
  }
  ?>
    <tr style="background-color:#fff;">
      <td><?php echo $PH['cj_id'];?></td>
      <td style="color:<?php echo $clrDuplicate;?>"><?php echo $PH['circuit'];?></td>
      <td class='right'><?php echo number_format($PH['price'],2);?></td>
    </tr>
  <?php
}
if(checkAllow('sitaddmorepaydetail') and $confSts==0){?>
   <tr style="background-color:#fff;" id="showpay">
     <td><input type="text" style="background-color:green;width:100%;height:20px;color:#fff;" id="data1" value="<?php echo $PH['confirmstatus']?>"></td>
     <td><input type="text" style="background-color:green;width:100%;height:20px;color:#fff;" id="data2"></td>
     <td><input type="text" style="background-color:green;width:50%;height:20px;color:#fff;" id="data3">
      <select class="tax" style="width:40%;">
                <option value="0">ไม่คิดภาษี</option>
                <option value="0.03">3%</option>
      </select>
      </td>
  </tr>
     <tr style="background-color:#fff;">
     <td colspan="3" class='right'><button id="savemore"> บันทึกรายการ </button></td>
   </tr>
<?php
}
 ?>
    <tr style="background-color:#fff;">
      <td colspan='2' class="right">รวมยอด</td>
      <td class='right' id="sumgrandtotal"><?php echo number_format($sumTotalList,2);?></td>
    </tr>
<?php
if(($confSts==0 or $confSts==9999) and $_COOKIE['permission']==1){
  ?>
  <tr style="background-color:#fff;">
    <td colspan='2'><span class="button confRollback" for="<?php echo $phid?>" empid="<?php echo $empId?>" style="font-size:13px;"> Rollback <<<<< </span></td>

    <td align='right'>
      <?php
      if($confSts==0 and $_COOKIE['permission']==1){
        ?>
        <a target="_blank" href="payhireconfirmation.php?hid=<?php echo $phid?>"> คลิ๊กเพื่อยืนยันส่งเมล์ </a>
        <?php
      }
      ?>
    </td>
  </tr>
  <?php
}else if($confSts==9999 and $_COOKIE['uid']==$empId){
  ?>
  <tr style="background-color:#fff;">
    <td colspan='3' align='right'>รอยืนยันยอด</td>
  </tr>
  <?php
}else if($confSts>0 and $confSts<9999){
  ?>
  <tr style="background-color:#fff;">
    <td colspan='3' align='right' style="color:green;">ยืนยันยอดเงินตั้งเบิกแล้ว</td>
  </tr>
  <?php
}else if($confSts==0 and $_COOKIE['uid']==$empId){
  ?>
  <tr style="background-color:#fff;">
    <td colspan='3' align='right' style="color:green;">รอการเงินตรวจสอบส่งเมล์</td>
  </tr>
  <?php
}
?>
