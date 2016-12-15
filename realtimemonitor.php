<?php
/*
Log file
290815 0948 : just created
*/

include('cookies.php');
include("functions/function.php");
include("../com_source/headmenu.php");
include("../com_source/config.php");
date_default_timezone_set("Asia/Bangkok");
//echo date('H:i:s', time());
/*
$to_time = strtotime(date('H:i:s', time()));
$from_time = strtotime("10:00:00");
echo round(abs(strtotime(date('H:i:s', time())) - strtotime(date('H:i:s', time()))) / 60,2). " minute<br>";

echo round(abs(strtotime('18:00:00') - strtotime(date('H:i:s', time()))) / 60,2);
*/


$d = date('d');

(isset($_GET['due'])? $_GET['due'] : $d);

if(isset($_GET['due'])){
	$due = $_GET['due'];
	$expd = explode("-",$_GET['due']);
	$d = $expd[2];
}else{
	$due = date('Y-m')."-".$d;
}

/*
function returntypejob($workaction,$ordertype,$chgaddflg,$catvflg,$jobname,$doctype,$bundle){
	if($workaction=='F'){
		$typejob = "Dis";
	}else if($workaction=='T' and $ordertype=='I' and 	$chgaddflg=='N'){
		$typejob = "Net";
		if($catvflg=='Y'){
			$typejob .= "+TV";
		}
	}else if($workaction=='T' and $ordertype=='C' and 	$chgaddflg=='N'){
		$typejob = "Chg Mod";
	}else if($workaction=='T' and $ordertype=='C' and 	$chgaddflg=='Y'){
		$typejob = "Chg Addr";
	}else if($jobname=='FTTX' and $ordertype=='I' and   $doctype=='HSI'){
		$typejob = "<span style=\"color:#F88017;\">FTTx</span>";
		if($bundle <> ""){
			$typejob .= "<span style=\"color:#F88017;\">+TV</span>";
		}
	}else if ($jobname=='FTTX' and $ordertype=='D' and   $doctype=='HSI'){
		$typejob = "<span style=\"color:#F88017;\">Dis FTTx</span>";
	}else if ($jobname=='FTTX' and $ordertype=='C' and   $doctype=='FIBERTV'){
		$typejob = "<span style=\"color:#F88017;\">Dis/New FIBERTV</span>";
	}else if ($jobname=='FTTX' and $ordertype=='C' and   $doctype=='FLP'){
		$typejob = "<span style=\"color:#F88017;\">Chg Add FTTx</span>";
	}
	return $typejob;
}
*/

?>
<meta http-equiv="refresh" content="600">

<script src="jquery/jquery.js"></script>
<script>
$(document).ready(function(){
	$(".selectstatusobject").change(function(){
		$(".spPhone").show();
		$(".btnSave").hide();
		$(".txtCRM").hide();
		id = $(this).attr('for');
		$("#phone_"+id).hide();
		$("#CRMtxt_"+id).show();
		$("#bsave_"+id).show();
		$("#CRMtxt_"+id).focus();
	});

	$(".followbtnsave").click(function(){
		 idfor = $(this).attr('for');
		 brand = $("input#"+idfor).attr('brandkey');
		 jid = $("input#"+idfor).attr('jid');
		 note = $("input#"+idfor).val();
		 engid = $("input#"+idfor).attr('engid');
		 if(note==''){
			 alert('บันทึกโดยไม่มีข้อความไม่ได้');
			 return false;
		 }
		 $(this).hide();
 		$.ajax({
 		   type: "POST",
 		   url: "memo_workingfollowup.php",
 		   cache: false,
 		   data: "brand="+brand+"&jid="+jid+"&note="+note+"&engid="+engid,
 		   success: function(msg){
		 			if(msg.indexOf("login_frm.php") > -1){
		 				window.location.replace("login_frm.php");
		 				return false;
		 			}
					//$(msg).insertAfter("input#"+idfor);
					$("div#list_"+idfor).append(msg);
					alert('บันทึกเรียบร้อย');
					$("input#"+idfor).val('');
					$(this).show();
 		   }
 	   });
	});

	$(".btndispatch").click(function(){
		id = $(this).attr('for');
		idmemo = $(this).attr('idmemo');
		key = $(this).attr('key');
		$("#bsave_"+id).hide();
		disname = $("#namedispatch_"+id).val();
		//alert("id="+id+" idmemo="+idmemo+" disname="+disname);
		//return false;
		$.ajax({
		   type: "POST",
		   url: "returnjobtodispatch.php",
		   cache: false,
		   data: "jid="+id+"&disname="+disname+"&idmemo="+idmemo+"&branch="+key,
		   success: function(msg){
			if(msg.indexOf("login_frm.php") > -1){
				window.location.replace("login_frm.php");
				return false;
			}
			//$("#show").html(msg);
			$(".spPhone").show();
			//$("#CRMtxt_"+id).show();
			$("#namedispatch_"+id).val("");
			$("#memotext_"+id).hide();
		   }
	   });

	});

	$(".btnSave").click(function(){
		id = $(this).attr('for');
		comment = $("#CRMtxt_"+id).val();
		statusNo = $("#status_"+id).val();
		status = $("#status_"+id).text();
		alert(id+" "+comment+" "+statusNo+" "+status[statusNo-31]);
		$.ajax({
		   type: "POST",
		   url: "memoappointmentrec.php",
		   cache: false,
		   data: "resCnft="+statusNo+"&jid="+id+"&duedate=<?php echo $due?>&commt=["+status[statusNo-31]+"]"+comment+"&statustxt="+status[statusNo-31],
		   success: function(msg){
			if(msg.indexOf("login_frm.php") > -1){
				window.location.replace("login_frm.php");
				return false;
			}
			//$("#show").html(msg);
			if(status[statusNo-31]=='R'){
				$("#tr_"+id).css("color", "#EC49E1");
				$("#phone_"+id).html(comment);
			}
			if(status[statusNo-31]=='C'){
				$("#tr_"+id).css("color", "#fff");
				$("#phone_"+id).html(comment);
			}
			if(status[statusNo-31]=='X') $("#tr_"+id).css("color", "green");
			if(status[statusNo-31]=='D') $("#tr_"+id).css("color", "black");


			$(".spPhone").show();
			$(".btnSave").hide();
			$(".txtCRM").val("");
			$(".txtCRM").hide();

		   }
	   });
	});

	$(".chgR2D").click(function(){
    jid = $(this).attr("for");
    brand = $(this).attr('brandkeyR2D');
    window.open("updatestatusR2D.php?jid="+jid+"&brand="+brand,"List","scrollbars=no, resizable=no, width=450, height=350");
    //$("span#sp_"+id).remove();
 	});

});
</script>
<div id="show"></div>
<?php
$d = date('d');
if(isset($_GET['due'])){
	$due = $_GET['due'];
	$expd = explode("-",$_GET['due']);
	$d = $expd[2];
}else{
	$due = date('Y-m')."-".$d;
	$tm = $d+1;
	$tomollow = date('Y-m')."-".$tm;
}

echo "<table width=\"80%\">";

$tronStatus = array('D','R','X','C');
//$tomollow = date('d')+1;
foreach ($allBranch as $key => $value){
	echo "<tr style=\"background-color:#064A0B\"><td width=\"15%\" colspan=\"8\" style=\"color:#fff\"> -: ".strtoupper($key)." :-  <a href=\"realtimemonitor.php?due=".$tomollow."\">พรุ่งนี้</a></td></tr>";
	$objDB = mysql_select_db("tidnet_".$key);
	mysql_query("set character set utf8");
	if(@mysql_ping()){
		$strTable = "tidnet_".$key.".jobassign";
		$strCondition = "job_status NOT IN ('Q','N','P','Z') and conf_date='".$due."'";
		$strSort = "order by assigned_eng,conftime";
		if($_GET['debug']) echo "111111111 SELECT * FROM $strTable WHERE $strCondition $strSort";
		$jobs = fncSelectConditionRecord($strTable,$strCondition,$strSort);
	}
	$y = 1;
	while($job = mysql_fetch_array($jobs)){
		$memoAp = fncSelectConditionRecord('memo_appointment',"jid='".$job['jid']."'",'order by memo_date_time');
		$memoTxt = '';
		while($memoA = mysql_fetch_array($memoAp)){
			$memoTxt .= "[".$memoA['memo_date_time']."] ".$memoA['memotxt']."\n\n";
		}
		if($job['assigned_eng']==0) $bgcolor = "#fff";

		if($tempEng <> $job['assigned_eng']){
			$tempEng = $job['assigned_eng'];

			echo "<tr style=\"background-color:#286CBF\"><td colspan=\"8\">";

			if($job['assigned_eng']<>0){
				echo "<span style=\"color:#fff;weight:900;\">".nameofengineer($job['assigned_eng'])." (".nameofengineer($job['assigned_eng'],1).")</span>";
			}else{
				echo "<span style=\"color:#fff;weight:900;\">ยังไม่ได้จ่ายงานให้ช่าง</span>";
			}
			echo "</td></tr>";
		}

		$hNo = explode(":",$job['conftime']);
		$diff = $hNo[0] - date('H', time());
		$color = "";
		$txtphoneorReturn = "<span id=\"phone_".$job['jid']."\" class=\"spPhone\">".$job['cust_phone']."</span>";
		$late = '';
		if($job['job_status']=='D'){

			if($diff==1 or $diff==0){
				$color = 'blue';
			}else if(($diff<0)){
				$color = 'red';
				$late = "[สาย]";
			}
		}else if($job['job_status']=='R'){
			$color = "#EC49E1";

			$table = "memo_appointment";
			$condition = "return_status=1 and jid='".$job['jid']."'";
			$orderby = "order by memo_date_time";

			if(fncCountRow($table,$condition)>0){
				if($_GET['debug']) echo "SELECT * FROM $table WHERE $condition  $orderby";
				$retRes = mysql_fetch_array(fncSelectConditionRecord($table,$condition,$orderby));
				$txtphoneorReturn = "<span hidden id=\"phone_".$job['jid']."\" class=\"spPhone\">".$job['cust_phone']."</span>";
				$txtphoneorReturn .= "<span id=\"memotext_".$job['jid']."\">".$retRes['memotxt'];
				$txtphoneorReturn .= " [ชื่อดิสแพท : <input type=\"text\" id=\"namedispatch_".$job['jid']."\" for=\"".$job['jid']."\" class=\"namedispatch\"><button key=\"".$key."\" id=\"btndispatch_".$job['jid']."\" for=\"".$job['jid']."\" idmemo=\"11".$retRes['memo_date_time']."\" class=\"btndispatch\">save</button></span>";
			}

		}else if($job['job_status']=='X'){
			$color = "green";
		}else if($job['job_status']=='C'){
			$color = "#fff";
		}



		if($job['job_status']<>'R'){
			$statusSelect = $key." : [".$job['job_status']."]";
		}else{
			/*
			$statusSelect = "<select id=\"status_".$job['jid']."\" for=\"".$job['jid']."\" class=\"selectstatusobject\" name=\"statusSelect\">";
			for($is=0; $is<count($tronStatus); $is++){
				$selected = '';
				$valStatus = $is+31;
				if($tronStatus[$is]==$job['job_status']) $selected = 'selected';
				$statusSelect .= "<option ".$selected." value='".$valStatus."'>".$tronStatus[$is]."</option>";
			}
			$statusSelect .= "</select>";
			*/
			$statusSelect = "<span class=\"button chgR2D\" for=\"".$job['jid']."\" brandkeyR2D=\"".$key."\" style=\"font-size:13px;\"> [R] </button>";
		}
		$t = "workingfollowup";
		$c = "jid='".$job['jid']."'";
		$s = "order by notetime";
		$followList = fncSelectConditionRecord($t,$c,$s);
		//echo "SELECT * FROM $t WHERE $c  $s";
		$listfl = "";
		while($flist = mysql_fetch_array($followList)){
			$listfl .= $flist['notetime']." ".$flist['note']." <span style=\"color:red;font-size:10px;\">*".$flist['whodid']." [ช่าง:".nameofengineer($flist['emp_id'],1)."]</span><br>";
		}


		$typejob = returntypejob($job['work_action'],$job['SO_CCSS_ORDER_TYPE'],$job['SO_CHG_ADDR_FLG'],$job['CATV_FLG'],$job['jobname'],$job['sodoctype'],$job['bundle']);

		echo "<tr id=\"tr_".$job['jid']."\" style=\"color:".$color."\"><td colspan=\"3\">".$key." [".$y."]<br><h6>".$job['create_time']."</h6></td><td>".$typejob."<br>".$job['conftime']." ".$late."<br><h6>[".$job['handler_id']."/".$job['handler_name']."]<h6>
		</td><td width=\"20%\">".$job['cust_name']."<br>".$job['circuit']."
		<br>".$job['bundle']."</td><td style=\"text-align: center;\" title=\"".$memoTxt."\">".$statusSelect."</td><td width=\"20%\">".$txtphoneorReturn."
		<input id=\"CRMtxt_".$job['jid']."\" class=\"txtCRM\" hidden type=\"text\"> <button for=\"".$job['jid']."\" class=\"btnSave\" id=\"bsave_".$job['jid']."\" hidden>save</button></td>
		<td width=\"30%\"><input type=\"text\" id=\"".$key."_".$y."\" jid=\"".$job['jid']."\" brandkey=\"".$key."\" engid=\"".$job['assigned_eng']."\">
		<span for=\"".$key."_".$y."\" class=\"button followbtnsave\"> บันทึก </span><div id=\"list_".$key."_".$y."\">".$listfl."</div></td>";

		$y++;
	}
	echo "<tr><td colspan=\"7\">.</td></tr>";
}

?>
