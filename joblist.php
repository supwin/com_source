<?php
include('cookies.php');
include("functions/function.php");
include("headmenu.php");

if(!checkAllow('sit_viewjoblist')) die('คุณไม่มีสิทธิ์ใช้งานหน้านี้');

$today = date('Y-m-d');
$month = date('Y-m')."-01";

//echo $today;
?>
<table>
	<tr class="header">
		<td>สาขา</td>
		<td>Due-Date</td>
		<td>Job Type</td>
		<td>Circuit</td>
		<td>ชื่อลูกค้า</td>
		<td>ที่อยู่ / เบอร์โทร</td>
		<td>Job Status</td>
	</tr>
<?php
function printTR($job,$colorTr,$key){
	if($job['jobname']=='DOCSIS') $colorTr = "#9CF78A";
	if($job['jobname']=='CATV') $colorTr = "#FFC0CB";
	if($job['jobname']=='FTTX' or $job['jobname']=="FTTH") $colorTr = "#FAA461";
	echo "<tr style=\"background-color:".$colorTr.";\" ><td>".strtoupper($key)."</td><td>".convdateMini($job['due_date'])."</td><td>".$job['jobname']."</td><td>".$job['circuit']."</td><td>".$job['cust_name']."</td><td>".$job['cust_addr']."<br>โทร.".$job['cust_phone']."</td><td>".$job['job_status']."</td?</tr>";
}

foreach ($allBranch as $key => $value){	


	$strTable = "tidnet_".$key.".jobassign";
	if($_GET['type']=='2'){
		$strCondition = "job_status='D' and due_date>='".$today."'";
		$sort = "order by due_date";
		if($_GET['debug']) echo "SELECT * FROM $strTable WHERE $strCondition <br>";
		$jobs = fncSelectConditionRecord($strTable,$strCondition,$sort);
		while($job = mysql_fetch_array($jobs)){
			printTR($job,$colorTr,$key);
		}
	}else if($_GET['type']=='1' and constant('ABVT')==$key){
		$strTable = "tidnet_".constant('ABVT').".jobassign";
		$strCondition = "job_status='D' and due_date>='".$today."'";
		$sort = "order by due_date";
		if($_GET['debug']) echo "SELECT * FROM $strTable WHERE $strCondition <br>";
		$jobs = fncSelectConditionRecord($strTable,$strCondition,$sort);
		while($job = mysql_fetch_array($jobs)){
			printTR($job,$colorTr,$key);
		}
	}else if($_GET['type']=='3'){
		$strCondition = "job_status='X' and due_date>='".$month."'";
		$sort = "order by due_date";
		if($_GET['debug']) echo "SELECT * FROM $strTable WHERE $strCondition <br>";
		$jobs = fncSelectConditionRecord($strTable,$strCondition,$sort);
		while($job = mysql_fetch_array($jobs)){
			printTR($job,$colorTr,$key);
		}
		
	}
}
