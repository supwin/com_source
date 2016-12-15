<?php
include('cookies.php');
include('functions/function.php');
include("headmenu.php");

if(!checkAllow('sit_diffpaymentcheck')){
	die('คุณไม่มีสิทธิ์ใช้งานในส่วนนี้ได้ค่ะ..');
}
$cir = '1';
$cdate = '0';
$range = '2';

move_uploaded_file($_FILES["fileCSV"]["tmp_name"],"csveqm/".$_FILES["fileCSV"]["name"]); // Copy/Upload CSV

$objCSV = fopen("csveqm/".$_FILES["fileCSV"]["name"], "r");

?>


<form action="diffpayment.php" method="post" enctype="multipart/form-data">
<label for="file">Different List</label>
<input name="fileCSV" type="file" id="fileCSV">
<input name="btnSubmit" type="submit" id="btnSubmit" value="Submit">
</form>
<table>
    <tr>
      <td>cj_id</td>
      <td>closeddate</td>
      <td>circuit</td>
      <td>ลูกค้า</td>
      <td>ช่าง</td>
      <td>ระยะสายช่างเบิก</td>
      <td>ระยะสายจากทรู</td>
    </tr>
<?php
$strTable = "closedjob";
while (($objArr = fgetcsv($objCSV, 1000, ",")) !== FALSE) {
		$rowObj++;
    $closeddate = $objArr[$cdate];
    $strCondition = "closeddate='".$closeddate."' and circuit='".$objArr[$cir]."'";
    $job = fncSelectSingleRecord($strTable,$strCondition);
    $color ="fff";
    if($objArr[$range]<>$job['bcable']){
      $color = 'red';
    }
    ?>
        <tr bgcolor="<?php echo $color?>">
          <td><?php echo $job['cj_id']?></td>
          <td><?php echo $objArr[$cdate]?></td>
          <td><?php echo $objArr[$cir]?></td>
          <td><?php echo $job['cust_name']?></td>
          <td><?php echo nameofengineer($job['emp_id'],1);?></td>
          <td><?php echo $job['bcable']?></td>
          <td><?php echo $objArr[$range]?></td>
        </tr>
    <?php
    //echo "<span style=\"color:".$color."\">".$objArr['0'].",".$objArr['1'].",".$objArr['2']."</sprn><br>";
}
?>
</table>
