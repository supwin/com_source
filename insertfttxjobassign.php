<?php
include('cookies.php');
include('functions/function.php');
include('headmenu.php');
?>

<script type="text/javascript">
$(document).ready(function(){
	$("button.chgstatus").click(function(){
			 cir = $(this).val()
			 so_no = $(this).attr("so_no")
			 typejob = $(this).attr("typejob")
			 id = $(this).attr("for")
			 status = $(this).attr("jstatus")
			 dbstatus = $(this).attr("dbstatus")
			 result = $(this).attr("result")
			 hid = $(this).attr("hid")
			 hname = $(this).attr("hname")
			 due = $(this).attr("due")
			 window.open("updatestatusjobassign.php?id="+id+"&cir="+cir+"&so_no="+so_no+"&typejob="+typejob+"&status="+status+"&dbstatus="+dbstatus+"&result="+result+"&hid="+hid+"&hname="+hname+"&due="+due,"List","scrollbars=no, resizable=no, width=500, height=400");
			 //$("span#sp_"+id).remove();
	});

	function removeRow(row){
		$("span#sp_"+id).remove();
	}

});

</script>

<?php
if(!checkAllow('sit_importjobassign')){
	die('คุณไม่มีสิทธิ์ใช้งานในส่วนนี้ได้ค่ะ..');
}
move_uploaded_file($_FILES["fileCSV"]["tmp_name"],"csveqm/".$_FILES["fileCSV"]["name"]); // Copy/Upload CSV

$strTable = "tidnet_common.techcode";
$strCondition = "1";
$empHdl = fncSelectConditionRecord($strTable,$strCondition);

while($hdl = mysql_fetch_array($empHdl)){
	$hdlArray[$hdl['name']] = $hdl['code'];
}
$strTable = "jobassign";
$objCSV = fopen("csveqm/".$_FILES["fileCSV"]["name"], "r");
mysql_query(BEGIN);

$checkColumn = '1';
$due_date = '0'; //'2016-08-04';
$serviceAccessNo = '1'; //'9604292357,077902139,117718864';
$cust_name = '2'; //'จรินทร์ นาพุทธ';
$cust_phone = '3'; //'0824142537';
$cust_addr = '4'; //'165/31, -, -, -, -, -, -, ท่าทองใหม่, กาญจนดิษฐ์, สุราษฎร์ธานี, 84160';
$handler = '5';
$so_no = '6'; // 'W201607291004102297';
$sodoctype = '7'; //'FIBERTV';
$type = '8'; //'Change';
$ampm = '9'; //'13:00-18:00';
$first_due= '10'; //'2016-07-29 10:03:54';
$team = '11';
$job_status = '12'; //'Scheduled';
$wocreatetime = '13';
$nameColumn_AppointmentD = "Appointment Date";
$nameColumn_Service = "Service Access No.";
$nameColumn_CustomerN = "Customer Name";
$nameColumn_CustomerC = "Customer Contact Phone";
$nameColumn_Address = "Address";
$nameColumn_Handler = "Handler";
$nameColumn_AccessN = "Access Number";
$nameColumn_ProductN = "Product Name";
$nameColumn_Install = "Install Flag";
$nameColumn_AppointmentT = "Appointment Timeslot";
$nameColumn_Submitted = "Submitted Date";
$nameColumn_Team = "Team";
$nameColumn_Operation= "Operation Status";

echo "สาขา : $depot <BR>";

$chgTxt = '';
$insTxt = '';
$nchgTxt = '';
$chgedTxt = '';

while (($objArr = fgetcsv($objCSV, 1000, ",")) !== FALSE) {
		$rowObj ++;
		if($rowObj==1){

			if(($objArr[$checkColumn]=="Service Access No.") and ($objArr[$due_date]==$nameColumn_AppointmentD) and
			($objArr[$cust_name]==$nameColumn_CustomerN) and ($objArr[$cust_phone]==$nameColumn_CustomerC) and
			($objArr[$cust_addr]==$nameColumn_Address) and ($objArr[$handler]==$nameColumn_Handler) and
			($objArr[$so_no]==$nameColumn_AccessN) and ($objArr[$sodoctype]==$nameColumn_ProductN) and
			($objArr[$type]==$nameColumn_Install) and ($objArr[$ampm]==$nameColumn_AppointmentT) and
			($objArr[$first_due]==$nameColumn_Submitted) and ($objArr[$team]==$nameColumn_Team) and
			($objArr[$job_status]==$nameColumn_Operation)){
			echo "ตรวจสอบไฟล์ถูกต้อง <br>";
			continue;
			} else {
			?>
			<script>
			openAlert('ไฟล์ไม่ถูกต้องกรุณาตรวจสอบ!!!');
			//window.location='jobassign.php';
			</script>
			<?php
			die('ไฟล์ไม่ถูกต้องกรุณาตรวจสอบ!!!');
		    }
		}

		$dpt = explode("_",$objArr[$team]);  // ตัด _Team1 ออกจาก depot code ที่ Q-run ให้มา
		$checkdepot = $dpt[0];

		if($checkdepot != $depot){
			?>
			<script>
			openAlert('File ที่ uploade มาน่าจะผิดสาขานะ <?php echo $depot;?> <?php echo "สาขาจาก csv". $checkdepot." circuit/fixedlineno/bundle ".$objArr[$serviceAccessNo]." kk ".$objArr[$so_no]?>');
			</script>
			<?php
			die('File ที่ uploade มาน่าจะผิดสาขานะ');
		}
			//AMPM
			switch ($objArr[$ampm]) {
				case '09:00-12:00':
					$a = 'AM';
					break;
				case '13:00-18:00':
					$a = 'PM';
					break;
			}
			//Install Flag
			switch ($objArr[$type]) {
				case 'Change':
					$t = 'C';
					break;
				case 'Installation':
					$t = 'I';
					break;
				case 'Termination':
					$t = 'D';
					break;
			}

			//OperationStatus
			switch ($objArr[$job_status]) {
				case 'Scheduled':
					$jstatus = 'D';
					break;
				case 'Rejected':
					$jstatus = 'R';
					break;
				case 'Closed':
					$jstatus = 'C';
					break;
				case 'Completed':
					$jstatus = 'X';
					break;
				case 'Accepted':
					$jstatus = 'D';
					break;
				case 'Handling':
					$jstatus = 'D';
					break;
			}

			//ตัดช่องว่างออก วันที่และเวลาออกจากกัน เอาแต่วันที่
			$ccExplode = explode(" ",$objArr[$first_due]);
			$objArr[$first_due] = $ccExplode[0];

			switch (strlen($objArr[$serviceAccessNo])) {
				case '30':
					$ccExplode		= explode(",",$objArr[$serviceAccessNo]);
					$circuit 		= $ccExplode[0];
					$fixedlineno 	= $ccExplode[1];
					$bundle			= $ccExplode[2];
					break;
				case '20':
					$ccExplode		= explode(",",$objArr[$serviceAccessNo]);
					$circuit 		= $ccExplode[0];
					$fixedlineno 	= "";
					$bundle			= $ccExplode[1];
					break;
				case '10':
					$circuit 		= $objArr[$serviceAccessNo];
					$fixedlineno 	= "";
					$bundle			= "";
					break;
				case '9':
					$circuit 		= $objArr[$serviceAccessNo];
					$fixedlineno 	= "";
					$bundle			= "";
					break;
			}

				//เชคว่าข้อมูลในระบบหรือยัง ถ้ามีเข้า loop if
				$strTable = "jobassign";
				$strCondition = "so_no='".$objArr[$so_no]."' and cust_name='".$objArr[$cust_name]."'";


			if(fncCountRow($strTable,$strCondition)>0){
					$lst = fncSelectSingleRecord($strTable,$strCondition);
					//หากสถานะ db เป็น D
					if ($lst['job_status'] == 'D' and $jstatus == 'R'){
						$rsNo = "986";
						$chgTxt .= "<tr><td>".$rowObj."</td><td>".$objArr[$so_no]."</td><td>".$lst['circuit']."</td><td>".$lst['cust_name']."</td><td> ใน ".$abvt." เป็น ".$lst['job_status']." ต้องการเปลี่ยนเป็น ".$jstatus." ไหม?</td>";
						$chgTxt .= "<td><button class=\"chgstatus\" for=\"".$rowObj."\" so_no=\"".$objArr[$so_no]."\" dbstatus=\"".$lst['job_status']."\" jstatus=\"".$jstatus."\" value=\"".$circuit."\" typejob=\"FTTX\" result=\"".$rsNo."\" >ยืนยัน</button></td> ";
					} else if($lst['job_status'] == 'R' and $jstatus == 'D'){
						$rsNo = "989";
						$chgTxt .= "<tr><td>".$rowObj."</td><td>".$objArr[$so_no]."</td><td>".$lst['circuit']."</td><td>".$lst['cust_name']."</td><td> ใน ".$abvt." เป็น ".$lst['job_status']." ต้องการเปลี่ยนเป็น ".$jstatus." ไหม?</td>";
						$chgTxt .= "<td><button class=\"chgstatus\" for=\"".$rowObj."\" so_no=\"".$objArr[$so_no]."\" dbstatus=\"".$lst['job_status']."\" jstatus=\"".$jstatus."\" value=\"".$circuit."\" typejob=\"FTTX\" result=\"".$rsNo."\" due=\"".$objArr[$due_date]."\" hid=\"".$hdlArray[$objArr[$handler]]."\" hname=\"".$objArr[$handler]."\">ยืนยัน</button></td> ";
					} else if(($lst['job_status']=='D' AND $jstatus == 'C') OR ($lst['job_status']=='R' AND $jstatus == 'C')){
							if($lst['job_status']=='R'){
								$rsNo = "988";
							} else if($lst['job_status']=='D') {
								$rsNo = "987";
							}

			  			$strCondition = "so_no='".$objArr[$so_no]."' and circuit='".$circuit."' and cust_name='".$objArr[$cust_name]."'";
						$strCommand = " job_status='".$jstatus."'";
						$update = "UPDATE $strTable SET  $strCommand WHERE $strCondition <br>";

						$lstC = fncSelectSingleRecord($strTable,$strCondition);
						$strTableinsert = "memo_appointment";
						$strField = "memo_date_time,jid,
						  			 due_date,
						  			 emp_id,
						  			 result,
						  			 memotxt,
						  			 who_did";
						$strValue = tidnetNow().",'".$lstC['jid']."',
									 '".$lstC['due_date']."',
									 '".$lstC['assigned_eng']."',
									 '".$rsNo."',
									 'ยกเลิกออเดอร์จากทรูจากการ Import Jobassign',
							 		 '".$_COOKIE['uid']."'";
						if(!fncInsertRecord($strTableinsert,$strField,$strValue)){
							mysql_query(ROLLBACK);
							die('ยกเลิกกระบวนการ มีปัญหาเกี่ยวกับการ insert table : memo_appointment');
						}
						if(!fncUpdateRecord($strTable,$strCommand,$strCondition)){
							mysql_query(ROLLBACK);
							die('ยกเลิกกระบวนการ มีปัญหาเกี่ยวกับการ update table : jobassign');
						}
						$chgedTxt .= "<tr><td>".$rowObj."</td><td>".$objArr[$so_no]."</td><td>".$lst['circuit']."</td><td>".$lst['cust_name']."</td><td colspan=\"2\"> ใน ".$abvt." เป็น ".$lst['job_status']." เปลี่ยนเป็น ".$jstatus." แล้ว</td>";
					}else {
						$nchgTxt .= "<tr style=\"color:#778899;\"><td>".$rowObj."</td><td>".$objArr[$so_no]."</td><td>".$lst['circuit']."</td><td>".$lst['cust_name']."</td><td colspan=\"2\"> สถานะของงานคือ  ".$lst['job_status']."</td>";
					}
					}else {

					$strField = "jobname,so_no,
									circuit,
									sodoctype,
									fixedlineno,
									job_status,
									first_due,
									due_date,
									conf_date,
									ampm,
									SO_CCSS_ORDER_TYPE,
									cust_name,
									cust_addr,
									cust_phone,
									bundle,
									handler_id,
									handler_name,
									wo_create_time,
									create_time
									";
					//echo "$strField";
					if($jstatus=='X')  $jstatus='D';  // กรณีเอางาน X เข้าให้แก้ status เป็น  D  เพื่อให้ช่างปิดงานใน tidnet
					$strValue = "'FTTX','".trim($objArr[$so_no])."',
								'".trim($circuit)."',
								'".trim($objArr[$sodoctype])."',
								'".trim($fixedlineno)."',
								'".trim($jstatus)."',
								'".trim($objArr[$first_due])."',
								'".trim($objArr[$due_date])."',
								'".trim($objArr[$due_date])."',
								'".$a."',
								'".$t."',
								'".trim($objArr[$cust_name])."',
								'".trim($objArr[$cust_addr])."',
								'".trim($objArr[$cust_phone])."',
								'".trim($bundle)."',
								'".$hdlArray[$objArr[$handler]]."',
								'".$objArr[$handler]."',
								'".$objArr[$wocreatetime]."',
								now()
								";
								//echo "$strValue";

		$insTxt .= "<tr style=\"color:green;\"><td colspan=\"6\">".$rowObj." : INSERT INTO ".$strTable." (".$strField.") VALUES (".$strValue.") <B>job status = ".$jstatus."</td>";
		if(!fncInsertRecord($strTable,$strField,$strValue)){
			mysql_query(ROLLBACK);
			die('ยกเลิกกระบวนการ');
		}
	}
}

mysql_query(COMMIT);
echo "<BR>บันทึกงานทั้งหมดเรียบร้อย.";
 ?>
<table >
	<tr>
			<th>Row</th>
			<th>Sono</th>
			<th>Cicuit</th>
			<th>ชื่อลูกค้า</th>
			<th>Note</th>
			<th>Button</th>
	</tr>
	<?php echo $chgTxt;?>
	<?php echo $chgedTxt;?>
	<?php echo $nchgTxt;?>
	<?php echo $insTxt;?>
</table>
