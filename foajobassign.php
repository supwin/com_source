<?php
include('cookies.php');
header ("Last-Modified: " . gmdate ("D, d M Y H:i:s") . " GMT");
include("functions/function.php");
include("headmenu_mobile.php");
?>
<html>
<head>

<style type="text/css">
body,td,th {
	font-size: 16px;
}
#select2,#select3{
	jid = 0;
    display: none;
}
</style>

<script type="text/javascript">
//ส่งค่า data-id ในปุ่มคืนงานให้ใน modal
$(document).on("click", ".btn-block.btn-info.btn-lg.return", function () {
     var jid = $(this).data('id');
     $(".modal-body #jid").val( jid );
});

$(document).on("click", ".btn-block.btn-danger.btn-lg.closejob", function () {
     var jid = $(this).data('id');
     $(".modal-body #jid").val( jid);
});


//เมื่อเลือกชนิดคืนงานเป็นเลื่อนนัดจะมี input วันที่
$(function () {
    $(".jobreturn.form-control").on("change",function(){
        var i_select=$(this).val();
         if(i_select=="201"){
            $(".css_more").show();
         } else {
      		$(".css_more").hide();
    	 }
    });

    $('#return').on('hidden.bs.modal', function (e) {
	    $(this).find("select,input#note,input#file").val('').end()
	    $(this).find("input#rejdate").val(0).end()
    });
 });

$(document).on("click", ".showcomment", function () {
	var jid = $(this).attr('for');
	  div1 = document.getElementById("due_"+jid);
	  div2 = document.getElementById("all_"+jid);
		  if(div1.style.display == "none"){
			    div1.style.display = "block";
			    div2.style.display = "none";
			    $(".showcomment").html('<span class="glyphicon glyphicon-collapse-down"></span> คลิกเพื่อดูประวัตินัดทั้งหมด');
		  }else{
			    div1.style.display = "none";
			    div2.style.display = "block";
			    $(".showcomment").html('<span class="glyphicon glyphicon-collapse-up"></span> ประวัตินัดทั้งหมด');
		  }
});
/*
$(document).ready(function() {
	$("input.btn.btn-info.submit").attr('disabled', 'disabled');
	$("form").keyup(function() {
	// To Disable Submit Button
	$("input.btn.btn-info.submit").attr('disabled', 'disabled');
	// Validating Fields
	jid = $("input#jid.returnjob").val();
		var rej = $("select#"+jid+" option:selected").val();
		var note = $("#note").val();
		var rejdate = $("#rejdate").val();
	if ( note !== "" ) {
		// To Enable Submit Button
		$("input.btn.btn-info.submit").removeAttr('disabled');
		}
	});
});
*/

$(document).ready(function(){
	 $("span.badge.empSms").click(function(){
		$(this).hide();
		jid = $(this).attr('for');
		no  = $(this).attr('no');
		due = $(this).attr('due');
		 if (!confirm('คุณต้องการที่จะส่ง SMS ไปที่เบอร์ '+no+ ' ใช่หรือไม่?')){
		 	$(this).show();
		 	return false;
		 } 
		//alert(jid+no);
		$.ajax({
		   type: "POST",
		   url: "memoengsendsms.php",
		   cache: false,
		   data: "jid="+jid+"&no="+no+"&due="+due,
		   success: function(msg){
		   		 $.ajax({ 
		   		 	 type: "POST",
					   url: "smsimage.php",
					   cache: false,
					   data: "jid="+jid,
		   		 });
		   		alert("ส่ง SMS ถึงลูกค้าหมายเลขโทรศัพท์ "+no+ " เรียบร้อยแล้วค่ะ");
			if(msg.indexOf("login_frm.php") > -1){
				window.location.replace("login_frm.php");
				return false;
			}

		   }
	   });
	});

	  $("input#modsn").blur(function(){
		sn = $(this).val();
		$.ajax({
		   type: "POST",
		   url: "check_modsn.php",
		   cache: false,
		   data: "sn="+sn,
		   success: function(txt){
		   	if(txt=='1'){
		   		$("input#modsn").add().css({"background-color": "green", "color": "white"});
		   		$("input#catvsn").attr("disabled", false);
		   	} else if (txt=='2'){
		   		alert("Serial "+sn+" อยู่ในระหว่างการโอน กรุณาตรวจสอบ");
		   		$("input#modsn").val('');
		   		window.open("stockeqm.php", '_blank');
		   	} else if (txt=='3'){
		   		alert("Serial "+sn+" ไม่อยู่ในสต๊อกของท่าน กรุณาตรวจสอบ");
		   		$("input#modsn").val('');
		   	} else if (txt=='0'){
		   		alert("กรุณาใส่เลข Serial");
		   		$("input#modsn").val('');
		   	}

		   }
			});
		});

	  	$("input#catvsn").blur(function(){
		sn = $(this).val();
		$.ajax({
		   type: "POST",
		   url: "check_modsn.php",
		   cache: false,
		   data: "sn="+sn,
		   success: function(txt){
		   	if(txt=='1'){
		   		$("input#catvsn").add().css({"background-color": "green", "color": "white"});
		   	} else if (txt=='2'){
		   		alert("Serial "+sn+" อยู่ในระหว่างการโอน กรุณาตรวจสอบ");
		   		$("input#catvsn").val('');
		   		window.open("stockeqm.php", '_blank');
		   	} else if (txt=='3'){
		   		alert("Serial "+sn+" ไม่อยู่ในสต๊อกของท่าน กรุณาตรวจสอบ");
		   		$("input#catvsn").val('');
		   	} else if (txt=='0'){
		   		alert("กรุณาใส่เลข Serial");
		   		$("input#catvsn").val('');
		   	}

		   }
			});
		});

			$(".btn-block.btn-danger.btn-lg.closejob").click(function(){
		        jjid = $(this).attr("for");
					$.ajax({
					   type: "POST",
					   url: "foa_typejob.php",
					   cache: false,
					   data: "jjid="+jjid,
					   success: function(txt){
						   	if(txt=='Dis'){
						   	$("div#modem").hide();
						   	$("div#catv").hide();
						  	$("div#option1").hide();
						   	<?php  ?>
						   	} else if (txt=="Net+TV"){
							$("div#option1").hide();
						   	} else if (txt=="Net"){
							$("div#catv").hide();
							$("div#option1").hide();
						   	} else if (txt=="FTTx+TV"){

						   	} else if (txt=='FTTx'){
						   	$("div#catv").hide();
						   	$("button#bar2").hide();
						   	}
									$.ajax({
									   type: "POST",
									   url: "foa_custname.php",
									   cache: false,
									   data: "jjid="+jjid,
									   success: function(txt){
										document.getElementById("demo").innerHTML = txt;
									   }
									});
					   }
					});

				});

				$(".btn-block.btn-info.btn-lg.return").click(function(){
		        jjid = $(this).attr("for");
						$.ajax({
						type: "POST",
						url: "foa_custname.php",
						cache: false,
						data: "jjid="+jjid,
						success: function(txt){
							document.getElementById("demo2").innerHTML = txt;
						}
					});
				});

				$("button.close").click(function(){
							$("div#modem").show();
						   	$("div#catv").show();
						  	$("div#option1").show();
						  	$("input#catvsn").attr("disabled", true);
						  	$("input#modsn").val('');
						  	$("input#catvsn").val('');
						  	$("select#bcable").val('0');
				});

});

function getGeo(){
					if (navigator.geolocation) {
						navigator.geolocation.watchPosition(showPosition);
					} else {
						alert("Geolocation is not supported by this browser.");
					}
				}

				function showPosition(position) {
					//alert('test');
				    //"Latitude: " + position.coords.latitude + "<br>Longitude: " + position.coords.longitude;
					$.ajax({
					   type: "POST",
					   url: "savelatlng.php",
					   cache: false,
					   data: "lat="+position.coords.latitude+"&lng="+position.coords.longitude,
					   success: function(msg){
							$("#show").html(msg);
						}
					});
				}

				// เก็บค่า lat,long ณ ปปัจจุบันที่ช่างที่เปิดใช้งานหน้า foajobassign.php
<?php
	if($_COOKIE['permission']==4 and $_COOKIE['superuser']!=1){
		?>
		getGeo();
		<?php
	}
?>
</script>
</head>
<?php
function returnForm($job_id,$job_status){
    $strTable = "returngroup";
    $strCondition = "available=0";
    $allReason = fncSelectConditionRecord($strTable,$strCondition);
    $strTable1 = "jobassign";
    $strCondition1 = "jid=$job_id";
    $typej = fncSelectConditionRecord($strTable,$strCondition);
  //  $jobreturn = "<form method=\"post\"> ";
    $jobreturn = '';
    $jobreturn = "<label for=\"sel1\">ชนิดการคืนงาน :</label>";
    $jobreturn .= "<select class=\"jobreturn form-control\" name=\"typejobreturn\">
        <option value=\"0\">--- เลือกคืนงาน ---</option>";
        while($reason = mysql_fetch_array($allReason)){

        		$jobreturn .= " <option value=\"".$reason[id]."\">".$reason[groupname]."</option>";
    }
    $jobreturn .=   "</select>";
    return $jobreturn;
}

function returnConfResultCode($rcr){
		switch ($rcr) {
			case 0:
				$txt =  "ยังนัดไม่ได้";
			break;
			case 11:
				$txt =  "รับนัด";
			break;
			case 12:
				$txt =  "ยกเลิก";
			break;
			case 13:
				$txt =  "เลื่อน";
			break;
			case 21:
				$txt =  "เซลล์รับนัด";
			break;
			case 22:
				$txt =  "เซลล์ยกเลิก";
			break;
			case 23:
				$txt =  "เซลล์เลื่อน";
			break;
		}
		return $txt;
	}

			$d = date('d');
			if(checkAllow('sit_importjobassign'))$d++;

			(isset($_GET['due'])? $_GET['due'] : $d);
			if(isset($_GET['due'])){
				$due = $_GET['due'];
				$expd = explode("-",$_GET['due']);
				$d = $expd[2];
			}else{
				$due = date('Y-m')."-".$d;
			}
			if($_GET['debug']) echo "due = ".$due;

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


<body>
  <!-- Modal Return Job-->
  <div class="modal fade" id="return" role="dialog" tabindex="-1" >
    <div class="modal-dialog">
      <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
           <button type="button" class="close" data-dismiss="modal">&times;</button>

              <h4 class="modal-title" align="center">รายละเอียดคืนงาน : <span id="demo2"></span></h4>
        </div>

        <div class="modal-body">

        <form action="uploadreturnjob.php" method="post" class="dropzone" id="my-awesome-dropzone" enctype="multipart/form-data">
		        <input  type="hidden" name="jid" id="jid" class="returnjob" value=""/>
						<?php
							$jobreturn = returnForm($jid,$jobstatus);
							echo $jobreturn;
						?><BR>
		               <div id="select2" class="css_more">
		 						<label >วันที่นัดติดตั้งใหม่ :</label>
		                		<input type="date" class="form-control" id="rejdate" name="rejdate" placeholder="กรุณาใส่หมายเหตุ"><BR>
		                </div><br>
		              			<label >หมายเหตุ :</label>
		                		<input type="text" class="form-control" id="note" name="note" placeholder="กรุณาใส่หมายเหตุ"><BR>
		                <label >รูปภาพคืนงาน :</label>
		                <div class="dropzone-previews"></div>
							<div class="fallback">
								<input name="file" type="file" multiple/>
							</div>
		</form>
								<br><div align="center"><input align="right" type="submit" id="submit-all" class="btn btn-info submit" value="บันทึก"></div>
      	</div>
        <div class="modal-footer"></div>
     </div>
    </div>
  </div>


<div class="modal fade" id="closejob" role="dialog" tabindex="-1" >
    <div class="modal-dialog">
      <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
           <button type="button" class="close" data-dismiss="modal">&times;</button>

              <h4 class="modal-title" align="center">ปิดงาน : <span id="demo"></span></h4>
        </div>

        <div class="modal-body">

        <form action="uploadclosejob.php" method="post" class="dropzone" id="my-awesome-dropzone1" enctype="multipart/form-data">


		        <!--<form id="createProject" class="dropzone" action="uploadphoto.php" method="post" enctype="multipart/form-data" >-->
		        <input type="hidden"  name="jid" id="jid" class="returnjob" value=""/>

		        	<div id="modem">
		                <label style="color:green">SN : Modem <button type="button" class="btn btn-primary">  <a style="color:white;" href="intent://scan/#Intent;scheme=zxing;package=com.google.zxing.client.android;end"> ยิงบาร์โค๊ด </a> </button></label>
		                <input type="text" class="form-control" id="modsn" name="modsn" placeholder="กดค้าง แล้วเลือกวางเลข Serial Moderm">
		                <br>
		            </div>
		            <div id="catv">
		                <label style="color:#ff4d88">SN : CATV <button type="button" class="btn btn-primary">  <a style="color:white;" href="intent://scan/#Intent;scheme=zxing;package=com.google.zxing.client.android;end"> ยิงบาร์โค๊ด </a> </button></label>
		                <input type="text" disabled="true" class="form-control" id="catvsn" name="catvsn" placeholder="กดค้าง แล้วเลือกวางเลข Serial CATV">
		                <br>
		            </div>
		                <div class="form-group" id="option1">
						  <label>ระยะสาย OFC (FTTX)</label>
						  <select class="form-control" id="bcable" name="bcable" >
						    <option value="0">0</option>
						    <option>25</option>
						    <option>50</option>
						    <option>75</option>
						    <option>100</option>
						    <option>125</option>
						    <option>150</option>
						    <option>175</option>
						    <option>200</option>
						    <option>225</option>
						    <option>250</option>
						    <option>275</option>
						    <option>300</option>
						    <option>325</option>
						    <option>350</option>
						    <option>375</option>
						    <option>400</option>
						    <option>425</option>
						    <option>450</option>
						    <option>475</option>
						    <option>500</option>
						    <option>525</option>
						    <option>550</option>
						    <option>575</option>
						    <option>600</option>
						    <option>625</option>
						    <option>650</option>
						    <option>675</option>
						    <option>700</option>
						    <option>725</option>
						    <option>750</option>
						    <option>775</option>
						    <option>800</option>
						    <option>825</option>
						    <option>850</option>
						    <option>875</option>
						    <option>900</option>						    						    						
						    <option>925</option>
						    <option>950</option>
						    <option>975</option>
						    <option>1000</option>						      
						  </select>
						</div><br>
						<label >รูปภาพปิดงาน :</label>
		                <div class="dropzone-previews1"></div>
							<div class="fallback">
								<input name="file" type="file" multiple/>
							</div>




		      		</form>
		      		<br><div align="center"><input  type="submit" id="submit-all1" class="btn btn-info submit1" value="บันทึก"></div>
      		</div>
        <div class="modal-footer"></div>
     </div>
    </div>
  </div>
	<?php
		if($_GET['all']){

		$linkalleng = "<span class=\"glyphicon glyphicon-eye-close\" aria-hidden=\"true\"></span> <a href=\"foajobassign.php?due=".$due."\">ดูงานเฉพาะตัว</a>";


	}else{
		$moreCondition = " and assigned_eng='".$_COOKIE["uid"]."'";
		$linkalleng = "<span class=\"glyphicon glyphicon-eye-open\" aria-hidden=\"true\">   </span> <a href=\"foajobassign.php?all=1&due=".$due."\">ดูงานทุกช่าง</a>    ";
	}

	$originalVer = "<span class=\"glyphicon glyphicon-eye-open\" aria-hidden=\"true\"></span> <a href=\"jobassign.php?orgver=1&due=".$due."\">version เดิม</a>";



	$table = "jobassign join tap_location on jobassign.tap=tap_location.tap";
	$condition = "job_status='D' and conf_date='".$due."'".$moreCondition;
	$sort = "order by assigned_eng,result_cnf_code,conftime";
	$start = "SELECT * FROM $table WHERE $condition $sort";
	$query = mysql_query($start);
	$strComment = "memo_appointment";
	$strCommentSort = " order by memo_date_time asc";
	?>

	<h4 align="center"><a href="foajobassign.php?due=<?php  echo date('Y-m-d',strtotime($due.' -1 day')) ?>&all="><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span></a>
					   <a href="foajobassign.php"> งานติดตั้งวันที่ <?php  echo convdate($due)?></a>
					   <a href="foajobassign.php?due=<?php  echo date('Y-m-d',strtotime($due.' +1 day')) ?>&all="><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></a><br><br>
					  <!-- <button type="submit" class="btn btn-default"><?php //echo "$linkalleng"; ?></button>-->
					  <button type="submit" class="btn btn-default"><?php echo "$originalVer"; ?></button> </h4>

	<?php  $table1 = "jobassign";
	$condition1 = "conf_date='".$due."' and assigned_eng='".$_COOKIE["uid"]."'";
	$start1 = "SELECT count(*) FROM $table1 WHERE $condition1 ";
	$query1 = mysql_query($start1);
	$jbe = mysql_fetch_array($query1);
	if ($jbe[0]=='0') { echo "<div class=\"alert alert-warning\" role=\"alert\"><p align=\"center\"><strong> คุณไม่มีงานนัดติดตั้งวันนี้ค่ะ </strong><span class=\"glyphicon glyphicon-trash\" aria-hidden=\"true\"></span></p></div>"; }

	$count = 1;
	  while($jb = mysql_fetch_array($query)){
	$cutT =  explode(":", $jb['conftime']);
	$time = $cutT[0] .":" . $cutT[1];

	$typej = returntypejob($jb['work_action'],$jb['SO_CCSS_ORDER_TYPE'],$jb['SO_CHG_ADDR_FLG'],$jb['CATV_FLG'],$jb['jobname'],$jb['sodoctype'],$jb['bundle']);
	$cphone = explode(",",$jb['cust_phone']);
	$pi0 = 0;
	$pi1 = 1;
	$pi2 = 2;

	$id_test[$count] = $jb['jid'];
?>



    <table class="table">
    <div id="demo555"></div>
      <tr>
        <!--<td class="col-sm-1"><span class="badge"><?php  echo $count; ?></span><br><br></td>-->
        <td class="col-sm-11" align="left">

        <?php  if($jb['assigned_eng']>0) { echo "<b>ช่าง : </b>".nameofengineer($jb['assigned_eng'])."<br>";
    		}else { echo "<div class=\"alert alert-danger\" role=\"alert\"><p align=\"center\"><strong>งานที่กำลังรอเลือกช่าง   </strong><span class=\"glyphicon glyphicon-time\" aria-hidden=\"true\"></span></p>"; } ?>

        <?php  echo "<kbd-1 align=\"right\"><b>  ".$time." น.</b></kbd-1><br>";?>
        		<?php if($typej=='Dis' and $jb['distype']=='a') $typej .= " '".$jb['distype']."'";?>
				<?php if($typej=='Dis' and $jb['distype']=='b') $typej .= " '".$jb['distype']."' [เก็บ Modem ด้วย]";?>
        <?php   echo "<b>Type : </b>".$typej;  ?><br>
        <?php   echo "<b>Circuit : </b><span style=color:#0066CC>".$jb['circuit']." ,".$jb['cc99']." </span>"; ?><br>
        <?php   echo "<b>CATV No. : </b><span style=color:#0066CC>".$jb['bundle']."</span>";  ?><br>
        <?php   echo "<b>Fixed Line : </b><span style=color:#0066CC>".$jb['fixedlineno']."</span>";  ?><br>
   		<?php   if($jb['jobname']!=="DOCSIS"){
        	echo "<b>L2 :</b> ".$jb['tap']."<b> / DP-Pair :</b>".$jb['pair']; ?><br>
        <?php   echo "<b>Code : </b>".$jb['handler_id']." / ".$jb['handler_name'].""; ?><br><br>
        <?php  } else { ?> <br> <?php  } ?>
        <?php   echo "<kbd>ข้อมูลลูกค้า</kbd>";  ?><br>
		<?php   echo "<span class=\"glyphicon glyphicon-user\" aria-hidden=\"true\">     </span>   ".$jb['cust_name'];  ?><br>
		<?php   echo "<span class=\"glyphicon glyphicon-phone\" aria-hidden=\"true\">     </span>
					<a href=\"tel:".$cphone[$pi0]."\"> ".$cphone[$pi0]."</a>";
				echo "<a href=\"tel:".$cphone[$pi1]."\"> ".$cphone[$pi1]."</a>";
				echo "<a href=\"tel:".$cphone[$pi2]."\"> ".$cphone[$pi2]."</a><br>";

		?>
		<?php /*  echo "<span class=\"glyphicon glyphicon-phone\" aria-hidden=\"true\">     </span>
				  <a href=\"tel:".$cphone[$pi0]."\"> ".$cphone[$pi0]."</a> <span class=\"badge empSms\" for=\"".$jb['jid']."\" no=\"".$cphone[$pi0]."\" due=\"".$jb['conf_date']."\" >ส่ง SMS ถึงลูกค้า</span><br>";
				  if($cphone[$pi1]!='' and $cphone[$pi1]!=' '){
				  	echo "<span class=\"glyphicon glyphicon-phone\" aria-hidden=\"true\">     </span>
				  <a href=\"tel:".$cphone[$pi1]."\"> ".$cphone[$pi1]."</a> <span class=\"badge empSms\" for=\"".$jb['jid']."\" no=\"".$cphone[$pi1]."\" due=\"".$jb['conf_date']."\" >ส่ง SMS ถึงลูกค้า</span><br>";
				  }
				  if($cphone[$pi2]!='' and $cphone[$pi2]!=' '){
				  	echo "<span class=\"glyphicon glyphicon-phone\" aria-hidden=\"true\">     </span>
				  <a href=\"tel:".$cphone[$pi2]."\"> ".$cphone[$pi2]."</a> <span class=\"badge empSms\" for=\"".$jb['jid']."\" no=\"".$cphone[$pi2]."\" due=\"".$jb['conf_date']."\" >ส่ง SMS ถึงลูกค้า</span><br>";
				  }
						*/ ?>
		<?php   echo "<span class=\"glyphicon glyphicon-map-marker\" aria-hidden=\"true\">     </span>   ".$jb['cust_addr'];  ?><br><br>
		<?php  echo "<kbd>บันทึกถึงช่าง</kbd> <button type=\"button\" class=\"btn btn-success btn-xs showcomment\" for=\"".$jb['jid']."\"><span class=\"glyphicon glyphicon-collapse-down\"></span> คลิกเพื่อดูประวัตินัดทั้งหมด</button><br><kbd>ผลนัด</kbd> ".returnConfResultCode($jb['result_cnf_code']);?>

		<?php echo "<div id=\"due_".$jb['jid']."\">"; ?>
		<?php 	$strCommentCondition = "jid='".$jb['jid']."' and due_date='".$due."'";
		if($_GET['debug']) echo "SELECT * FROM $strComment WHERE $strCommentCondition  $strCommentSort";
		$comments = fncSelectConditionRecord($strComment,$strCommentCondition,$strCommentSort);
		$cm = 0;
		$sl = 0;
		while($cmm = mysql_fetch_array($comments)){
			if($cmm['result']<20){
				$cm++;
				$timeRectxt = "<span class=\"glyphicon glyphicon-pushpin\" aria-hidden=\"true\" for=\"".$cm."\">     </span>";
				$fclr = "#008080";
			}else{
				$sl++;
				$timeRectxt = "<span class=\"glyphicon glyphicon-time\" aria-hidden=\"true\" for=\"".$sl."\">     </span>";
				$fclr = "Olive";
			}
			echo $timeRectxt." : <span style=\"color:".$fclr."\">".$cmm['memotxt']."</span> <span style=\"color:red;font-size:10px;\">*".nameofengineer($cmm['who_did'],1)."</span><br>";
		 } ?>
		 <?php echo "</div><br>"; ?>

		<?php	$strCommentCondition = "jid='".$jb['jid']."'";
				$comments = fncSelectConditionRecord($strComment,$strCommentCondition,$strCommentSort);

     		 echo "<div id=\"all_".$jb['jid']."\" style=\"display:none;\">";
	  			while($cmm = mysql_fetch_array($comments)){
				if($cmm['result']<20){
					$cm++;
					$timeRectxt = "<span class=\"glyphicon glyphicon-pushpin\" aria-hidden=\"true\" for=\"".$cm."\">     </span>";
					$fclr = "#008080";
				}else{
					$sl++;
					$timeRectxt = "<span class=\"glyphicon glyphicon-time\" aria-hidden=\"true\" for=\"".$sl."\">     </span>";
					$fclr = "Olive";
				}
					echo $timeRectxt." : <span style=\"color:".$fclr."\">".$cmm['memotxt']."</span> <span style=\"color:red;font-size:10px;\">*".nameofengineer($cmm['who_did'],1)."</span><br>";
		 		}
  			echo "</div><br>";
		?>

  <!-- ปุ่มคืนงานแบบอัพโหลดรูปได้-->
  				 <?php  if($jb['assigned_eng']==$_COOKIE["uid"] ) {  ?>
 						<a class="btn-block btn-info btn-lg return" role="button"  data-toggle="modal" for="<?php echo $jb['jid']; ?>" data-id="<?php echo $jb['jid']; ?>"  href="#return"><center><span class="glyphicon glyphicon-share-alt"> คืนงาน </center></span></a>
 				  <?php   }  ?>

			<?php  if (($jb['lat'] == '0' and $jb['lng'] == '0') and $jb['jobname'] == 'FTTX') { ?>
						<button type="button" class="btn-block btn-warning btn-lg disabled"><span class="glyphicon glyphicon-alert"></span> เช็คพิกัดจาก FOA</button>
			<?php } else {?>
						<button type="button" class="btn-block btn-primary btn-lg" target="_blank" onclick="window.location.href='http://www.google.com/maps/place/<?php  echo $jb['lat'].",".$jb['lng'] ?>'"><span class="glyphicon glyphicon-map-marker" "></span> <?php  echo $jb['lat'].",".$jb['lng'] ?></button>
						<button type="button" class="btn-block btn-success btn-lg" onclick="window.location.href='https://gateway.truecorp.co.th/install/searchServiceOrderOption.do?action=load&mode=30&orderId=<?php  echo $jb['so_no']."&workActn=".$jb['work_action'] ?>'">IVR</button>
			<?php }

			if($jb['modsn']=='' and $jb['assigned_eng']==$_COOKIE["uid"]) { ?>

				<a class="btn-block btn-danger btn-lg closejob" role="button"  data-toggle="modal" for="<?php echo $jb['jid']; ?>" data-id="<?php echo $jb['jid']; ?>"  href="#closejob"><center><span class="glyphicon glyphicon-ok"> ปิดงาน </center></span></a>

	<?php	}else if ($jb['modsn']!=='' and $jb['assigned_eng']==$_COOKIE["uid"]){ ?>

				<a class="btn-block btn-danger disabled btn-lg closejob" role="button"><center><span class="glyphicon glyphicon-ok"> ปิดงานเรียบร้อยแล้ว </center></span></a>
	<?php	}else if($jb['assigned_eng']!==$_COOKIE["uid"]) {

	}?>

			</tr>
			<tr id="trjid_<?php  echo $jb['jid']; ?>"></tr>
        </td>
          <?php   if($jb['assigned_eng']==0) { echo "</div>"; } ?>

    <?php  $count++; } ?>

    </body>

<script type="text/javascript">

 // The camelized version of the ID of the form element
 Dropzone.options.myAwesomeDropzone = {

 // set following configuration
    autoProcessQueue: false,
    uploadMultiple: true,
    parallelUploads: 30,
    maxFiles: 30,
    addRemoveLinks: true,
    previewsContainer: ".dropzone-previews",
    dictRemoveFile: "ลบ",
    dictDefaultMessage: "เลือกรูปภาพที่จะคืนงาน",
    dictFileTooBig: "Image size is too big. Max size: 10mb.",
    dictMaxFilesExceeded: "อนุญาตให้อัพโหลดรูปภาพไม่เหิน 30 รูป",
    acceptedFiles: ".jpeg,.jpg,.png,.gif,.JPEG,.JPG,.PNG,.GIF",

   // The setting up of the dropzone
   init: function() {
     var myDropzone = this;

   // Upload images when submit button is clicked.
   $("#submit-all").click(function (e) {
   	if(!confirm('คุณต้องการที่จะคืนงานนี้ใช่หรือไม่?')){
        		e.preventDefault();
            	e.stopPropagation();
     			return false;
    		}else{
		        e.preventDefault();
		        e.stopPropagation();
		        myDropzone.processQueue();
 			}
    });
   // Refresh page when all images are uploaded
    myDropzone.on("complete", function (file) {
         if (myDropzone.getUploadingFiles().length === 0 && myDropzone.getQueuedFiles().length === 0) {
        window.location.reload();

        }
      });
    }
 }

 Dropzone.options.myAwesomeDropzone1 = {

 // set following configuration

    autoProcessQueue: false,
    uploadMultiple: true,
    parallelUploads: 30,
    maxFiles: 30,
    addRemoveLinks: true,
    previewsContainer: ".dropzone-previews1",
    dictRemoveFile: "ลบ",
    dictDefaultMessage: "คลิกเพื่อเลือกรูปภาพที่จะปิดงาน",
    dictFileTooBig: "Image size is too big. Max size: 10mb.",
    dictMaxFilesExceeded: "อนุญาตให้อัพโหลดรูปภาพไม่เหิน 30 รูป",
    acceptedFiles: ".jpeg,.jpg,.png,.gif,.JPEG,.JPG,.PNG,.GIF",

   // The setting up of the dropzone
   init: function() {
     var myDropzone1 = this;

   // Upload images when submit button is clicked.
   $("#submit-all1").click(function (e) {
   	if(!confirm('คุณต้องการที่จะปิดงานนี้ใช่หรือไม่?')){
        		e.preventDefault();
            	e.stopPropagation();
     			return false;
    		}else{
		        e.preventDefault();
		        e.stopPropagation();
		        myDropzone1.processQueue();
 			}
    });
   // Refresh page when all images are uploaded
    myDropzone1.on("complete", function (file) {
         if (myDropzone1.getUploadingFiles().length === 0 && myDropzone1.getQueuedFiles().length === 0) {
        window.location.reload();

        }
      });
    }
 }
 </script>
</html>
