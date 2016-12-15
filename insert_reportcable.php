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
$bcable = '0'; // 
$circuit = '1'; // 
$date_complete = '2'; //
$namecolumn0 = "ระยะสาย";
$namecolumn1 = "FTTx Number";
$namecolumn2 = "Closed_Date";
$rowObj = 1;

while (($objArr = fgetcsv($objCSV, 1000, ",")) !== FALSE) {

		if($rowObj==1){
			switch ($objArr[$checkColumn]) {
    				case "FTTx Number":
					if($objArr[$bcable]==$namecolumn0
						&& $objArr[$circuit]==$namecolumn1
					    && $objArr[$date_complete]==$namecolumn2){
					break;
					};
    				default:
					?>
					<script>
					openAlert('ไฟล์ไม่ถูกต้องกรุณาตรวจสอบ!!! <?php echo $objArr[$checkColumn]?>');
					//window.location='report_cablerange.php';
					</script>
					<?php
					die();
			}
			$rowObj++;
			continue;
		}

		if($objArr[$bcable]!==''){
			$strTableJob = "closedjob";
			$strCondition = "circuit='".$objArr[$circuit]."' and rgcable='HSI'";
			if(fncCountRow($strTableJob,$strCondition)>=1){

				$strCommand = "bcable_ivr='".$objArr[$bcable]."'";
				fncUpdateRecord($strTableJob,$strCommand,$strCondition);
				echoSuccf("update ".$objArr[$circuit]." แล้ว");
			}else{
				echoSuccf("ไม่พบ ".$objArr[$circuit]." ในฐานข้อมูล");

			}

		} else {
			echoSuccf("ไม่มีข้อมูลระยะสาย ".$objArr[$circuit]." จากทาง True");
		}

		
//end while{}
}
	mysql_query("COMMIT");
	echoSuccf("บันทึกทั้งหมดเรียบร้อย");

fclose($objCSV);
 ?>
 <p><a href="report_cablerange.php">กลับหน้า ข้อมูลระยะสาย</a></p>
