<?php
include('cookies.php');
include('functions/function.php');
include('db_function/phpMySQLFunctionDatabase.php');
mysql_query("SET NAMES  UTF8");

$jid = $_POST['jid'];
$no = $_POST['no'];
$due = $_POST['due'];

		$strTable = "memo_sendsms";
		$strField = "datetime,emp_id,jid,due_date,tel";
		$strValue = tidnetNow().",
				    '".$_COOKIE['uid']."',
				    '".$jid."',
				    '".$due."',
				    '".$no."'";
//echo " << INSERT INTO $strTable ($strField) VALUES ($strValue) ";
if(!fncInsertRecord($strTable,$strField,$strValue)){
			mysql_query(ROLLBACK);
			die('ยกเลิกกระบวนการ');
		} 

			$strTable = "jobassign";
			$strCondition = "jid='".$jid."'";
			$job = fncSelectSingleRecord($strTable,$strCondition);
			$circuit = $job['circuit'];
			$custName =  $job['cust_name'];
			$phoneNoTidnet = '0952780269';

			//เปลี่ยนเป็นเบอร์ลูกค้าด้วย
			$PhoneList	= $no;
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
					
?>
