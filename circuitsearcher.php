<?php
include('cookies.php');
include('db_function/phpMySQLFunctionDatabase.php');

$cir = $_POST['cir'];

$cirTable = "closedjob,tidnet_common.typeofjob,employee";
$cirCondition = "circuit='".$cir."' and closedjob.typejob=tidnet_common.typeofjob.id and (closedjob.emp_id=employee.id or closedjob.emp_id='0')";
$cirSort = " order by closeddate";
$cirQuery = fncSelectConditionRecord($cirTable,$cirCondition.$cirSort);
$round=1;
while($circuit = @mysql_fetch_array($cirQuery)){
	if(($circuit['emp_id']==0) and ($round>1)){
		continue;
	}
	?>
	<div>-:- ผลการค้นหา </div>
	<table>
		<tr>
			<td>หมายเลข Circuit</td><td><?php echo $cir?></td>
		</tr>
		<tr>
			<td>ประเภทของงาน</td><td><?php echo $circuit['tname']?></td>
		</tr>
		<tr>
			<td>สถานะ</td>
			<td>
				<?php
					if($circuit['series']==''){
						echo "ช่างยังไม่ปิดตั้งเบิก";
					}else{
						echo "ปิดงานตั้งเบิกเรียบร้อย"
				?>
						<tr>
							<td>หมายเลข SN </td><td><?php echo $circuit['series']?></td>
						</tr>
				<?php
				}
				?>
			</td>
		</tr>
		<tr>
			<td>เลขใบตั้งเบิก</td><td><?php if($circuit['payhireheader_id']<>0) echo "PH-ID:".$circuit['payhireheader_id'];?></td>
		</tr>
		<tr>
			<td>วันที่ปิดงาน IVR</td><td><?php echo $circuit['closeddate']?></td>
		</tr>
		<tr>
			<td>ลูกค้า</td><td><?php echo $circuit['cust_name']?></td>
		</tr>
		<tr>
			<td>ช่าง</td><td><?php if($circuit['emp_id']<>0) echo $circuit['name']?></td>
		</tr>
	</table>
<?php
	$round +=1;
}
?>
