<?php
/*
Log file
120714 2249 : เพิ่มเติมให้สามารถทำสต๊อกอุปกรณ์เก็บคืนได้ โดยจาก job type No.5 คือ change package นั่นเอง
200714 1154 : ยกเลิก type 4 ไม่ให้ใส่ code ช่างแล้ว
260714 2223 : กำหนดใ้ห้ type 4 สามารถใช้ code ช่างปิดงานได้
020814 1047 : แก้ไขให้ moreCondition เช็ค query จาก การเปลี่ยนแปลงของ typeobjob ซะเลย จะได้ยืดหยุ่นได้อีกหน่อย
030814 2140 : แก้ bug ที่ select id แล้วสับสนว่า id table ไหน ตอนปิดงานด้วย code ช่าง
050814 1042 : เพิ่มความสามารถให้บันทึก circuit ที่ใช้กับอุปกรณ์ serial นี้ด้วย  eqm_sn.closedcircuit
060814 1418 : แก้ไขให้ update eqm_sn ด้วย  updateEqmSN($sn,$closedcir='') ซะ
210814 1915 : แก้ bug กรณี ส่ง circuit ไปตัดสต๊อกด้วย
091014 1239 : แก้ bug กรณีเก็บ CATV กลับมาปิดงานแล้วไม่บันทึก empid
*/
include('cookies.php');
include("db_function/phpMySQLFunctionDatabase.php");
include("functions/function.php");
include('cost.php');

// static variable;
$moveinstallcatvIncreadTravelJobNo = '12';
$newinstallcatvIncreadTravelJobNo = '13';


$sn = $_POST['sn'];
$cir = $_POST['cir'];
$cld = $_POST['cld'];  // closed date
$tjob = $_POST['tjob'];
$oldSN = $_POST['oldSN'];
if($oldSN=='undefined') $oldSN = '';
$rg = $_POST['rg'];
$bc = $_POST['bc'];
$wc = $_POST['wc'];
$ofc = $_POST['ofc'];
if($tjob == '') $tjob = getTypeJobfromClosedjob($cir); // ถ้ามาจาก showstockeng.php จะไม่มี tjob มาให้
$travel = $_POST['travel'];


if($travel==1){
	$price = getpricejob($newinstallcatvIncreadTravelJobNo,$bc,$wc,$ofc);
	$typejobAddtravel = ",typejob='".$newinstallcatvIncreadTravelJobNo."',price='".$price."'";
}
$engid = getEngFrmSN($sn);  // จำไม่ได้ว่าทำไมต้องไปเอา engid จึงให้เช็คถ้า 99 ให้ใช้ engid จาก session แก้ที่บันทัน 79 #พี่หนึ่ง

$m = date('n');

$moreCommand = "";
$moreCondition = "";

//---- กรณีงานที่ตัดสต๊อกด้วย code ช่าง
$engTable = "employee";
$engCondition = "name='".$sn."'";
$engineer = fncSelectSingleRecord($engTable,$engCondition);
if($engineer[name]==$sn){
	$sn = 'xxxxxxxxxx';
	$engid = $engineer[id];
	if($travel==1){
		$price = getpricejob($moveinstallcatvIncreadTravelJobNo,$bc,$wc,$ofc);
		$typejobAddtravel = ",typejob='".$moveinstallcatvIncreadTravelJobNo."',price='".$price."'";
	}
	//$moreCondition = " and typejob='".$tjob."' and typejob in ('1','2','3','4','6','8','9','12',14,16)";
	$moreCondition = " and typejob in (SELECT tidnet_common.typeofjob.id FROM tidnet_common.typeofjob,usesn WHERE tidnet_common.typeofjob.usesn=usesn.id and usesn.codeeng='1')";
}
//---- กรณีงานที่ตัดสต๊อกด้วย code ช่าง


//$dwsum = $bc+$wc;
$pricecb = getpricejob($tjob,$bc,$wc,$ofc);
/*
if($dwsum>40) $pricecb = '1400';
if($dwsum>70){
	$over70bw = $dwsum-70;
	if($over70bw > 30) $over70bw=30;
	$over70price = $over70bw*20;
}	*/
//echo $tjob,$bc;
mysql_query("BEGIN");
$resultQuery = true;

if($engid=='' or $engid>='9000'){  // แก้ไขเรื่อง endid เป็น 99 ที่ปลายเหตุ ยังหาสาเหตุไม่เจอ
	$engid = $_COOKIE['uid'];
}

$strTable = "closedjob";
//$strCommand = "series='".$sn."', emp_id='".$engid."', rgcable='".$rg."', bcable='".$bc."', wcable='".$wc."', dwsum='".$dwsum."', dwov70='".$over70bw."', price='".$pricecb."', overrange='".$over70price."' ".$typejobAddtravel;

if($ofc>0){
	$bc = $ofc;
}


$strCommand = "series='".$sn."', sn_return='".$oldSN."', emp_id='".$engid."',bcable='".$bc."',wcable='".$wc."', price='".$pricecb."' ".$typejobAddtravel;

$strCondition = "circuit='".$cir."' and typejob='".$tjob."' and closeddate='".$cld."' ".$moreCondition;
//echo "UPDATE $strTable SET  $strCommand WHERE $strCondition ";
//die();
if(!fncUpdateRecord($strTable,$strCommand,$strCondition)){
	die("UPDATE $strTable SET  $strCommand WHERE $strCondition ");
	mysql_query("ROLLBACK");
	$resultQuery = false;
}

$useoldsn = typejobUseSN_OLDSN($tjob);

if($useoldsn==1 and $sn<>'xxxxxxxxxx'){  // เป็น type job ที่มีการเก็บอุปกรณ์ กลับมา และมีการลงทะเบียน serial ที่เก็บคืนกลับมา
	$oldSN = $sn;
}
if(($oldSN<>'' and $tjob==5) or ($sn<>'xxxxxxxxxx' and $useoldsn==1)){
	$returnTable = "eqm_return";
	$returnField = "date,circuitofreturn,series,type_id,status,whodid";
	$returnValue = tidnetNow().",'".$cir."','".$oldSN."',1,0,".$_COOKIE['uid'];
	//die("INSERT INTO $returnTable ($returnField) VALUES ($returnValue) ");
	if(!fncInsertRecord($returnTable,$returnField,$returnValue)){
		die("INSERT INTO $returnTable ($returnField) VALUES ($returnValue) ");
		mysql_query("ROLLBACK");
		$resultQuery = false;
	}
}

$trmTable="trm";
$trmCommand = "engineer_id='".$engid."'";
$trmCondition = "circuit='".$cir."'";
//fncUpdateRecord($trmTable,$trmCommand,$trmCondition);


if($sn <> 'xxxxxxxxxx'){
/*
	$strTable = "eqm_sn";
	$strCommand = "date_movement=ADDTIME(now(), '14:00:00'), responcible='99', closedcircuit='".$cir."'";
	$strCondition = "sn='$sn'";
	if(!fncUpdateRecord($strTable,$strCommand,$strCondition)) $resultQuery = false;
*/
	if(!updateEqmSN($sn,$cir)) $resultQuery = false;
}


if($resultQuery){
	mysql_query("COMMIT");
	echo "1";
}else{
	mysql_query("ROLLBACK");
	echo "0";
}


function mustCreateTrm($tj){
	$table = 'tidnet_common.typeofjob';
	$condition = "id='".$tj."'";
	$j = fncSelectSingleRecord($engTable,$engCondition);
	if($j[trm]==1){
		return true;
	}else{
		return false;
	}

}

 ?>
