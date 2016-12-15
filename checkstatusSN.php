<?php
include('cookies.php');
include("functions/function.php");
include("../com_source/headmenu.php");
include("../com_source/config.php");


?>
<script language="Javascript" type="text/javascript">
$(document).ready(function(){
 	$(".receive").click(function(){
    sn = $(this).attr("for");
    branch = $("#branch_"+sn).attr("for");
    window.open("receiveSN.php?sn="+sn+"&branch="+branch,"List","scrollbars=no, resizable=no, width=350, height=250");
 	});

 	$(".indulgent").click(function(){
    sn = $(this).attr("for");
    branch = $("#branch_"+sn).attr("for");
    window.open("indulgentSN.php?sn="+sn+"&branch="+branch,"List","scrollbars=no, resizable=no, width=350, height=250");
 	});
});

indulgent
</script>
<table>
<form action="" method="post">
	<tr>
		<td colspan="4">
			<select id="reasonoption" name="reasonoption">
				<option value="0">เลือก Status ที่ต้องการ</option>
				<!--
				<option value="9091">9091=หักค่าปรับไว้แล้ว</option>
				<option value="9096">9096=ตัดสต๊อก ADSL,AP</option>
				<option value="9097">9097=ตัดสต๊อกไม่ปิดงาน</option>
				<option value="9098">9098=ของเสีย/ส่งคืน</option>
				<option value="9100">9100=ตัดสต๊อกจุดเสริม</option>
				-->
				<option value="9111">9111=ผ่อนผัน</option>
				<option value="9198">9198=ของเสีย/ส่งคืน(รอรับ)</option>
				<option value="9199">9199=ปิดงานไม่ได้</option>
			</select>
			&nbsp;&nbsp;
			<button type="submit" id="btn" name="save" > ตรวจสอบ </button>
		</td>
	</tr>
</form>	
    <tr class='header'>
	    <td class='center'>สาขา</td>
	    <td class='center'>ชื่อ-นามสกุล</td>
	    <td class='center'>Serial</td>
	    <td class='center'>สถานะ</td>
    </tr>
    <?php
if(isset($_POST['save'])){
	$status = $_POST['reasonoption'];

	$strTable = "tidnet_common.master_employee";
	$strCondition = "permission=4";
	$empList = fncSelectConditionRecord($strTable,$strCondition);
	$report = Array();

	while($emp = mysql_fetch_array($empList)){
		foreach ($allBranch as $key => $models){
		    $strSNTable = "tidnet_".$key.".eqm_sn";
		    $strSNCondition = "responcible=".$status." and oldowner='".$emp['id']."'";
		    $query = fncSelectConditionRecord($strSNTable,$strSNCondition);
		    while($listSN = mysql_fetch_array($query)){
		    	if($listSN['responcible']=='9198'){
		    		$statussn = "<td>ของเสีย/ส่งคืน รอรับของ</td>
		    					 <td><button class=\"receive\" for=\"".$listSN['sn']."\">รับของ</button>
		    					 <input type=\"hidden\" id=\"branch_".$listSN['sn']."\" for=\"".$key."\">
		    					 </td>";

		    	} else if ($listSN['responcible']=='9199'){
		    		$statussn = "<td>ปิดงานไม่ได้</td>";
		    	}  else if ($listSN['responcible']=='9111'){
		    		$statussn = "<td>ผ่อนผัน</td>
		    					<td><button class=\"indulgent\" for=\"".$listSN['sn']."\">รับของ</button>
		    					<input type=\"hidden\" id=\"branch_".$listSN['sn']."\" for=\"".$key."\">
		    					</td>";
		    	}

		    	echo "<tr>";
		    		echo "<td>".$key."</td>";
		    		echo "<td>".$emp['name']."</td>";
		    		echo "<td>".$listSN['sn']."</td>";
		    		echo $statussn;
		    	echo "</tr>";
		    }
	  	}

	}
}
    ?>

</table>

