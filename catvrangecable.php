<?php
include('cookies.php');
include("functions/function.php");
include("headmenu.php");
?>

<form action="catvrangecable.php" method="post" enctype="multipart/form-data">
	<label for="file">CATV Customer List File :</label>
	<input name="fileCSV" type="file" id="fileCSV">
  	<input type="submit" value="Submit">
</form>

<br/><br/>

<table>
	<tr class="header">
		<td>ลำดับ</td>
		<td>DEPOT</td>
		<td>Completed Date</td>
		<td>Customer No.</td>
		<td>Work Order No.</td>
		<td>Customer Name</td>
		<td>Range Cable</td>
		<td>Service Detail</td>
	</tr>
<?php
move_uploaded_file($_FILES["fileCSV"]["tmp_name"],"csveqm/".$_FILES["fileCSV"]["name"]); // Copy/Upload CSV
$objCSV = fopen("csveqm/".$_FILES["fileCSV"]["name"], "r");
$rowObj = 0;
while (($objArr = fgetcsv($objCSV, 1000, ",")) !== FALSE) {
		
		if($rowObj==0){
			/*
			switch ($objArr[$checkColumn]) {
    				case "SI IBS DEPOT KEY":
					$f = 'o';
					break;
    				case "CUSTOMER_ID":
					$f = 'v';
					break;
    				case "Confirmed Complete Time":
					$f = 'q';
					break;
    				default:
					?>
					<script>
					openAlert('ไฟล์ไม่ถูกต้องกรุณาตรวจสอบ!!!<?php echo $objArr[$checkColumn]?>');
					//window.location='closedjob.php';
					</script>
					<?php
					die();
			}
			*/
			$rowObj++;
			continue;
		}
		$woNo = $objArr[6];
		if($woNo==''){
			die();
		}
		$l = 'http://tvgcc.truevisionstv.com/TVGWEB/WorkOrder/customer_workorder_detail.aspx?workordernr='.$woNo;
		$lines = file($l);
echo var_dump($lines);
		while ($line = array_shift($lines)) {
			$r++;
			if($r==345){
				//$lat = trim(preg_replace('/\t+/', '',strip_tags(str_replace('&nbsp;','',$line))));
				$kk = $line;			
			}
			if($r==350){
				//$lng = trim(preg_replace('/\t+/', '',strip_tags(str_replace('&nbsp;','',$line))));
				$kk = $line;			
			}	
		}
		?>
		<tr>
			<td><?php echo $rowObj++?></td>
			<td><?php echo $objArr[3]?></td>
			<td><?php echo $objArr[8]?></td>
			<td><?php echo $objArr[5]?></td>
			<td><?php echo $objArr[6]?></td>
			<td><?php echo $objArr[7]?></td>
			<td><?php echo $kk?></td>
			<td><?php echo $l?></td>
		</tr>
		<?php
}
?>
</table>	
