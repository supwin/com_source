 <?php
/*
Log file
200714 2226 : just created
*/
include('cookies.php');
include("functions/function.php");
include("db_function/phpMySQLFunctionDatabase.php");
$snlst = $_POST['snlst'];
$eid = $_COOKIE['uid'];

function getTd($sn){
	$strTable = "eqm_sn";
	$strCondition = "sn='".$sn."'";
	$stkList = fncSelectConditionRecord($strTable,$strCondition);
	$snInTable = mysql_fetch_array($stkList);
	
	if($snInTable['responcible']==9098) return '2';

	if($snInTable['responcible']==9099) return '3';

	if($snInTable['responcible']>0){
		return nameofengineer($snInTable['responcible']);
	}
	
	if($snInTable['sn']!='' and isset($snInTable['sn'])){
		$strT = "eqm_checkstock";
		$strF = "sn,checked_date,whodid";
		$strV = "'".$snInTable['sn']."',".tidnetNow().",'".$eid."'";
		fncInsertRecord($strT,$strF,$strV);
		return '1';
	}else{
		return '0';
	}	
}

$sneach = explode("\n",trim($snlst));

for($r=0; $r<count($sneach); $r++){
	$lst['sn'] = $sneach[$r];
	$lst['result'] = getTd($sneach[$r]);
	$seriallst[$r] = $lst;
}
echo json_encode($seriallst);
