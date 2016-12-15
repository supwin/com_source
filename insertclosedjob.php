<?php
/*
log file
180614 0508 : ทำ update indexjob กับ cust_name ด้วย  updateClosedJobInfo();
190614 1810 : พบ bug แสดง log หน้าเว็บตอน import ข้อมูล circuit ที่ import แล้ว เช่น  ด้านล่างนี้ ซึ่งไม่ถูกต้อง  และยังไม่ได้รับการแก้ไข
			9100515835 มีอยู่แล้ว และปิดงานด้วย xxxxxxxxxx ของ ธีระ แก้วกาหลง แล้ว
			9100027469 มีอยู่แล้ว และปิดงานด้วย xxxxxxxxxx ของ ธีระ แก้วกาหลง แล้ว
			9100798666 มีอยู่แล้ว และปิดงานด้วย xxxxxxxxxx ของ ธีระ แก้วกาหลง แล้ว
			9100798467 มีอยู่แล้ว และปิดงานด้วย xxxxxxxxxx ของ ธีระ แก้วกาหลง แล้ว
			9100798657 มีอยู่แล้ว และปิดงานด้วย xxxxxxxxxx ของ ธีระ แก้วกาหลง แล้ว

060714 1831 : พบ bug เมื่อช่างตัดสต๊อกและงานนั้นยังไม่ได้ import จาก ivr จะทำให้ serial นั้นไปรอตัดใน waiting list และเมื่อข้อมูลจาก ivr เข้ามา
				มันจะไม่สนใจชข้อมูล่างที่ปิดงานจาก ivr มันจะ update ใหม่จาก tidnet stock เลยทันที ซึ่งหากเป็นช่างคนละคน จะทำให้เกิด bug ที่มีการตัดสต๊อกด้วยช่างคนละคนกับใน ivr ได้
				แก้ไขเรียบร้อย 1851 น. โดยการเช็ค id ช่างด้วยว่าเป็น id เดียวกันระหว่างช่างที่ปิดงานจาก ivr กับที่ตัดสต๊อกใน tidnet stock โดยเพิ่ม พารามิเตอร์ใน checkCutStock() ในไฟล์ function.php

120714 1846 : แก้ไข กรณี import closed job เข้ามาโดยที่มีการตัดสต๊อกก่อนแล้ว ระบบจะลืม import ข้อมูลชื่อและที่อยู่ลูกค้าเข้ามาด้วย ซึ่งได้แก้ไขเรียบร้อยแล้ว
170714 1220 : แก้ไข ในเรื่องของราคาระยะสายเกินที่คำนวณเก็บลง db ผิด
200714 0956 : แก้ไข bug ที่ไม่ได้ insert ข้อมูลระยะสายดำ-ขาว
050814 1409 : เพิ่ม field ข้อมูลจาก ivr ไว้เปรียบเทียบการแก้ไข
011014 1449 : เพิ่มความสามารถที่จะไม่ insert job ที่ไม่ใช่ status Z
*/

include('cookies.php');
include('functions/function.php');
include('headmenu.php');

if(!checkAllow('sit_importclosedjob')){
	die('คุณไม่มีสิทธิ์ใช้งานหน้านี้ได้');
}

move_uploaded_file($_FILES["fileCSV"]["tmp_name"],"csveqm/".$_FILES["fileCSV"]["name"]); // Copy/Upload CSV


$strTable = "closedjob";
$objCSV = fopen("csveqm/".$_FILES["fileCSV"]["name"], "r");
mysql_query(BEGIN);
$resultQuery = true;

$checkColumn = '1';

// true Online
$indexjob['o'] = '100'; // index
$dateColumn['o'] = '3';  // วันที่
//$statusColumn['o'] = '5';  // วันที่
$circuitColumn['o'] = '4'; // circuit
$cableColumn['o'] = '12'; // ประเภทสายเคเบิ้ล
$bcableColumn['o'] = '13'; // ระยะสายดำ
$wcableColumn['o'] = '14'; // ระยะสายบาว
$dwCableSum['o'] = '15'; // ระยะรวมสายเคเบิ้ล
$engIdColumn['o'] = '3'; // id ช่าง
$engNameColumn['o'] = '17'; // ชื่อช่าง
$typeJobColumn['o'] = '20'; // ประเภทของ job
$typeoverCableColumn['o'] = '21'; //ประเภทการเก็บเงินลูกค้า   // รอเช็คประเภทการเก็บเงินแบบเก็บจาก dispatch ทั้งหมดก่อน
$overCableColumn['o'] = '23'; //ระยะสายที่เกิน 70
$custnameColumn['o'] = '7';// ชื่อลูกค้า
$custAddrColumn['o'] = '8'; // ที่อยู่ลูกค้า
$depotColumn['o'] = '1';
$ColumIVR_SOCLOSEDDT = '3';
$ColumIVR_SISRVNUM = '4';
$ColumIVR_CICUSTOMERNAME = '7';
$ColumIVR_CICUSTOMERADDRESS = '8';
$ColumIVR_RG6 = '12'; //คอลัมนืสายดำที่ใช้
$ColumIVR_DWCABLEOUTLINE = '13';
$ColumIVR_DWCABLEINLINE = '14';
$ColumIVR_DWCABLESUM = '15';
$ColumIVR_24 = '24'; //คอลัมน์ค่าสายเกิน
$NameColumIVR_SOCLOSEDDT = "SO CLOSED DT";
$NameColumIVR_SISRVNUM = "SI SRV NUM";
$NameColumIVR_CICUSTOMERNAME = "CI CUSTOMER NAME";
$NameColumIVR_CICUSTOMERADDRESS = "CI CUSTOMER ADDRESS";
$NameColumIVR_RG6 = "สายดำที่ใช้";
$NameColumIVR_DWCABLEOUTLINE = "DW CABLE OUT LINE";
$NameColumIVR_DWCABLEINLINE = "DW CABLE IN LINE";
$NameColumIVR_DWCABLESUM = "DW CABLE SUM";
$NameColumIVR_24 = "ค่าสายเกิน";

// true Online
/*
$indexjob['o'] = '100'; // index
$dateColumn['o'] = '2';  // วันที่
$circuitColumn['o'] = '3'; // circuit
$cableColumn['o'] = '12'; // ประเภทสายเคเบิ้ล
$bcableColumn['o'] = '13'; // ระยะสายดำ
$wcableColumn['o'] = '14'; // ระยะสายบาว
$dwCableSum['o'] = '15'; // ระยะรวมสายเคเบิ้ล
$engIdColumn['o'] = '3'; // id ช่าง
$engNameColumn['o'] = '18'; // ชื่อช่าง
$typeJobColumn['o'] = '21'; // ประเภทของ job
$overCableColumn['o'] = '25'; //ระยะสายที่เกิน 70
$custnameColumn['o'] = '5';// ชื่อลูกค้า
$custAddrColumn['o'] = '6'; // ที่อยู่ลูกค้า
$depotColumn['o'] = '1'; //ไมไ่ด้ใช้ใน true online
*/

// true visions
$indexjob['v'] = '0'; // index
$firstDate['v'] = '3'; //first date
$installDate['v'] = '4'; //due date
$dateColumn['v'] = '5';  // วันที่
$servicetypecode['v'] = '6';//'4'; // type of service
$statusColumn['v'] = '7';//'5';  // วันที่
$packageColumn['v'] = '10';//'8'; // ชื่อแพคเกจ ของ truevisions
$circuitColumn['v'] = '1'; // circuit
$cableColumn['v'] = '20'; // ประเภทสายเคเบิ้ล  กำหนด 20 เพราะไม่ได้ใช้อะไรอยู่แล้ว
$bcableColumn['v'] = '20'; // ระยะสายดำ กำหนด 20 เพราะไม่ได้ใช้อะไรอยู่แล้ว
$wcableColumn['v'] = '20'; // ระยะสายบาว กำหนด 20 เพราะไม่ได้ใช้อะไรอยู่แล้ว
$dwCableSum['v'] = '20'; // ระยะรวมสายเคเบิ้ล เพราะไม่ได้ใช้อะไรอยู่แล้ว
$engIdColumn['v'] = '20'; // id ช่าง  กำหนด 20 เพราะไม่ได้ใช้อะไรอยู่แล้ว
$engNameColumn['v'] = '20'; // ชื่อช่าง  กำหนด 20 เพราะไม่ได้ใช้อะไรอยู่แล้ว
$typeJobColumn['v'] = '9';//'7'; // ประเภทของ job
$overCableColumn['v'] = '25'; //ระยะสายที่เกิน 70
$custnameColumn['v'] = '2';// ชื่อลูกค้า
$custAddrColumn['v'] = '20'; // ที่อยู่ลูกค้า กำหนด 20 เพราะไม่ได้ใช้อะไรอยู่แล้ว
$depotColumn['v'] = '11';//'9'; // depot code เพื่อเช็คสาขา

$ColumnTV_A = '0';
$ColumnTV_B = '1';
$ColumnTV_C = '2';
$ColumnTV_D = '3';
$ColumnTV_E = '4';
$ColumnTV_F = '5';
$ColumnTV_G = '6';
$ColumnTV_H = '7';
$ColumnTV_J = '9';
$ColumnTV_K = '10';
$ColumnTV_L = '11';
$NameColumnTV_WorkOrderNo = "Work Order No.";
$NameColumnTV_Cust = "Cust No.";
$NameColumnTV_Name = "Name";
$NameColumnTV_RegisterD = "Register Date time";
$NameColumnTV_InstallD = "Install Date time";
$NameColumnTV_CompleteDate = "Complete Date time";
$NameColumnTV_Type = "Type";
$NameColumnTV_Status = "Status";
$NameColumnTV_Reason= "Reason";
$NameColumnTV_Main = "Main Package";
$NameColumnTV_Depot = "Depot Code";



// FOA from Q-Run
$indexjob['q'] = '0'; // index
$dateColumn['q'] = '1';  // วันที่
$circuitColumn['q'] = '2'; // circuit
$custnameColumn['q'] = '3';// ชื่อลูกค้า
$custAddrColumn['q'] = '4'; // ที่อยู่ลูกค้า กำหนด 20 เพราะไม่ได้ใช้อะไรอยู่แล้ว
$depotColumn['q'] = '5'; // depot code เพื่อเช็คสาขา
$statusColumn['q'] = '6';  // สถานะงาน
$cableColumn['q'] = '7'; //ประเภทสาย cable ใช้ชั่วคราวจาก column product name
$typeJobColumn['q'] = '7'; //ประเภท job ใช้ชั่วคราวจาก column product name
$ColumFOA_WorkOrderNo = '0';
$ColumFOA_ServiceAccessNo = '2';
$ColumFOA_CustomerName = '3';
$ColumFOA_Address = '4';
$ColumFOA_Team = '5';
$ColumFOA_OperationStatus = '6';
$ColumFOA_ProductName = '7';
$NameColumFOA_WorkOrderNo = "Work Order No.";
$NameColumFOA_ServiceAccessNo = "Service Access No.";
$NameColumFOA_CustomerName = "Customer Name";
$NameColumFOA_Address = "Address";
$NameColumFOA_Team = "Team";
$NameColumFOA_OperationStatus = "Operation Status";
$NameColumFOA_ProductName = "Product Name";



$rowObj = 1;

function updateClosedJobInfo($dateconv,$circuit,$typejob,$field,$newValue){

	$uTable = "closedjob";
	$uCommand = " ".$field."='".$newValue."'";
	$typejobField = "typejob = '".$typejob."'";
	if(($typejob=='6') or ($typejob=='12')) $typejobField = "typejob in ('6','12')";
	if(($typejob=='7') or ($typejob=='13')) $typejobField = "typejob in ('7','13')";
	$uCondition = "closeddate='".$dateconv."' and circuit='".$circuit."' and ".$typejobField;
	if(!fncUpdateRecord($uTable,$uCommand,$uCondition)){
		die(echoError("UPDATE $uTable SET  $uCommand WHERE $uCondition"));
	}
}


while (($objArr = fgetcsv($objCSV, 1000, ",")) !== FALSE) {

		if($rowObj==1){
			switch ($objArr[$checkColumn]) {
    				case "SI IBS DEPOT KEY":
					if($objArr[$ColumIVR_SOCLOSEDDT]==$NameColumIVR_SOCLOSEDDT
						&& $objArr[$ColumIVR_SISRVNUM]==$NameColumIVR_SISRVNUM
					    && $objArr[$ColumIVR_CICUSTOMERNAME]==$NameColumIVR_CICUSTOMERNAME
						&& $objArr[$ColumIVR_CICUSTOMERADDRESS]==$NameColumIVR_CICUSTOMERADDRESS
					    && $objArr[$ColumIVR_RG6]==$NameColumIVR_RG6
						&& $objArr[$ColumIVR_DWCABLEOUTLINE]==$NameColumIVR_DWCABLEOUTLINE
					    && $objArr[$ColumIVR_DWCABLEINLINE]==$NameColumIVR_DWCABLEINLINE
						&& $objArr[$ColumIVR_DWCABLESUM]==$NameColumIVR_DWCABLESUM
					    && $objArr[$ColumIVR_24]==$NameColumIVR_24){
						$f = 'o';
					break;
					};

    				case "Cust No.":
					if($objArr[$ColumnTV_A]==$NameColumnTV_WorkOrderNo
						&& $objArr[$ColumnTV_B]==$NameColumnTV_Cust
						&& $objArr[$ColumnTV_C]==$NameColumnTV_Name
						&& $objArr[$ColumnTV_D]==$NameColumnTV_RegisterD
						&& $objArr[$ColumnTV_E]==$NameColumnTV_InstallD
						&& $objArr[$ColumnTV_F]==$NameColumnTV_CompleteDate
						&& $objArr[$ColumnTV_G]==$NameColumnTV_Type 
						&& $objArr[$ColumnTV_H]==$NameColumnTV_Status
						&& $objArr[$ColumnTV_J]==$NameColumnTV_Reason
						&& $objArr[$ColumnTV_K]==$NameColumnTV_Main
						&& $objArr[$ColumnTV_L]==$NameColumnTV_Depot){
						$f = 'v';
					break;
					};

    				case "Confirmed Complete Time":
					if($objArr[$ColumFOA_WorkOrderNo]==$NameColumFOA_WorkOrderNo
						&& $objArr[$ColumFOA_ServiceAccessNo]==$NameColumFOA_ServiceAccessNo
					    && $objArr[$ColumFOA_CustomerName]==$NameColumFOA_CustomerName
						&& $objArr[$ColumFOA_Address]==$NameColumFOA_Address
					    && $objArr[$ColumFOA_Team]==$NameColumFOA_Team
						&& $objArr[$ColumFOA_OperationStatus]==$NameColumFOA_OperationStatus
					    && $objArr[$ColumFOA_ProductName]==$NameColumFOA_ProductName){
						$f = 'q';
					break;
					};
    				default:
					?>
					<script>
					openAlert('ไฟล์ไม่ถูกต้องกรุณาตรวจสอบ!!! <?php echo $objArr[$checkColumn]?>');
					//window.location='closedjob.php';
					</script>
					<?php
					die();
			}
			$rowObj++;
			continue;
		}

		if($objArr[$depotColumn[$f]]!=$depot){

			$depotFromCSV = $objArr[$depotColumn[$f]];

			if($f=='q'){
				//echo "111 = ".$objArr[$depotColumn[$f]];
				//echo "222 = ".$dpt[0];
				//echo "333 = ".$dpt
				$dpt = explode("_",$objArr[$depotColumn[$f]]);  // ตัด _Team1 ออกจาก depot code ที่ Q-run ให้มา
				$depotFromCSV = $dpt[0];
			}

			if($dpt[0]!=$depot){  // เอา depot ที่ตัด _Team1 ออกแล้วมาเช็คอีกครั้งหนึ่ง
				/* เก็บไว้ debug
				echo "f = ".$f."<br>";
				echo "depotcolumn - ".$depotColumn[$f]."<br>";
				echo "cir = ".$objArr[4]."<br>";
				echo $objArr[$depotColumn[$f]]." - ".$depot;
				*/

				?>
				<script>
				openAlert('File ที่ uploade มาน่าจะผิดสาขานะ<?php echo "file = ".$depotFromCSV?> , <?php echo "system = ".$depot?>');
				</script>
				<?php
				die();
			}
		}

		if($objArr[$circuitColumn[$f]]=='') continue;

		if($f=='q'){
			if($objArr[$statusColumn[$f]]!='Completed') continue;

			$ccExplode = explode(",",$objArr[$circuitColumn[$f]]);
			$circuitConverted = $ccExplode[0];
			$circuitCATVFTTXFOA = $ccExplode[2];  // CATV ที่ติดตั้งโดย FTTX FOA
		}else{
			$circuitConverted = $objArr[$circuitColumn[$f]];
		}

		//if($objArr[$statusColumn[$f]]!='Z' and $f=='v'){// continue;  // สำหรับไม่ insert status อื่นที่ไม่ใช่ Z
		if($f=='v'){ // ถ้าเป็น CATV ให้ทำ jobassign ด้วย
			$strTableJob = "jobassign";
			$strCondition = "so_no='".$objArr[$indexjob['v']]."'";
			$firstdateCATV = convDDMMYYtoYYYYMMDD($objArr[$firstDate['v']]);
			$duedateCATV = convDDMMYYtoYYYYMMDD($objArr[$dateColumn['v']]);
			$installCATV = convDDMMYYtoYYYYMMDD($objArr[$installDate['v']]);
			if(fncCountRow($strTableJob,$strCondition)>=1){
				$strCommand = "job_status='".$objArr[$statusColumn['v']]."', due_date='".$installCATV."', conf_date='".$installCATV."'";
				fncUpdateRecord($strTableJob,$strCommand,$strCondition);
				echoSuccf("update ".$objArr[$indexjob['v']]." แล้ว");
			}else{
				$strField = "jobname,so_no, circuit, job_status, first_due, due_date, conf_date, SO_CCSS_ORDER_TYPE,  cust_name";
				$strValue = "'CATV','".$objArr[$indexjob['v']]."','".$objArr[$circuitColumn['v']]."','".$objArr[$statusColumn['v']]."','".$firstdateCATV."','".$installCATV."','".$installCATV."','".$objArr[$servicetypecode['v']]."','".$objArr[$custnameColumn['v']]."'";
				fncInsertRecord($strTableJob,$strField,$strValue);
				echoSuccf("insert ".$objArr[$indexjob['v']]." แล้ว");

			}

							//CHECK BUNDLE
				$name = $objArr[$custnameColumn['v']];
				//แยกคำนำหน้า
				$FLname = explode(" ", $name);
				$fname = $FLname[1];
				$lname = $FLname[2];

				$condition_checkbundle = "cust_name LIKE'%".$fname."%'AND cust_name LIKE'%".$lname."%' AND jobname = 'DOCSIS'
										  AND due_date BETWEEN date_add( '".$installCATV."',interval -3 day)
										  AND date_add('".$installCATV."',interval 3 day)";

				// SET Circuit TV เข้า DOCSIS
				$sql2 = "SELECT * FROM jobassign WHERE $condition_checkbundle";
				$checkbundle = mysql_query($sql2);
				//$checkbundle = select_bundle($condition_checkbundle);
				if($checkbundle!=''){
					$row_Jid = mysql_fetch_array($checkbundle);
					$Jid = $row_Jid[0];
					$bundleDOCSIS = $row_Jid[5];
					$bundleTV = $objArr[$circuitColumn['v']];
					$sql = "UPDATE jobassign SET bundle = $bundleTV WHERE jid = $Jid";
					$complete_bundle = mysql_query($sql);

				$condition_checkbundle2 = "circuit = $bundleTV";
				$sql3 = "SELECT * FROM jobassign WHERE $condition_checkbundle2";
				$checkbundle2 = mysql_query($sql3);
				// SET Circuit DOCSIS เข้า TV
				$row_CATV = mysql_fetch_array($checkbundle2);
				$Jid2 = $row_CATV[0];
				$sql2 = "UPDATE jobassign SET bundle = $bundleDOCSIS WHERE jid = $Jid2";
				$complete_bundle2 = mysql_query($sql2);
				}
				else {
					echo "Update Bundle ไม่ได้";
				}

			if($objArr[$statusColumn[$f]]!='Z') continue;  // ถ้า CATV ไม่เป็น Z ให้ทำการ insert ถ้าไม่มีอยู่แล้ว เมืื่อเสร็จแล้วให้ข้ามไป row ต่อไป
		}

		$dateconv = convdatexls($objArr[$dateColumn[$f]]);


		 //ไม่ให้งาน tv จาก zsmart เข้า 
			$strSQL1 = "SELECT LEFT('$circuitConverted', 1)";
			$foacir = mysql_query($strSQL1);
			while ($res = mysql_fetch_array($foacir)) {
				$firstcir = $res[0];
			}
			//echo $firstcir;	
		if($f=='q' and ($firstcir == '1') and ($objArr[$cableColumn[$f]] == "FIBERTV"))	{
			//echoError($circuitConverted." ของzsmart ไม่ให้เข้านาจา");
			continue;
		}

		//เอาข้อมูลไปหาประเภทของ job
		$typeofJob = mergeJob($objArr[$cableColumn[$f]],$objArr[$typeJobColumn[$f]],$objArr[$dwCableSum[$f]]);
		$sumcb = $objArr[$bcableColumn[$f]]+$objArr[$wcableColumn[$f]];
		$priceJ = getCostFrmtypeJob($typeofJob,$sumcb);



		$job = checkExistClosedJob($dateconv,$circuitConverted,$typeofJob);

		if($f=='v' and $job['typejob']=='25'){
			echoError($job[circuit]." มีอยู่แล้ว ให้ข้ามไปเลย");
			continue;
		}

		if($job[circuit]==$circuitConverted){
			//$series = "";
			if($job[series]<>'') $seriesJob = " และปิดงานด้วย ".$job[series]." ของ ".nameofengineer($job[emp_id])." แล้ว";

			if($job[emp_id]=='') updateClosedJobInfo($dateconv,$circuitConverted,$typeofJob,'emp_id',$objArr[$indexjob[$f]]);
			// ทำไว้แก้ bug ที่ไม่มีการ insert indexjob,cust_name แต่ทิ้งไว้ก็ไม่ได้เป็นปัญหา แถมดีด้วยไว้สำหรับเพิ่มเิติม update
			
			if($job[indexjob]<>$objArr[$indexjob[$f]]) updateClosedJobInfo($dateconv,$circuitConverted,$typeofJob,'indexjob',$objArr[$indexjob[$f]]);
			if($job[cust_name]<>$objArr[$custnameColumn[$f]]) updateClosedJobInfo($dateconv,$circuitConverted,$typeofJob,'cust_name',$objArr[$custnameColumn[$f]]);
			
			echoError($job[circuit]." มีอยู่แล้ว ".$seriesJob);
			
			unset($job);
		
		}else{
			$overrangeCabel = 0;//($objArr[$overCableColumn[$f]]/25)*0;  // สรุปยอด ค่าระยะสายเกินเพื่อ Tidnet จ่ายทีมติดตั้ง
			//$priceJ = cost2price($objArr[$costColumn]);

			//if(($objArr[$cableColumn[$f]]='RG6') and ($objArr[$dwCableSum[$f]]>=41)) $typeofjob .="+41";

			$engId = getIDEng($objArr[$engNameColumn[$f]]);
			$snResult = checkCutStock($circuitConverted,$engId); // คืนค่าเป็น array [sn], [responcible]
			//if($objArr[$engNameColumn[$f]]<>'') $engId = getIDEng($objArr[$engNameColumn[$f]]);
			$over70 = ($objArr[$dwCableSum[$f]]-70);
			if($over70 < 0 ) $over70 = 0;
			if($snResult['circuit']<>$circuitConverted){
				// กรณี insert closedjob ก่อนช่างตัดสต๊อก
				$strValue = "'".$dateconv."'
				,'".$circuitConverted."'
				,'".$objArr[$custnameColumn[$f]]."'
				,'".preg_replace('#[^ก-๙a-zA-Z0-9\-\/\.\ ]#u','', $objArr[$custAddrColumn[$f]])."'
				,'".$engId."'
				,'".$typeofJob."'
				,'".$priceJ['tuc2tidnet']."'
				,'".$priceJ['tidnet2sub']."'
				,'".$overrangeCabel."'
				,'".trim($objArr[$cableColumn[$f]])."'
				,'".$objArr[$bcableColumn[$f]]."'
				,'".$objArr[$wcableColumn[$f]]."'
				,'".$objArr[$dwCableSum[$f]]."'
				,'".$over70."'
				,'".$objArr[$indexjob[$f]]."'
				,'".trim($objArr[$cableColumn[$f]])."'
				,'".$objArr[$bcableColumn[$f]]."'
				,'".$objArr[$wcableColumn[$f]]."'
				,'".$over70."'
				,'".$priceJ['tidnet2sub']."'
				,'".$overrangeCabel."'";

				$strField = "closeddate,
				circuit,
				cust_name,
				cust_addr,
				emp_id,
				typejob,
				truepay,
				price,
				overrange,
				rgcable,
				bcable,
				wcable,
				dwsum,
				dwov70,
				indexjob,
				rgcable_ivr,
				bcable_ivr,
				wcable_ivr,
				dwov70_ivr,
				price_ivr,
				overange_ivr";
				//echo "INSERT INTO $strTable ($strField) VALUES ($strValue) <br>";
				$strTable = "closedjob";
				$objInsert = fncInsertRecord($strTable,$strField,$strValue);


				if(!$objInsert){
					echoError($circuitConverted." บันทึกไม่ได้เลย");
					echo "www";
					$resultQuery = false;
				}else{
					echoSuccf($circuitConverted." เพิ่มแล้วรอช่างตัดสต๊อกตั้งเบิก");
				}
			}else{
				//ช่างบันทึกตัดสต๊อกแล้ว จึง insert พร้อมๆ กัน
				if($snResult[travel]==1) $typeofJob += 6;

				$strValue = "'".$dateconv."',
				'".$circuitConverted."',
				'".$objArr[$custnameColumn[$f]]."',
				'".preg_replace('#[^ก-๙a-zA-Z0-9\-\/\.\ ]#u','', $objArr[$custAddrColumn[$f]])."',
				'".$snResult['oldowner']."',
				'".$typeofJob."',
				'".$priceJ['tidnet2sub']."',
				'".$overrangeCabel."',
				'".$snResult['sn']."',
				'".trim($objArr[$cableColumn[$f]])."',
				'".$objArr[$bcableColumn[$f]]."',
				'".$objArr[$wcableColumn[$f]]."',
				'".$objArr[$dwCableSum[$f]]."',
				'".$over70."',
				'".$objArr[$indexjob[$f]]."',
				'".trim($objArr[$cableColumn[$f]])."',
				'".$objArr[$bcableColumn[$f]]."',
				'".$objArr[$wcableColumn[$f]]."',
				'".$over70."',
				'".$priceJ['tidnet2sub']."',
				'".$overrangeCabel."'";
				$fieldInsert = "closeddate,circuit,cust_name,cust_addr,emp_id,typejob,price,overrange,series,rgcable,bcable,wcable,dwsum,dwov70,indexjob,rgcable_ivr,bcable_ivr,wcable_ivr,dwov70_ivr,price_ivr,overange_ivr";
				$strTable = "closedjob";
				if(fncInsertRecord($strTable,$fieldInsert,$strValue)){
					echoSuccf($circuitConverted." บันทึกปิดงานแล้ว");
					//echo "INSERT INTO $strTable ($strField) VALUES ($strValue)";
				}else{
					echo "xxx";
					//echo "INSERT INTO $strTable ($strField) VALUES ($strValue)";
					$resultQuery = false;
				}
				if(updateEqmSN($snResult[sn],$circuitConverted)){
					echoSuccf($circuitConverted." เครียร์สต๊อกของช่างแล้ว");
				}else{
					echo "eee";
					$resultQuery = false;
				}
			}

			if(closedmap($circuitConverted)){
				echoSuccf(' บันทึกปิดแผนที่แล้ว');
			}
		}
		//ถ้าเป็น New CATV ทั้งมีค่าน้ำมันและไม่มีค่าน้ำมัน ให้สร้าง TRM Record.
		if(($typeofJob==7) or ($typeofJob==13))	createTRMRecord($circuitConverted,$objArr[$custnameColumn[$f]],'tvscc','',$objArr[$dateColumn[$f]],'',$objArr[$packageColumn[$f]]);
		/*
		if($circuitCATVFTTXFOA <> ''){
			$jobcatv = checkExistClosedJob("",$circuitCATVFTTXFOA,'7');
			$jobcatv25 = checkExistClosedJob("",$circuitCATVFTTXFOA,'25');
			$typejobrec = fncSelectSingleRecord('typeofjob','id=25');

			if($jobcatv['circuit']==$circuitCATVFTTXFOA){
				$strConditionCATVUpdate = "circuit='".$circuitCATVFTTXFOA."' and typejob='7'";
				$strCommandCATVUpdate = "typejob='25', price='".$typejobrec['tidnet2sub']."'";
				fncUpdateRecord($strTable,$strCommandCATVUpdate,$strConditionCATVUpdate);
			}elseif($jobcatv25['circuit']!=$circuitCATVFTTXFOA){

				$strValue = "'".$dateconv."'
				,'".$circuitCATVFTTXFOA."'
				,'".$objArr[$custnameColumn[$f]]."'
				,'".$objArr[$custAddrColumn[$f]]."'
				,'".$engId."'
				,'".$typejobrec['id']."'
				,'".$typejobrec['tuc2tidnet']."'
				,'".$typejobrec['tidnet2sub']."'
				,'".$overrangeCabel."'
				,'".trim($objArr[$cableColumn[$f]])."'
				,'".$objArr[$bcableColumn[$f]]."'
				,'".$objArr[$wcableColumn[$f]]."'
				,'".$objArr[$dwCableSum[$f]]."'
				,'".$over70."'
				,'".$objArr[$indexjob[$f]]."'
				,'".trim($objArr[$cableColumn[$f]])."'
				,'".$objArr[$bcableColumn[$f]]."'
				,'".$objArr[$wcableColumn[$f]]."'
				,'".$over70."'
				,'".$priceJ."'
				,'".$overrangeCabel."'";
				$objInsert = fncInsertRecord($strTable,$strField,$strValue);

				if(!$objInsert){
					echoError($circuitCATVFTTXFOA." บันทึกไม่ได้เลย");
					echo "www";
					$resultQuery = false;
				}else{
					echoSuccf($circuitCATVFTTXFOA." เพิ่มแล้วรอช่างตัดสต๊อกตั้งเบิก");
				}
			}


			$circuitCATVFTTXFOA = '';
		}*/
//conf_date>='2016-11-01'
//Internet Fiber

if($f=='q'){
$strTable = "jobassign";
$strCondition = "circuit='".$circuitConverted."'and job_status='X' and conf_date>='2016-11-01'";
//$strCondition = "circuit='".$circuitConverted."'and job_status='X' and conf_date<='2016-10-31' and conf_date>='2016-10-01'";
$strTable2 = "closedjob,eqm_sn";
$strCondition2 = "closedjob.circuit ='".$circuitConverted."' and eqm_sn.circuit ='".$circuitConverted."' and closedjob.bcable = '0' and eqm_sn.oldowner != '0'";
	if(fncCountRow($strTable,$strCondition)>=1){
		if(fncCountRow($strTable2,$strCondition2)>=1){
			$jobassign = fncSelectSingleRecord($strTable,$strCondition);
			$sn = $jobassign['modsn'];
			$circuit = $jobassign['circuit'];
			$ofc = $jobassign['bcable'];

			$strTable = "eqm_sn";
			$strCondition = "sn='".$sn."'";
			$eqm_sn = fncSelectSingleRecord($strTable,$strCondition);
			$emp_id = $eqm_sn['oldowner'];
			$responcible = $eqm_sn['responcible'];
	
			if(fncCountRow($strTable,$strCondition)>=1){
	
				if($responcible=='9099' OR $responcible=='9093'){
					$strTable = "closedjob";
					$strCondition = "circuit='".$circuit."'";
					$closedjob = fncSelectSingleRecord($strTable,$strCondition);
					$typejob = $closedjob['typejob'];
					$cld = $closedjob['closeddate'];
					$price = getpricejob($typejob,$bc,$wc,$ofc);

					$strCommand = "series='".$sn."', emp_id='".$emp_id."',bcable='".$ofc."', price='".$price."'";
					$strCondition = "circuit='".$circuit."' and typejob='".$typejob."' and closeddate BETWEEN date_add( '".$cld."',interval -5 day) AND date_add('".$cld."',interval 5 day)";
					//echo "UPDATE $strTable SET  $strCommand WHERE $strCondition ";
					//die();
					if(!fncUpdateRecord($strTable,$strCommand,$strCondition)){
						echo "UPDATE $strTable SET  $strCommand WHERE $strCondition ";
						$resultQuery = false;
					}
					$strTable = "eqm_sn";
					$strCommand = "oldowner='0'";
					$strCondition = "sn='".$sn."'";
					if(!fncUpdateRecord($strTable,$strCommand,$strCondition)){
						echo "UPDATE $strTable SET  $strCommand WHERE $strCondition ";
						$resultQuery = false;				
					}
					if($responcible=='9093'){
					updateEqmSN($sn,$circuit);
					}
					echo "คิดเงินถูกต้อง";
				}
			}
		} else {
			echo "คิดเงินแล้ว หรือให้ช่างตัดสต็อกเอง";
		}
	} else {
		echo "ไม่ได้ปิดงานในระบบ Gsys";
	}
}

//Internet DOCSIS
if($f=='o'){

$strTable = "jobassign";
$strCondition = "circuit='".$circuitConverted."' and job_status='X' and conf_date>='2016-11-01'";
$strTable2 = "closedjob,eqm_sn";
$strCondition2 = "closedjob.circuit ='".$circuitConverted."' and eqm_sn.circuit ='".$circuitConverted."' and eqm_sn.oldowner != '0'";
	if(fncCountRow($strTable,$strCondition)>=1){
		if(fncCountRow($strTable2,$strCondition2)>=1){
			$jobassign = fncSelectSingleRecord($strTable,$strCondition);
			$sn = $jobassign['modsn'];
			$circuit = $jobassign['circuit'];
			$strTable = "eqm_sn";
			$strCondition = "sn='".$sn."'";
			$eqm_sn = fncSelectSingleRecord($strTable,$strCondition);
			$emp_id = $eqm_sn['oldowner'];
			$responcible = $eqm_sn['responcible'];
			if(fncCountRow($strTable,$strCondition)>=1){

				if($responcible=='9099' OR $responcible=='9093'){

					$strTable = "closedjob";
					$strCondition = "circuit='".$circuit."'";
					$closedjob = fncSelectSingleRecord($strTable,$strCondition);
					$typejob = $closedjob['typejob'];
					$cld = $closedjob['closeddate'];
					$bc = $closedjob['bcable'];
					$wc = $closedjob['wcable'];

					$price = getpricejob($typejob,$bc,$wc,$ofc);

					$strCommand = "series='".$sn."', emp_id='".$emp_id."', price='".$price."'";
					$strCondition = "circuit='".$circuit."' and typejob='".$typejob."' and closeddate BETWEEN date_add( '".$cld."',interval -5 day) AND date_add('".$cld."',interval 5 day)";
					//echo "UPDATE $strTable SET  $strCommand WHERE $strCondition ";
					//die();
					if(!fncUpdateRecord($strTable,$strCommand,$strCondition)){
						echo "UPDATE $strTable SET  $strCommand WHERE $strCondition ";
						$resultQuery = false;
					}
					$strTable = "eqm_sn";
					$strCommand = "oldowner='0'";
					$strCondition = "sn='".$sn."'";
					if(!fncUpdateRecord($strTable,$strCommand,$strCondition)){
						echo "UPDATE $strTable SET  $strCommand WHERE $strCondition ";
						$resultQuery = false;				
					}
					if($responcible=='9093'){
					updateEqmSN($sn,$circuit);
					}
					echo "คิดเงินถูกต้อง";
				}
			}
		} else {
			echo "คิดเงินแล้ว หรือให้ช่างตัดสต็อกเอง";
		}	
	} else {
		echo "ไม่ได้ปิดงานในระบบ Gsys";
	}
}

if($f=='v'){
$strTable = "jobassign";
$strCondition = "bundle='".$circuitConverted."' and job_status='X' and conf_date>='2016-11-01'";
$strTable2 = "closedjob,eqm_sn";
$strCondition2 = "closedjob.circuit ='".$circuitConverted."' and eqm_sn.circuit ='".$circuitConverted."' and eqm_sn.oldowner != '0'";
	if(fncCountRow($strTable,$strCondition)>=1){
		if(fncCountRow($strTable2,$strCondition2)>=1){
			$jobassign = fncSelectSingleRecord($strTable,$strCondition);
			$sn = $jobassign['catvsn'];
			$circuit = $jobassign['bundle'];
			$strTable = "eqm_sn";
			$strCondition = "sn='".$sn."'";
			$eqm_sn = fncSelectSingleRecord($strTable,$strCondition);
			$emp_id = $eqm_sn['oldowner'];
			$responcible = $eqm_sn['responcible'];
			if(fncCountRow($strTable,$strCondition)>=1){

				if($responcible=='9099' OR $responcible=='9093'){
					$strTable = "closedjob";
					$strCondition = "circuit='".$circuit."'";
					$closedjob = fncSelectSingleRecord($strTable,$strCondition);
					$typejob = $closedjob['typejob'];
					$cld = $closedjob['closeddate'];

					$price = getpricejob($typejob,$bc,$wc,$ofc);

					$strCommand = "series='".$sn."', emp_id='".$emp_id."', price='".$price."'";
					$strCondition = "circuit='".$circuit."' and typejob='".$typejob."' and closeddate BETWEEN date_add( '".$cld."',interval -5 day) AND date_add('".$cld."',interval 5 day)";
					//echo "UPDATE $strTable SET  $strCommand WHERE $strCondition ";
					//die();
					if(!fncUpdateRecord($strTable,$strCommand,$strCondition)){
						echo "UPDATE $strTable SET  $strCommand WHERE $strCondition ";
						$resultQuery = false;
					}
					$strTable = "eqm_sn";
					$strCommand = "oldowner='0'";
					$strCondition = "sn='".$sn."'";
					if(!fncUpdateRecord($strTable,$strCommand,$strCondition)){
						echo "UPDATE $strTable SET  $strCommand WHERE $strCondition ";
						$resultQuery = false;				
					}
					if($responcible=='9093'){
					updateEqmSN($sn,$circuit);
					}
					echo "คิดเงินถูกต้อง";
				}
			}
		} else {
			echo "คิดเงินแล้ว หรือให้ช่างตัดสต็อกเอง";
		}
	} else {
		echo "ไม่ได้ปิดงานในระบบ Gsys";
	}
}

//end while{}
}

if($resultQuery){
	mysql_query("COMMIT");
	echoSuccf("บันทึกทั้งหมดเรียบร้อย");
}else{
	mysql_query("ROLLBACK");
	echoError("มีข้อขัดข้อง ยกเลิกการบันทึกทั้งหมดแล้ว");
}


fclose($objCSV);
 ?>
 <p><a href="closedjob.php">กลับหน้า รายงาน IVR</a></p>
