<!DOCTYPE html>
<html>
<head>
<?php
/*
log file
260215 0918 : just created
*/

include('cookies.php');
include("functions/function.php");
include("headmenu.php");
mysql_select_db("tidnet_".$abvt);
$yyy = date('Y');
$ddd = date('d');
$mmm = date('m');
$boss = $_COOKIE['uid'];
$date2 = $ddd."-".$mmm."-".$yyy;
$date3 = $yyy."-".$mmm."-".$ddd;
if(isset($_GET['due'])){
	$date_select = $_GET['due'];
} else {
	$date_select = $yyy."-".$mmm;
}
$strTable = "tidnet_common.advance_money as adv join tidnet_common.master_employee as emp on emp_id=emp.id";
$star = "emp.name as name, emp.nickname as nickname, emp.id as emp_id, adv.date_created as date_create , adv.date_advance as date_advance , adv.id AS id, adv.type as type , adv.emp_pay AS emp_pay, adv.status as status, adv.pic_id AS pic, adv.total as total, adv.total_req as total_req,memoemp, adv.comment as comment , adv.date_created as date_created, adv.interestpercent as interestpercent, adv.emp_per1 as emp_per1";
if($_COOKIE['permission']<>1){
	$strCondition = "emp_id='".$_COOKIE['uid']."'";
} else {
	$strCondition = "adv.type = '1' and  adv.date_created LIKE '%".$date_select."%'";
	if($_GET['eng']<>10000000 and $_GET['eng']<>''){
		$strCondition .= " and emp_id='".$_GET['eng']."'";
	}
}

if($_GET['debug']) echo "SELECT $star FROM $strTable WHERE $strCondition  $strSort";
//die();
?>
<script>
$(document).ready(function(){

	$("select.btns").change(function(){
		val = $(this).val();
		id = $(this).attr('selid');
		selectedindexnum = $(this).prop('selectedIndex');
		if(val==1){
		$('input').attr('disabled',true);
		$('button').attr('disabled',true);
		$("#note_"+id).removeAttr('disabled');
		$("#butSelect_"+id).removeAttr('disabled');
		$("#total_"+id).removeAttr('disabled');
		$("#tax_"+id).removeAttr('disabled');
		} else {
		$("#note_"+id).removeAttr('disabled');
		$("#butSelect_"+id).removeAttr('disabled');
		}
	});
	
	$("span.dateselected").click(function(){
		due = "2016-"+$("select#monthNum option:selected").val()+"-";
		eng = $('select[name=engineerid]').val()
		window.location.replace("advancelist.php?due="+due+"&eng="+eng);
	});


	$("input.total").on("click", function(){
		id = $(this).attr('fortotal');
		$("#but_"+id).removeAttr('disabled');
    });

	$(".btnsave").on("click", function(){
		$(this).hide();
    });

});
</script>

</head>
<body>
<?php
if(checkAllow('adv_money_frm')){ /*?>
	<form method="post" action="advmoneysave.php">
		<div id="form" style="width:500px;padding-bottom:15px;">
			<fieldset>
				<legend style="color:blue;font-weight:bold;padding-left: 5px;"> บันทึกรายการเบิกเงิน</legend>
				<table class="noneborder">
				<tr>
					<td width="150" align="right">วันที่ให้
						<select name="typeadv">
							<option value="1">เบิก</option>
							<option value="2">ปรับ</option>
							<option value="3">พิเศษ</option>
						</select>
					 :</td>
					<td width="250"><?dmyListFunction('Got',date('d'));?></td>
				</tr>
				<tr>
					<td align="right">ผู้เบิก :</td>

					<td><?getemplist('empget')?></td>
				</tr>
				<tr>
					<td align="right">จำนวนเงิน :</td>
					<td><input id="totalmoney" name="totalmoney" type="text"> บาท</td>
				</tr>
				<tr>
					<td align="right" valign="top">บันทึกช่วยจำ :</td>
					<td><textarea id="memo" name="memo" style="width:250px;height:80px;"></textarea></td>
				</tr>
				<tr>
					<td></td><td align="right"><input type="submit" value=" บันทึก "></td>
				</tr>
				</table>
		   </fieldset>
		</div>
	</form>
<?
} */ }else { ?>
	<form method="post" action="advmoneysave.php">
		<div id="form" style="width:500px;padding-bottom:15px;">
			<fieldset>
				<legend style="color:blue;font-weight:bold;padding-left: 5px;"> บันทึกรายการขอเบิกเงิน</legend>
				<table class="noneborder">
				<!--<tr>
					<td width="150" align="right">วันที่ให้เบิก :</td>
					<td width="250"><?php dmyListFunction('Got'); ?></td>
				</tr>-->
				<tr>
					<td align="right">ผู้เบิก :</td>

					<td><?php echo nameofengineer($_COOKIE['uid']) ?></td>
				</tr>
				<tr>
					<td align="right">จำนวนเงิน:</td>
					<td><input id="totalmoney" name="totalmoney"> บาท</td>
				</tr>
				<tr>
					<td align="right" valign="top">เหตุผลที่ขอเบิก :</td>
					<td><textarea id="memo" name="memo" style="width: 250px; height: 80px;"></textarea></td>
				</tr>
				<tr>
					<td></td><td align="right"><input type="submit" class="btnsave" value=" บันทึก "></td>
				</tr>
				</table>
		   </fieldset>
		</div>
	</form>
<?php
}

?>
<form method="post" enctype="multipart/form-data">
<table>
<?php
if($_COOKIE['permission']==1){ ?>
		<tr class="label"><td colspan="10">รายการเบิกเงิน
		<select name="monthNum" id="monthNum">
			<?php
			if($dm=='')$dm=date('m');
			for($di=1;  $di<=12; $di++){
				$sel = "";
				if($di-$dm==0) $sel = "selected";
				$dival = $di;
				if($di<10) $dival = "0".$di;
				echo "<option ".$sel." value=".$dival.">".convmonthMini($di)."</option>";
			}
			?>
		</select>
		<span> เลือกช่าง</span>
				<select name="engineerid">
					<option value="10000000">ทุกช่าง</option>
				<?php

						$strTableel = "tidnet_common.master_employee";
						$strConditionel = "permission >'0' and dontshowat not like '%@caltopay@%' order by permission DESC";
						$emp = fncSelectConditionRecord($strTableel,$strConditionel);
						while($em = mysql_fetch_array($emp)){
								$nickname = '';
								if($em['nickname']<>''){
									$nickname = "(".$em['nickname'].")";
								}
								$selectedOption = "";
								if($selectedEmpId==$em['id']){
									$selectedOption = "selected";
								}
								echo "<option value=".$em['id']." ".$selectedOption.">".$em['name']." ".$nickname."</option>";
						}

				 ?>
			 </select>
			 <span class="button dateselected"> ขอข้อมูล </span>
	</td>
	</tr>
<?php } else { ?>
	<tr class="label"><td colspan="11">รายการเบิกเงิน</td></tr>
<?php } ?>
	<tr class="header">
		<td>วันที่</td>
		<td>ชื่อ</td>
		<td>ยอดเบิก</td>
		<td>บันทึกช่วยจำ</td>
		<td>สถานะ</td>
		<td>ยอดอนุมัติ</td>
		<td>ค่าธรรมเนียม</td>
		<td>บันทึกถึงช่าง</td>
		<td>ผู้ตรวจสอบ</td>
	</tr>
<?php
$strSort =  "ORDER BY date_created DESC";
$alladv = fncSelectStarConditionRecord($star,$strTable,$strCondition,$strSort);
while($lst = mysql_fetch_array($alladv)){ ?>


<?php
/*
    if(isset($_POST["but_".$lst['id'] ])){

    $id = $lst['id'];
    $empid = $lst['emp_id'];
    $emp_pay = $_COOKIE['uid'];
    $total_req = $lst['totalmoney'];
	$memoemp = $lst['memo'];
    $total = $_POST["total_".$id];
    $pic = $_FILES["filUpload_".$id]["name"];
    if($pic==''){
    	$pic_id = 0;
    } else {
    	$Str_file = explode(".",$pic);
    	$namePic = $id.".jpg";
    	$pic_id = $id;
    	move_uploaded_file($_FILES["filUpload_".$id]["tmp_name"],"img/advance/".$namePic);
    }
    $image = "<img src=\"http://".$abvt.".tidnet.co.th/img/advance/".$namePic."\">";

    $resultCondition = "UPDATE tidnet_common.advance_money SET date_advance = '$date3',total ='$total',emp_pay ='$emp_pay',pic_id ='$pic_id' WHERE id = $id";
  	$result = mysql_query($resultCondition);
  if($result){
  	  	$tableA = "tidnet_common.advance_money";
		$conditionA = "id=".$id;
		$queryA = fncSelectSingleRecord($tableA,$conditionA);
		$select_total_emp = explode(".",$queryA['total_req']);
		$total_req = $select_total_emp[0];
		$memoemp = $queryA['memoemp'];
		$comment = $queryA['comment'];
		$status = $queryA['status'];
	    if($status == 1){
	    	$statusTXT = "อนุมัติ  (โอนเงินเรียบร้อย)";
	    } else {
	    	$statusTXT = "ไม่อนุมัติ";
	    }
  		$table = "employee";
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
			$to = $email;
			$subject = "ขอเบิกเงิน ".$name_emp." (".$nickname_emp.") สาขา".$branch." ".$date2;
			$message = $image."<br><br>";
			$message .= "=====================================<br>";
			$message .= "เรียน ฝ่ายการเงิน / ผู้บริหาร<br>";
			$message .= "  ....ผม ".$name_emp." (".$nickname_emp.") ได้ทำการขอเบิกเงินจากคุณ หนึ่ง เป็นจำนวนเงิน ".$total_req." บาท เพื่อใช้ใน ".$memoemp." <br>
						รบกวนฝ่ายการเงิน โอนเข้าบัญชี ".$account_number." , ชื่อบัญชี ".$account_name." , ธนาคาร ".$account_branch."<br>เบื่องต้นผมได้คุยและรับรู้เรื่องเงื่อนไขของการเบิกแล้ว<br><br>";
			$message .= "จึงเรียนมาเพื่อทราบ<br><br>";
			$message .= "ช่าง ".$name_emp." (".$nickname_emp.") สาขา".$branch."<br>";
			$message .= "=====================================<br><br><br>";
			$message .= "ผลการขอเบิกเงินคือ <h2>".$statusTXT."</h2><br>";
			$message .= "บันทึกจากผู้ตรวจสอบ ".$comment."<br><br>";
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

			$strHeader .= "cc: supwin@gmail.com\r\n";
			$strHeader .= "cc: aiyara.wina2532@gmail.com\r\n";
			$strHeader .= "cc: sompob.chanintornpipat@gmail.com\r\n";
			if($check == 'm'){ //กรณีช่างมีอีเมล 2 อีเมล
			$strHeader .= "cc: ".$email2."\r\n";
			} else {
			}
			$strHeader .= "MIME-Version: 1.0".$eol;
			$strHeader .= "Content-Type: multipart/mixed; boundary=\"".$separator."\"".$eol;
			$strHeader .= "Content-Transfer-Encoding: 7bit".$eol;
			$strHeader .= "This is a MIME encoded message.".$eol;
			// message
			$strHeader .= "--".$separator.$eol;
			$strHeader .= "Content-Type: text/html; charset=\"UTF-8\"".$eol;
			$strHeader .= "Content-Transfer-Encoding: 8bit".$eol;

			// send message
			if(mail($to, $subject, $message, $strHeader)){
				header( "location: advancelist.php" );
			} else {
				echo "ไม่สามารถส่ง Email ได้ กรุณาตรวจสอบ";
			}
?>
    <script>
        window.location='advancelist.php'
    </script>
<?php
    }
    else
    {
 	echo "ผิดพลาด กรุณาตรวจสอบ".$resultCondition;
   }
}
*/

    if(isset($_POST["butSelect_".$lst['id'] ])){

    $id = $lst['id'];
    $empid = $lst['emp_id'];
    $emp_per1 = $_COOKIE['uid'];
    $status = $_POST["option_".$id];
    $tax = $_POST["tax_".$id];
    $total = $_POST["total_".$id];
    if($status == 1){
    	$statusTXT = "อนุมัติ  (รอการโอนเงิน)";
    	$resultStatus = '1';
    } else  if($status == 2) {
    	$statusTXT = "ไม่อนุมัติ";
    	$resultStatus = '2';
    } else  if($status == 3) {
    	$statusTXT = "ยกเลิก";
    	$resultStatus = '3';
    }
    $comment = $_POST["note_".$id];

 if($resultStatus!=='3'){

    	$resultCondition = "UPDATE tidnet_common.advance_money SET date_advance = '$date3',total ='$total',comment ='$comment',status ='$status',	interestpercent ='$tax',emp_per1 ='$emp_per1' WHERE id = $id";
  		$result = mysql_query($resultCondition);
 	 		if($result){
		  	  	$tableA = "tidnet_common.advance_money";
				$conditionA = "id=".$id;
				$queryA = fncSelectSingleRecord($tableA,$conditionA);
				$select_total_emp = explode(".",$queryA['total_req']);
				$total_req = $select_total_emp[0];
				$memoemp = $queryA['memoemp'];

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
					$to = $email;
					$subject = "ขอเบิกเงิน ".$name_emp." (".$nickname_emp.") ".$date2;
					$message = "=====================================<br>";
					$message .= "เรียน ฝ่ายการเงิน / ผู้บริหาร<br>";
					$message .= "  ....ผม ".$name_emp." (".$nickname_emp.") ได้ทำการขอเบิกเงินจากคุณ หนึ่ง เป็นจำนวนเงิน ".$total_req." บาท เพื่อใช้ใน ".$memoemp." <br>
								รบกวนฝ่ายการเงิน โอนเข้าบัญชี ".$account_number." , ชื่อบัญชี ".$account_name." , ธนาคาร ".$account_branch."<br>เบื่องต้นผมได้คุยและรับรู้เรื่องเงื่อนไขของการเบิกแล้ว<br><br>";
					$message .= "จึงเรียนมาเพื่อทราบ<br><br>";
					$message .= "ช่าง ".$name_emp." (".$nickname_emp.") สาขา".$branch."<br>";
					$message .= "=====================================<br><br><br>";
					$message .= "ผลการขอเบิกเงินคือ <h2>".$statusTXT."</h2><br>";
					if($resultStatus1=='1'){
					$message .= "ยอดเงินอนุมัติคือ <h2>".$total." บาท</h2><br>";
					} else {

					}
					$message .= "บันทึกจากผู้ตรวจสอบ ".$comment."<br>";
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
					$strHeader .= "cc: supwin@gmail.com\r\n";
					$strHeader .= "cc: aiyara.wina2532@gmail.com\r\n";
					$strHeader .= "cc: sompob.chanintornpipat@gmail.com\r\n";
					if($check == 'm'){ //กรณีช่างมีอีเมล 2 อีเมล
					$strHeader .= "cc: ".$email2."\r\n";
					} else {
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
						if($resultStatus=='1'){
							$date_plan = $date3;
							$list = "ขอเบิกเงิน ".$name_emp." (".$nickname_emp.")";
							$input_cash_plan = $total_req;
							$IN = explode(".",$input_cash_plan);
							if($IN[1]==''){
								$cash_plan = $IN[0].".00";
							} else {
								$cash_plan = $input_cash_plan;
							}
							$strTable = "cash";
							$strField = "date_plan,list,cash_plan,whocreated";
							$strValue = "'".$date_plan."','".$list."','".$total."','998".$id."'";
							mysql_select_db("tidnet_ryg");
							if(fncInsertRecord($strTable,$strField,$strValue)){
							mysql_select_db("tidnet_".$abvt);
							header( "location: advancelist.php" );
							} else {
							echo "ไม่สามารถทำรายการเข้าเงินสดย่อยได้ กรุณาตรวจสอบ";
							die();
							}
						}
					} else {
						echo "ไม่สามารถส่ง Email ได้ กรุณาตรวจสอบ";
					}
		?>
		    <script>
		        window.location='advancelist.php'
		    </script>
		<?php
		    }
		    else
		    {
		 	echo "ผิดพลาด กรุณาตรวจสอบ".$resultCondition;
		 	die();
		   }

	} else {
		$resultCondition = "UPDATE tidnet_common.advance_money SET comment ='$comment',status ='$status',emp_per1 ='$emp_per1' WHERE id = $id";
  		$result = mysql_query($resultCondition);
  		if($result){
		?>
		    <script>
		        window.location='advancelist.php'
		    </script>
		<?php
	} else {
		echo "ไม่สามารภยกเลิกได้ กรุณาตรวจสอบ";
		die();
	}
	}

}


	if($lst['status']==0){
		$resultAdv = "รอตรวจสอบ";
	} elseif($lst['status']==1){
		$resultAdv = "อนุมัติ";
	} elseif($lst['status']==2){
		$resultAdv = "ไม่อนุมัติ";
	} elseif($lst['status']==3){
		$resultAdv = "ยกเลิก";
	}

	$select_total_emp = explode(".",$lst['total_req']);
	$total_emp = $select_total_emp[0];

	$select_total = explode(".",$lst['total']);
	$total_advance = $select_total[0];

	$memo_emp = $lst['memoemp'];
	/*
	$selectdate = explode(" ",$lst['date_created']);
	$date = $selectdate[0];
	*/
	$time = 0;
	$dateC = $lst['date_advance'];
	if($dateC == '0000-00-00'){
		$selectdate = explode(" ",$lst['date_created']);
		$dateC = $selectdate[0];
		$date = convdateMini($dateC,$time);
	} else {
		$date = convdateMini($dateC,$time);
	}

	$table = "tidnet_common.master_employee";
	$condition = "id=".$lst['emp_per1'];
	$query = fncSelectSingleRecord($table,$condition);
	$name_emp1 = $query['nickname'];

	$table2 = "tidnet_common.master_employee";
	$condition2 = "id=".$lst['emp_pay'];
	$query2 = fncSelectSingleRecord($table2,$condition2);
	$name_emp2 = $query2['nickname'];

	$select_total = explode(".",$lst['total']);
	$total_advance1 = $select_total[0];

	if($_COOKIE['permission']==1){
			if($resultAdv=='รอตรวจสอบ'){
				if($boss==1 or $boss==122){
				$options = "<td><select selid=\"".$lst['id']."\" class=\"btns\" name = \"option_".$lst['id']."\" id = \"option_".$lst['id']."\">
						<option value=\"0\" >รออนุมัติ</option>
						<option value=\"1\" style=\"background-color:green;color:#fff\">อนุมัติ</option>
						<option value=\"2\" style=\"background-color:brown;color:#fff\">ไม่อนุมัติ</option>
						<option value=\"3\" style=\"background-color:black;color:#fff\">ยกเลิก</option>
					    </select></td>";
				$total_money = "<td align=\"center\"><div style=\"width: 80px;\"><input type=\"text\" size=\"5\" class=\"total\" disabled=\"true\" name=\"total_".$lst['id']."\" id=\"total_".$lst['id']."\" value=\"".$lst['total_req']."\"></div></td>";
				}else{
					$options = "<td align=\"center\" style=\"color:blue;\">".$resultAdv."</td>";
					$total_money = "<td align=\"center\"><div style=\"width: 100px;\">".$lst['total']." บาท</div></td>";
				}
			} else if ($resultAdv=='อนุมัติ') {
				$options = "<td align=\"center\" style=\"background-color:green;color:#fff\"><div style=\"width: 100px;\">".$resultAdv."</div></td>";
					$select_total_mo = explode(".",$lst['total']);
					$total_money2 = $select_total_mo[0];
					$total_money = "<td align=\"right\">".$total_money2."</td>";
			} else if ($resultAdv=='ไม่อนุมัติ') {
				$options = "<td align=\"center\" style=\"background-color:brown;color:#fff\">".$resultAdv."</td>";
				$total_money = "<td align=\"center\"></td>";
			} else if ($resultAdv=='ยกเลิก') {
				$options = "<td align=\"center\" style=\"background-color:black;color:#fff\">".$resultAdv."</td>";
				$total_money = "<td align=\"center\"></td>";
			}

			if($lst['interestpercent']=='0.00' and $resultAdv=='รอตรวจสอบ' ){
				$tax = "<td align=\"center\"><div style=\"width: 100px;\">
				<select name = \"tax_".$lst['id']."\" id = \"tax_".$lst['id']."\" disabled=\"true\">
                <option value=\"0\">ไม่คิดภาษี</option>
                <option value=\"0.01\">1%</option>
                <option value=\"0.03\">3%</option>
                <option value=\"0.05\">5%</option>
                <option value=\"0.07\" selected>7%</option>
      			</select><div></td>";
			} else if($lst['interestpercent']=='0.00' and $resultAdv!=='รอตรวจสอบ'){
				$tax = "<td align=\"center\"></td>";
			} else if($lst['interestpercent']!=='0.00' and $resultAdv!=='รอตรวจสอบ'){
				$tax = "<td align=\"center\">".$total_money2*$lst['interestpercent']." บาท</td>";
			}

			if($lst['total']!=='0.00'){
				$total_advance = "<td align=\"center\"><div style=\"width: 100px;\">".$total_advance1." บาท</div></td>";
			} else {
				$total_advance = "<td align=\"center\"><input class=\"total\" size=\"6\" type=\"text\" name=\"total_".$lst['id']."\" id=\"total_".$lst['id']."\" fortotal=\"".$lst['id']."\"></input>
								<button  disabled=\"true\" name=\"but_".$lst['id']."\" id=\"but_".$lst['id']."\" butid=\"".$lst['id']."\">บันทึก</button>
								</td>";
			}

			if($lst['pic']=='0'){
				$pic = "<td><input type=\"file\" name=\"filUpload_".$lst['id']."\" id=\"filUpload_".$lst['id']."\"></td>";
			} else {
				$pic = "<td align=\"center\">
				<button style=\"width:100px;height:25px\"><a href=\"img/advance/".$lst['id'].".jpg\" target=\"_blank\" style=\"color:black\">ดูรูปภาพ</a></button></td>";
				//<img id=\"myImg_".$lst['id']."\" src=\"img/advance/".$lst['id'].".jpg\" width=\"100\" for=\"".$lst['id']."\" height=\"50\">
			}

			if($lst['comment']!==''){
				$comment = "<td>".$lst['comment']."</td>";
			} else {
				$comment = "<td><input type=\"text\" disabled=\"true\" name=\"note_".$lst['id']."\" id=\"note_".$lst['id']."\"></input>
							<button disabled=\"true\" class=\"confirm\"  name=\"butSelect_".$lst['id']."\" id=\"butSelect_".$lst['id']."\" butSelectid=\"".$lst['id']."\">บันทึก</button></td>";
			}
			$emp1 = "<td align=\"center\">".$name_emp1."</td>";
			$emp_pay = "<td align=\"center\">".$name_emp2."</td></tr>";


	} else if($_COOKIE['permission']!==1){

			if($resultAdv=='รอตรวจสอบ'){
				$options = "<td align=\"center\" style=\"color:blue;\">".$resultAdv."</td>";
				$tax = "<td align=\"center\">".$lst['interestpercent']."</td>";
				$total_money = "<td align=\"center\"></td>";

			} else if ($resultAdv=='อนุมัติ') {
				$options = "<td align=\"center\" style=\"background-color:green;color:#fff\">".$resultAdv."</td>";
					$select_total_mo = explode(".",$lst['total']);
					$total_money2 = $select_total_mo[0];
					$total_money = "<td align=\"center\">".$total_money2." บาท</td>";
				$total_money = "<td align=\"center\"><div style=\"width: 100px;\">".$lst['total']." บาท</div></td>";
			} else if ($resultAdv=='ไม่อนุมัติ') {
				$options = "<td align=\"center\" style=\"background-color:brown;color:#fff\">".$resultAdv."</td>";
				$total_money = "<td align=\"center\"></td>";
			} else if ($resultAdv=='ยกเลิก') {
				$options = "<td align=\"center\" style=\"background-color:black;color:#fff\">".$resultAdv."</td>";
				$total_money = "<td align=\"center\"></td>";
			}

			if($lst['total']!=='0.00'){
				$total_advance = "<td align=\"center\"><div style=\"width: 100px;\">".$total_advance." บาท</div></td>";
			} else {
				$total_advance = "<td style=\"color:red\">รอการโอนเงิน</td>";
			}

			if($lst['pic']=='0'){
				$pic = "<td></td>";
			} else {
				$pic = "<td align=\"center\">
				<button style=\"width:100px;height:25px\"><a href=\"
				\img/advance/".$lst['id'].".jpg\" target=\"_blank\" style=\"color:black\">ดูรูปภาพ</a></button></td>";
			}

			if($lst['interestpercent']!=='0.00'){
				$tax = "<td align=\"center\">".$total_money2*$lst['interestpercent']." บาท</td>";
			} else {
				$tax = "<td align=\"center\"></td>";
			}

			$comment = "<td align=\"center\">".$lst['comment']."</td>";
			$emp1 = "<td align=\"center\">".$name_emp1."</td>";
			$emp_pay = "<td align=\"center\">".$name_emp2."</td></tr>";
		}


if($_COOKIE['permission']==1){
		echo "<tr><td><div style=\"width: 75px;\">".$date."</div></td>
		<td><div style=\"width: 150px;\">".$lst['name']." [".$lst['nickname']."]</div></td>
		<td><div style=\"width: 100px;\">".$total_emp." บาท</div></td>
		<td>".$memo_emp."</td>";
		if($resultAdv=='รอตรวจสอบ'){
		echo $options."
			".$total_money."
			".$tax."
			".$comment."
			".$emp1;
		} else if ($resultAdv=='ไม่อนุมัติ' OR $resultAdv=='ยกเลิก') {
		echo $options."
		".$total_money."
		".$tax."
		".$comment."
		".$emp1;
		} else if ($resultAdv=='อนุมัติ') {
		echo $options."
		".$total_money."
		".$tax."
		".$comment."
		".$emp1;
		/*
		".$pic."
		".$total_advance."
		".$emp_pay;
		*/
		}


} else {
		echo "<tr><td><div style=\"width: 75px;\">".$date."</div></td>
		<td><div style=\"width: 150px;\">".$lst['name']." [".$lst['nickname']."]</div></td>
		<td><div style=\"width: 100px;\">".$total_emp." บาท</div></td>
		<td>".$memo_emp."</td>";
		if($resultAdv=='รอตรวจสอบ'){
		echo $options;
		} else if ($resultAdv=='ไม่อนุมัติ' OR $resultAdv=='ยกเลิก') {
		echo $options."
		".$total_money."
		".$tax."
		".$comment."
		".$emp1;
		} else if ($resultAdv=='อนุมัติ') {
		echo $options."
		".$total_money."
		".$tax."
		".$comment."
		".$emp1;
		}
}

}
?>

</table>
</form>
