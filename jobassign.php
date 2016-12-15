<?php
// แก้ไขสถานะเมื่อแอดมินกดยกเลิก จาก C ให้กลายเป็น R เพราะในระบบจะไม่สามารถเปลี่ยนจาก C เป็นสถานะอื่นได้จากการ import jobassign [รับงานจากพี่หนึ่ง] / 2016-11-04
/*
$useragent = $_SERVER['HTTP_USER_AGENT']; // เก็บว่าคนดูใช้ Browser ตัวใด
// ใช้ If ทำการแยกประเภทของ Browser ของคนดู ว่ามันเป็นของ คอมพิวเตอร์ หรือ โทรศัพท์เคลื่อนที่

if(preg_match('/android|avantgo|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)) and $_COOKIE['permission']==4 and $_GET['orgver']<>1)
{
// ทำการเขียนโปรแกรมต่อที่นี่ กรณีเป็นการดูเว็บเพจจากโทรศัพท์เคลื่อนที่ ?>
 <script>
    window.location='foajobassign.php'
  </script>
<?php  //echo "เป็นการดูจากโทรศัพท์เคลื่อนที่";
}
*/
include('cookies.php');
include("functions/function.php");
include("headmenu.php");

$d = date('d');
if(checkAllow('sit_importjobassign'))$d++;
$zoneselected = $_GET['z'];
(isset($_GET['due'])? $_GET['due'] : $d);
if(isset($_GET['due'])){
	$due = $_GET['due'];
	$expd = explode("-",$_GET['due']);
	$d = $expd[2];
}else{
	$due = date('Y-m')."-".$d;
}

if($_GET['debug']) echo "due = ".$due;

?>
<script language="Javascript" type="text/javascript">
$(document).ready(function(){
	$("span.getmap").click(function(){
		so_no = $(this).attr('sono');
		tap = $(this).attr('tap');
		wact = $(this).attr('wact');
		$(this).hide();
		$.ajax({
		   type: "POST",
		   url: "getmap.php",
		   cache: false,
		   data: "so_no="+so_no+"&tap="+tap+"&wact="+wact,
		   success: function(msg){
			  $("#show").html(msg);
			if(msg.indexOf("login_frm.php") > -1){
				window.location.replace("login_frm.php");
				return false;
			}
			if(msg==1){
				$("#"+so_no+"_"+wact).hide();
			}
			else{
				$("#"+so_no+"_"+wact).append("<span style=\"color:red;\">ไม่สามารถปักหมุดได้ "+msg+"...</span>");
			}
		   }
	   });
	});

	$("select#zone").change(function(){
		zone = $("select#zone option:selected").val();
		var cururl = window.location.href;
		if (cururl.indexOf("php?") >= 0) {
			due = "<?php echo $_GET['due'];?>";
			all = "<?php echo $_GET['all'];?>";
			window.location.replace(window.location.pathname+"?due="+due+"&all="+all+"&z="+zone);
		}else{
			window.location.replace(cururl+"?z="+zone);
		}
	});

	$("select#aa_bb").change(function(){
		engid = $("select#aa_bb option:selected").val();

		var jid = $('input:checkbox:checked').map(function() {
	    return this.value;
		}).get();

		duedate = "<?php echo $due?>";
		$.ajax({
			 type: "POST",
			 url: "assigned.php",
			 cache: false,
			 data: "engid="+engid+"&jid="+jid+"&duedate="+duedate,
			 success: function(msg){
				 //$("div#show").html(msg);
			if(msg.indexOf("login_frm.php") > -1){
				window.location.replace("login_frm.php");
					return false;
				}
				window.location.replace(window.location.href);
			 }
		 });

	});


	//เลือกวันที่แบบ datepicker พี่หนึ่งให้ทำ 01/11/2559
	$("input.datejob").change(function(){
		due = $("input.datejob").val();
		window.location.replace("jobassign.php?due="+due+"&all=<?php echo $_GET['all']?>");
	});

	$("select.engineername").change(function(){
		jid = $(this).attr('for');
		engid = $("select#engsel_"+jid+" option:selected").val();
		//alert(engid);
		duedate = "<?php echo $due?>";
		$.ajax({
		   type: "POST",
		   url: "assigned.php",
		   cache: false,
		   data: "engid="+engid+"&jid="+jid+"&duedate="+duedate,
		   success: function(msg){
			   //$("div#show").html(msg);
				if(msg.indexOf("login_frm.php") > -1){
					window.location.replace("login_frm.php");
					return false;
				}

		   }
	   });
	});

	$("select.conftime").change(function(){
		jid = $(this).attr('for');
		cftime = $("select#cft_"+jid+" option:selected").val();
		duedate = "<?php echo $due?>";
		$.ajax({
		   type: "POST",
		   url: "makeappointment.php",
		   cache: false,
		   data: "cftime="+cftime+"&jid="+jid+"&duedate="+duedate,
		   success: function(msg){
			   //$("div#show").html(msg);
			if(msg.indexOf("login_frm.php") > -1){
				window.location.replace("login_frm.php");
				return false;
			}
		   }
	   });
	});
	/*
	function escapeRegExp(string) {
	    return string.replace(/([.*+?^=!:${}()|\[\]\/\\])/g, "\\$1");
	}

	function replaceAll(string, find, replace) {
	  return string.replace(new RegExp(escapeRegExp(find), 'g'), replace);
	}*/

	$("select.resconftime").change(function(){
		duedate = "<?php echo $due?>";
		jid = $(this).attr('for');
		engid = $("select#engsel_"+jid+" option:selected").val();
		resCnft = $("select#rescnf_"+jid+" option:selected").val();

		if(resCnft==26){
			if($("#pullCnft").val()==1){
				openAlert('ต้องเลื่อนได้ทีละราย');
				return false;
			}
			$("#pullCnft").val(1);
			$("#tdpull_"+jid).html("<form method=\"post\" action=\"pulljobassign.php\">ดึงทำวันที่ <input type=\"date\" name=\"datejob\" class=\"datejob\" id=\"datePicker\"><input type=\"submit\" value=\" <<< ดึง <<< \"><input type=\"hidden\" name=\"jid\" value=\""+jid+"\"><input type=\"hidden\" name=\"empid\" value=\""+engid+"\"></form>");
			$("#tdpull_"+jid).css("background-color","yellow");
			return false;
		}

		if(resCnft==11 && $("select#cft_"+jid+" option:selected").val()==0){
			openAlert('เลือกกำหนดเวลานัดหมายก่อนยืนยันรับนัดค่ะ');
			$("select#cft_"+jid).val()=0;
			return false;
		}
		$("input#resTxt_"+jid).val('');
		$("button.butsave").prop('disabled','disabled');
		$("input#resTxt_"+jid).prop('disabled','disabled');
		$("select#cft_"+jid).prop('disabled', 'disabled');
		$("select#engsel_"+jid).prop('disabled','disabled');

		inputTXT = '';

		if(resCnft==='12' || resCnft==='22') {
			inputTXT = "ยกเลิกเนื่องจาก....";
		}else if(resCnft==='13' || resCnft==='23' ) {
			inputTXT = "เลื่อนวัน....ที่.....";
		}else if(resCnft==='14' || resCnft==='24' || resCnft==='15' || resCnft==='25' || resCnft==='ผลนัด'){
			if(resCnft==='ผลนัด'){
				$("div#resdiv_"+jid).html("");
			}else{
				inputTXT = "โทรติดต่อหมายเลข.....";
        if(resCnft==='14' || resCnft==='15'){
          $("input#check_"+jid).css("display", "block");
          $("span#sp_"+jid).css("display", "block");
        }
			}
			$("select#cft_"+jid).prop('disabled', false);
			$("select#engsel_"+jid).prop('disabled',false);
		}else{
			inputTXT = "[รับนัด]";
		}

		if(resCnft != 'ผลนัด'){
			$("button#"+jid).prop('disabled',false);
			$("input#resTxt_"+jid).prop('disabled',false);
			$("input#resTxt_"+jid).val(inputTXT);
		}

		$.ajax({
		   type: "POST",
		   url: "resultappointment.php",
		   cache: false,
		   data: "resCnft="+resCnft+"&jid="+jid+"&duedate="+duedate,
		   success: function(msg){
				//alert(msg);
			   //$("div#show").html(msg);
			if(msg.indexOf("login_frm.php") > -1){
				window.location.replace("login_frm.php");
				return false;
			}
		   }
	   });
	});

	$("button.butsave").click(function(){
		$(this).hide();
		duedate = "<?php echo $due?>";
		jid = $(this).attr('id');
		resCnft = $("select#rescnf_"+jid+" option:selected").val();
		empid = $("select#engsel_"+jid+" option:selected").val();

		if($("input#check_"+jid+":checked").length==0){ // ถ้าไม่มีการเลือก checkbox
            sms = '';
        } else {
        	sms = ' / ระบบส่ง sms แล้ว';
        }
        $("input#check_"+jid).hide();
        $("span#sp_"+jid).hide();

		//alert(resCnft);
		statusTxt = '';
		if(resCnft==12) statusTxt = 'R';
		if(resCnft==13) statusTxt = 'R';
		//alert(statusTxt);
		commt = $("input#resTxt_"+jid).val();
		$.ajax({
		   type: "POST",
		   url: "memoappointmentrec.php",
		   cache: false,
		   data: "resCnft="+resCnft+"&jid="+jid+"&duedate="+duedate+"&statustxt="+statusTxt+"&commt="+commt+"&emp="+empid+"&sms="+sms,
		   success: function(msg){

		   	if( sms != ''){
		   		/*
		   		 $.ajax({
		   		 	 type: "POST",
					   url: "smsimage.php",
					   cache: false,
					   data: "jid="+jid,
		   		 });
		   		 */
		   	}

			if(msg.indexOf("login_frm.php") > -1){
				window.location.replace("login_frm.php");
				return false;
			}
			$("div#comm_"+jid).append("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+msg);
			$("div#show").html(msg+" "+jid);
			$("input#resTxt_"+jid).val("");
			$("input#resTxt_"+jid).prop('disabled', true);
			$("button").show();
			$("button#"+jid).prop('disabled', true);
		   }
	   });
	});

	$(".searchTap").keyup(function(){
		id = $(this).attr('id');
		txt = $(this).val();
		if(txt.length<=1) return false;
		$.ajax({
		   type: "POST",
		   url: "searchtap.php",
		   cache: false,
		   data: "txt="+txt,
		   success: function(msg){
		   if(msg.indexOf("login_frm.php") > -1){
				window.location.replace("login_frm.php");
				return false;
		    }
			var arr = $.parseJSON(msg);
			$("div#taplist").html('');
			$("div#taplist").html('<div style=\"margin:0px 3px;\"> -:- คลิ๊ก tap ที่ต้องการ</div>');
			$.each(arr, function(i,v) {
				vrep = v.replace(txt.toUpperCase(), "<span style=\"color:red\">"+txt.toUpperCase()+"</span>");
				newdiv = "<div torow=\""+id+"\" value=\""+v+"\" class=\"tplst\" style=\"background-color:#ffffff;margin-bottom:2px;padding:0px 5px;cursor:pointer;\"><<< "+vrep+"</div>";
				$("div#taplist").append(newdiv);
			});
		   }
	   });
	});

	  $("div#taplist").on('click', 'div',function(){
		id = $(this).attr('torow');
		value = $(this).attr('value').replace(/ /g,'');
		$("input#"+id).val(value);
		$("input#"+id).addClass('bggreen txtwhite');
		$("#btn_"+id).attr("disabled", false);
	  });

	$("button.btntap").click(function(){
		id = $(this).attr('name');
		tap = $("input#"+id).val();
		$.ajax({
		   type: "POST",
		   url: "catvsavetap.php",
		   cache: false,
		   data: "jid="+id+"&tap="+tap,
		   success: function(msg){
			if(msg.indexOf("login_frm.php") > -1){
				window.location.replace("login_frm.php");
				return false;
			}

			 if(msg==1){
				$("p#catv_"+id).fadeOut('xslow', function(){
					a = '';
				});
			 }else{
				openAlert('ติดขัดบางประการ ไม่สามารถบันทึกงานได้อย่างสมบูรณ์\nกรุณาแจ้งพี่หนึ่งโดยด่วน');
			 }
		}
		});
	});

	$("span.viewjobdetail").click(function(){
		$('tr.showjobdetail').remove();
		jid = $(this).attr('for');
		//alert(jid);
		$.ajax({
		   type: "POST",
		   url: "jobcarddetail.php",
		   cache: false,
		   data: "jid="+jid,
		   success: function(msg){
				$('tr#trjid_'+jid).after('<tr class=\"showjobdetail\" id=\"jobdetail_'+jid+'\"><td></td><td colspan=\"6\">'+msg+'</td></tr>');
	           }
		});

	});

	$("span.techcode").click(function(){
		copyToClipboard(this);
	});

	function copyToClipboard(elem) {
		  // create hidden text element, if it doesn't already exist
	    var targetId = "_hiddenCopyText_";
	    var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
	    var origSelectionStart, origSelectionEnd;
	    if (isInput) {
	        // can just use the original source element for the selection and copy
	        target = elem;
	        origSelectionStart = elem.selectionStart;
	        origSelectionEnd = elem.selectionEnd;
	    } else {
	        // must use a temporary form element for the selection and copy
	        target = document.getElementById(targetId);
	        if (!target) {
	            var target = document.createElement("textarea");
	            target.style.position = "absolute";
	            target.style.left = "-9999px";
	            target.style.top = "0";
	            target.id = targetId;
	            document.body.appendChild(target);
	        }
	        target.textContent = elem.textContent;
	    }
	    // select the content
	    var currentFocus = document.activeElement;
	    target.focus();
	    target.setSelectionRange(0, target.value.length);

	    // copy the selection
	    var succeed;
	    try {
	    	  succeed = document.execCommand("copy");
	    } catch(e) {
	        succeed = false;
	    }
	    // restore original focus
	    if (currentFocus && typeof currentFocus.focus === "function") {
	        currentFocus.focus();
	    }

	    if (isInput) {
	        // restore prior selection
	        elem.setSelectionRange(origSelectionStart, origSelectionEnd);
	    } else {
	        // clear temporary content
	        target.textContent = "";
	    }
			alert('ก๊อปปี้โค้ดช่างลง clipboard เรียบร้อย');
	    return succeed;
	}

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
	<?php
	if($_COOKIE['permission']==4 and $_COOKIE['superuser']!=1){
		?>
		getGeo();
		<?php
	}
	?>


	$("#datejobNum").change(function(){
		//date = $("#datejobNum").val();
		due = $("select#yearjobNum option:selected").val()+"-"+$("select#monthjobNum option:selected").val()+"-"+$("select#datejobNum option:selected").val();
		window.location.replace("jobassign.php?due="+due+"&all=<?php echo $_GET['all']?>");
	});

	$(".salephonebtn").click(function(){
		$(".spinpt").hide();
		$(".salephonebtn").show();
		id = $(this).attr('for');
		$(this).hide();
		$("#spinpt_"+id).show();
		$("#spinpt_"+id).focus();
	});

	$(".phTxt").click(function(){
		id = $(this).attr('for');
		$(this).hide();
		$("input#text_"+id).show();
	});


	$("input.textphone").blur(function(){
		ph = $(this).val();
		id = $(this).attr('for');
		if(ph.length<10){
			if(ph.length>0) openAlert('เบอร์ที่บันทึกไม่น่าจะถูกต้อง');
			return false;
		}
		$.ajax({
		   type: "POST",
		   url: "savephonejob.php",
		   cache: false,
		   data: "phNo="+ph+"&jid="+id,
		   success: function(msg){
				$("input.textphone").hide();
				$("#phTxt_"+id).css({"color": "black", "font-size": "100%"});
				$("#phTxt_"+id).show();
				$("#phTxt_"+id).html(msg);
			}
		});
	});

	$(".bndTxt").click(function(){
		$(".textbundle").hide();
		$(".textfixedline").hide();
		$(".bndTxt").show();
		$(".fxTxt").show();

		id = $(this).attr('for');
		$(this).hide();
		$("input#textbnd_"+id).show();
		name = $("span#custname_"+id).text();
		//alert(name);
		$.ajax({
		   type: "POST",
		   url: "findbundle.php",
		   cache: false,
		   data: "name="+name+"&jid="+id,
		   success: function(msg){
				//alert(msg);
				if(msg.indexOf("login_frm.php") > -1){
					window.location.replace("login_frm.php");
					return false;
				}
				var arr = $.parseJSON(msg);
				$("div#bundlelist").html('');
				$("div#bundlelist").html('<div style=\"margin:0px 3px;\"> -:- คลิ๊กงานที่ต้องการ</div>');
				$.each(arr, function(i,v) {
					newdiv = "<div torow=\""+id+"\" value=\""+v+"\" class=\"bndlist\" style=\"background-color:#ffffff;margin-bottom:2px;padding:0px 5px;cursor:pointer;\">"+v+"</div>";
					$("div#bundlelist").append(newdiv);
				});
			}
		});

	});


	$("input.textbundle").blur(function(){
		bnd = $(this).val();
		id = $(this).attr('for');
		if(bnd.length<8){
			if(bnd.length>0) openAlert('เบอร์ที่บันทึกไม่น่าจะถูกต้อง');
			return false;
		}
		$.ajax({
		   type: "POST",
		   url: "savebundleob.php",
		   cache: false,
		   data: "bndNo="+bnd+"&jid="+id,
		   success: function(msg){
				$("input.textbundle").hide();
				$("#bndTxt_"+id).css({"color": "black", "font-size": "100%"});
				$("#bndTxt_"+id).show();
				$("#bndTxt_"+id).html(msg);
			}
		});
	});

	$("#moreselected").change(function(){
		val = $("select#moreselected option:selected").val();
		window.location.href = "joblist.php?type="+val;
	});



	$(".fxTxt").click(function(){
		$(".textbundle").hide();
		$(".textfixedline").hide();
		$(".bndTxt").show();
		$(".fxTxt").show();

		id = $(this).attr('for');
		$(this).hide();
		$("input#textfx_"+id).show();
	});


	$("input.textfixedline").blur(function(){
		fx = $(this).val();
		id = $(this).attr('for');
		if(fx.length<9){
			if(fx.length>0) openAlert('เบอร์ที่บันทึกไม่น่าจะถูกต้อง');
			return false;
		}
		$.ajax({
		   type: "POST",
		   url: "savefixelineob.php",
		   cache: false,
		   data: "fxNo="+fx+"&jid="+id,
		   success: function(msg){
				$("input.textfixedline").hide();
				$("#fxTxt_"+id).css({"color": "black", "font-size": "100%"});
				$("#fxTxt_"+id).show();
				$("#fxTxt_"+id).html(msg);
			}
		});
	});


	$(".spinpt").blur(function(){
		ph = $(this).val();
		if(ph.length<10){
			if(ph.length>0) openAlert('เบอร์ที่บันทึกไม่น่าจะถูกต้อง');
			$(".spinpt").hide();
			$(".salephonebtn").show();
		}else{
			id = $(this).attr('for');
			$(".spinpt").hide();
			$("#spbtn_"+id).text('แก้ไข');
			newph = "<br><span style=\"background-color:#yellow;color:saddlebrown;font-size:8px;\">[<a href=\"tel:"+ph+"\">"+ph+"</a>]</span> ";
			$("#spbtn_"+id).before(newph);
			$(".salephonebtn").show();
		}
	});

	$(".jobreturn").change(function(){
		id = $(this).attr('for');
		resultSelect = $("select#jret_"+id+" option:selected").val();
		alert(resultSelect+"_"+id);
		if(resultSelect>0) {
			$(".subReason").css('display','none');
			$("#subReason_"+resultSelect+"_"+id).css('display','block');
		}else{
			return false;
		}
	});


	$(".chgbck").click(function(){
		jid = $(this).attr('for');
		window.open("verifychargeback.php?jid="+jid,"Ratting","width=550,height=170,0,status=0,");
	});

});
</script>
<!-- <div id="show"></div> -->
<div id="bundlelist" style="margin-left: 800px !important;margin-top: 300 !important;position: fixed;top: 100;"></div>
<div id="taplist" style="position: absolute;left: 550px;top: 200px;"></div>
<?php
$colorArray = array('fff','95B9C7','93FFE8','CCFFFF','99C68E','F3E5AB','CCFF33','009933','FFFF33','FFC0CB','66FFCC','F8F8FF','F5F5F5','EEE8AA','F3E5AB','CCFF33','95B9C7','93FFE8','CCFFFF','99C68E','F3E5AB','CCFF33','009933','FFFF33','FFC0CB','66FFCC','F8F8FF','F5F5F5','EEE8AA','FFFFE0','CCFF33','009933','FFFF33','FFC0CB','66FFCC','FAFAD2','CCFFCC','FFF0F5','F5DEB3','FFB5C5','CC9966','66CC99','FA8072','A2CD5A','EEE685','CD9B9B','FF6A6A','CDBA96','EE7621','FAFAD2','666633','D8BFD8','D2691E','20B2AA','FFFACD','E6E6FA','F8F8FF','F5F5F5','EEE8AA','FFFFE0','CCFF33','009933','FFFF33','FFC0CB','66FFCC','FAFAD2','CCFFCC','FFF0F5','F5DEB3','FFB5C5','CC9966','66CC99','FA8072','A2CD5A','EEE685','CD9B9B','FF6A6A','CDBA96','EE7621');
if(checkAllow('sit_importjobassign')){?>
<table><input type="hidden" value="0" id="pullCnft">
	<tr>
		<td>
	<form action="insertjobassign.php" method="post" enctype="multipart/form-data">
	<label for="file">Job Assign List</label>
	<input name="fileCSV" type="file" id="fileCSV">
	<input name="btnSubmit" type="submit" id="btnSubmit" value="Submit">
	</form>
		</td>
		<td>
	<form action="insertfttxjobassign.php" method="post" enctype="multipart/form-data">
	<label for="file">FTTx Assign List</label>
	<input name="fileCSV" type="file" id="fileCSV">
	<input name="btnSubmit" type="submit" id="btnSubmit" value="Submit">
	</form>
		</td>
	</tr>
</table>
<br>
<table class="container">
	<tr>
		<td class="container">
<?php
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

$strTable = "jobassign";
$strCondition = "job_status IN ('D','N','P') and conf_date='".$due."'";
$strSort = "order by assigned_eng";
if($_GET['debug']) echo "SELECT * FROM $strTable WHERE $strCondition $strSort";
$jobs = fncSelectConditionRecord($strTable,$strCondition,$strSort);

$j = "[['หจก.ติดเน็ต สาขา".constant('BRANCH')."',".constant('BRANCHLAT').",".constant('BRANCHLNG').",0,'0']";
$strTableTap = "tap_location";
$strFieldTap = "tap,lat,lng";

$catvJob = '<p style=\"font-weight:900;color:#ea2591;\">รายการงาน CATV วันนี้</p>';
while($job = mysql_fetch_array($jobs)){
	$jr++;
	$condition = "tap='".$job['tap']."'";
	//echo "SELECT * FROM tap_location WHERE $condition <br>";
	$tap = fncSelectSingleRecord($strTableTap,$condition);
	if($tap['tap']==null){
		$catvStatusD = array('N','P');
		if($job['job_status']=='D' and checkAllow('sit_importjobassign')){

			if($job['jobname']=='FTTX' or $job['jobname']=='FTTH'){
				$tap['lat'] = 0.00;
				$tap['lng'] = 0.00;
				$strValueTap = "'".$job['tap']."','".$tap['lat']."','".$tap['lng']."'";
				//fncInsertRecord($strTableTap,$strFieldTap,$strValueTap);  // ไม่ให้มีการเพิ่มแล้ว 20 ส.ค. 16

				if($_GET['debug']) echo "<br>INSERT INTO $strTableTap ($strFieldTap) VALUES ($strValueTap) <br>";

			}else{
				echo "<p id=\"".$job['so_no']."_".$job['work_action']."\">[".$job['SO_CCSS_ORDER_TYPE']."] ".$job['circuit']." ".$job['cust_name'];
				echo " <span class=\"button getmap\" wact=\"".$job['work_action']."\"sono=\"".$job['so_no']."\" tap=\"".$job['tap']."\" >ขอพิกัด</span></p>";
			}

		}else if(in_array($job['job_status'],$catvStatusD)){
			$nameofcust = explode(" ",$job['cust_name']);
			$strConditionBundel = "cust_name like '%".$nameofcust['1']."%".$nameofcust['2']."' and job_status='D' and bundle=''";
			$net = fncSelectSingleRecord($strTable,$strConditionBundel);
			if($_GET['debug']) echo "<br>91-".substr($net['circuit'],0,1)."<br>";

			if($net['jobname']=='DOCSIS' or $net['jobname']=='FTTH' or $net['jobname']=='FTTX'){
				$strCommand = "cust_addr='".$net['cust_addr']."', cust_phone='".$net['cust_phone']."', rcu_node='".$net['rcu_node']."', tap='".$net['tap']."', bundle='".$net['circuit']."', assigned_eng='".$net['assigned_eng']."'";
				$strCondition = "jid = '".$job['jid']."'";
				fncUpdateRecord($strTable,$strCommand,$strCondition);  //update CATV จากข้อมูลของ Net
				fncUpdateRecord($strTable,"bundle='".$job['circuit']."'","jid='".$net['jid']."'"); //update Net Bundle CATV
				// update ข้อมูลใน db ของ CATV จากข้อมูลของ internet
				//echo "<br>update ".$job['jid']."<br>";
			}else{
				$catvJob .= "<p style=\"color:#ea2591\" id=\"catv_".$job['jid']."_".$job['work_action']."\">[".$job['SO_CCSS_ORDER_TYPE']."] ".$job['circuit']." ".$job['cust_name'];
				if(checkAllow('sit_importjobassign')) $catvJob .= " <input type=\"text\" class=\"searchTap\" id=\"".$job['jid']."\"><button id=\"btn_".$job['jid']."\" disabled=\"true\" name=\"".$job['jid']."\" class=\"btntap\"> บันทึก tap </button>";
				$catvJob .= "</p>";
			}
		}

		if($tap['lat']<>0.00) continue;

	}

	if($job['bundle']<>'' and ($net['jobname']=='DOCSIS' or $net['jobname']=='FTTH' or $net['jobname']=='FTTX')){
		//echo "b = ".$job['bundle']." / 91 = ".$job['circuit']."<br>";
		continue;
	}


	$nameofcust = explode(" ",$job['cust_name']);
	$strConditionBundel = "cust_name like '%".$nameofcust['1']."%' and cust_name like '%".$nameofcust['2']."' and (job_status='N' or job_status='P') and bundle='' and Jobname not in ('DOCSIS','FTTX','FTTH','ADSL','')";
	$net = fncSelectSingleRecord($strTable,$strConditionBundel);

	if($_GET['debug']){
		echo "<br>SELECT * FROM $strTable WHERE $strConditionBundel ";
	}

	if($net['circuit']<>''){
		fncUpdateRecord($strTable,"bundle='".$net['circuit']."'","jid='".$job['jid']."'"); //update Net Bundle CATV
		$strCommand = "cust_addr='".$job['cust_addr']."', cust_phone='".$job['cust_phone']."', rcu_node='".$job['rcu_node']."', tap='".$job['tap']."', bundle='".$job['circuit']."', assigned_eng='".$job['assigned_eng']."'";
		$strCondition = "jid = '".$net['jid']."'";
		fncUpdateRecord($strTable,$strCommand,$strCondition);  //update CATV จากข้อมูลของ Net
	}


	if($j!='[')$j .= ",";


	$typejob = returntypejob($job['work_action'],$job['SO_CCSS_ORDER_TYPE'],$job['SO_CHG_ADDR_FLG'],$job['CATV_FLG']);
	if($job['jobname']=='FTTH' or $job['jobname']=='FTTX') $typejob='New FTTH';

	$mapLink = "<a target=\"_blank\" href=\"https://www.google.com/maps/place/".$tap['lat'].",".$tap['lng']."\">Google Map</a> | <a target=\"_blank\" href=\"https://gateway.truecorp.co.th/install/searchServiceOrderOption.do?action=load&mode=30&orderId=".$job['so_no']."&workActn=".$job['work_action']."\">True Map</a>";

	$img = '';
	if($job['assigned_eng']>0) $img = "<br><img src=\"img/employee/".$job['assigned_eng'].".png\">";
	if($job['conftime']<>"00:00:00") $img .= " เวลานัด - ".$job['conftime'];
	$shw = "<span name=\"circ\">".$job['circuit']."</span> [".$job['ampm']."]<br>".$job['cust_name']." [".$typejob."] <br>".$mapLink." ".$img;
	$j .= "['".$shw."',".$tap['lat'].",".$tap['lng'].",".$jr.",'".$job['assigned_eng']."']";

	if($tp[$job['tap']]<=0){
		$tp[$job['tap']] = $jr;
	}else{
		$loct[$tp[$job['tap']]] = $shw;
	}

}

$strTableGeo = "employee";
$strConditonGeo = "lat>0";
if($_GET['debug']) echo "SELECT * FROM $strTableGeo WHERE $strConditonGeo  $strSortGeo";
$geoAll = fncSelectConditionRecord($strTableGeo,$strConditonGeo);

while($geo = mysql_fetch_array($geoAll)){
	if($geo['lat']>0){
		$jr++;
		$j .= ",['".$geo['nickname']." ".convdateMini($geo['timeatgeo'])."',".$geo['lat'].",".$geo['lng'].",".$jr.",'9999','".$geo['id']."']";
	}
}


$j .= "]";

?>
<form action="searchjobassignhistory.php" method="post">

เลือก due date ที่ <input type="date" name="datejob" class="datejob" id="datePicker">
<input type="button" onclick="window.location.href='jobassign.php?due=<?php echo date("Y-m-d") ?>&all=<?php echo $_GET['all']?>'" value="งานวันนี้">
<select id="moreselected">
	<option value="0">more..</option>
	<option value="1">งาน D ของ <?php echo strtoupper(constant("ABVT"));?></option>
	<option value="2">งาน D ทุกสาขา</option>
	<option value="3">งาน X ทุกสาขาในเดือนนี้</option>
</select>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="text-indent: 5em;">ค้นประวัตินัดหมาย : <input type="text" name="searchtxt"></span>&nbsp;<input type="submit" value=" search... "> <span style="color:red; font-size:10px;">*ค้นได้จากบางส่วนของ circuit หรือ จากชื่อ-สกุล</span></form>
		</td>
	</tr>
	<?php
	if($_COOKIE['permission']==4){

		?>
			<tr>
				<td class="container">
		<?php

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
		/*
		$strTable = "jobassign as j join appointment as a on j.jid=a.jid and j.due_date=a.due_date";
		$strCondition = "j.job_status='D' and a.conf_date='".$due."'";//"j.due_date='".$due."'";
		$strSort = "order by a.emp_id, a.confirmtime";
		*/
		if($_GET['all']){
			$linkalleng = "<a href=\"jobassign.php?due=".$due."\">ดูงานเฉพาะตัว</a>";
		}else{
			$moreCondition = " and assigned_eng='".$_COOKIE["uid"]."'";
			$linkalleng = "<a href=\"jobassign.php?all=1\">ดูงานทุกช่าง</a>";
		}

		$strTable = "jobassign join tap_location on jobassign.tap=tap_location.tap";
		$strCondition = "job_status='D' and conf_date='".$due."'".$moreCondition;//"j.due_date='".$due."'";
		$strSort = "order by assigned_eng,result_cnf_code,conftime";
		if($_GET['debug']) echo "ช่าง SELECT * FROM $strTable WHERE $strCondition  $strSort";
		$jbs = fncSelectConditionRecord($strTable,$strCondition,$strSort);
		echo "<table>
				<tr class=\"label\">
					<td colspan=\"7\">รายชื่อลูกค้าที่รับนัดเข้าติดตั้งวันที่  ".convdate($due)."<span style=\"color:#fff\">.............</span> ".$linkalleng."</td>
				</tr>";
		$confirmed[0] = 0;

		$strComment = "memo_appointment";
		$strCommentSort = " order by memo_date_time";


			while($jb = mysql_fetch_array($jbs)){
				$jbr++;
				$confirmed[$jbr] = $jb['jid'];
				if($eng_temp <> $jb['assigned_eng']){
					$eachSerial = 1;
					$eng_temp = $jb['assigned_eng'];
					?>
						<tr class="header">
							<td align="center">ลำดับ</td><td align="center">ชื่อลูกค้า</td><td align="center">ประเภท</td><td align="center">ชื่อช่าง</td><td align="center" width="50">ผลนัด</td><td align="center" width="60">เวลานัด</td><td align="center" widht="400">บันทึกถึงช่าง</td>
						</tr>
					<?php
				}
				$clr = "";
				$styleV = "color:#4682B4;font-weight:bold;";
				if($jb['result_cnf_code']!=11){
					$clr = "color:#aaa;";
					$styleV = "color:#aaa;";
				}
				echo "<tr style=\"".$clr."background-color:".$color."\">";
				echo "<td rowspan=\"3\" align=\"center\" style=\"vertical-align:middle;\">".$eachSerial++."</td>";
				echo "<td>".$jb['cust_name']."</td>";
				echo "<td>".returntypejob($jb['work_action'],$jb['SO_CCSS_ORDER_TYPE'],$jb['SO_CHG_ADDR_FLG'],$jb['CATV_FLG'],$jb['jobname'],$jb['sodoctype'],$jb['bundle'])."</td>";
				if($jb['assigned_eng']>0){
					$imgEng = "<img src=\"img/employee/".$jb['assigned_eng'].".png\" title=\"".nameofengineer($jb['assigned_eng'])."\">";
				}else{
					$imgEng = "รอเลือกช่าง";
				}
				echo "<td align=\"center\">".$imgEng."</td>";
				//echo "<td>".nameofengineer($jb['assigned_eng'])."</td>";
				//echo "<td>".$jb['name']."</td>";
				//$time = explode(":",$jb['conftime']);
				//echo "<td>".$time[0].":".$time[1]." น</td>";
				echo "<td>".returnConfResultCode($jb['result_cnf_code'])."</td>";
				echo "<td>".substr($jb['conftime'],0,5)." น.</td>";
				echo "<td>";

				$strCommentCondition = "jid='".$jb['jid']."' and due_date='".$due."'";
				if($_GET['debug']) echo "SELECT * FROM $strComment WHERE $strCommentCondition  $strCommentSort";
				$comments = fncSelectConditionRecord($strComment,$strCommentCondition,$strCommentSort);
				$cm = 0;
				$sl = 0;
				while($cmm = mysql_fetch_array($comments)){
					if($cmm['result']<20){
						$cm++;
						$timeRectxt = "ลูกค้า ".$cm;
						$fclr = "#008080";
					}else{
						$sl++;
						$timeRectxt = "เซลล์ ".$sl;
						$fclr = "Olive";
					}

					echo $timeRectxt." : <span style=\"color:".$fclr."\">".$cmm['memotxt']."</span> <span style=\"color:red;font-size:10px;\">*".nameofengineer($cmm['who_did'],1)."</span><br>";
				}
				$cphone = explode(",",$jb['cust_phone']);
				$pi = 0;
				$phoneTXT = "";
				for($pi=0; $pi<=count($cphone); $pi++){
					if($cphone[$pi]<>'') $phoneTXT .= "<a href=\"tel:".$cphone[$pi]."\">".$cphone[$pi]."</a> ";
				}

				$L2 = '';
				if($jb['jobname']=='FTTH' or $jb['jobname']=='FTTX') $L2 = "<br>L2 : ".$jb['tap']." /  DP-Pair : ".$jb['pair'];

				if($jb['fixedlineno']<>'') $fixedlineNo = " Fixed Line : <span style=\"".$styleV."\">".$jb['fixedlineno']."</span>";

				/*$jobreturn = "<select class=\"jobreturn\" id=\"jret_".$jb['jid']."\" for=\"".$jb['jid']."\">
						<option value=\"0\">เลือกคืนงาน</option>
						<option value=\"1\">เลื่อน</option>
						<option value=\"2\">คืนนอกเสปค</option>
						<option value=\"3\">คืนฝนตก</option>
						<option value=\"4\">คืนผิดเงื่อนไขการขาย</option>
						<option value=\"5\">คืนงานลูกค้ายกเลิก</option>
					</select>";

				//$jobreturn .= "<select class=\"subReason\" id=\"subReason_2_".$jb['jid']."\" style=\"display: none;\">
						<option value=\"0\">เลือกเหตุผล</option>
						<option value=\"1\">ผ่านหม้อแปลง</option>
						<option value=\"2\">ไม่มีแนวเสา/option>
						<option value=\"3\">ร้อยท่อ</option>
						<option value=\"4\">ระยะสายเกิน</option>
						<option value=\"5\">อื่นๆ</option>
					</select>";*/


				//$returnbut = "<span class=\"button retjobbut\" id=\"dd\" for=\"".$jb['jid']."\" style=\"background-color:yellow;\">[คืน/เลื่อนงาน]</span>";
				echo "</td>";
				echo "</tr>";
				echo "<tr style=\"".$clr."background-color:".$color."\"><td colspan=\"4\" width=\"380\"><span style=\"".$styleV."\">".$jb['circuit']."</span> , <span style=\"".$styleV."\">".$jb['cc99']."</span> CATV No.-<span style=\"".$styleV."\">".$jb['bundle']."</span> [<span class=\"techcode\" style=\"".$styleV."\">".$jb['handler_id']."</span>/<span style=\"".$styleV."\">".$jb['handler_name']."</span>]<br>".$fixedlineNo." ".$L2." ".$returnbut."</td><td colspan=\"2\"> [<a href=\"https://www.google.com/maps/place/".$jb['lat'].",".$jb['lng']."\" target=\"_blank\">".$jb['lat']." : ".$jb['lng']."</a>] , <a href=\"https://gateway.truecorp.co.th/install/searchServiceOrderOption.do?action=load&mode=30&orderId=".$jb['so_no']."&workActn=".$jb['work_action']."\" target=\"_blank\">IVR</a> <span style=\"padding-left: 15px;\" for=\"".$jb['jid']."\" class=\"viewjobdetail\">[ใบงาน]</span><br>".$jobreturn."</td></tr><tr id=\"trjid_".$jb['jid']."\" style=\"".$clr."background-color:".$color."\"><td colspan=\"6\">".$phoneTXT." ".$jb['cust_addr']."</td></tr>";
				echo "<tr style=\"background-color:#fff;\"><td colspan=\"7\"></td></tr>";
			}

}
		?>
		</table>
				</td>
			</tr>
	<?php}?>
	<tr>
		<td class="container">
			<?php
			function engineerlist($id,$forjid,$enb,$abvt,$due,$dval=''){
				$strTable = "tidnet_common.master_employee left join tidnet_common.holiday on id<>emp_id and dateholiday=".$due." and branch='".$abvt."'";// e left join tidnet_common.holidy h on e.id<>h.emp_id";
				//$strCondition = "permission=4 and status=1 and dontshowat NOT LIKE '%@assignlist@%'";
				$strCondition = "permission=4 and workat like '%@".$abvt."@%' and dontshowat NOT LIKE '%@assignlist@%'";
				//echo "SELECT * FROM $strTable WHERE $strCondition";
				$engs = fncSelectConditionRecord($strTable,$strCondition," order by name");
				if($id=='aa'){
					$sel = "<select class=\"assignengineername\" id='".$id."_".$forjid."' for='".$forjid."' ".$enb.">";
				}else{
					$sel = "<select class=\"engineername\" id='".$id."_".$forjid."' for='".$forjid."' ".$enb.">";
				}
				$sel .= "<option value=0>เลือกช่างรับงาน</option>";
				while($eng = mysql_fetch_array($engs)){
					$select = '';
					if($dval==$eng['id']) $select = "selected";
					$sel .= "<option value='".$eng['id']."' ".$select.">".$eng['name']." (".$eng['nickname'].")</option>";
				}
				$sel .= "</select>";
				return $sel;
			}


			function conftimeList($id,$forjid,$enb,$dval=''){
				$sel = "<select class=\"conftime\" dir=\"rtl\" id='".$id."_".$forjid."' for='".$forjid."' ".$enb.">";
				$sel .= "<option value=0>..</option>";
				for($i=7;$i<22;$i++){
					$select = '';
					$iH = $i;
					if($i<10) $iH = '0'.$i;
					if($dval==$iH.":00:00") $select = "selected";
					$sel .= "<option value='".$i.":00' ".$select.">".$i.":00</option>";
				}
				$sel .= "</select>";
				return $sel;
			}
/*
			function resultcnfList($id,$forjid,$dval=''){
				if($_COOKIE['permission']==1){
					$icV = array('','รับนัด','ยกเลิก','เลื่อน','ไม่รับสาย','โทรไม่ติด','ดึงงาน');
				} else {
					$icV = array('','รับนัด','ยกเลิก','เลื่อน','ไม่รับสาย','โทรไม่ติด');
				}
				$reSel = "<select class=\"resconftime\" id=\"".$id."_".$forjid."\" for=\"".$forjid."\">";
				$reSel .= "<option>ผลนัด</option>";
				$reSel .= "<optgroup label=\"ลูกค้า\">";
					for($ic=11; $ic<=15; $ic++){
						$selt = "";
						if($ic-$dval==0 and $ic<14) $selt = "selected";
						$reSel .= "<option value=\"".$ic."\" ".$selt.">".$icV[$ic-10]."</option>";
					}
				$reSel .= "</optgroup>";
				$reSel .= "<optgroup label=\"เซลล์\">";
					for($ic=21; $ic<=26; $ic++){
						$selt = "";
						if($ic-$dval==0 and $ic<24) $selt = "selected";
						if($ic==26){
							if($_COOKIE['permission']==1){
								$selt = "style=\"background-color:brown;color:#fff;\"";
								$reSel .= "<option value=\"".$ic."\" ".$selt.">".$icV[$ic-20]."</option>";
							}
						} else {
							$reSel .= "<option value=\"".$ic."\" ".$selt.">".$icV[$ic-20]."</option>";
						}
					}
				$reSel .= "</optgroup>";
				$reSel .= "</select>";
				return $reSel;
			}
*/
			function resultcnfList($id,$forjid,$dval=''){
				$icV = array('','รับนัด','ยกเลิก','เลื่อน','ไม่รับสาย','โทรไม่ติด','ดึงงาน');
				$reSel = "<select class=\"resconftime\" id=\"".$id."_".$forjid."\" for=\"".$forjid."\">";
				$reSel .= "<option>ผลนัด</option>";
				$reSel .= "<optgroup label=\"ลูกค้า\">";
					for($ic=11; $ic<=15; $ic++){
						$selt = "";
						if($ic-$dval==0 and $ic<14) $selt = "selected";
						$reSel .= "<option value=\"".$ic."\" ".$selt.">".$icV[$ic-10]."</option>";
					}
				$reSel .= "</optgroup>";
				$reSel .= "<optgroup label=\"เซลล์\">";
					for($ic=21; $ic<=26; $ic++){
						$selt = "";
						if($ic-$dval==0 and $ic<24) $selt = "selected";
						if($ic==26) $selt = "style=\"background-color:brown;color:#fff;\"";
						$reSel .= "<option value=\"".$ic."\" ".$selt.">".$icV[$ic-20]."</option>";
					}
				$reSel .= "</optgroup>";
				$reSel .= "</select>";
				return $reSel;
			}

			if(checkAllow(sit_assignconftime)){

				//=== ส่วนของงานเลื่อน ดิวเดท

			//	$tCfdate = "jobassign";
			//$cCfdate = "new_confdate='".$due."'";
			//	$nCfdate = fncSelectConditionRecord($tCfdate,$cCfdate);


				//while($ncf = mysql_fetch_array($nCfdate)){
					//echo "<tr><td colspan=\"7\">".$ncf['circuit']." ".$ncf['cust_name']." <a href=\"searchjobassignhistory.php?cir=".$ncf['circuit']."\" target=\"_blank\">ดูประวัติ</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			// เปลี่ยนดิวจาก <span style=\"color:orange\">".convdate($ncf['conf_date'])."</span> เป็น <span style=\"color:orange\">".convdate($ncf['new_confdate'])."</span> <a href=\"changeduedate.php?jid=".$ncf['jid']."&ncf=".$ncf['new_confdate']."\">ยืนยัน</a></td></tr>";
      //  }
				//=== ส่วนของงานเลื่อน ดิวเดท

				$strTable = "district";
				$jobZone = fncSelectRecord($strTable);
				$zoneOption = "<select id=\"zone\"><option value=\"0\">เลือกโซน</option>";
				while($jzone = mysql_fetch_array($jobZone)){
					$selected = "";
					if($zoneselected == $jzone[dname]) $selected = "selected";
					$zoneOption .= "<option ".$selected." value=\"".$jzone[dname]."\">".$jzone[dname]."</option>";
				}
				$zoneOption .= "</select>";


				//$strTable = "jobassign join tap_location on jobassign.tap=tap_location.tap";
				$strTable = "jobassign";
				$strCondition = "conf_date='".$due."' and job_status='D'";// and jid not in ( " . implode($confirmed, ", ") . " )";
				if($zoneselected<>'') $strCondition .= " and cust_addr like '%".$zoneselected."%'";
				$strSort = "order by jobassign.assigned_eng, jobassign.conftime";
				if($_GET['debug']) echo "11 >> SELECT * FROM ".$strTable." WHERE ".$strCondition." ".$strSort;
				$jbws = fncSelectConditionRecord($strTable,$strCondition,$strSort);

				while($jbw = mysql_fetch_array($jbws)){
					if($iy<1){
						$iy++;
						echo "<table>
							<tr class=\"label\">
								<td colspan=\"6\">รายชื่อลูกค้ารอการบันทึกนัดหมาย  ".convdate($due)." ".$zoneOption." ".engineerlist('aa','bb','cc',$abvt,$due)."</td>
							</tr>";
						if($jbw['assigned_eng']==0){
							?>
							<tr class="header">
								<td align="center">ลำดับ</td><td align="center"> Circuit </td><td align="center">ชื่อลูกค้า</td><!--<td align="center">เบอร์โทร</td>--><td>ผลการติดต่อ</td>
							</tr>
							<?php
						}
					}
					if($etemp<>$jbw['assigned_eng']){
						$etemp = $jbw['assigned_eng'];
						if($etemp>0){
							$w++;
							?>
								<tr class="header">
									<td align="center">ลำดับ</td><td align="center"> Circuit </td><td align="center">ชื่อลูกค้า / <?php echo nameofengineerMast($etemp)."(".nameofengineerMast($etemp,1).")" ?></td><!--<td align="center">เบอร์โทร</td>--><td>ผลการติดต่อ</td>
								</tr>
							<?php
						}
					}
					$enb = '';
					$typej = returntypejob($jbw['work_action'],$jbw['SO_CCSS_ORDER_TYPE'],$jbw['SO_CHG_ADDR_FLG'],$jbw['CATV_FLG'],$jb['jobname'],$jb['sodoctype'],$jb['bundle']);
					//if($jbw['jobname']=='FTTH' or $jbw['jobname']=='FTTX') $typej='<span style="color:#F88017;">New FTTH</span>';


				     if($jbw['jobname']=='FTTX'){
				    	$foa = 'FTTx';
				    	$tv = "";
				    	//chg add
				    	if($jbw['sodoctype'] == 'FLP' and $jbw['SO_CCSS_ORDER_TYPE'] =='C') {
				    			$typej='<span style="color:#F88017;">Chg Add '.$foa.'</span>';
				    	//new connection
				    	}elseif($jbw['sodoctype'] == 'HSI' and $jbw['SO_CCSS_ORDER_TYPE'] =='I') {
				    	//if bundle <> ""	echo +tv
				    			if ($jbw['bundle'] <> "") {
				    				$tv = "+TV";
				    			}
				    			$typej='<span style="color:#F88017;">'.$foa.''.$tv.'</span>';
				    	//Disconnection
				    	}elseif($jbw['sodoctype'] == 'HSI' and $jbw['SO_CCSS_ORDER_TYPE'] =='D') {
				    			$typej='<span style="color:#F88017;">Dis '.$foa.'</span>';
				    	//Disconnection or add additional TV
				    	}elseif($jbw['sodoctype'] == 'FIBERTV' and $jbw['SO_CCSS_ORDER_TYPE'] =='C') {
				    			$typej='<span style="color:#F88017;">Dis/New FIBERTV</span>';
				    	}
				    }

					if(in_array($jbw['result_cnf_code'],array(11,12,13,21,22,23))) $enb = 'disabled';
					$mk = "<img height=\"20\" src=\"http://maps.google.com/mapfiles/ms/icons/red-dot.png\" title=\"".$jbw['lat']." : ".$jbw['lng']." [".$jbw['cust_addr']."]\">";
					echo "<tr style=\"background-color:#".$colorArray[$w].";\">";
					echo "<td align=\"center\" rowspan=\"2\" style=\"vertical-align:middle;\">".++$i."</td>";

					$fixedLine = $jbw['fixedlineno'];
					if($jbw['fixedlineno']=='') $fixedLine = "<span class=\"fxTxt\" id=\"fxTxt_".$jbw['jid']."\" for=\"".$jbw['jid']."\" style=\"color:blue;font-size:10px;\">Fixedline No.</span><input type=\"text\" class=\"textfixedline\" size=\"7\" for=\"".$jbw['jid']."\" hidden id=\"textfx_".$jbw['jid']."\">";

					$bundle = $jbw['bundle'];
					if($jbw['bundle']=='') $bundle = "<span class=\"bndTxt\" id=\"bndTxt_".$jbw['jid']."\" for=\"".$jbw['jid']."\" style=\"color:blue;font-size:10px;\">Bundle No.</span><input type=\"text\" class=\"textbundle\" size=\"7\" for=\"".$jbw['jid']."\" hidden id=\"textbnd_".$jbw['jid']."\">";


					echo "<td>".$jbw['circuit']."<br>".$bundle."<br>".$fixedLine."</td>";


					$cphone = explode(",",$jbw['cust_phone']);
					$pii = 0;
					$phoneTXT = "";
					$insert_phone = "<span class=\"phTxt\" id=\"phTxt_".$jbw['jid']."\" for=\"".$jbw['jid']."\" style=\"color:green;font-size:10px;\">คลิ๊กเพื่อบันทึกเบอร์โทร...</span><input type=\"text\" maxlength=\"10\" class=\"textphone\" for=\"".$jbw['jid']."\" hidden id=\"text_".$jbw['jid']."\">";
					for($pi=0; $pii<=count($cphone); $pii++){
						if($cphone[$pii]<>'') $phoneTXT .= "<a href=\"tel:".$cphone[$pii]."\">".$cphone[$pii]."</a> ";
					}
					if($jbw['salephone']<>''){
						$salephoneTxt = $jbw['salephone'];
					}else{
						$salephoneTxt = " <span class=\"button salephonebtn\" id=\"spbtn_".$jbw['jid']."\" for=\"".$jbw['jid']."\"  style=\"font-size:8px;color:saddlebrown;float:right;\">[ใส่เบอร์เซลล์]</span><input style=\"width:60px;float:right;\" type=\"text\" class=\"spinpt\" for=\"".$jbw['jid']."\" id=\"spinpt_".$jbw['jid']."\" hidden>";
					}
					/*
					if($phoneTXT=='') $phoneTXT = "<span class=\"phTxt\" id=\"phTxt_".$jbw['jid']."\" for=\"".$jbw['jid']."\" style=\"color:blue;font-size:10px;\">คลิ๊กเพื่อบันทึกเบอร์โทร...</span><input type=\"text\" class=\"textphone\" for=\"".$jbw['jid']."\" hidden id=\"text_".$jbw['jid']."\">";
					*/
					echo "<td>".$mk." <span id=\"custname_".$jbw['jid']."\">".$jbw['cust_name']."</span> [ <span style=\"".$styleV."\">".$jbw['handler_id']." / ".$jbw['handler_name']."</span> ] <br>".$phoneTXT." ".$insert_phone."<br>".$jbw['cust_addr']."</td>";

					//20160810 เพิ่ม ส่วนของที่อยู่ ในกรณีเมื่อดูผ่าน smartphone มันเป็นรูป บางทีไม่เห็น

					//echo "<td>".$salephoneTxt."</td>";

					//echo "<td><input type=\"text\" style=\"width:90px;\"> <button>เบอร์เซลล์</button></td>";

					//echo "<td rowspan=\"2\"><div id=\"resdiv_".$jbw['jid']."\">sdfg</div></td><td rowspan=\"2\"></td>";
					echo "<td rowspan=\"2\" valign=\"top\"><input disabled type=\"text\" weight=\"50\" height=\"20\" id=\"resTxt_".$jbw['jid']."\">  <button disabled id=\"".$jbw['jid']."\" class=\"butsave\"> save </button> <input type=\"checkbox\" id=\"check_".$jbw['jid']."\" style=\"display:none\"> <span id=\"sp_".$jbw['jid']."\" style=\"display:none\">ติ๊กเพื่อส่ง sms เมื่อ save</span>";

					$strComment = "memo_appointment";
					$strCommentSort = " order by memo_date_time";
					$strCommentCondition = "jid='".$jbw['jid']."'";

					$comments = fncSelectConditionRecord($strComment,$strCommentCondition,$strCommentSort);
					if($_GET['debug']) echo "SELECT * FROM $strComment WHERE $strCommentCondition  $strCommentSort";
					$cm = 0;
					$sl = 0;
					$hisTxt = '';
					$txtAppointment = '';
					$cfdatetemp = '';
					while($cmm = mysql_fetch_array($comments)){
						/*if($cmm['due_date']== $due){
							if($cmm['result']<20){
								$cm++;
								$timeRectxt = "ลูกค้า ".$cm;
								$fclr = "#008080";
							}else{
								$sl++;
								$timeRectxt = "เซลล์ ".$sl;
								$fclr = "Olive";
							}
							echo $timeRectxt." : <span style=\"color:".$fclr."\">".$cmm['memotxt']."</span> <span style=\"color:red;font-size:10px;\">*".nameofengineer($cmm['who_did'],1)."</span><br>";
						}else*/
						if($cmm['due_date'] <= $due){
							if($cfdatetemp <> $cmm['due_date']){
								$txtAppointment .= "<span style=\"color:blue;\">นัดติดตั้งวันที่ ".convdateMini($cmm['due_date'])."</span><br>";
								$cfdatetemp = $cmm['due_date'];
							}
							if((($cmm['result'] == '14') or ($cmm['result'] == '15')) and  (strpos($cmm['memotxt'], 'ส่ง sms แล้ว') == true)) {
							$target = "img/sendsms/".$jbw['jid']."_".$cmm['memo_date_time'].".png";
							$txtAppointment .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style=\"color:".$fclr."\">".convdateMini($cmm['memo_date_time'])." : ".$cmm['memotxt']."</span><a target =\"_blank\" href=\"".$target."\">รูปภาพ</a> <span style=\"color:red;font-size:10px;\">*".nameofengineer($cmm['who_did'],1)." [ช่าง:".nameofengineer($cmm['emp_id'],1)."]</span><br>";

							}else{
							$txtAppointment .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style=\"color:".$fclr."\">".convdateMini($cmm['memo_date_time'])." : ".$cmm['memotxt']."</span> <span style=\"color:red;font-size:10px;\">*".nameofengineer($cmm['who_did'],1)." [ช่าง:".nameofengineer($cmm['emp_id'],1)."]</span><br>";
							}
						}
						if($txtAppointment<>''){
							$hisTxt = "<span style=\"color:#8A2908;font-size:10px;\" class=\"historyAppmt\" for=\"".$jbw['jid']."\">ประวัตินัด</span><div id=\"appointmenthistory_".$jbw['jid']."\">".$txtAppointment."</div>";
						}
					}
					echo "<div id=\"comm_".$jbw['jid']."\">".$hisTxt."</div></td>";
					echo "</tr>";
					echo "<tr style=\"background-color:#".$colorArray[$w].";\">";
					echo "<td>".$typej."</td><td id=\"tdpull_".$jbw['jid']."\">";
					if($abvt=='ask'){
							if( ($_COOKIE["uid"]!==247) and ($_COOKIE["uid"]!==99) ){
								echo "<input type=\"checkbox\" name=\"checkboxEngineer[]\" value=\"".$jbw['jid']."\" id=\"checkb_".$jbw['jid']."\"> ".engineerlist('engsel',$jbw['jid'],$enb,$abvt,$due,$jbw['assigned_eng'])." ";
							} else {

							}
						echo conftimeList('cft',$jbw['jid'],$enb,$jbw['conftime'])." ".resultcnfList('rescnf',$jbw['jid'],$jbw['result_cnf_code'])."</td>";
					} else {
						echo "<input type=\"checkbox\" name=\"checkboxEngineer[]\" value=\"".$jbw['jid']."\" id=\"checkb_".$jbw['jid']."\"> ".engineerlist('engsel',$jbw['jid'],$enb,$abvt,$due,$jbw['assigned_eng'])." ".conftimeList('cft',$jbw['jid'],$enb,$jbw['conftime'])." ".resultcnfList('rescnf',$jbw['jid'],$jbw['result_cnf_code'])."</td>";
					}

					echo "</tr>";
					echo "<tr><td colspa=\"6\"></td></tr>";
					$wr=true;
				}
				if($wr) echo "</table>";

			}
			?>

		</td>
	</tr>
	<table>
	<tr style="background-color:#FFDCDC;">
		<td style="background-color:#FFDCDC;" colspan="5">
			<?php echo $catvJob?>
		</td>
	</tr>
	</table>
	<tr>
		<td class="container" colspan="5">
		<br>
		  <div id="map" style="width: 800px; height: 600px;"></div>
		</td>
	</tr>
</table>
  <script src="http://maps.google.com/maps/api/js?v=3.exp&signed_in=true" type="text/javascript"></script>
  <script type="text/javascript" src="http://gmaps-samples-v3.googlecode.com/svn/trunk/geolocate/geometa.js"></script>
  <script type="text/javascript">

	var locations = <?php echo $j;?>;

    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: <?php echo constant("ZOOMMAP")?>,
      center: new google.maps.LatLng(<?php echo constant("LAT")?>,<?php echo constant("LNG")?>),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var infowindow = new google.maps.InfoWindow();

    var marker, i;
	var tempLoc;
	var colIcon = 0;
	mIcon = ["red-dot.png", "gray-dot.png", "blue-dot.png", "pink-dot.png", "yellow-dot.png", "green-dot.png", "gray-dot.png", "red-dot.png", "yellow-dot.png", "blue-dot.png", "purple-dot.png", "pink-dot.png"];
    for (i = 0; i < locations.length; i++) {
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        map: map
      });
	if(locations[i][4]!="9999"){
		if(locations[i][4]!=tempLoc){
			colIcon++;
			tempLoc = locations[i][4];
		}
		marker.setIcon('img/tidnet.png');
		if(i>0){
			marker.setIcon('http://maps.google.com/mapfiles/ms/icons/'+mIcon[colIcon]);
		}

	}else{
		//if(doesFileExist('img/employee/'+locations[i][5]+'_'+locations[i][5]+'.png')){
			marker.setIcon('img/employee/'+locations[i][5]+'_'+locations[i][5]+'.png');
		//}else{
			//marker.setIcon('img/employee/pIcon_smaill.png');
		//}
	}

      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(locations[i][0]);
          infowindow.open(map, marker);
        }
      })(marker, i));
    }
</script>
