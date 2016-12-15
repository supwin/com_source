
<?php
include('cookies.php');
include('functions/function.php');
include('db_function/phpMySQLFunctionDatabase.php');

$hid = $_POST['hid'];

$strTable = "chargeback_reply";
$strCondition = "header_id='".$hid."'";
$strSort = " order by rid";

$rpylist = fncSelectConditionRecord($strTable,$strCondition,$strSort);

while($rpy = mysql_fetch_array($rpylist)){

  echo "<div><p><span>".convdate($rpy['date_reply'])."</span> <span>".$rpy['description']."</span></p><p>".$rpy['who_reply']."</p></div>";

}
