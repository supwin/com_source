
<?php
include('cookies.php');
?>
<p>รายการบันทึกปรับ!!! ..... </p>
<?php

$strTable = "tidnet_common.chargebackjob_header";
$strCondition = '1';
if($_COOKIE['permission']==4){
  $strCondition = "chgbaked_who='".$_COOKIE['uid']."'";
}
$cbHlist = fncSelectConditionRecord($strTable,$strCondition);

if($_GET['debug']) echo "SELECT * FROM $strTable WHERE $strCondition  $strSort";
?>
<script>
$(document).ready(function(){
	$(".clicktodetail").click(function(){
    $(".detailtr").remove();
		hid = $(this).attr('for');
    //alert(hid);
    frm = "<textarea id=\"textarea_"+hid+"\" style=\"width:100%;height:40px;\"></textarea><br><span class=\"cbreply button\" for=\""+hid+"\"> ตอบ </span>";
   $.ajax({
      type: "POST",
      url: "getchargebackdetaillist.php",
      cache: false,
      data: "hid="+hid,
      success: function(msg){
        detailTxt = "<tr class=\"detailtr\"><td class=\"noneborder\"></td><td colspan=\"5\">"+msg+" "+frm+"</td></tr><tr class=\"detailtr\" style=\"background-color:transparent\" class=\"noneborder\"><td colspan=\"6\"><hr></td></tr>";
        $('#cbhlist_'+hid).after(detailTxt);
      }
    });
  });
});
</script>
<table id="tbList" style="background-color:#fff">
  <tr class="header">
    <td>เลขที่</td>
    <td>วันที่แจ้ง</td>
    <td>หัวข้อ</td>
    <td>รายละเอียดการปรับ</td>
    <td>สถานะ</td>
    <td>ดูรายละเอียด</td>
  </tr>
<?php
while($cb = mysql_fetch_array($cbHlist)){

  echo "<tr id=\"cbhlist_".$cb['hid']."\">";
  echo "<td>CB-".$cb['hid']."</td>";
  echo "<td>".convdate($cb['createddatetime'])."</td>";
  echo "<td>".$cb['complaintype']." ".$cb['jobname']." ".$cb['circuit']."</td>";
  echo "<td>".$cb['description']."</td>";
  if($cb['status']==0){
    echo "<td>เข้าใหม่ ตอบภายใน 24 ชม.</td>";
  }else if($cb['status']==1){
    echo "<td>รอพิจารณา</td>";
  }else if($cb['status']==2){
    echo "<td>ยืนยันปรับ</td>";
  }
  echo "<td class=\"center\"><span class=\"button clicktodetail\" for=\"".$cb['hid']."\"> รายละเอียด </span></td>";
  echo "</tr>";

}
