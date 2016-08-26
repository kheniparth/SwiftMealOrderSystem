<?php
session_start();
include_once '../dbconfig.php';

  //this function add an item in the database
  function addItem(){

    //checks whether data is set or not
    if(isset($_POST['itemname'])){
      $name = $_POST['itemname'];
    }
    if(isset($_POST['addItemCategoryList'])){
      $Cat_id = $_POST['addItemCategoryList'];
    }
    if(isset($_POST['itemdescription'])){
      $description = $_POST['itemdescription'];
    }
    if(isset($_POST['itemingredients'])){
      $ingredients = $_POST['itemingredients'];
    }
    if(isset($_POST['itemcookingtime'])){
      $time = $_POST['itemcookingtime'];
    }
    if(isset($_POST['itemprice'])){
      $price = $_POST['itemprice'];
    }
    if(isset($_POST['veg'])){
      $veg = (int)$_POST['veg'];
    }
    if(isset($_POST['hot'])){
      $hot = (int)$_POST['hot'];
    }

    //create item array from data checked above for prepared statement
    $item = array(":name"=>$name, ":Cat_id" => $Cat_id, ":description" => $description,
                  ":ingredients" => $ingredients, ":price" => $price, ":time" => $time, ":veg" => $veg,
                  ":hot" => $hot, ":user_id"=>$_SESSION['user_session']);

    try{
      //get global database connectin variable
      global $db_con;
      //create and execute insert query
      $stmt = $db_con->prepare("INSERT INTO FoodItem_details
        (FoodItem_name, description, ingredients, Cat_id, price, veg, hot, cookingTime, Cust_id)
        VALUES (:name, :description, :ingredients, :Cat_id, :price, :veg, :hot, :time, :user_id)");
      if($stmt->execute($item)){
        //print ok if query executes successfully
        echo 'ok';
      }
    }
    catch(PDOException $e){
      //print error message if any exception occurs
     echo $e->getMessage();
    }

  }

  //this function edits the item
  function editItem(){

    //check if data is set or not
    if(isset($_POST['item_id'])){
      $id = $_POST['item_id'];
    }
    if(isset($_POST['editItemName'])){
      $name = $_POST['editItemName'];
    }
    if(isset($_POST['editItemCategoryList'])){
      $Cat_id = $_POST['editItemCategoryList'];
    }
    if(isset($_POST['editItemDescription'])){
      $description = $_POST['editItemDescription'];
    }
    if(isset($_POST['editItemIngredients'])){
      $ingredients = $_POST['editItemIngredients'];
    }
    if(isset($_POST['editItemCookingtime'])){
      $time = $_POST['editItemCookingtime'];
    }
    if(isset($_POST['editItemPrice'])){
      $price = $_POST['editItemPrice'];
    }
    if(isset($_POST['veg'])){
      $veg = (int)$_POST['veg'];
    }
    if(isset($_POST['hot'])){
      $hot = (int)$_POST['hot'];
    }

    //crate item array for prepared statement
    $item = array(":name"=>$name, ":Cat_id" => $Cat_id, ":description" => $description,
                  ":ingredients" => $ingredients, ":price" => $price, ":time" => $time, ":veg" => $veg,
                  ":hot" => $hot, ":id"=>$id);

    try{
      //get global connection object
      global $db_con;
      //create and execute update query
      $stmt = $db_con->prepare("UPDATE FoodItem_details SET
        FoodItem_name = :name, description = :description, ingredients = :ingredients, Cat_id = :Cat_id,
        price = :price, veg = :veg, hot = :hot, cookingTime = :time WHERE FoodItem_id = :id");
      if($stmt->execute($item)){
        //print ok if query executed successfully
        echo 'ok';
      }
    }
    catch(PDOException $e){
      //print exception error message
     echo $e->getMessage();
    }
  }

  function deleteItem(){
    //check whether item id is set or not
    if(isset($_POST['item_id'])){
      $id = $_POST['item_id'];
   }

   //create item array for prepared statement
    $item = array(":uid" => $_SESSION['user_session'], ":id"=>$id);

    try{
      //get global database connection
      global $db_con;
      //create and execute delete query
      $stmt = $db_con->prepare("DELETE FROM FoodItem_details WHERE FoodItem_id = :id AND Cust_id=:uid");
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

  //get all food items for table
  function getAllFoodItems(){
    //create result array to hold fetched data
    $result = [];
     try{
       //get global database connection
       global $db_con;
       //create and execute select query
       $stmt = $db_con->prepare("SELECT * FROM FoodItem_details WHERE Cust_id=:uid");
       $stmt->execute(array(":uid"=>$_SESSION['user_session']));
       while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
         //attach fetched data into array
         $result[] = $row;
       }
      //print JSON structure of result array
     echo json_encode($result);
     }
     catch(PDOException $e){
       //print exception error message
      echo $e->getMessage();
     }
  }

  //get food item data by id
  function getFoodItemById(){

    //check if item id is set or not
    if(isset($_POST['item_id'])){
      $item_id = $_POST['item_id'];
    }
    //create an array to hold data
    $result = [];
     try{
       //get global database connection object
       global $db_con;
       //create and execute select query
       $stmt = $db_con->prepare("SELECT * FROM FoodItem_details WHERE FoodItem_id=:item_id AND Cust_id=:uid");
       $stmt->execute(array(":uid"=>$_SESSION['user_session'], ":item_id"=>$item_id));
       while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
         //attach fetched data in result
         $result[] = $row;
       }
       //print JSON structure of the result array
     echo json_encode($result);
     }
     catch(PDOException $e){
       //print exception error message
      echo $e->getMessage();
     }
  }



  //check  session is set or not
  if(!isset($_SESSION['user_session'])){
    //navigate user to login page if session is not set
    header("Location: http://techmuzz.com/smos/login/index.php");
  }else{
    //check if function variable is set or not
    if(isset($_POST['function'])){
      $function = $_POST['function'];
      //call respective function from the value of function variable
      switch ($function) {
        case 'addItem':
          addItem();
          break;
        case 'editItem':
          editItem();
          break;
        case 'deleteItem':
          deleteItem();
          break;
        case 'getAllFoodItems':
          getAllFoodItems();
          break;
        case 'getFoodItemById':
          getFoodItemById();
          break;
        default:
          printData();
          break;
      }
    }
  }

 ?>
