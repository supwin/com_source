<?php
/*
log file

180614 0845 : แก้ไข convdate() และ convdateMini() ให้มี paramiter กรณีไม่ต้องการ time ตอน return เพื่อเริ่มใช้กับ prtinvtrueonline.php;
200614 1623 : เพิ่มความสามารถในการโอนย้าย อุปกรณ์ไปยังบริษัทข้างเคียงเพื่อยืมคืนกันได้  โดยเพิ่ม checkPermmissionNo($uid)
250614 2231 : เอา function getNewDocNum() จาก createdbinv.php มาไว้ใน function.php แล้ว
260614 1632 : update sumIncome() ให้ sum เฉพาะงานที่ปิดตัดสต๊อกแล้วเท่านั้น
060714 1853 : เพิ่ม พารามิเตอร์ใน checkCutStock() เพื่อตรวจเช็คตอน import ข้อมูลปิดงานกับในการตัดสต๊อกของช่างที่ตัดใน Tidnet Stock ว่าตรงกันรึไม่ หากไม่ตรงจะไม่อนุญาติให้ปิดงาน
070714 1836 : เพิ่ม function existCATVCover() ไว้สำหรับตรวจสอบใบปะหน้า CATV ที่ซ้ำ จะไม่ทำการ insert ซ้ำจากการทำงานของ ไฟล์ insertcatvreport.php
140714 1525 : เพิ่ม function checkintidnetstock() และ checkinreturnstock() เพื่อตรวจสอบการซ้ำซ้อนของ serial ในสต๊อกก่อน เก็บเข้าสต๊อก return
200714 0955 : เพิ่มเติมให้แสดงระยะสายในหน้าของ jobofdate ใน showjobofdate();
250714 1035 : เพิ่ม getqtyitem($acsId) เพือเอาค่า qty ของ item
270714 1117 : เพิ่มให้การใช้งาน feture เพิ่มค่าเดินทาง ให้เช็ค jobindex ด้วย สำหรับกรณีได้งานทั้ง ถอดและติด ทำใน  showjobofdate()
270714 1138 : แก้ไข getnewIDTypejob() ให้ค้นจาก indexjob แทน circuit
060814 1410 : เพิ่มเติมให้ checkintidnetstock ตรวจสอบ 97 ด้วย
			: updateEqmSN($sn,$closedcir='') เพิ่ม closedcircuit ด้วย ตอน update eqm_sn
200914 1516 : แก้ไข addtime ใน tidnetNow();
300914 0621 : เพิ่มเิติมให้ showjobofdate สามารถยกเลิกการปิดงาน บันทึก serial ได้ เพื่อบันทึกใหม่กรณีบันทึกผิดรายการ
021014 1542 : เพิ่ม getModemId($sn,$resiponcible=0) ให้สามารถกำหนด resiponcible ได้ด้วย
061014 1123 : เพิ่ม getEqmQTY()
061014 1907 : เพิ่มความสามารถด้านสิทธิ์ ในการยกเลิกใบงานติดตั้ง
081014 1418 : เพิ่มการแสดง nofify เมื่อมี modem รอเข้าสต๊อก
161014 0836 : เพิ่ม  nextnprevMonth() สำหรับทำ link next and prev month เพื่อ query จาก mysql
171014 1012 : ปรับแก้ linkMonth() เพื่อ getasclist.php
171014 1035 : ตัด jobofdate() ออกไปอยู่ที่ไฟล์ jobofdate เองแล้ว
171014 1127 : แก้เรื่อง next month สำหรับ mysql query
171014 2116 : เพิ่มให้ updateEqmSN($sn,$closedcir='',$responcible='99') สามารถส่ง code responcible ไปด้วยเพื่อแก้ไขงานที่ตัดสต๊อกโดยไม่ปิดงาน
181014 2333 : เพิ่มให้ trmlist.php คิดค่าปรับกรณีเกิน 3 วัน และ 5 วัน ตามลำดับ
*/
function tidnetNow(){
	return "ADDTIME(now(), '00:00:00')";
}

function nextnprevMonth($thismonth,$thisyear){
	if($thismonth=="") $thismonth = date('m');
	if($thisyear=="") $thisyear = date('Y');
	if($thismonth > 1 and $thismonth < 12){
		$link['mnext'] = $thismonth+1;
		$link['mprev'] = $thismonth-1;
		$link['ynext'] = $link['yprev'] = $thisyear;
	}else if($thismonth==1){
		$link['mnext'] = "2";
		$link['mprev'] = "12";
		$link['ynext'] = $thisyear;
		$link['yprev'] = $thisyear-1;
	}else if($thismonth==12){
		$link['mnext'] = "1";
		$link['mprev'] = "11";
		$link['ynext'] = $thisyear+1;
		$link['yprev'] = $thisyear;
	}
	$link['mcur'] = $thismonth;
	$link['ycur'] = $thisyear;
	$query['since'] = $thisyear."-".$thismonth."-01";
	$query['until'] = $link['ynext']."-".$link['mnext']."-01";
	$return['link'] = $link;
	$return['query'] = $query;
	return $return;
}

function monthTHtomonthNum($mTH){
	switch ($mTH){
		case 'ม.ค.':
			return '01';
		break;
		case 'ก.พ.':
			return '02';
		break;
		case 'มี.ค.':
			return '03';
		break;
		case 'เม.ย.':
			return '04';
		break;
		case 'พ.ค.':
			return '05';
		break;
		case 'มิ.ย.':
			return '06';
		break;
		case 'ก.ค.':
			return '07';
		break;
		case 'ส.ค.':
			return '08';
		break;
		case 'ก.ย.':
			return '09';
		break;
		case 'ต.ค.':
			return '10';
		break;
		case 'พ.ย.':
			return '11';
		break;
		case 'ธ.ค.':
			return '12';
		break;
	}
}

function revertDate($datetxt){
	$d = explode(" ",$datetxt);
	$yNum = $d['2']+1957;
	return $yNum."-".monthTHtomonthNum($d['1'])."-".$d['0'];
}

function linkMonth($link,$more='',$page=''){
	if($page=='') $page = $_SERVER['PHP_SELF'];
	$curmonth = $page;
	if($more<>''){
		$curmonth .= "?".$more;
		$more .="&";
	}
	if($link['ycur']."-".$link['mcur']<date('Ym')) $nextTXT =  " | <a href=\"".$page."?".$more."m=".$link['mnext']."&y=".$link['ynext']."\">เดือน".convmonth($link['mnext'])." >></a>";
	return "<a href=\"".$page."?".$more."m=".$link['mprev']."&y=".$link['yprev']."\"><< เดือน".convmonth($link['mprev'])."</a> | ข้อมูลเดือน".convmonth($link['mcur'])." <a href=\"".$curmonth."\">[ ปัจจุบัน ]</a>".$nextTXT;
}

function convdate($date,$time=1) {  // ถ้า  $time=0 จะไม่ใส่เวลาพ่วงท้ายด้วย
	if($date == '0000-00-00' or $date == '0000-00-00 00:00:00'){
		return '---';
	}else{
		$MONTH = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฏาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
		$datetime = explode(" ", $date);
		$dt = explode("-", $datetime[0]);
		$tyear = $dt[0];
		$dt[0] = $dt[2] +0;
		$dt[1] = $MONTH[$dt[1]+0];
		$dt[2] = $tyear+543;
		if($time==1) $timetxt = $datetime[1];
		return join(" ", $dt)." ".$timetxt;
	}
}

function convdateMini($date,$time=1) {  // ถ้า  $time=0 จะไม่ใส่เวลาพ่วงท้ายด้วย
	if($date == '0000-00-00' or $date == '0000-00-00 00:00:00'){
		return '---';
	}else{
		$MONTH = array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
		$datetime = explode(" ", $date);
		$dt = explode("-", $datetime[0]);
		$tyear = $dt[0];
		$dt[0] = $dt[2] +0;
		$dt[1] = $MONTH[$dt[1]+0];
		$dt[2] = ($tyear+543)-2500;
		if($time==1) $timetxt = $datetime[1];
		return join(" ", $dt)." ".$timetxt;
	}
}

function convDDMMYYtoYYYYMMDD($ddmmyyyy){
	$dnt = explode(' ',$ddmmyyyy);
	$part = explode('/',$dnt[0]);
/*
	if($part['1'] < 10){
		$lastone = "0".$part['1'];
	}else{
		$lastone = $part['1'];
	}*/
	return $part[2]."-".$part['1']."-".$part['0'];
}

function convmonth($m) {
    $MONTH = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฏาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
	return $MONTH[$m];
}

function convmonthMini($m) {
    $MONTH = array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
	return $MONTH[$m];
}

function dmyListFunction($name='',$dd='',$dm=''){
	?>
		<select name="date<?php echo $name?>Num" id="date<?php echo $name?>Num">
			<?php
			if($dd=='')$dd=date('d')+1;

			for($di=1;  $di<=31; $di++){
				$sel = "";
				if($di-$dd==0) $sel = "selected";
				echo "<option ".$sel." value=".$di.">".$di."</option>";
			}
			?>
		</select>
		<select name="month<?php echo $name?>Num" id="month<?php echo $name?>Num">
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
		<select name="year<?php echo $name?>Num" id="year<?php echo $name?>Num">
			<?php $thisy = date('Y')-1957;?>
			<option value="<?php echo date('Y')?>"><?php echo $thisy-1?></option>
			<option selected value="<?php echo date('Y')?>"><?php echo $thisy?></option>
			<option value="<?php echo date('Y')?>"><?php echo $thisy+1?></option>
		</select>
		<?php
}

function prvnxtYM($m,$y){ // ยังไม่ได้นำไปใช้ที่ใด
	$t['mcur'] = $m;
	$t['mpvr'] = $m-1;
	$t['mnxt'] = $m+1;

	$t['ycur'] = $y;
	$t['ypvr'] = $y-1;
	$t['ynxt'] = $y+1;

	if($t['mcur'] = 1){
		$t['mpvr'] = 12;
	}
	if($t['mcur'] = 12){
		$t['mnxm'] = 1;
	}
	return $t;
}


function checkAllow($field){
	if($_COOKIE['permission']==1){
		return true;
		die();
	}
	$strTable = "employee";
	$strCondition = "id='".$_COOKIE['uid']."'";
	$emp = fncSelectSingleRecord($strTable,$strCondition);
	if($emp[$field]==1){
		return true;
	}else{
		return false;
	}
}
function checkPermmissionNo($uid){
	$strTable = "employee";
	$strCondition = "id=$uid";
	$engineer = fncSelectSingleRecord($strTable,$strCondition);
	return $engineer['permission'];
}

function nameofengineer($eid,$nickname=0){
	$strTable = "employee";
	$strCondition = "id=$eid";
	$engineer = fncSelectSingleRecord($strTable,$strCondition);
	if($nickname){
		return $engineer['nickname'];
	}else{
		return $engineer['name'];
	}
}

function getmail($uid){
	$strTable = "employee";
	$strCondition = "id=$uid";
	$engineer = fncSelectSingleRecord($strTable,$strCondition);
	return $engineer['email'];
}

function getIDEng($empName){
	$strTable = "employee";
	$strCondition = "name='".$empName."'";
	$engineer = fncSelectSingleRecord($strTable,$strCondition);
	return $engineer['id'];
}

function getItemName($itemId){
	$strTable = "stock_acs ";
	$strCondition = "id='".$itemId."'";
	$item = fncSelectSingleRecord($strTable,$strCondition);
	return $item['description'];
}

function getModemId($sn,$resiponcible=0){
	$strTable = "eqm_sn";
	$strCondition = "sn = '".$sn."' and responcible='".$resiponcible."'";
	$eqsn = fncSelectSingleRecord($strTable,$strCondition);
	return $eqsn['id_eqm'];
}

function getmaillistbysit($field){
	$strTable = "employee";
	$strCondition = $field."='1'";
	$allobj = fncSelectConditionRecord($strTable,$strCondition);
	while($obj = mysql_fetch_array($allobj)){
		$mail .= ",".$obj['email'];
	}
	return $mail;
}

function getemplist($name){
	$strTable = "employee";
	$strCondition = "permission>1 and permission<10 and nickname<>''";
	$allobj = fncSelectConditionRecord($strTable,$strCondition);
	echo "<select name=\"".$name."\" id=\"".$name."\">";
	while($obj = mysql_fetch_array($allobj)){
		echo "<option value=\"".$obj['id']."\">".$obj['name']." [".$obj['nickname']."]</option>";
	}
	echo "</select>";
}

function partnerlist($showat,$name,$defultselected=''){
	$strTable = "partners";
	$strCondition = "showat like '%@".$showat."@%'";
	$allprt = fncSelectConditionRecord($strTable,$strCondition);
	echo "<select name=\"".$name."\" id=\"".$name."\">";
	echo "<option value=\"0\">กรุณารายการที่ต้องการ</option>";
	while($prt = mysql_fetch_array($allprt)){

		echo "<option value=\"".$prt['id']."\" creditor=\"".$prt['creditor_acc']."\" title=\"".$prt['thaddr']."]\" ".($defultselected==$prt['id']? "selected" : "").">".$prt['thname']."</option>";
	}
	echo "</select>";
}


function acclist($showat,$name,$defultselected=''){
	$strTable = "chartofacc";
	$strCondition = "showat like '%@".$showat."@%'";
	$allacc = fncSelectConditionRecord($strTable,$strCondition);
	echo "<select name=\"".$name."\" id=\"".$name."\">";
	echo "<option value=\"0\">กรุณาเลือกบัญชีที่ต้องการ</option>";
	while($acc = mysql_fetch_array($allacc)){

		echo "<option value=\"".$acc['id']."\" title=\"".$acc['description']."\" ".($defultselected==$acc['id']? "selected" : "").">".$acc['acc_name']."</option>";
	}
	echo "</select>";
}

function getwaitinginstock($itemId){
	$strTable = "po_header,po_detail";
	$star = "item_id, SUM( qty )-(SUM( gotqty )+SUM(cancelqty)) AS waiting";
	$strCondition = "item_id =  '".$itemId."' AND pono = poheaderno AND STATUS IN ('new','sent','gotsome')";
	$strSort = "GROUP BY item_id";
	$itemwaiting = fncSelectStarConditionRecord($star,$strTable,$strCondition,$strSort);
	$itemqty = mysql_fetch_array($itemwaiting);
	return $itemqty[waiting];
}

function diffDateMysql($closeddate){  // ตรวจสอบ เดือนของงานที่ส่งวันที่มา เลยสิ้นเดือนมาเกิน 2 วันหรือไม่ เพื่อปิดการ ปรับแก้ค่าเดินทาง

	$dt = explode("-", $closeddate);
	if (in_array($dt[1],array('1','3','5','7','8','10','12'))){
		$d = '31';
	}else{
		$d = '30';
	}
	$strdate = $dt[0]."-".$dt[1]."-".$d;
	date_default_timezone_set('Asia/Bangkok');
	$stpdate = date("Y-m-d");
	$diff = strtotime($stpdate)-strtotime($strdate);
	if($diff < 0) $diff = 0; // ตรวจสอบค่าต่างเป็นลบ ให้เครียร์เป็น 0

	return abs($diff)/(60*60*24) ;
}

function getTypeJobfromClosedjob($cir){
	$clsTable = "closedjob";
	$clsConditon = "circuit='".$cir."'";
	$j = fncSelectSingleRecord($clsTable,$clsConditon);
	return $j['typejob'];
}


function convdatexls($date){
	if(strpos($date," ")){
		$d = explode(" ", $date);
		$date = $d[0];
	}
    $dt = explode("/", $date);
	return $dt[2]."-".$dt[1]."-".$dt[0];
}

function jobTrans($j){
	if($j=='net') return "net";
	if($j=='tv') return "tv";
	if($j=='tvext') return "tv + ค่าเดินทาง";
	if($j=='netdisc') return "net รื้อถอน";
	if($j=='tvdisc') return "tv รื้อถอน";
	if($j=='chgeq') return "เปลี่ยนอุปกรณ์";
}

function mergeJob($cable,$type,$dwsum){
		$cbtype = trim($cable);
		if(($cbtype == 'RG6') OR ($cbtype == 'RG11')){
			$dw = '';
			if(($dwsum>=41)and($cbtype == 'RG6')) $dw = "+41";
			$nameOfJob =  $type."[".trim($cable)."]".$dw;
		}else{
			$nameOfJob =  $type;
		}
		$strTable = "tidnet_common.typeofjob";
		$strCondition = "keyword='".$nameOfJob."'";
		$j = fncSelectSingleRecord($strTable,$strCondition);

		return $j[id];
}

function checkCutStock($cir,$engId='0'){
	$strTable = "eqm_sn";
	$strCondition = "circuit='".$cir."'";
	if($engId>0) $strCondition .= " and responcible='".$engId."'";
	return fncSelectSingleRecord($strTable,$strCondition);
}

function insertClosedJob($strValue){
	$strTable = "closedjob";
	$strField = "closeddate,circuit,emp_id,typejob,price,overrange,series";
	return fncInsertRecord($strTable,$strField,$strValue);
}

function updateEqmSN($sn,$closedcir='',$responcible='9099'){
	$strTable = "eqm_sn";
	$strCommand = "circuit='', closedcircuit='".$closedcir."', responcible='".$responcible."', date_movement=".tidnetNow();
	$strCondition = "sn='".$sn."'";
	return fncUpdateRecord($strTable,$strCommand,$strCondition);
}

function existModel($model){
	$strTable = "eqm_model";
	$strCondition = " id = '$model'";
	return fncSelectSingleRecord($strTable,$strCondition);
}

function echoSuccf($txt){
	echo "<div class=\"succf\">".$txt."</div>";
}

function echoError($txt){
	echo "<div class=\"errort\">".$txt."</div>";
}

function checkExistClosedJob($dateC,$circuit,$typejob){
	$strTable = "closedjob";
	$typejobField = "typejob = '".$typejob."'";
	if(($typejob=='6') or ($typejob=='12')) $typejobField = "typejob in ('6','12')";
	if(($typejob=='7') or ($typejob=='13')) $typejobField = "typejob in ('7','13')";
	$strCondition = "closeddate='".$dateC."' and circuit='".$circuit."' and ".$typejobField;
	return fncSelectSingleRecord($strTable,$strCondition);
}

function cost2price($price){
	if($price == '1850'){
		return 1400;
	}else if($price == '1480'){
		return 1150;
	}else if($price == '350'){
		return 200;
	}else if($price == '250'){
		return 150;
	}else{
		return $price;
	}
}

function sumIncome(){
	if($_COOKIE['permission']==4){
		$y = date(Y);
		$m = date(n);
		$strTable = "closedjob";
		$sumField = "price+overrange";
		$strCondition = "emp_id='".$_COOKIE['uid']."' and (closeddate between '".$y."-".$m."-01' and '".$y."-".$m."-31') and series<>''";
		return fncSumFieldRecord($strTable,$sumField,$strCondition);
	}
}

function sumBuyacs($m,$y,$eid=''){
	if(($_COOKIE['permission']==4) or ($eid<>'')){  // จากหน้าปริ้นใบตั้งเบิก permission admin จะได้สามารถทำได้เพราะส่ง eid มาด้วย
		if($eid=='') $eid = $_COOKIE['uid'];
		$strTable = "acs_billheader,acs_billdetail";
		$sumField = "item_qty*item_price";
		$strCondition = "billtoemp_id='".$eid."' and acs_billheader.id=acs_billdetail.header_id  and (billdatetime between '".$y."-".$m."-01' and '".$y."-".$m."-31')";
		return fncSumFieldRecord($strTable,$sumField,$strCondition);
	}
}

function getStock($mdType){
	$strTable = "eqm_sn";
	$strCondition = "id_eqm='".$mdType."' and responcible='".$_COOKIE['uid']."' and circuit=''";
	return fncCountRow($strTable,$strCondition);
}



function getEngFrmSN($sn){
	$strTable = "eqm_sn";
	$strCondition = "sn='".$sn."'";
	$obj = fncSelectSingleRecord($strTable,$strCondition);
	return $obj[responcible];
}

function getpricejob($typejob){
	$strTable = "tidnet_common.typeofjob";
	$strCondition = "id='".$typejob."'";
	$obj = fncSelectSingleRecord($strTable,$strCondition);
	return $obj[tidnet2sub];
}

function getnewIDTypejob($indexjob){
	$strTable = "closedjob";
	$strCondition = "indexjob='".$indexjob."'";
	$obj = fncSelectSingleRecord($strTable,$strCondition);
	if($obj[typejob]>='10') return $obj[typejob]-6;
	if($obj[typejob]<='10') return $obj[typejob]+6;
}

function genDocNo($docid){
	$strTable = "doc_series";
	$strCondition = "id='".$docid."'";
	$d = fncSelectSingleRecord($strTable,$strCondition);
	$num = $d['current'] + $d['seq'];
	if(updateDocNo($docid,$num)){
		return $d['prefix']." ".$num." ".$d['postfix'];
	}else{
		return "Generating Failed.";
	}
}

function updateDocNo($docid,$num){
	$strTable = "doc_series";
	$strCommand = "current='".$num."',date_update=".tidnetNow();
	$strCondition = "id='".$docid."'";
	return fncUpdateRecord($strTable,$strCommand,$strCondition);
}

function newDraftPO($draftNo,$suppId){
	$strTable = "po_header";
	$strField = "draftno,date_draft,whodid_draft,status,supplier_id";
	$strValue = "'".$draftNo."',".tidnetNow().",'".$_COOKIE['uid']."','draft','".$suppId."'";
	return fncInsertRecord($strTable,$strField,$strValue);
}

function commandMearge($m,$n){
	if($m==''){
		$m = $n;
	}else{
		$m .= ",".$n;
	}

	return $m;
}

function updaeclosedjob($cir,$indexjob,$sn,$closeddate,$typejob){
	$strTable = "closedjob";
	$strCommand = "emp_id='97',series='".$sn."'";
	$strCondition = "indexjob='".$indexjob."' and circuit='".$cir."' and closeddate='".$closeddate."' and typejob='".$typejob."'";
	return fncUpdateRecord($strTable,$strCommand,$strCondition);
}


function createTRMRecord($circuit,$custname,$importfrom,$datesh,$datecp,$total='',$pack=''){
	$trm = duplicateCheck($circuit);
	$dateship = convDDMMYYtoYYYYMMDD($datesh);
	$datecomp = convDDMMYYtoYYYYMMDD($datecp);
	// หา id ช่างติดตั้ง ------------------
	$engId = getEngFromClosedJob($circuit);
	if($engId==''){
		$sn = checkCutStock($circuit);
		$engId = $sn['responcible'];
	}
	// หา id ช่างติดตั้ง ------------------


	$strTable = "trm";
	if($trm[circuit]==$circuit){
		echo "ข้อมูล TRM สมาชิก ".$circuit." มีอยู่แล้ว ";
		$strCondition = " circuit = '".$circuit."'";

		if($trm[cust_name]=='') $strCommand=commandMearge($strCommand," cust_name='".$custname."'");
		if($trm[total]==0) $strCommand=commandMearge($strCommand," total='".$total."'");

		if($trm['date_ship']=="0000-00-00") $strCommand=commandMearge($strCommand," date_ship='".$dateship."'");
		if($trm['date_complete']=="0000-00-00") $strCommand=commandMearge($strCommand," date_complete='".$datecomp."'");

		if($trm['from_'.$importfrom]=="0000-00-00 00:00:00"){
			$strCommand=commandMearge($strCommand," from_".$importfrom."=".tidnetNow());
			echo "update ข้อมูล TRM จาก ".$importfrom." เรียบร้อย ";
		}else{
			echo "update ข้อมูล TRM จาก ".$importfrom." ไปก่อนแล้ว ";
		}

		if($trm['engineer_id']==0){
			if($engId<>0 and $engId<>''){
				echo "update ข้อมูลช่าง".nameofengineer($engId)." เป็นผู้ติดตั้งเรียบร้อย";
				$strCommand=commandMearge($strCommand," engineer_id='".$engId."'");
			}
		}else{
			//$engIdUpdate = $trm['engineer_id'];
			echo "บันทึกข้อมูลช่าง".nameofengineer($trm['engineer_id'])." เป็นผู้ติดตั้งอยู่แล้ว";
		}


		//$strCommand = $updatetiem."engineer_id='".$engIdUpdate."', total='".$total."',cust_name='".$custname."'";
		//echo "<div>UPDATE $strTable SET  $strCommand WHERE $strCondition </div>";
		fncUpdateRecord($strTable,$strCommand,$strCondition);  // ซ้ำและ update เฉพาะชื่อช่างและเวลา import
	}else{
		if($pack == 'CP Enjoy Pack Special Ch'){
			$paidbill = "ฟรีค่าประกันอุปกรณ์";
			$confbill = "ฟรีค่าประกันอุปกรณ์";
			$statustxt = 4;
		}

		$strField = "circuit,status,from_".$importfrom.",engineer_id,total,cust_name,date_ship,date_complete,paid_billno,conf_billno";
		$strValue = "'".$circuit."','".$statustxt."',".tidnetNow().",'".$engId."','".$total."','".$custname."','".$dateship."','".$datecomp."','".$paidbill."','".$confbill."'";
		fncInsertRecord($strTable,$strField,$strValue);
		echo "ข้อมูล TRM สมาชิก ".$circuit." ได้ถูกเพิ่มแล้วจากข้อมูลของ ".$importfrom;
		if($engId<>0 and $engId<>'') echo " โดยช่าง".nameofengineer($engId);
	}
	echo "<br>INSERT INTO ".$strTable." (".$strField.") VALUES (".$strValue.") <br>";
}

function duplicateCheck($acc){
	$strTable = "trm";
	$strCondition = " circuit = '".$acc."'";
	return fncSelectSingleRecord($strTable,$strCondition);
}

function getEngFromClosedJob($cir){
	$strTable = "closedjob";
	$strCondition = "circuit='".$cir."' and typejob in ('7','13')";
	$job = fncSelectSingleRecord($strTable,$strCondition);
	return $job[emp_id];
}

function getCostFrmtypeJob($typeofJob){
	$strTable = "tidnet_common.typeofjob";
	$strCondition = "id='".$typeofJob."'";
	$c = fncSelectSingleRecord($strTable,$strCondition);
	return $c[tidnet2sub];
}

function closedmap($circuit){
	$strTable = "map";
	$strCommand = "status='1'";
	$strCondition = "circuit='".$circuit."'";
	fncUpdateRecord($strTable,$strCommand,$strCondition);
}

function sumTRMListEachOne($eid=''){

	if($eid<>'') $searchByEng = " and engineer_id='".$eid."'";

	$sql = "SELECT SUM(IF(status = \"1\", 1,0)) AS 'paidTrm', SUM(IF(status = \"0\", 1,0)) AS 'pendingTrm' FROM trm where 1".$searchByEng." GROUP BY engineer_id ORDER BY status DESC";

	$trm = fncSelectFullSQL($sql);

	if($trm['pendingTrm']>0) $classresult = "color:#ffffff;background-color:red;";
	echo "<tr><td colspan=\"2\" class=\"right\">TRM รอการชำระ</td><td style=\"".$classresult."\">".$trm['pendingTrm']." รายการ</td></tr>";
	echo "<tr><td colspan=\"2\" class=\"right\">TRM ชำระแล้วรอตรวจสอบ</td><td>".$trm['paidTrm']." รายการ</td></tr>";
}


function notificationOrder(){
	$orderTable = "ordering_acs_header";
	$orderCondition = " status = 'new'";
	return fncCountRow($orderTable,$orderCondition);
}

function notificationModemIn(){
	$table = "serial_tmp";
	$strCondition = '1';
	return fncCountRow($table,$strCondition);
}



function getNewDocNum($docid){
	$strTable = "doc_series";
	$strCondition = "id='".$docid."'";
	$docNum = fncSelectSingleRecord($strTable,$strCondition);
	$num = $docNum['current']+$docNum['seq'];
	$doc_id = $docNum['doc_id']+$docNum['seq'];
	$ret[3] = $num;
	for($i=strlen($num); $i<$docNum['digit']; $i++) $num = "0".$num;

	$strCommand = "current='".$num."', doc_id='".$doc_id."'";
	$strCondition .= " and current='".$docNum['current']."'";
	$ret[2] = true;
	//echo "UPDATE $strTable SET  $strCommand WHERE $strCondition ";
	if(!fncUpdateRecord($strTable,$strCommand,$strCondition)) $ret[2]=false;

	$ret[0] = $doc_id;
	$ret[1] = $docNum['prefix']."".$num."".$docNum['postfix'];
	return $ret;
}

function existCATVCover($jobcardNo){
	$strTable = 'catv';
	$strCondition = "jobcard_no='".$jobcardNo."'";
	$countRow = fncCountRow($strTable,$strCondition);
	if($countRow>=1)
		return true;
	else
		return false;
}

function checkintidnetstock($oldsn){
	$strTable = "eqm_sn";
	$strCondition = "responcible not in ('9099','9098','9097') and sn='".$oldsn."'";
	$countRow = fncCountRow($strTable,$strCondition);
	if($countRow>=1)
		return true;
	else
		return false;
}

function checkinreturnstock($oldsn){
	$strTable = "eqm_return";
	$strCondition = "status not in ('2','3') and series='".$oldsn."'";
	$countRow = fncCountRow($strTable,$strCondition);
	if($countRow>=1)
		return true;
	else
		return false;
}

function getqtyitem($acsId){
	$strTable = "stock_acs";
	$strCondition = "id='".$acsId."'";
	$row = fncSelectSingleRecord($strTable,$strCondition);
	return $row['qty'];
}

function getCustNameClosedjob($cir){
	$strTable = "closedjob";
	$strCondition = "circuit='".$cir."'";
	$row = fncSelectSingleRecord($strTable,$strCondition);
	return $row['cust_name'];
}

function typejobUseSN_NEWSN($tjob){
	$strTable = "tidnet_common.typeofjob,usesn";
	$strCondition = "tidnet_common.typeofjob.usesn=usesn.id and usesn.newsn='1' and tidnet_common.typeofjob.id='".$tjob."'";
	return  fncCountRow($strTable,$strCondition);
}

function typejobUseSN_OLDSN($tjob){
	$strTable = "tidnet_common.typeofjob,usesn";
	$strCondition = "tidnet_common.typeofjob.usesn=usesn.id and usesn.oldsn='1' and tidnet_common.typeofjob.id='".$tjob."'";
	return  fncCountRow($strTable,$strCondition);
}


function typejobUseSN_CODEENG($tjob){
	$strTable = "tidnet_common.typeofjob,usesn";
	$strCondition = "tidnet_common.typeofjob.usesn=usesn.id and usesn.codeeng='1' and tidnet_common.typeofjob.id='".$tjob."'";
	return  fncCountRow($strTable,$strCondition);
}

function checkstatusdigi($status,$nameID='statusSnReturn'){  // สำหรับงาน serail เสีย/ส่งคืน
	switch ($status){
		case 0:
			return "รอตรวจรับ";
		break;
		case 1:
			return "ตรวจรับแล้ว";
		break;
		case 2:
			return "ส่งคืนแล้ว";
		break;
		case 3:
			return "ชำระแล้ว";
		break;
	}
}

function detailStatus($status){

	switch ($status){
		case 'new':
			return "<span style=\"color:orange;font-weight:900;\">ใหม่</span>";
		break;
		case 'canceled':
			return "<span style=\"color:#999999;font-weight:900;\">ยกเลิกแล้ว</span>";
		break;
		case 'got':
			return "<span style=\"color:green;font-weight:900;\">รับของครบ</span>";
		break;
		case 'gotsome':
			return "<span style=\"color:blue;font-weight:900;\">รับไม่ครบ</span>";
		break;
		case 'sent':
			return "<span style=\"color:brown;font-weight:900;\">สั่งไปแล้ว</span>";
		break;
		case 'stop':
			return "<span style=\"color:brown;font-weight:900;\">หยุดรอ</span>";
		break;
	}
}


function getEqmQTY($eqmId,$empId=0){  // $empId เอาไว้ใช้การนับ sn ของช่างด้วย
	$strTable = "eqm_sn";
	$strCondition = "id_eqm='".$eqmId."' and responcible='".$empId."'";
	return fncCountRow($strTable,$strCondition);
}

function updateChargebackTRM($id,$chargeback){
	$strTable = "trm";
	$strCommand = "chargeback='".$chargeback."' ,chargeback_date=".tidnetnow();
	$strCondition = "id='".$id."'";
	fncUpdateRecord($strTable,$strCommand,$strCondition);
}

function ismobile() {
    $is_mobile = '0';

    if(preg_match('/(android|up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
        $is_mobile=1;
    }

    if((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml')>0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
        $is_mobile=1;
    }

    $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4));
    $mobile_agents = array('w3c ','acs-','alav','alca','amoi','andr','audi','avan','benq','bird','blac','blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno','ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-','maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-','newt','noki','oper','palm','pana','pant','phil','play','port','prox','qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar','sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-','tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp','wapr','webc','winw','winw','xda','xda-');

    if(in_array($mobile_ua,$mobile_agents)) {
        $is_mobile=1;
    }

    if (isset($_SERVER['ALL_HTTP'])) {
        if (strpos(strtolower($_SERVER['ALL_HTTP']),'OperaMini')>0) {
            $is_mobile=1;
        }
    }

    if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'windows')>0) {
        $is_mobile=0;
    }

    return $is_mobile;
}


function parse_number($number, $dec_point=null) {
    if (empty($dec_point)) {
        $locale = localeconv();
        $dec_point = $locale['decimal_point'];
    }
    return floatval(str_replace($dec_point, '.', preg_replace('/[^\d'.preg_quote($dec_point).']/', '', $number)));
}


function select_bundle($condition_checkbundle) {
	$strSQL = "SELECT * FROM jobassign WHERE $condition_checkbundle";
	return @mysql_query($strSQL);
}

?>
