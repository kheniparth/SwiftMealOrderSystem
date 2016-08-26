<?php
session_start();
include_once '../dbconfig.php';


$result = array("Disapproved"=> "0", "Approved" => "0", "Total" => "0");
 try{
   global $db_con;
   $stmt = $db_con->prepare("SELECT * FROM Feedback_details WHERE Cust_id=:uid");
   $stmt->execute(array(":uid"=>$_SESSION['user_session']));
   while($row=$stmt->fetch(PDO::FETCH_ASSOC)){

     if($row['Status']==true){
       $result['Disapproved']++;
     }else{
       $result['Approved']++;
     }
 }
 $result['Total'] = $result['Disapproved']+ $result['Approved'];
 echo json_encode($result);
 }
 catch(PDOException $e){
  echo $e->getMessage();
 }
 ?>
