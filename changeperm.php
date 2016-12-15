<?php
include('cookies.php');
include('db_function/phpMySQLFunctionDatabase.php');
//include('functions/function.php');
//include("headmenu.php");
$eng = $_GET['eng'];
$url = $_GET['url'];
if($_COOKIE['superuser']==1){
	$strTable = "employee";
	$strCondition = "id ='".$eng."'";
	$emp = fncSelectSingleRecord($strTable,$strCondition);
	setcookie("user",$emp["user"],time()+3600);
	setcookie("permission",$emp["permission"],time()+3600);
	setcookie("uid",$emp["id"],time()+3600);
	setcookie("name",$emp["name"],time()+3600);
	setcookie("id_ticket",$emp["id_ticket"],time()+3600);
}else{
	$alertmsg = "ไม่สามารถเปลี่ยนตัวผู้ใช้ระบบได้";
}
?>


	<script src="jquery/jquery.js">
	</script>
	<script>
		$(document).ready(function(){
			<?php
				if($alertmsg<>''){?>
					openAlert('<?php echo $alertmsg?>');
				<?php
				}
			?>
			$(location).attr('href','<?php echo $url?>');
		});
	</script>
