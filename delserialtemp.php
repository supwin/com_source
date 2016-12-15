 <?php
/*
Log file 

070714 18.00 : created เพื่อลย s/n ที่ผิดพลาดเพื่อรอตรวจสอบเข้าสต๊อก 

*/ 
 
 
include('cookies.php');
include('functions/function.php');
include("db_function/phpMySQLFunctionDatabase.php");

$table = "serial_tmp";
$condition = "sn in ('".str_replace(',','\',\'',$_POST['allsn'])."')";
$star = "count(*) as total, model_id as id";
$strSort = "group by id";
fncDeleteRecord($table,$condition);
$i=0;
$counts = fncSelectStarConditionRecord($star,$table," 1",$strSort);
//echo "SELECT $star FROM $table WHERE 1 $strSort";
while($count = mysql_fetch_array($counts)){
	$crow[$count['id']] = $count['total'];
	++$i;
}
if($i==0){
	echo 0;
}else{
	echo json_encode($crow);
}

//echo "DELETE FROM $table WHERE $condition ";

?>
