<?php
include('cookies.php');
include("db_function/phpMySQLFunctionDatabase.php");
include('functions/function.php');



if(isset($_POST['submitbut']))
    {   
      mysql_query("BEGING");

      $id = $_GET['id'];  
      $key = $_GET['key'];

      mysql_select_db("tidnet_".$key);

      $note = $_POST['note'];
	    $orderTable = "eqm_coming";
	    $orderCommand = "note='".$note."' , cancelled='1'";
	    $orderCondition = "id='".$id."' ";
      
	       if(fncUpdateRecord($orderTable,$orderCommand,$orderCondition)) {?>
          <script>
         // alert('success');
        window.location='eqmwaitinglist.php'
          </script>
        <?php
         }else{
        ?>
          <script>
           alert('Can not delete order.');
           window.location='eqmwaitinglist.php'
          </script>
        <?php
        }
    }
  
?>
