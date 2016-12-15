<?php
include('cookies.php');
include('functions/function.php');
include("headmenu.php");

if(!checkAllow('sit_returnbackstock')){
	echo "คุณไม่มีสิทธิ์ใช้งานหน้านี้";
}

$bklid = $_GET['bklid'];


$star = "e.sn";
$strTable = "closedjob as c join eqm_sn as e on c.series=e.sn";  // รอแก้ไข modem ที่ถูกตัดสต๊อกแต่ไม่ได้ตั้งเบิก จะไม่แสดงต้องนำออกมาแสดงด้วย
$strCondition = "e.id_eqm=13 and back_lotid=0";
//
$lst = fncSelectStarConditionRecord($star,$strTable,$strCondition);
while($sn = mysql_fetch_array($lst)){
  if($snlst<>'') $snlst .=",";
  $snlst .= "'".$sn['sn']."'";
}
$strCommand = "back_lotid='$bklid'";
$strTable = "eqm_sn";
$strCondition = "sn in (".$snlst.")";
//echo "UPDATE $strTable SET  $strCommand WHERE $strCondition ";

if(fncUpdateRecord($strTable,$strCommand,$strCondition)){
  echo "back_lotid-".$depot."_".$bklid.".xls <a href=\"backlotid.php?prov=".$branchName[$abvt]."&depot=".$depot."&bklid=".$bklid."\">ดาวน์โหลด</a>";
}else{
  echo "ไม่มีรายการตัดสต๊อก";
}

?>
