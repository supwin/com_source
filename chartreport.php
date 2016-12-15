<?php
/*
Log file

ใช้เพื่อการแสดงจำนวน SN ของช่างแต่ละทีม

16 09 16 just created

*/

if($_COOKIE['user']==""){
	?>
	<script>
		window.location ='login_frm.php';
	</script>
	<?php
}

include('functions/function.php');
include("../com_source/headmenu.php");


$prevMonth = strtotime("previous month");
/*
$prevMonthPlush33days = strtotime("+33 days", $prevMonth);
$oneMonthAnd3NextDays = array();
while ($prevMonth <= $prevMonthPlush33days) {
  array_push($oneMonthAnd3NextDays,date("Y-m-d", $prevMonth));
  $prevMonth = strtotime("+1 days", $prevMonth);
}
if($_GET['debug']){
	echo "<pre>";
	var_dump($oneMonthAnd3NextDays);
	echo "</pre>";
}*/

$star = "first_due, jobname, count(*) as cnt";
$strTable = "jobassign";
$strCondition = "first_due>='".date('Y-m-d',$prevMonth)."' and first_due<='".date('Y-m-d',strtotime("+33 days", $prevMonth))."'";
$strSort = " group by jobname, first_due order by jobname";

if($_GET['debug']) echo "SELECT $star FROM $strTable WHERE $strCondition  $strSort";
$jobgroup = fncSelectStarConditionRecord($star,$strTable,$strCondition,$strSort);
$dataPointTmp = '';
$r=0;
$dataPoint = array();
while($jobChart=mysql_fetch_array($jobgroup)){
  if($dataPointTmp<>$jobChart['jobname']){
    $dataPointTmp=$jobChart['jobname'];
    $data[$r] = $jobChart['jobname'];
    $dataPoint[$jobChart['jobname']] = array();
		$dateRun = $prevMonth;
		//echo "<p><div style=\"background-color:blue\">jobname=".$data[$r]."</div></p>";
    $r++;
  }
	while($jobChart['first_due'] <> date('Y-m-d',$dateRun)){
		//echo "<p><div style=\"background-color:green\">".$jobChart['first_due']." ".date('Y-m-d',$dateRun)."</div></p>";
		array_push($dataPoint[$jobChart['jobname']],array("y"=>0,"label" => date('Y-m-d',$dateRun)));
		$dateRun = strtotime("+1 days", $dateRun);
	}
	//echo "<p><div style=\"background-color:gray\">".$jobChart['first_due']." ".date('Y-m-d',$dateRun)."</div></p>";
  array_push($dataPoint[$jobChart['jobname']],array("y"=>$jobChart['cnt'],"label" => $jobChart['first_due']));
	$dateRun = strtotime("+1 days", $dateRun);
  //echo "<div>".count($dataPoint[$jobChart['jobname']])."</div>";
}


if($_GET['debug']){
   echo "<pre>";
   var_dump($dataPoint);
   echo "</pre>";
 }

?>



<script type="text/javascript">

    $(function () {
        var chart = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            title: {
                text: "ยอดจำนวนที่รับมาในแต่ละประเภท"
            },
            axisX: {
                title: "วันที่"
            },
            axisY: {
                title: "จำนวนงาน",
            },
            data: [
              <?php
              $all = count($data);
              for($i=0; $i<$all; $i++){
                ?>
            {
                type: "stackedColumn",
                legendText: "<?php echo $data[$i]?>",
                showInLegend: "true",
                indexLabelPlacement: "inside",
                indexLabelFontColor: "white",
                dataPoints: <?php echo json_encode($dataPoint[$data[$i]], JSON_NUMERIC_CHECK); ?>
            },
            <?php }?>
            ]
        });
        chart.render();
    });
</script>
<script src="jquery/canvasjs.min.js"></script>

<div id="chartContainer" style="height:400px; width:80%;"></div><br><br>


<?php
$star = "closeddate,description, typejob, count(*) as cnt";
$strTable = "closedjob join tidnet_common.typeofjob on typejob=tidnet_common.typeofjob.id";
$strCondition = "closeddate>='".date('Y-m-d',$prevMonth)."' and closeddate<='".date('Y-m-d',strtotime("+33 days", $prevMonth))."'";
$strSort = " group by typejob, closeddate order by typejob, closeddate";

if($_GET['debug']) echo "SELECT $star FROM $strTable WHERE $strCondition  $strSort";
$jobgroup = fncSelectStarConditionRecord($star,$strTable,$strCondition,$strSort);
$dataPointTmp = '';
$r=0;
$dataPoint = array();
while($jobChart=mysql_fetch_array($jobgroup)){
  if($dataPointTmp<>$jobChart['description']){
    $dataPointTmp=$jobChart['description'];
    $data[$r] = $jobChart['description'];
		$dateRun = $prevMonth;
    $dataPoint[$jobChart['description']] =  array();
    $r++;
  }
	while($jobChart['closeddate'] <> date('Y-m-d',$dateRun)){
		//echo "<p><div style=\"background-color:green\">".$jobChart['first_due']." ".date('Y-m-d',$dateRun)."</div></p>";
		array_push($dataPoint[$jobChart['description']],array("y"=>0,"label" => date('Y-m-d',$dateRun)));
		$dateRun = strtotime("+1 days", $dateRun);
	}
	//echo "<p><div style=\"background-color:gray\">".$jobChart['first_due']." ".date('Y-m-d',$dateRun)."</div></p>";
  array_push($dataPoint[$jobChart['description']],array("y"=>$jobChart['cnt'],"label" => $jobChart['closeddate']));
	$dateRun = strtotime("+1 days", $dateRun);
  //echo "<div>".count($dataPoint[$jobChart['jobname']])."</div>";
}



echo "232323 = ".$r;

if($_GET['debug']){
   echo "twotwo<pre>";
   var_dump($dataPoint);
   echo "</pre>";
 }

?>

<script type="text/javascript">

    $(function () {
        var chart = new CanvasJS.Chart("chartContainer2", {
            animationEnabled: true,
            title: {
                text: "ยอดปิดงานในแต่ละประเภท"
            },
            axisX: {
                title: "วันที่"
            },
            axisY: {
                title: "จำนวนงาน",
            },
            data: [
              <?php
              $all = count($data);
              for($i=0; $i<$all; $i++){
                ?>
            {
                type: "stackedColumn",
                legendText: "<?php echo $data[$i]?>",
                showInLegend: "true",
                indexLabelPlacement: "inside",
                indexLabelFontColor: "white",
                dataPoints: <?php echo json_encode($dataPoint[$data[$i]], JSON_NUMERIC_CHECK); ?>
            },
            <?php }?>
            ]
        });
        chart.render();
    });
</script>

<div id="chartContainer2" style="height:400px; width:80%;"></div>
