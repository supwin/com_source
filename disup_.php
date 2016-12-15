<?php
/*
Log file

ใช้เพื่อการแสดงจำนวน SN ของช่างแต่ละทีม

16 09 16 just created

*/

if($_COOKIE['user']==""){
	?>
	<script>
		window.location ='login_frm.php';
	</script>
	<?php
}

include('functions/function.php');
include("../com_source/headmenu.php");

$fromDate = '2016-11-30';

echo "from Date = ".$fromDate."<br>";

$d=$_GET['d'];
$eng=$_GET['e'];
$limit=$_GET['l'];
$t = $_GET['t'];
$sort = $_GET['s'];

function txtrand(){

    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randstring = '';
    for ($i = 0; $i < 10; $i++) {
        $txt .= $characters[rand(0, strlen($characters))];
    }
    return $txt;
}
/*
$myTxt = fopen("../com_source/distxt.txt", "r") or die("Unable to open file!");

if($t==''){
  $txt = txtrand();
  //echo "<p><span style=\"background-color:#fff>".$txt."</span></p>";
  echo $txt;
  fwrite($myTxt, $txt);
  fclose($myTxt);

  die();
}


$txtFromFile = fread($myTxt,filesize("../com_source/distxt.txt"));

echo "<div>d=".$txtFromFile."</div>";*/
echo "<div>d=".$d."</div>";
echo "<div>eng=".$eng."</div>";
echo "<div>limit=".$limit." = 333</div>";
echo "<div>sort=".$sort."</div>";
//if($_COOKIE['uid']==1 and $t==$txtFromFile){
$today = date('d');

if($_COOKIE['permission']==1 and $eng>0 and $d>=$today and $limit>0){
  //$txt = txtrand();
  //echo "<p><span style=\"background-color:#fff>".$txt."</span></p>";

  //fwrite($myTxt, $txt);
  //fclose($myTxt);

  //die();

	mysql_query('BEGIN');
	$date="2016-11-".$d;

  $strTable = "jobassign";
  $strCommand = "due_date='".$date."', conf_date='".$date."',assigned_eng='".$eng."',result_cnf_code='11', conftime='21:00:00'";
  $strCondition = "due_date = '".$fromDate."' and job_status='D' and work_action='F' order by tap ".$sort." limit ".$limit;

	$packjob = fncSelectConditionRecord($strTable,$strCondition);
	$rollback = 0;

	$strsTableMemo = "memo_appointment";
	$strField = 'memo_date_time,jid,due_date,emp_id,result,memotxt,who_did';
	while($jobeach = mysql_fetch_array($packjob)){
			$strConditionEach = "jid='".$jobeach['jid']."'";

			$strValue1 = tidnetNow().",'".$jobeach['jid']."','".$date."','".$eng."','39','ดึงงานทำวันที่ ".$date."','".$_COOKIE['uid']."'";
			$strValue2 = tidnetNow().",'".$jobeach['jid']."','".$date."','".$eng."','11','งานรื้อถอนพี่หนึ่งจัดให้','".$_COOKIE['uid']."'";

		  echo "<div style=\"background-color:gray\">UPDATE ".$strTable." SET  ".$strCommand." WHERE ".$strConditionEach."</div>";
			echo "<div style=\"background-color:gray\">INSERT INTO ".$strsTableMemo." (".$strField.") VALUES (".$strValue1.")</div>";
			echo "<div style=\"background-color:gray\">INSERT INTO ".$strsTableMemo." (".$strField.") VALUES (".$strValue2.")</div>";
			  if(fncUpdateRecord($strTable,$strCommand,$strConditionEach)){
					if(!fncInsertRecord($strsTableMemo,$strField,$strValue1) or !fncInsertRecord($strsTableMemo,$strField,$strValue2)){
						$rollback = 1;
 			      echo "<div style=\"color:red\">not ok ".$eng."</div>";
					}else{
			     	echo "<div style=\"color:green\">ok ".$eng."</div>";
					}
			   }else{
					 $rollback = 1;
			      echo "<div style=\"color:red\">not ok ".$eng."</div>";
			   }
	}

	if($rollback==1){
		mysql_query('ROLLBACK');
	}else{
		mysql_query('COMMIT');
		echo "<div style=\"background-color:green\"> OK >>> COMMIT </div>";
	}

}else{
  echo "คุณไม่สามารถดำเนินการได้";
}
  /*
  $strTable = "jobassign";
  $strCommand = "due_date='2016-09-18', conf_date='2016-09-18',assigned_eng='75'";
  //$strCondition = "due_date = '2016-09-30' and job_status='D' and work_action='F' and cust_addr like '%วิชิต%' order by tap limit 10";

  echo "UPDATE ".$strTable." SET  ".$strCommand." WHERE ".$strCondition;

//  if(fncUpdateRecord($strTable,$strCommand,$strCondition)){
     echo "ok";
  // }else{
     echo "no";
  // }
}esle{
  echo "คุณไม่มีสิทธิ์";
}
*/

 ?>
