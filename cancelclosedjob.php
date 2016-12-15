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


$jid = $_GET['jid'];

if(isset($_POST['save'])){
mysql_query('BEGIN');
	$circuit = $_POST['circuit'];
	$bundle = $_POST['bundle'];
	$conf_date = $_POST['conf_date'];
	$modsn = $_POST['modsn'];
	$catvsn = $_POST['catvsn'];
	$bcable = $_POST['bcable'];
	$note = $_POST['note'];
	$assigned_eng = $_POST['assigned_eng'];

	$strTable = "jobassign";
	$strCondition = "jid='".$jid."'and job_status='X' and assigned_eng='".$assigned_eng."'";
	$strCommand = "job_status = 'D', modsn = '' , catvsn = '' , bcable = '0' ,closedjob_img = ''";
	fncUpdateRecord($strTable,$strCommand,$strCondition);
	  if(mysql_affected_rows()<=0){
	    echoError("ไม่สามารถยกเลิกปิดงาน UPDATE $strTable SET $strCommand WHERE $strCondition");
	    mysql_query('ROLLBACK');
	    die();
	  }

	if($modsn!==''){
	$strTable2 = "eqm_sn";
	$strCondition2 = "sn='".$modsn."'and oldowner='".$assigned_eng."' and responcible='9093' and circuit = '".$circuit."'";
	$strCommand2 = "oldowner = '0', responcible = '".$assigned_eng."' , circuit = ''";
	fncUpdateRecord($strTable2,$strCommand2,$strCondition2);
	  if(mysql_affected_rows()<=0){
	    echoError("ไม่สามารถยกเลิกปิดงาน UPDATE $strTable2 SET $strCommand2 WHERE $strCondition2");
	    mysql_query('ROLLBACK');
	    die();
	  }
	}

	if($catvsn!==''){
	$strTable3 = "eqm_sn";
	$strCondition3 = "sn='".$catvsn."'and oldowner='".$assigned_eng."' and responcible='9093' and circuit = '".$bundle."'";
	$strCommand3 = "oldowner = '0', responcible = '".$assigned_eng."' , circuit = ''";
	fncUpdateRecord($strTable3,$strCommand3,$strCondition3);
	  if(mysql_affected_rows()<=0){
	    echoError("ไม่สามารถยกเลิกปิดงาน UPDATE $strTable3 SET $strCommand3 WHERE $strCondition3");
	    mysql_query('ROLLBACK');
	    die();
	  }
	}

	$strTableinsert = "memo_appointment";
	$strField = "memo_date_time,jid,due_date,emp_id,result,memotxt,who_did";
	$strValue = tidnetNow().",'".$jid."','".$conf_date."','".$assigned_eng."',9,
				'ยกเลิกการปิดงาน สาเหตุ ".$note."','".$_COOKIE['uid']."'";
	if(fncInsertRecord($strTableinsert,$strField,$strValue)){ 
mysql_query('COMMIT');
?>
		<script>
		alert('ยกเลิกการปิดงานสำเร็จ');
		window.opener.location.href = 'jobassign.php';
		window.close();	
		</script>
<?php  }else{ 
		echo $strTableinsert."<br>".$strField."<br>".$strValue; 
		die(); 
	  }
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
$strTable = "jobassign";
$strCondition = "jid='".$jid."' and job_status = 'X'";
$job = fncSelectSingleRecord($strTable,$strCondition);	
?>
<p style="background-color:blue;color:#ffffff;font-weight:450;vertical-align: middle;text-align:center;height:20px;">ยกเลิกการปิดงาน</p>
 <form method="post">
<table>

	<tr>
		<td><span style="background-color:green;color:white;"><?php echo $job['circuit'] ?></span>
		<input type="hidden" name="circuit" value="<?php echo $job['circuit']; ?>">
			<span style="background-color:red;color:white"><?php echo $job['bundle'] ?></span>
		<input type="hidden" name="bundle" value="<?php echo $job['bundle']; ?>">
			<br>
			<?php echo $job['conf_date']; ?>
		<input type="hidden" name="conf_date" value="<?php echo $job['conf_date']; ?>">
			<br>
			<?php echo $job['cust_name']; ?> <br>
			ช่าง :<?php echo nameofengineerMast($job['assigned_eng'])." (".nameofengineerMast($job['assigned_eng'],1).")"; ?>
			<input type="hidden" name="assigned_eng" value="<?php echo $job['assigned_eng']; ?>">
		<td align="right">
			Modem : <?php echo $job['modsn']; ?>
			<input type="hidden" name="modsn" value="<?php echo $job['modsn']; ?>"> <br>
			CATV : <?php echo $job['catvsn']; ?>
			<input type="hidden" name="catvsn" value="<?php echo $job['catvsn']; ?>"><br>
			ระยะสาย : <?php echo $job['bcable']; ?> เมตร
			<input type="hidden" name="bcable" value="<?php echo $job['bcable']; ?>">
		</td>
	</tr>

</table>
<hr>
<span>สาเหตุ : </span><input type="text" name="note" required><br><br>
<input type="button" id="cancel" value="ยกเลิก">
<button type="submit" id="btn" name="save" >ยืนยัน</button>
</form>