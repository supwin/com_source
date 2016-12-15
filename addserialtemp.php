 <?php
/*
Log file 

220614 2252 : created เพื่อบันทึก s/n ที่รอตรวจสอบเข้าสต๊อก 

*/ 
 
 
include('cookies.php');
include('functions/function.php');
include("db_function/phpMySQLFunctionDatabase.php");

$sn = $_POST['sn'];
$mac = $_POST['mac'];
$mId = $_POST['modelid'];
$ever = $_POST['ever'];
$lot = $_POST['lot'];


$table = "serial_tmp";
$field = "sn, mac, model_id, lot_coming, everhere, date_time, whodid";
$values = "'".$sn."','".$mac."','".$mId."','".$lot."','".$ever."',".tidnetNow().",'".$_COOKIE['uid']."'";
if(!fncInsertRecord($table,$field,$values)){
	echo "0";
}else{
	echo "1";
}
?>
