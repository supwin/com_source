<?php
include('cookies.php');
include('namebranch.php');
include('functions/function.php');
include('db_function/phpMySQLFunctionDatabase.php');
mysql_query(BEGIN);
$phid = $_POST['phid'];
$data1 = $_POST['data1'];
$data2 = $_POST['data2'];
$data3 = $_POST['data3'];
$tax = $_POST['tax'];

if($data3 < 0){
	$tax = 0;
	$typedetail = '3301';
} else {
	$typedetail = '3101';
}
	$strTablePHD = "tidnet_common.payhiredetail";
	$strField = "typedetail,header_id,cj_id,circuit,price";
	$strValue = "'".$typedetail."','".$phid."','".$data1."','".$data2."','".$data3."'";

	if(!fncInsertRecord($strTablePHD,$strField,$strValue)){
			mysql_query(ROLLBACK);
			echo "<tr style=\"background-color:#fff;\"><td colspan=\"3\">INSERT INTO ".$strTablePHD." (".$strField.") VALUES (".$strValue.") <td></tr>";
			die();
		}else{
			echo "<tr style=\"background-color:#fff;\">
			      <td>".$data1."</td>
			      <td>".$data2."</td>
			      <td class=\"right\">".number_format($data3,2)."</td>
   				</tr>";

					if($tax>0){
	   				$Conditionh = "header_id='".$phid."' and typedetail='3101'";
						$sumPrice3101 = fncSumFieldRecord($strTablePHD,"price",$Conditionh);
						$threepercentUpdate = $sumPrice3101*-0.03;
						$commandUpdate = "price='".$threepercentUpdate."'";
						$conditionUpdate = "header_id='".$phid."' and typedetail='3111'";
						fncUpdateRecord($strTablePHD,$commandUpdate,$conditionUpdate);
						if(mysql_affected_rows()<=0){
							mysql_query(ROLLBACK);
							echo "<tr style=\"background-color:#fff;\"><td colspan=\"3\">UPDATE ".$strTablePHD." SET  ".$commandUpdate." WHERE ".$conditionUpdate." <td></tr>";
							die();
						}
					}


					$strConditionAllPrice = "header_id='".$phid."'";
					$sumPrice = fncSumFieldRecord($strTablePHD,"price",$strConditionAllPrice);
					$strTablePHH = "tidnet_common.payhireheader";
					$strCommandsumtotalUpdate = "sumtotal='".$sumPrice."'";
					$strConditionsumtotalUpdate = "id='".$phid."'";
					fncUpdateRecord($strTablePHH,$strCommandsumtotalUpdate,$strConditionsumtotalUpdate);
					if(mysql_affected_rows()<=0){
						mysql_query(ROLLBACK);
						echo "<tr style=\"background-color:#fff;\"><td colspan=\"3\">UPDATE ".$strTablePHH." SET  ".$strCommandsumtotalUpdate." WHERE ".$strConditionsumtotalUpdate." <td></tr>";
						die();
					}
		}

mysql_query(COMMIT);





?>
