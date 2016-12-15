<?php
include('cookies.php');
include('db_function/phpMySQLFunctionDatabase.php');
include("functions/function.php");

$key = $_POST['key'];

$strTable = "tidnet_common.requestor";
$strCondition = "name like '%".$key."%'";

$reqs = fncSelectConditionRecord($strTable,$strCondition);
while($req = mysql_fetch_array($reqs)){
	$reqJ[$req['req_id']] = array('name'=>$req['name'],'phone'=>$req['phone'],'type'=>$req['type']);
}

if(count($reqJ)<=0){
	echo "เพิ่มผู้ติดต่อ #new";?>
	<div align="right">
		<p>ชื่อผู้ติดต่อ <input id="newrequestor" name="newrequestor" size="20" type="text" value="<?php echo $key?>"></p>
		<p>เบอร์ติดต่อ <input id="newphone" name="newphone" size="20" type="text"></p>
		<p>ประเภท 
			<select id="type_req">
				<optgroup label="TUC">
					<option value="11">เซลล์</option>
					<option value="12">Dispatch</option>
					<option value="13">USAN</option>
				</optgroup>
				<option value="21">ลูกค้า</option>
				<option value="31">ทีมติดตั้ง</option>
				<option value="41">ผรม.รายอื่น</option>
				<option value="52">admin</option>
				<option value="91">อื่นๆ</option>
			</select>
		</p>
		<p><span class="button" id="newsave"> บันทึกผู้ติดต่อใหม่ </span></p>
	</div>
<?php
	//echo json_encode($arr);
}else{
	echo json_encode($reqJ);
}

?>

