<?php
include('cookies.php');
include("functions/function.php");
include("db_function/phpMySQLFunctionDatabase.php");


/*
Log file
071014 1729 : just created
071014 2043 : เพิ่มความสามารถเปิด POPUP Window เพื่อให้มีการยืนยันข้อมูลก่อนบันทึก
081014 1404 : จัดภาษาไทยได้
*/


$sn = $_GET['sn'];
$branch = $_GET['branch'];

if(isset($_POST['save'])){
mysql_query('BEGIN');
	$sn = $_POST['sn'];
	$oldowner = $_POST['oldowner'];
	$responcible = $_POST['responcible'];
	$note = $_POST['note'];

	$strTable = "tidnet_".$branch.".eqm_sn";
	$strCondition = "sn='".$sn."' and oldowner='".$oldowner."' and responcible='".$responcible."'";
	$strCommand = "oldowner='0',responcible='".$oldowner."' , note='".$note."'";
	fncUpdateRecord($strTable,$strCommand,$strCondition);
	  if(mysql_affected_rows()<=0){
	    echoError("ไม่สามารถยกเลิกปิดงาน UPDATE $strTable SET $strCommand WHERE $strCondition");
	    mysql_query('ROLLBACK');
	    die();
	  }
mysql_query('COMMIT');
?>
		<script>
		alert('ทำรายการสำเร็จ');
		window.close();	
		</script>
<?php
}
// isset ตรวจสอบการมีของ $post เมื่อได้รับค่าจากตัวแปร $_POST มา
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
$strTable = "tidnet_".$branch.".eqm_sn";
$strCondition = "sn='".$sn."' and responcible = '9111'";
$serial = fncSelectSingleRecord($strTable,$strCondition);
 if($serial['responcible']!=='9111'){
	echo "Serial นี้ไม่อยู่ในสถานะ ผ่อนผัน";
	die();
   }	
?>
<p style="background-color:blue;color:#ffffff;font-weight:450;vertical-align: middle;text-align:center;height:20px;">ผ่อนผัน</p>
 <form method="post">
<table>

	<tr>
		<td><span><?php echo "สาขา ".$branch; ?></span><br>
			<span style="background-color:red;color:white"><?php echo $serial['sn']; ?></span>
		<input type="hidden" name="sn" value="<?php echo $serial['sn']; ?>">
			<br><br>
			ช่าง :<?php echo nameofengineerMast($serial['oldowner'])." (".nameofengineerMast($serial['oldowner'],1).")"; ?>
			<input type="hidden" name="oldowner" value="<?php echo $serial['oldowner']; ?>">
		<br>
			<?php if($serial['responcible']=='9111'){
				echo "ผ่อนผัน";
				}?>
		<input type="hidden" name="responcible" value="<?php echo $serial['responcible']; ?>">
	</tr>

</table>
<hr>
<span>บันทึก : </span><input type="text" name="note" required><br><br>
<input type="button" id="cancel" value="ยกเลิก">
<button type="submit" id="btn" name="save" >ยืนยัน</button>
</form>