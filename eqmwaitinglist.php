<?php
include('cookies.php');
include("functions/function.php");
include("headmenu.php");

if(!checkAllow('sit_createcomming')) die('คุณไม่มีสิทธิ์ใช้งานหน้านี้');

?>
<script>
$(document).ready(function(){
	$("select").change(function(){
		model = $("select#model option:selected").val();
		branch = $("select#branch option:selected").val();
		dateArrive = $("#dateArrive").val();
		total = $("#total").val()
		dateEmail = $("#dateEmail").val();
		//alert(model+" "+branch);
		if(model!='เลือกรุ่น' && branch!='non' && dateArrive!='' && total>0 && dateEmail!=''){
			$("#submitbut").prop( "disabled", false );
		}else{
			$("#submitbut").prop( "disabled", true );
		}
	});	
	$("input").blur(function(){
		model = $("select#model option:selected").val();
		branch = $("select#branch option:selected").val();
		dateArrive = $("#dateArrive").val();
		total = $("#total").val()
		dateEmail = $("#dateEmail").val();
		//alert(model+" "+branch+" "+dateArrive+" "+total+" "+dateEmail);
		if(model!='เลือกรุ่น' && branch!='non' && dateArrive!='' && total>0 && dateEmail!=''){
			$("#submitbut").prop( "disabled", false );
		}else{
			$("#submitbut").prop( "disabled", true );
		}
	});

	
	    $("#total").keydown(function (e) {
		// Allow: backspace, delete, tab, escape, enter and .
		if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
		     // Allow: Ctrl+A, Command+A
		    (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) || 
		     // Allow: home, end, left, right, down, up
		    (e.keyCode >= 35 && e.keyCode <= 40)) {
		         // let it happen, don't do anything
		         return;
		}
		// Ensure that it is a number and stop the keypress
		if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
		    e.preventDefault();
		}
	    });
});
</script>
<?php 

//var_dump($allBranch);
$star = "eqm_coming.id as lotid, date_arrive, status, date_mail, brand, model, total, got,note, eqm_coming.cancelled";
$strTable = "eqm_coming join eqm_model on eqm_coming.eqm_id=eqm_model.id";
$strCondition = " 1";
//echo "SELECT * FROM $strTable WHERE $strCondition";
$resultlst = "<table><tr class=\"header\"><td>Lot-ID</td><td>วันที่รอเข้า</td><td>วันที่รับแจ้ง</td><td>รุ่น</td><td>จำนวน</td><td>รับแล้ว</td><td>สถานะ</td></tr>";
$resultGot = "<table><tr class=\"header\"><td>Lot-ID</td><td>วันที่รอเข้า</td><td>วันที่รับแจ้ง</td><td>รุ่น</td><td>จำนวน</td><td>รับแล้ว</td><td>สถานะ</td></tr>";
$branchOption = "<select id=\"branch\" name=\"branch\"><option value=\"non\">เลือกสาขา</option>";
function eqmgotstatus($val){
	if($val==1) return "รอเข้า";
	if($val==0) return "รับครบแล้ว";
	if($val<0) return "รับบางส่วน";
	
}
mysql_query("BEGING");
foreach ($allBranch as $key => $value){		
	$branchOption .= "<option value=\"".$key."\">".$branchName[$key]." [".$value."]</option>";		
	mysql_select_db("tidnet_".$key);

	$starCome = "id,total";
	$strTableCome = "eqm_coming";
	$strConditionCome = "total<>got and status<>0";	
	$lstsCome = fncSelectStarConditionRecord($starCome,$strTableCome,$strConditionCome);
	
	while($come = mysql_fetch_array($lstsCome)){
		$gotTotal = fncCountRow("eqm_sn","lot_id='".$come['id']."'");
		if($gotTotal<>$come['got']){
			$newStatus = $come['total']-$gotTotal;
			$strComd = " got='".$gotTotal."', status='".$newStatus."'";
			$strConditionUp = "id='".$come['id']."'";
			//echo "UPDATE eqm_coming SET ".$strComd." WHERE ".$strConditionUp." <br> ";
			fncUpdateRecord("eqm_coming",$strComd,$strConditionUp);
		}
	}

	$lsts = fncSelectStarConditionRecord($star,$strTable,$strCondition);
	$y = false;
	$yy = false;
	while($lst = mysql_fetch_array($lsts)){
		//echo "<br>".$key." ".$y." <br>";
		
		$lotid = $lst['lotid'];
		for($l=strlen($lst['lotid']); $l<4; $l++) $lotid = "0".$lotid;

		$resultlstHead = '';
		$resultGotHead = '';
		if(!$y)	$resultlstHead = "<tr style=\"background-color:#fff\"><td colspan=\"9\">สาขา ".$branchName[$key]." [".$value."]</td></tr>";
		if(!$yy) $resultGotHead = "<tr style=\"background-color:#fff\"><td colspan=\"9\">สาขา ".$branchName[$key]." [".$value."]</td></tr>";


		//if($_COOKIE['permission']==1 and $_COOKIE['superuser']==1) {
		if($_COOKIE['permission']==1) {
			$pms = "<form  action=\"cancelordercoming.php?id=".$lotid."&key=".$key."\" method=\"post\" onSubmit=\"JavaScript:if(!confirm('คุณต้องการที่จะลบ order นี้ใช่หรือไม่?')){return false};\">       
				<textarea  name=\"note\" id=\"note\" type=\"text\"  rows=\"3\" required></textarea><br>
              <button type=\"submit\" name=\"submitbut\" >ยกเลิก</button></form>";
		}		 
		
		if($lst['status']>0 and $lst['cancelled']==0){
			$resultlst .= $resultlstHead."<tr><td class=\"right\">".strtoupper($key)."-".$lotid."</td><td>".$lst['date_arrive']."</td><td>".$lst['date_mail']."</td><td>".$lst['brand']." - ".$lst['model']."</td><td align=\"right\">".$lst['total']."</td><td align=\"right\">".$lst['got']."</td> 
				<td valign=\"top\" align=\"right\"> ".$pms."</td></tr>";
			$y=1;
			$resultlstHead = '';
		}else{
			if($lst['note'] <> ""){
				$note= "<font color=\"red\"><b>".$lst['note']."</b></font>";
			}else{
				$note=eqmgotstatus($lst['status']);
			}

			$resultGot .= $resultGotHead."<tr><td class=\"right\">".strtoupper($key)."-".$lotid."</td><td>".$lst['date_arrive']."</td><td>".$lst['date_mail']."</td><td>".$lst['brand']." - ".$lst['model']."</td><td align=\"right\">".$lst['total']."</td><td align=\"right\">".$lst['got']."</td><td>".$note."</td></tr>";
			$yy=1;
			$resultGotHead = '';
		}
		
	}	
}
mysql_query("ROLLBACK");

//echo "lll";
$resultlst .= "</table>";
$resultGot .= "</table>";
$branchOption .= "</select>";

$strTable = $db.".eqm_model";
$models = fncSelectRecord($strTable);


$modelOption = "<select id=\"model\" name=\"model\"><option value=\"non\">เลือกรุ่น</option>";

while($model = mysql_fetch_array($models)){
	$modelOption .= "<option value=\"".$model['id']."\">".$model['brand']." - ".$model['model']."</option>";
}
$modelOption .= "</select>";


?>
บันทึกการรออุปกรณ์
<input type="hidden" value="0" id="count">
<form action="savetotaleqmincoming.php" method="post">
<table>
	<tr class="header">
		<td >สาขา</td><td>ยี่ห้อ / รุ่น</td><td>จำนวนรับ</td><td>วันส่งของ</td><td>วันที่อีเมล์</td><td>Action</td>
	</tr>
	<tr>		
		<td><?php echo $branchOption;?></td><td><?php echo $modelOption;?></td><td><input type="text" size="10" id="total" name="total"></td><td><input type="text" id="dateArrive" name="dateArrive"></td><td><input type="text" id="dateEmail" name="dateEmail"></td><td><input id="submitbut" type="submit" value=" บันทึก "></td>
	</tr>
</table>
	 
</form>
<br>
รายการรอ SN เข้าสต๊อก
<?php echo $resultlst;?>
<br>
รายการ SN เข้าสต๊อกแล้ว
<?php echo $resultGot;?>



