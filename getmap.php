
<?php
include('cookies.php');
include('functions/function.php');
include('db_function/phpMySQLFunctionDatabase.php');
mysql_select_db("tidnet_".$abvt);

$so_no = $_POST['so_no'];
$tap = $_POST['tap'];
$wact = $_POST['wact'];

header("Content-Type:text/plain; charset=utf-8;");
$_handle = curl_init();
curl_setopt($_handle, CURLOPT_URL, "https://gateway.truecorp.co.th/install/searchServiceOrderOption.do?action=load&mode=30&orderId=$so_no&workActn=$wact");
curl_setopt($_handle, CURLOPT_HEADER, 0);
curl_setopt($_handle, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($_handle, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
$_results = curl_exec($_handle);

if(curl_errno($_handle)){
    echo curl_error($_handle);
}
//echo htmlentities($_results);


$count;
preg_match_all("/<[^>]*?>(.+)<\/[^>]*?>/i", $_results, $_match);
foreach($_match[0] as $_text){

    /*$txtlat = stristr(strip_tags($_text),"100.923828");   เอาไว้ตรวจสอบบรรทัดที่เท่าไหร่
    if($txtlat == "100.923828"){
    echo strip_tags(str_replace('&nbsp;','',$_text));
    echo "count is ".$count;
    }
    */
    if($count == "18"){
    $lat = strip_tags(str_replace('&nbsp;','',$_text));
    }
    if($count == "20"){
    $lng = strip_tags(str_replace('&nbsp;','',$_text));
    }

$count++;
}
curl_close($_handle);

//echo "lat is".$lat." :  lng is".$lng;

$strTable = "tap_location";
$strField = "tap,lat,lng";
$strValue = "'".$tap."','".$lat."','".$lng."'";	
$strCondition = "tap='".$tap."'";
if((fncCountRow($strTable,$strCondition)<=0) and ($lat<>'') and ($lng<>'')){
	if(fncInsertRecord($strTable,$strField,$strValue)){
		echo "1";
	}else{
		echo "0";
	}
}else{
	echo "0";
}


?>
