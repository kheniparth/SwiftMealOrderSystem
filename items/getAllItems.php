<?php
 session_start();
 require_once '../dbconfig.php';

 if(!isset($_SESSION['user_session']))
 {
  header("Location: ./login/index.php");
 }else{

    $result = [];
     try{
       $stmt = $db_con->prepare("SELECT * FROM items WHERE user_id=:uid");
       $stmt->execute(array(":uid"=>$_SESSION['user_session']));
       while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
         $result[] = $row;

       }

     echo json_encode($result);
     }
     catch(PDOException $e){
      echo $e->getMessage();
     }
 }

?>
