<?php
	session_start();
	include('namebranch.php');
	include("db_function/phpMySQLFunctionDatabase.php");
	//$strSQL = "SELECT * FROM employee WHERE user = '".mysql_real_escape_string($_POST['txtUsername'])."'
	//and Password = '".mysql_real_escape_string($_POST['txtPassword'])."'";
	//$objQuery = mysql_query($strSQL);
	//$objResult = mysql_fetch_array($objQuery);

	$strTable = "tidnet_common.master_employee";
	$strCondition = "(workat like '%@".$abvt."@%' or permission in (1,3)) and user = '".mysql_real_escape_string($_POST['txtUsername'])."'and password = '".mysql_real_escape_string($_POST['txtPassword'])."'";

	//echo "SELECT * FROM $strTable WHERE $strCondition ";

	$objResult = fncSelectSingleRecord($strTable,$strCondition);

	if(!$objResult)
	{
			echo "Username and Password Incorrect!<br>back to <a href=\"login_frm.php\">login</a> ";
	}
	else
	{
			setcookie("user",$objResult["user"],time()+36000);
			setcookie("permission",$objResult["permission"],time()+36000);
			setcookie("uid",$objResult["id"],time()+36000);
			setcookie("name",$objResult["name"],time()+36000);
			setcookie("id_ticket",$objResult["id_ticket"],time()+36000);
			if($objResult["superuser"]==1){
				setcookie("superuser",$objResult["superuser"],time()+36000);
				setcookie("superuseruid",$objResult["id"],time()+36000);
				setcookie("superusername",$objResult["name"],time()+36000);
			}
			session_write_close();

			if($objResult["permission"])
			{
				header("location:index.php");
			}
	}
	mysql_close();
?>
