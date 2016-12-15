 <?php
/*
Log file 
060814 1359 : just created
171014 2116 : แก้ไขให้ updateEqmSN($sn,$cir,'97') ส่ง code 97 เพื่อไว้ตัดสต๊อกไม่ปิดงานไปด้วย
*/
include('cookies.php');
include('functions/function.php');
include("db_function/phpMySQLFunctionDatabase.php");

$sn = $_POST[sn];
$note = $_POST[note];
$cir = $_POST[cir];
$indexjob = $_POST[indexjob];
$closeddate = $_POST[closeddate];
$typejob = $_POST[typejob];

$strTable = "eqm_sn";
$strCondition = "sn='".$sn."' and closedcircuit='' and circuit='' and responcible not in ('9097','9098','9099')";
$mod = fncSelectSingleRecord($strTable,$strCondition);

mysql_query("Begin");

if($mod[sn]==$sn){
	if(!updateEqmSN($sn,$cir,'9097',$note) or !updaeclosedjob($cir,$indexjob,$sn,$closeddate,$typejob)){
		mysql_query("ROLLBACK");
		echo 2;
	}else{
		mysql_query("COMMIT");
		echo 1;
	}
}else{
	echo 0;
}	
