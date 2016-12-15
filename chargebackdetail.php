<?php
include('cookies.php');
include("functions/function.php");
include("headmenu.php");

function labelFunction($txt){
	return " <span class=\"label\"> ".$txt.":</span> ";
}
function space(){
	echo "<span style=\"padding-left:10px;padding-top:5px;padding-bottom:5px;\">";
}

$strReplyTable = "chargeback_reply";
if($_POST['sbm']<>''){

	$hid = $_POST['headeridforreply'];
	$description = $_POST['description'];
	
	$strReplyField = "header_id,who_reply,description";
	$strReplyValue = "'".$hid."','".$_COOKIE['uid']."','".$description."'";
	fncInsertRecord($strReplyTable,$strReplyField,$strReplyValue);
}


$translateComplain = array('','overdue','ร้องเรียน','defect','เข้างานสาย');
$id = $_GET['id'];
if($id<=0){
?>
<script>
window.location = "chargeback.php";
</script>
<?php
} 
$strTable = "chargebackjob_header";
$strCondition = "hid='".$id."'";
$post = fncSelectSingleRecord($strTable,$strCondition);

$strJobTable = "jobassign";
$strJobCondition = "circuit='".$post['circuit']."'";
$job = fncSelectSingleRecord($strJobTable,$strJobCondition);
echo "<div style=\"background-color:#fff;padding:5px;width:1024px;margin-bottom:20px;\">";
echo "<span style=\"font-size:16px;font-weight:900;\">-: รายละเอียดปัญหา :-</span>";
echo "<hr style=\"margin-bottom: 10px;\"/>";
echo "<span style=\"padding-top:5px;padding-bottom:5px;\">".labelfunction('หมายเลขลำดับ')." CB-ID:".$post['hid']." ".labelfunction('ประเภทร้องเรียน')." [".$post['complaintype']."]".$translateComplain[$post['complaintype']]."</span>";
echo "<br>";
echo space()." ".labelfunction('Circuit')." ".$post['circuit']." ".labelfunction('ลูกค้าชื่อ').$job['cust_name']."</span>";
echo "<br>";
echo space()." ".labelfunction('รายละเอียด')." :----<br>";
echo space()." <span style=\"font-weight:900;\">".$post['description']."</span>";
echo "</div>";

$strReplyCondition = "header_id='".$id."'";
$replies = fncSelectConditionRecord($strReplyTable,$strReplyCondition);
while($reply = mysql_fetch_array($replies)){
	echo "<div style=\"background-color:#fff;padding:5px;width:996px;margin-bottom:20px;margin-left:30px;margin-top:0px;\">".labelfunction('ตอบเมื่อ')." ".convdate($reply['date_reply'])." ".labelfunction('โดย')." ".nameofengineer($reply['who_reply'])." [".nameofengineer($reply['who_reply'],1)."] <br>".space()." ".$reply['description']."</div>";
}


if($_COOKIE['uid']==$post['chgbaked_who'] or checkAllow('sit_replychargeback')){
?>
	<form action="chargebackdetail.php?id=<?php echo $id?>" method="post">
		<div style="background-color:#fff;padding:5px;width:1024px;spacing-bottom:20px;">
			<input type="hidden" value="<?php echo $id?>" name="headeridforreply">
			<span style="font-size:16px;font-weight:900;">-: ชี้แจงสาเหตุของปัญหา :-</span>
			<hr style="margin-bottom: 10px;"/>
			<textarea style="width:900px;height:150px;" name="description"></textarea>
			<br>
			<input type="submit" name="sbm" value="บันทึก" style="text-aligment:right;"/>
		</div>
	</form>
<?php
}
?>
