<?php
 session_start();
 require_once '../dbconfig.php';

 if(!isset($_SESSION['user_session']))
 {
  header("Location: ./login/index.php");
 }else{

    $result = array("Total"=> "0", "Available" => "0", "Disable" => "0");
     try{
       $stmt = $db_con->prepare("SELECT * FROM items WHERE user_id=:uid");
       $stmt->execute(array(":uid"=>$_SESSION['user_session']));
       while($row=$stmt->fetch(PDO::FETCH_ASSOC)){

         if($row['status']==true){
           $result['Available']++;
         }else{
           $result['Disable']++;
         }


     }

     $result['Total']=count($row);
     echo json_encode($result);
     }
     catch(PDOException $e){
      echo $e->getMessage();
     }
 }

?>
