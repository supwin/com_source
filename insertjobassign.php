<?php
/*
log file 
160315 1127 : just created
*/
include('cookies.php');
include('functions/function.php');
include('headmenu.php');
 ?>

<script type="text/javascript">
$(document).ready(function(){
	$("button.chgstatus").click(function(){
			 cir = $(this).val()
			 so_no = $(this).attr("so_no")
			 wact = $(this).attr("wact")
			 id = $(this).attr("for")
			 status = $(this).attr("jstatus")
			 dbstatus = $(this).attr("dbstatus")
			 result = $(this).attr("result")
			 due = $(this).attr("due")
			 typejob = $(this).attr("typejob")
			 window.open("updatestatusjobassign.php?id="+id+"&cir="+cir+"&so_no="+so_no+"&wact="+wact+"&status="+status+"&dbstatus="+dbstatus+"&result="+result+"&due="+due+"&typejob="+typejob,"List","scrollbars=no, resizable=no, width=500, height=400");
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


$strTable = "jobassign";
$objCSV = fopen("csveqm/".$_FILES["fileCSV"]["name"], "r");
mysql_query(BEGIN);

$checkColumn = '4';

// true Online d
$depotTXT = '4';
$wact = '5';
$so_no = '6'; 
$circuit = '7'; 
$cc99 = '8';
$jobstatus = '9';
$duedate = '10';
$ampm = '11';
$sotype = '12';
$soflg = '13';
$firstdue = '16'; 
$saleId = '21';
$saleDiv = '22';
$campaignName = '23';
$sodoctype = '24';
$oldcampaingName = '25';
$disType = '30';
$fixNo = '31';
$catvflg = '33';
$cust_name = '35';
$ad1 = '36'; // เริ่มต้น
$ad2 = '45'; //สุดท้าย
$phone1 = '47';
$phone2 = '48';
$homepass = '49';
$spPTN = '50';
$rcu = '51';
$tap = '52';
$networkOwner = '53';
$commentDoc = '54';
$dwellingType = '55';
$amNode = '56';

$nameColumn_E = "SI IBS DEPOT KEY";
$nameColumn_F = "SO WORK ACTN CD";
$nameColumn_G = "SO ORDERID";
$nameColumn_H = "SI SRV NUM";
$nameColumn_I = "SI RELATED ASSET NUM";
$nameColumn_J = "SO ORDER STATUS";
$nameColumn_K = "SO DUE DATE";
$nameColumn_L = "SO DUE TIME";
$nameColumn_M = "SO CCSS ORDER TYPE";
$nameColumn_N = "SO CHG ADDR FLG";
$nameColumn_Q = "SO FIRST DUE DATE";
$nameColumn_V = "SO SALE ID";
$nameColumn_W = "SO SALE DIVISION";
$nameColumn_X = "SO CAMPAIGN NAME";
$nameColumn_Y = "SO DOCSIS TYPE";
$nameColumn_Z = "SO OLD CAMPAIGN NAME";
$nameColumn_AE = "SO DISCONNECT TYPE";
$nameColumn_AF = "SO TUC VOICE ASSET NUM";
$nameColumn_AH = "SO INST CATV FLG";
$nameColumn_AJ = "CUSTOMER NAME";
$nameColumn_AK = "SI HOUSE NUM";
$nameColumn_AT = "SI PROVINCE";
$nameColumn_AV = "SI CONTACT MOBILE";
$nameColumn_AW = "SI CONTACT PCT";
$nameColumn_AX = "SI HOMEPASS ID";
$nameColumn_AY = "SP PTN";
$nameColumn_AZ = "SP RCU";
$nameColumn_BA = "SP DPSDF CD";

echo "สาขา : $depot <BR>";
$chgTxt = '';
$insTxt = '';
$nchgTxt = '';
$chgedTxt = '';

while (($objArr = fgetcsv($objCSV, 1000, ",")) !== FALSE) {
		$rowObj++;
		if($rowObj==1){
			if( ($objArr[$checkColumn]==$nameColumn_E) and ($objArr[$wact]==$nameColumn_F) and
			($objArr[$so_no]==$nameColumn_G) and ($objArr[$circuit]==$nameColumn_H) and
			($objArr[$cc99]==$nameColumn_I) and ($objArr[$jobstatus]==$nameColumn_J) and
			($objArr[$duedate]==$nameColumn_K) and ($objArr[$ampm]==$nameColumn_L) and
			($objArr[$sotype]==$nameColumn_M) and ($objArr[$soflg]==$nameColumn_N) and
			($objArr[$firstdue]==$nameColumn_Q) and ($objArr[$saleId]==$nameColumn_V) and
			($objArr[$saleDiv]==$nameColumn_W) and ($objArr[$campaignName]==$nameColumn_X) and
			($objArr[$sodoctype]==$nameColumn_Y) and ($objArr[$oldcampaingName]==$nameColumn_Z) and
			($objArr[$disType]==$nameColumn_AE) and ($objArr[$fixNo]==$nameColumn_AF) and
			($objArr[$catvflg]==$nameColumn_AH) and ($objArr[$cust_name]==$nameColumn_AJ) and
			($objArr[$ad1]==$nameColumn_AK) and ($objArr[$ad2]==$nameColumn_AT) and
			($objArr[$phone1]==$nameColumn_AV) and ($objArr[$phone2]==$nameColumn_AW) and
			($objArr[$homepass]==$nameColumn_AX) and ($objArr[$spPTN]==$nameColumn_AY) and
			($objArr[$rcu]==$nameColumn_AZ) and ($objArr[$tap]==$nameColumn_BA) ){
			echo "ตรวจสอบไฟล์ถูกต้อง <br>";
			continue;
			} else {
			?>
			<script>
			openAlert('ไฟล์ไม่ถูกต้องกรุณาตรวจสอบ!!!');
			//window.location='jobassign.php';
			</script>
			<?php
			
			echo $objArr[$checkColumn]."<br>".$objArr[$wact].$nameColumn_F."<br>".$objArr[$so_no].$nameColumn_G."<br>".
			$objArr[$circuit].$nameColumn_H."<br>".$objArr[$cc99].$nameColumn_I."<br>".$objArr[$jobstatus].$nameColumn_J."<br>".
			$objArr[$duedate].$nameColumn_K."<br>".$objArr[$ampm].$nameColumn_L."<br>".$objArr[$sotype].$nameColumn_M."<br>".
			$objArr[$soflg].$nameColumn_N."<br>".$objArr[$firstdue].$nameColumn_Q."<br>".$objArr[$saleId].$nameColumn_V."<br>".
			$objArr[$saleDiv].$nameColumn_W."<br>".$objArr[$campaignName].$nameColumn_X."<br>".$objArr[$sodoctype].$nameColumn_Y."<br>".
			$objArr[$oldcampaingName].$nameColumn_Z."<br>".$objArr[$disType].$nameColumn_AE."<br>".$objArr[$fixNo].$nameColumn_AF."<br>".
			$objArr[$catvflg].$nameColumn_AH."<br>".$objArr[$cust_name].$nameColumn_AJ."<br>".$objArr[$ad1].$nameColumn_AK."<br>".
			$objArr[$ad2].$nameColumn_AT."<br>".$objArr[$phone1].$nameColumn_AV."<br>".$objArr[$phone2].$nameColumn_AW."<br>".
			$objArr[$homepass].$nameColumn_AX."<br>".$objArr[$spPTN].$nameColumn_AY."<br>".$objArr[$rcu].$nameColumn_AZ."<br>".
			$objArr[$tap].$nameColumn_BA."<br>";
			
			die('ไฟล์ไม่ถูกต้องกรุณาตรวจสอบ!!!');
		    }
		}			
		
		if($objArr[$depotTXT]!=$depot){
			?>
			<script>
			openAlert('File ที่ uploade มาน่าจะผิดสาขานะ <?php echo $depotTXT;?> <?php echo "aaa ". $objArr[$depotTXT]." xxx ".$objArr[$wact]." kk ".$objArr[$so_no]?>');
			</script>
			<?php
			die('File ที่ uploade มาน่าจะผิดสาขานะ');
		}
		
		$strCondition = "so_no='".$objArr[$so_no]."' and work_action='".$objArr[$wact]."' and circuit = '".$objArr[$circuit]."'";
		/*
		if(fncCountRow($strTable,$strCondition)>=1){  // ให้ update status แล้วไป row ถัดไปเลย
			$strCommand = "job_status='".$objArr[$jobstatus]."'";
			if(fncUpdateRecord($strTable,$strCommand,$strCondition)){
				echo "UPDATE $strTable SET  $strCommand WHERE $strCondition <br>";
				echo "Update ".$objArr[$so_no].", Work Action ".$objArr[$wact]." เป็น ".$objArr[$jobstatus]." เรียบร้อย<br><br>";
			}
			continue;  // ไม่ใช่ D ให้ข้ามไปเลย
		}*/
		
		$duedateTo = convDDMMYYtoYYYYMMDD($objArr[$duedate]);
		if(fncCountRow($strTable,$strCondition)>0){		
			$lst = fncSelectSingleRecord($strTable,$strCondition);
			//หากสถานะใน db เป็น C ให้update ปกติ
			if ($lst['job_status'] == 'D' and $objArr[$jobstatus] == 'R'){
						$rsNo = "986";
						$chgTxt .= "<tr><td>".$rowObj."</td><td>".$objArr[$so_no]."</td><td>".$lst['circuit']."</td><td>".$lst['cust_name']."</td><td> ใน ".$abvt." เป็น ".$lst['job_status']." ต้องการเปลี่ยนเป็น ".$objArr[$jobstatus]." ไหม?</td>";
						$chgTxt .= "<td><button class=\"chgstatus\" for=\"".$rowObj."\" so_no=\"".$objArr[$so_no]."\" dbstatus=\"".$lst['job_status']."\" jstatus=\"".$objArr[$jobstatus]."\" wact=\"".$objArr[$wact]."\" value=\"".$objArr[$circuit]."\" result=\"".$rsNo."\" typejob=\"DOCSIS\">ยืนยัน</button></td> ";
			} else if($lst['job_status'] == 'R' and $objArr[$jobstatus] == 'D'){
						$rsNo = "989";
						$chgTxt .= "<tr><td>".$rowObj."</td><td>".$objArr[$so_no]."</td><td>".$lst['circuit']."</td><td>".$lst['cust_name']."</td><td> ใน ".$abvt." เป็น ".$lst['job_status']." ต้องการเปลี่ยนเป็น ".$objArr[$jobstatus]." ไหม?</td>";
						$chgTxt .= "<td><button class=\"chgstatus\" for=\"".$rowObj."\" so_no=\"".$objArr[$so_no]."\" dbstatus=\"".$lst['job_status']."\" jstatus=\"".$objArr[$jobstatus]."\" wact=\"".$objArr[$wact]."\" value=\"".$objArr[$circuit]."\" due=\"".$duedateTo."\" result=\"".$rsNo."\" typejob=\"DOCSIS\" >ยืนยัน</button></td> ";

			} else if(($lst['job_status']=='D' AND $objArr[$jobstatus] == 'C') OR ($lst['job_status']=='R' AND $objArr[$jobstatus] == 'C')){					
						if($lst['job_status']=='R'){
							$rsNo = "988";
						} else if($lst['job_status']=='D') {
							$rsNo = "987";
						}
						$strCommand = " job_status='".$objArr[$jobstatus]."'";
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
						$chgedTxt .= "<tr><td>".$rowObj."</td><td>".$objArr[$so_no]."</td><td>".$lst['circuit']."</td><td>".$lst['cust_name']."</td><td colspan=\"2\"> ใน ".$abvt." เป็น ".$lst['job_status']." เปลี่ยนเป็น ".$objArr[$jobstatus]." แล้ว</td>";						
				} else { 
						$nchgTxt .= "<tr style=\"color:#778899;\"><td>".$rowObj."</td><td>".$objArr[$so_no]."</td><td>".$lst['circuit']."</td><td>".$lst['cust_name']."</td><td colspan=\"2\"> สถานะของงานคือ  ".$lst['job_status']."</td>";	

				}
		} else { //ไม่มีข้อมูลในระบบให้ insert ปกติ
			
		$cust_addr = $objArr[36]." ".$objArr[37]." ".$objArr[38]." ".$objArr[39]." ".$objArr[40]." ".$objArr[41]." ".$objArr[42]." ".$objArr[43]." ".$objArr[44]." ".$objArr[45];

		$firstdueTo = convDDMMYYtoYYYYMMDD($objArr[$firstdue]);
				
		$phone = $objArr[$phone1].", ".$objArr[$phone2];
		$strField = "jobname,so_no,
				work_action,
				home_pass_id,
				circuit,cc99,
				campname,
				sodoctype,
				oldcampname,
				distype,
				fixedlineno,
				dwellingtype,
				networkowner,
				commentdoc,
				spptn,
				saleid,
				salediv,
				amNode,
				job_status,
				first_due,
				due_date,
				conf_date,
				ampm,
				SO_CCSS_ORDER_TYPE,
				SO_CHG_ADDR_FLG,
				CATV_FLG,
				cust_name,
				cust_addr,
				cust_phone,
				rcu_node,
				tap,
				create_time";

		if($objArr[$jobstatus]=='X')  { $objArr[$jobstatus]='D';  } // กรณีเอางาน X เข้าให้แก้ status เป็น  D  เพื่อให้ช่างปิดงานใน tidnet		
		if($objArr[$jobstatus]=='I')  { $objArr[$jobstatus]='D';  } // กรณีเอางาน I(รอระบบเป็น X) เข้าให้แก้ status เป็น  D  เพื่อให้ช่างปิดงานใน tidnet	
		
		$strValue = "'DOCSIS','".trim($objArr[$so_no])."',
				'".trim($objArr[$wact])."',
				'".trim($objArr[$homepass])."',
				'".trim($objArr[$circuit])."',
				'".trim($objArr[$cc99])."',
				'".trim($objArr[$campaignName])."',
				'".trim($objArr[$sodoctype])."',
				'".trim($objArr[$oldcampaingName])."',
				'".trim($objArr[$disType])."',
				'".trim($objArr[$fixNo])."',
				'".trim($objArr[$dwellingType])."',
				'".trim($objArr[$networkOwner])."',
				'".trim($objArr[$amNode])."',
				'".trim($objArr[$spPTN])."',
				'".trim($objArr[$saleId])."',
				'".trim($objArr[$saleDiv])."',
				'".trim($objArr[$commentDoc])."',
				'".trim($objArr[$jobstatus])."',
				'".$firstdueTo."',
				'".$duedateTo."',
				'".$duedateTo."',
				'".trim($objArr[$ampm])."',
				'".trim($objArr[$sotype])."',
				'".trim($objArr[$soflg])."',
				'".trim($objArr[$catvflg])."',
				'".$objArr[$cust_name]."',
				'".preg_replace('#[^ก-๙a-zA-Z0-9\-\/\.\ ]#u','', $cust_addr)."',
				'".$phone."',
				'".$objArr[$rcu]."',
				'".$objArr[$tap]."',
				NOW()";


		$insTxt .= "<tr style=\"color:green;\"><td colspan=\"6\">".$rowObj." : INSERT INTO ".$strTable." (".$strField.") VALUES (".$strValue.") <B>job status = ".$objArr[$jobstatus]."</td>";
		if(!fncInsertRecord($strTable,$strField,$strValue)){
			mysql_query(ROLLBACK);
			die('ยกเลิกกระบวนการ');
		} 
	}

		$name = $objArr[35];
				//แยกคำนำหน้า
				$FLname = explode(" ", $name);
				$fname = $FLname[2];
				$lname = $FLname[4];

		$condition_checkbundle = "cust_name LIKE'%".$fname."%'AND cust_name LIKE'%".$lname."%' AND jobname = 'CATV'
								  AND due_date BETWEEN date_add( '".$duedateTo."',interval -3 day) 
								  AND date_add('".$duedateTo."',interval 3 day)";
		$sql2 = "SELECT * FROM jobassign WHERE $condition_checkbundle";
		$checkbundle = mysql_query($sql2);
			if($checkbundle!=''){
				$row_Jid = mysql_fetch_array($checkbundle);
				$Jid = $row_Jid[0];
				$bundleTV = $row_Jid[5];
				$bundleDOCSIS = $objArr[$circuit];
				$sql = "UPDATE jobassign SET bundle = $bundleDOCSIS WHERE jid = $Jid";
				$complete_bundle = mysql_query($sql);

				$condition_checkbundle2 = "circuit = $bundleDOCSIS";
				$sql3 = "SELECT * FROM jobassign WHERE $condition_checkbundle2";
				$checkbundle2 = mysql_query($sql3);
				// SET Circuit DOCSIS เข้า TV
				$row_DOCSIS = mysql_fetch_array($checkbundle2);
				$Jid2 = $row_DOCSIS[0];
				$sql2 = "UPDATE jobassign SET bundle = $bundleTV WHERE jid = $Jid2";
				$complete_bundle2 = mysql_query($sql2);
			} 
			else {
				echo "Update Bundle ไม่ได้";
			}
			
}

mysql_query(COMMIT);
echo "end.";
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
