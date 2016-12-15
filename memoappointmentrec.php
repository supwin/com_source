<?php
/*
log file 
180315 2309 : just created
*/
include('cookies.php');
include('functions/function.php');
/*
if(!checkAllow('sit_importjobassign')){
	die('คุณไม่มีสิทธิ์บันทึกข้อมูลนี้');
}*/
include('db_function/phpMySQLFunctionDatabase.php');
mysql_query("SET NAMES  UTF8");

$jid = $_POST['jid'];
$duedate = $_POST['duedate'];
$resCnft = $_POST['resCnft'];
$strTable = "memo_appointment";  
$commt = $_POST['commt'];
$status = $_POST['statustxt'];
$emp = $_POST['emp'];
$sms = $_POST['sms'];

if($status=='R' or $status=='C') $returnStatus = 1;

$strField = "memo_date_time,jid,due_date,emp_id,result,return_status,memotxt,who_did";
$strValue = tidnetNow().",'".$jid."','".$duedate."','".$emp."','".$resCnft."','".$returnStatus."','".$commt.$sms."','".$_COOKIE['uid']."'";
//echo $status." << INSERT INTO $strTable ($strField) VALUES ($strValue) ";
if(fncInsertRecord($strTable,$strField,$strValue)){

	$strCommentCondition = "jid='".$jid."'";// and due_date='".$duedate."'";
	$strCommentSort = " order by memo_date_time";
	//echo "SELECT * FROM $strTable WHERE $strCommentCondition  $strCommentSort<br>";
	$comments = fncSelectConditionRecord($strTable,$strCommentCondition,$strCommentSort);

	$timeRectxt = "";
	while($cmm = mysql_fetch_array($comments)){
		/*if($cmm['result']<20){
			$cm++;
			$timeRectxt = "ลูกค้า ".$cm;
			$fclr = "#008080";
		}*/
		echo convdateMini($cmm['memo_date_time'])." : <span style=\"color:".$fclr."\">".$cmm['memotxt']."</span> <span style=\"color:red;font-size:10px;\">*".nameofengineer($cmm['who_did'],1)." [ช่าง:".nameofengineer($emp,1)."]</span><br>";
	}

	if($status<>''){
		$strTable = "jobassign";
		$strCommand = "job_status='".$status."'";
		$strCondition = "jid='".$jid."'";
		fncUpdateRecord($strTable,$strCommand,$strCondition);
	}
	
	if($sms !== ''){
/*
			$strTable = "jobassign";
			$strCondition = "jid='".$jid."'";
			$job = fncSelectSingleRecord($strTable,$strCondition);
			$cust_phone = $job['cust_phone'];
			$circuit = $job['circuit'];
			$custName =  $job['cust_name'];
			$phoneNoTidnet = '0952780269';


			if(strpos($cust_phone, ",") == true){
	           $phoneno = explode(",",$cust_phone);
	           $PhoneList = $phoneno[0];
	        }else{
	         	$PhoneList = $cust_phone;
	        }
	        
			//$PhoneList	= "0901354511";

			$Username	= "supwin";
			$Password	= "smsisylzjko";

		    $Parameter	=	"User=$Username&Password=$Password";
			$API_URL		=	"http://member.smsmkt.com/SMSLink/GetCredit/index.php";

			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL,$API_URL);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch,CURLOPT_POST,1);
			curl_setopt($ch,CURLOPT_POSTFIELDS,$Parameter);

			$Result = curl_exec($ch);
			curl_close($ch);
			echo($Result);

		    echo "<br>";
		  	
			$Message		= urlencode("สมาชิก ".$circuit." ยืนยันนัดหมายติดตั้งอินเตอร์เน็ตทรู โทร ".$phoneNoTidnet);
			$Sender		= "TIDNETTRUE";
			$Parameter	=	"User=$Username&Password=$Password&Msnlist=$PhoneList&Msg=$Message&Sender=$Sender";
			$API_URL		=	"http://member.smsmkt.com/SMSLink/SendMsg/index.php";

			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL,$API_URL);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch,CURLOPT_POST,1);
			curl_setopt($ch,CURLOPT_POSTFIELDS,$Parameter);

			$Result = curl_exec($ch);
			curl_close($ch);
			echo($Result);
			
			echo "ส่งข้อความไปยังเบอร์ ".$PhoneList." เรียบร้อย";
			*/
	}
	
}
?>
