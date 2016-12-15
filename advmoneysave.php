<?php
/*
log file
260215 0918 : just created 
*/	
include('cookies.php');
include("functions/function.php");
include("db_function/phpMySQLFunctionDatabase.php");
include('namebranch.php');
mysql_select_db("tidnet_".$abvt);
$yy = date('Y');
$dd = date('d');
$mm = date('m');
$date = $dd."-".$mm."-".$yy;

$dateEvent = $_POST['yearGotNum'].'-'.$_POST['monthGotNum'].'-'.$_POST['dateGotNum'];
$typeAdv = 1;
$empid = $_COOKIE['uid'];
$total_req2 = $_POST['totalmoney'];
$S = explode(",",$total_req2);
	if($S[1]==''){
		$total_req = $total_req2;
	} else {
		$total_req = $S[0].$S[1];
	}
$memoemp = $_POST['memo'];
$status = 0;
$pic_id=0;
$strTable = "tidnet_common.advance_money";
$strField = "date_created,type,date_advance,emp_id,total_req,total,memoemp,comment,status,pic_id";
$strValue = tidnetNow().",'".$typeAdv."','".$dateEvent."','".$empid."','".$total_req."','".$total."','".$memoemp."','".$memo."','".$status."','".$status_pictureslip."'";

$table = "tidnet_common.master_employee";
$condition = "id=".$empid;
$query = fncSelectSingleRecord($table,$condition);
$name_emp = $query['name'];
$nickname_emp = $query['nickname'];
$emp_email = $query['email'];
$emp2_email = explode(",",$emp_email);	
$email = $emp2_email[0];
$email2 = $emp2_email[1];
$check = substr($email2, -1);
$account_number = $query['account_number'];
$account_name = $query['account_name'];
$account_branch = $query['account_branch'];
//echo "e1 = ".$e1."<br> e2 = ".$e2."<br> e3 = ".$e3;

//echo $name_emp."<br>".$nickname_emp."<br>".$email;

//ส่งอีเมล์
  	if(fncInsertRecord($strTable,$strField,$strValue)){
		$to = "supwin@gmail.com"; 
		$subject = "ขอเบิกเงิน ".$name_emp." (".$nickname_emp.") ".$date;
		$message = "=====================================<br>";
		$message .= "เรียน ฝ่ายการเงิน / ผู้บริหาร<br>";
		$message .= "  ....ผม ".$name_emp." (".$nickname_emp.") ได้ทำการขอเบิกเงินจากคุณ หนึ่ง เป็นจำนวนเงิน ".$total_req." บาท เพื่อใช้ใน ".$memoemp." <br>
					รบกวนฝ่ายการเงิน โอนเข้าบัญชี ".$account_number." , ชื่อบัญชี ".$account_name." , ธนาคาร ".$account_branch."<br>เบื่องต้นผมได้คุยและรับรู้เรื่องเงื่อนไขของการเบิกแล้ว<br><br>";
		$message .= "จึงเรียนมาเพื่อทราบ<br><br>";
		$message .= "ช่าง ".$name_emp." (".$nickname_emp.") สาขา".$branch."<br>";
		$message .= "=====================================<br><br><br>";
		$message .= "<h3>ไอยรา วินา<br>";
		$message .= "หจก.ติดเน็ต<br>";
		$message .= "090-9945409</h3>";
		//echo $message;

		// a random hash will be necessary to send mixed content
		$separator = md5(time());
		// carriage return type (we use a PHP end of line constant)
		$eol = PHP_EOL;

		// main header (multipart mandatory)

		$strHeader .= "From: Mr.Tidnet system<system@tidnet.co.th>\r\n";
		$strHeader .= "Reply-To: supwin@gmail.com\r\n";
		/*
		$strHeader .= "cc: aiyara@tidnet.co.th\r\n";
		$strHeader .= "cc: sukanya@tidnet.co.th\r\n";
		$strHeader .= "cc: wanwipa@tidnet.co.th\r\n";
		*/
		$strHeader .= "cc: aiyara.wina2532@gmail.com\r\n";
		$strHeader .= "cc: sompob.chanintornpipat@gmail.com\r\n";

		if($check == 'm'){ //กรณีช่างมีอีเมล 2 อีเมล
		$strHeader .= "cc: ".$email."\r\n";
		$strHeader .= "cc: ".$email2."\r\n";
		} else {
		$strHeader .= "cc: ".$email."\r\n";
		}

		$strHeader .= "MIME-Version: 1.0".$eol;
		$strHeader .= "Content-Type: multipart/mixed; boundary=\"".$separator."\"".$eol; 
		$strHeader .= "Content-Transfer-Encoding: 7bit".$eol;
		$strHeader .= "This is a MIME encoded message.".$eol;
		// message
		$strHeader .= "--".$separator.$eol;
		$strHeader .= "Content-Type: text/html; charset=\"UTF-8\"".$eol;
		$strHeader .= "Content-Transfer-Encoding: 8bit".$eol;
		$strHeader .= $message.$eol;

		// send message
		if(mail($to, $subject, "", $strHeader)){
			header( "location: advancelist.php" );
			exit(0);
		} else {
			echo "ไม่สามารถส่ง Email ได้ กรุณาตรวจสอบ";
		}
	}
?>