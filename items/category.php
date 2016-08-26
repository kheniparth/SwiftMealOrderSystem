<?php
session_start();
include_once '../dbconfig.php';

  //fetch all categories from the database
  function getAllCategories(){
    //create new array to hold all fetched queries
    $result = [];
     try{
       //get global database connection object
       global $db_con;
       //create and execute select query in the database
       $stmt = $db_con->prepare("SELECT * FROM Category_details WHERE Cust_id=:uid");
       $stmt->execute(array(":uid"=>$_SESSION['user_session']));
       while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
         //attach allow rows to result array
         $result[] = $row;
       }
     //print the JSON structure of result array
     echo json_encode($result);
     }
     catch(PDOException $e){
       //prints error if any occurs
      echo $e->getMessage();
     }
  }

  //this function add category to database
  function addCategory(){
    //checks whether category name is set in data query
    if(isset($_POST['categoryname'])){
      $name = $_POST['categoryname'];
    }
    //genearate item array for prepared statement
    $item = array(":name" => $name, ":uid" => $_SESSION['user_session']);
    try{
      //get global database connection object
      global $db_con;
      //create and execute insert object query
      $stmt = $db_con->prepare("INSERT INTO Category_details (Cat_name, Cust_id) VALUES (:name, :uid)");
      if($stmt->execute($item)){
        //print ok if the query executed successfully
        echo 'ok';
      }
    }
    catch(PDOException $e){
      //print eror message if any exception occurs
     echo $e->getMessage();
    }

  }

  //this function deletes a category in the database
  function deleteCategory(){

    //check whether cat id is set or not
    if(isset($_POST['cat_id'])){
      $id = $_POST['cat_id'];
   }

    //create item array for prepared statement
    $item = array(":uid" => $_SESSION['user_session'], ":id"=>$id);

    try{
      //get global database connection object
      global $db_con;
      //create and execute delete query
      $stmt = $db_con->prepare("DELETE FROM Category_details WHERE Cat_id = :id AND Cust_id=:uid");
      if($stmt->execute($item)){
        //print ok if query executes successfully
        echo 'ok';
      }
    }
    catch(PDOException $e){
      //print error message if exeception occurs
     echo $e->getMessage();
    }
  }


  //check for current session
  if(!isset($_SESSION['user_session'])){
    //navigate user to login page
   header("Location: http://techmuzz.com/smos/login/index.php");
  }else{

    //check function data in data query
    if(isset($_POST['function'])){
      $function = $_POST['function'];
      //call respective function on the base of function varible value
      switch ($function) {
        case 'getAllCategories':
          getAllCategories();
          break;
        case 'addCategory':
          addCategory();
          break;
        case 'deleteCategory':
          deleteCategory();
          break;
        default:
          break;
      }
    }
  }

?>
