<?php 
include('cookies.php');
$depot = $_GET['depot'];
$bklid = $_GET['bklid'];
$prov = $_GET['prov'];
header("Content-Type: application/vnd.ms-excel");
header('Content-Disposition: attachment; filename="back_lotid-'.$depot.'_'.$bklid.'.xls"');#ชื่อไฟล์
include('db_function/phpMySQLFunctionDatabase.php');
?>

<html xmlns:o="urn:schemas-microsoft-com:office:office"

xmlns:x="urn:schemas-microsoft-com:office:excel"

xmlns="http://www.w3.org/TR/REC-html40">

<HTML>

<HEAD>

<meta http-equiv="Content-type" content="text/html;charset=utf-8" />

</HEAD><BODY>

<TABLE  x:str BORDER="1">

  	<colgroup span="10" width="114"></colgroup>
  	<tr>
  		<td style="border-top: 3px solid #000000; border-bottom: 1px solid #000000; border-left: 3px solid #000000; border-right: 1px solid #000000" colspan=10 height="26" align="center" valign=middle bgcolor="#0000FF"><b><font face="Arial Unicode MS">แบบฟอร์ม บันทึกข้อมูล Serial Modem FTTx</font></b></td>
  		</tr>
  	<tr>
  		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 3px solid #000000; border-right: 1px solid #000000" rowspan=5 height="120" align="center" valign=middle bgcolor="#0000FF"><font face="Arial Unicode MS">ลำดับ</font></td>
  		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=5 align="left" valign=middle bgcolor="#008000"><b><font face="Arial Unicode MS">ตัวแทน : หจก.ติดเน็ต depot:<?php echo $depot?></font></b></td>
  		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=4 align="left" valign=middle bgcolor="#008000"><b><font face="Arial Unicode MS">จังหวัด : <?php echo $prov?></font></b></td>
  		</tr>
  	<tr>
  		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=2 align="center" valign=middle bgcolor="#FFA500"><b><font face="Liberation Serif">Huawei</font></b></td>
  		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=2 align="center" valign=middle bgcolor="#FFC0CB"><b><font face="Liberation Serif">ZTE</font></b></td>
  		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" rowspan=4 align="center" valign=middle bgcolor="#0000FF"><b><font face="Liberation Serif">Circuit</font></b></td>
  		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" rowspan=4 align="center" valign=middle bgcolor="#0000FF"><b><font face="Arial Unicode MS">ชื่อสกุล ลูกค้า</font></b></td>
  		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" rowspan=4 align="center" valign=middle bgcolor="#0000FF"><b><font face="Arial Unicode MS">วันที่ติดตั้ง</font></b></td>
  		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=2 align="center" valign=middle bgcolor="#FFC0CB"><font face="Arial Unicode MS">Modem ยี่ห้อ</font></td>
  		</tr>
  	<tr>
  		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle bgcolor="#FFA500"><b><font face="Liberation Serif">S/N</font></b></td>
  		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle bgcolor="#FFA500"><b><font face="Liberation Serif">TRUE S/N</font></b></td>
  		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle bgcolor="#FFC0CB"><b><font face="Liberation Serif">S/N</font></b></td>
  		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle bgcolor="#FFC0CB"><b><font face="Liberation Serif">D S/N</font></b></td>
  		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" rowspan=3 align="center" valign=middle bgcolor="#FFC0CB"><b><font face="Liberation Serif">Huawei</font></b></td>
  		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" rowspan=3 align="center" valign=middle bgcolor="#FFC0CB"><b><font face="Liberation Serif">ZTE</font></b></td>
  	</tr>
  	<tr>
  		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" rowspan=2 align="center" valign=middle bgcolor="#FFA500"><font face="Arial Unicode MS">ที่ขึ้นต้นด้วย 48XXX</font></td>
  		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" rowspan=2 align="center" valign=middle bgcolor="#FFA500"><font face="Arial Unicode MS">ที่ขึ้นต้นด้วย 3000XX</font></td>
  		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" rowspan=2 align="center" valign=middle bgcolor="#FFC0CB"><font face="Arial Unicode MS">ที่ขึ้นต้นด้วย 300XXXX</font></td>
  		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" rowspan=2 align="center" valign=middle bgcolor="#FFC0CB"><font face="Arial Unicode MS">ที่ขึ้นต้นด้วย ZTEXXX</font></td>
  		</tr>
  	<tr>
  		</tr>
  	<tr>
  		<td style="border-top: 1px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 1px solid #000000" colspan=10 height="26" align="center" valign=middle bgcolor="#0000FF"><b><font face="Arial Unicode MS">แบบฟอร์ม บันทึกข้อมูล Serial Modem FTTx</font></b></td>
  		</tr>
<?php
$star = "e.sn,c.circuit as cir,c.cust_name,c.closeddate";
$strTable = "closedjob as c join eqm_sn as e on c.series=e.sn";  // รอแก้ไข modem ที่ถูกตัดสต๊อกแต่ไม่ได้ตั้งเบิก จะไม่แสดงต้องนำออกมาแสดงด้วย
$strCondition = "back_lotid='".$_GET['bklid']."'";
$lst = fncSelectStarConditionRecord($star,$strTable,$strCondition);
while($sn = mysql_fetch_array($lst)){?>
  <tr>
    <td></td>
    <td></td>
      <?php
        if(substr($sn['sn'],0,4)=='3000'){  //กรณี Huawei
          ?>
            <td sdnum="1033;0;@"><?php echo $sn['sn']?></td>
            <td></td>
          <?php
        }else{ // กรณี เป็น ZTE
          ?>
            <td></td>
            <td sdnum="1033;0;@"><?php echo $sn['sn']?></td>
          <?php
        }
        ?>
    <td></td>
    <td sdnum="1033;0;@"><?php echo $sn['cir']?></td>
    <td><?php echo $sn['cust_name']?></td>
    <td><?php echo $sn['closeddate']?></td>
    <td></td>
    <td></td>
  </tr>
<?php
}
?>

</TABLE>

</BODY>

</HTML>
