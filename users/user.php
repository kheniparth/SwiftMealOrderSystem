<?php
session_start();
include_once '../dbconfig.php';

  //change user's password
  function changePassword(){
     try{
       global $db_con;
       $stmt = $db_con->prepare("UPDATE LogIn_details SET LogIn_password=:password WHERE LogIn_id=:uid");
       if($stmt->execute(array(":uid"=>$_SESSION['user_session'], ":password" => $_POST['newpassword']))){
         //print ok if user's password changed
         echo 'ok';
       }
     }
     catch(PDOException $e){
       //print exception error message
      echo $e->getMessage();
     }
  }

  //get current user infromation
  function getCurrentUser(){
    $result = [];
     try{
       global $db_con;
       $stmt = $db_con->prepare("SELECT * FROM Customer_info WHERE Cust_id=:uid");
       $stmt->execute(array(":uid"=>$_SESSION['user_session']));
       while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
         //attach data to result array
         $result[] = $row;
       }
       //print JSON output from result array
     echo json_encode($result);
     }
     catch(PDOException $e){
       //print exception error message
      echo $e->getMessage();
     }
  }

  if(!isset($_SESSION['user_session'])){
   header("Location: http:techmuzz.com/smos/login/index.php");
  }else{

    if(isset($_POST['function'])){
      //call curresponding function from function variable
      $function = $_POST['function'];
      switch ($function) {
        case 'changePassword':
          changePassword();
          break;
        case 'getCurrentUser':
          getCurrentUser();
          break;

      }
    }
  }

?>
