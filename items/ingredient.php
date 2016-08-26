<?php
session_start();
include_once '../dbconfig.php';

  //get all ingredients from the database
  function getAllIngredients(){
    $result = [];
     try{
       //get global database connection
       global $db_con;
       //create and execute select query
       $stmt = $db_con->prepare("SELECT * FROM Item_master WHERE Cust_id=:uid");
       $stmt->execute(array(":uid"=>$_SESSION['user_session']));
       while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
         //attach fetched data to result array
         $result[] = $row;
       }
      //print JSON output of result array
     echo json_encode($result);
     }
     catch(PDOException $e){
       //print exception error message
      echo $e->getMessage();
     }
  }

  //add an ingredient in the database
  function addIngredient(){
    //check if ingredient name is set or not
    if(isset($_POST['ingredientname'])){
      $name = $_POST['ingredientname'];
    }

    //create item array for prepared statement
    $item = array(":name" => $name, ":uid" => $_SESSION['user_session']);
    try{
      //get global database connection object
      global $db_con;
      //create and execute insert query
      $stmt = $db_con->prepare("INSERT INTO Item_master (Item_name, Cust_id) VALUES (:name, :uid)");
      if($stmt->execute($item)){
        //print ok if query gets executed successfully
        echo 'ok';
      }
    }
    catch(PDOException $e){
      //print exception error message
     echo $e->getMessage();
    }

  }

  //this function deletes an ingredient in the database
  function deleteIngredient(){

    //check if item id is set or not
    if(isset($_POST['item_id'])){
      $id = $_POST['item_id'];
   }
   //create item array for prepared statement
    $item = array(":uid" => $_SESSION['user_session'], ":id"=>$id);

    try{
      //get global database connection object
      global $db_con;
      //create and execute delete query
      $stmt = $db_con->prepare("DELETE FROM Item_master WHERE Item_id = :id AND Cust_id=:uid");
      if($stmt->execute($item)){
        //print ok if query gets executed
        echo 'ok';
      }
    }
    catch(PDOException $e){
      //print exception error message
     echo $e->getMessage();
    }
  }

  //check session whether is set or not
  if(!isset($_SESSION['user_session'])){
    //navigate user to login page
   header("Location: http:techmuzz.com/smos/login/index.php");
  }else{
    //check if function data is set or not
    if(isset($_POST['function'])){
      $function = $_POST['function'];
      //call respective funtion from value of function variable
      switch ($function) {
        case 'getAllIngredients':
          getAllIngredients();
          break;
        case 'addIngredient':
          addIngredient();
          break;
        case 'deleteIngredient':
          deleteIngredient();
          break;
        default:
          printData();
          break;
      }
    }
  }

?>
