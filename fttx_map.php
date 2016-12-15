<?php
//include('cookies.php');
include("functions/function.php");
include("headmenu.php");

$strTable = "fttxmap"; 
$strCondition = "1";
$strSort = " order by dp_splitter";
//$f = fncSelectRecord($strTable);
$f = fncSelectConditionRecord($strTable,$strCondition,$strSort);



$lst = "<table><tr><td align=\"center\"><span style=\"color:green;\">L2</span></td><td align=\"center\">อธิบายตำแหน่ง</td><td align=\"center\">LAT</td><td align=\"center\">LNG</td><td>ยืนยันตำแหน่ง</td></tr>";

$fjson='[';
while($fttx = mysql_fetch_array($f)){

	if($fttx['proved']){
		$proved = "<span style=\"color:green;\">ตรวจสอบแล้ว</span>";
		$bgcolor = "#9CFD89";
	}else{
		$proved = "<span>รอตรวจสอบ</span>";
		$bgcolor = "#FDF187";
	}

	if($fttx['cap_splitter']<>$fttx['dp_splitter']){
		$lst .= "<tr style=\"background-color:".$bgcolor.";\"><td><a target=\"_blank\" href=\"https://www.google.com/maps/place/".$fttx['lat'].",".$fttx['lng']."\">".$fttx['dp_splitter']."</a></td><td>".$fttx['dp_splitter_location']."</td><td>".$fttx['lat']."</td><td>".$fttx['lng']."</td><td>".$proved."</td></tr>";
		$typespliter = 'dp';
	}else{
		$typespliter = 'cap';
	}


	$jr++;

	if($fjson!='[')$fjson .= ",";

	$mapLink = "<a target=\"_blank\" href=\"https://www.google.com/maps/place/".$fttx['lat'].",".$fttx['lng']."\">Google Map</a>";

	$shw = "<span name=\"circ\">L1 : ".$fttx['cap_splitter']."</span><br><span name=\"circ\">L2 : ".$fttx['dp_splitter']."</span><br><span name=\"circ\">L2 Location : ".$fttx['dp_splitter_location']."</span><br>".$mapLink;
	
	$fjson .= "['".$shw."',".$fttx['lat'].",".$fttx['lng'].",".$jr.",'".$fttx['dp_splitter']."','".$typespliter."']";
	//echo "<br>".$jr;
}
$lst .= "</table>";
/*
$jr = 0;
$strTableGeo = "employee";
$strConditonGeo = "lat>0";
if($_GET['debug']) echo "SELECT * FROM $strTableGeo WHERE $strConditonGeo  $strSortGeo";
$geoAll = fncSelectConditionRecord($strTableGeo,$strConditonGeo);

while($geo = mysql_fetch_array($geoAll)){
	if($geo['lat']>0){
		$jr++;
		$fjson .= ",['".$geo['nickname']." ".convdateMini($geo['timeatgeo'])."',".$geo['lat'].",".$geo['lng'].",".$jr.",'9999','".$geo['id']."']";
	}
	//echo "<br>".$jr;
}*/


$fjson .= "]";

?>
<form action="insertfttxjobassign.php" method="post" enctype="multipart/form-data">
	<label for="file">Job Assign List</label>
	<input name="fileCSV" type="file" id="fileCSV">
	<input name="btnSubmit" type="submit" id="btnSubmit" value="Submit">  
	</form>
<br>
<div id="map" style="width: 800px; height: 1500px;"></div> 
<?php echo $lst;?>

  <script src="http://maps.google.com/maps/api/js?v=3.exp&signed_in=true" type="text/javascript"></script>
  <script type="text/javascript" src="http://gmaps-samples-v3.googlecode.com/svn/trunk/geolocate/geometa.js"></script>
  <script type="text/javascript">
	var locations = <?php echo $fjson;?>;

    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: <?php echo constant("ZOOMMAP")?>,
      center: new google.maps.LatLng(<?php echo constant("LAT")?>,<?php echo constant("LNG")?>),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var infowindow = new google.maps.InfoWindow();

    var marker, i;
	var tempLoc;
	//var colIcon = 0;
	//mIcon = ["red-dot.png", "blue-dot.png", "purple-dot.png", "yellow-dot.png", "green-dot.png","red-dot.png", "blue-dot.png", "purple-dot.png"];
    for (i = 0; i < locations.length; i++) {  
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        map: map
      });
	if(locations[i][5]=='dp'){
		marker.setIcon('http://maps.google.com/mapfiles/ms/icons/green-dot.png');
	}else if(locations[i][5]=='cap'){
		marker.setIcon('http://maps.google.com/mapfiles/ms/icons/red-dot.png');
	}
	/*
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
		marker.setIcon('img/employee/'+locations[i][5]+'_'+locations[i][5]+'.png');
	}*/
  	  
      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(locations[i][0]);
          infowindow.open(map, marker);
        }
      })(marker, i));
    }
</script>
