<?php
session_start();
include_once '../dbconfig.php';

  if(isset($_POST)){

    //get form data from POST
    if(isset($_POST['name'])){
      $name = $_POST['name'];
    }
    if(isset($_POST['address'])){
      $address = $_POST['address'];
    }
    if(isset($_POST['city'])){
      $city = $_POST['city'];
    }
    if(isset($_POST['country'])){
      $country = $_POST['country'];
    }
    if(isset($_POST['email'])){
      $email = $_POST['email'];
    }
    if(isset($_POST['username'])){
      $username = $_POST['username'];
    }
    if(isset($_POST['password'])){
      $password = $_POST['password'];
    }

    $created_date = date("Y-m-d H:i:s");
    //create item array for prepared statement
    $item = array(":name"=>$name, ":address" => $address, ":city" => $city,
                  ":country" => $country, ":email" => $email, ":create_date" => $created_date);

    $credentials = array(":username"=>$username, ":password" => $password);
    try{
      global $db_con;
      //check if username exists or not
      $stmt = $db_con->prepare("SELECT * FROM LogIn_details WHERE User_name=:username");
      $stmt->execute(array(":username"=>$username));
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $count = $stmt->rowCount();
      if($count > 0){
        echo 'Username already exist. Try again with different username.';
      }else{

        //insert data into database
        $stmt = $db_con->prepare("INSERT INTO Customer_info
          (Name, Address, City, Country, Email_id, Creat_date)
          VALUES (:name, :address, :city, :country, :email, :create_date)");
        $stmt1 = $db_con->prepare("INSERT INTO LogIn_details
          (User_name, LogIn_password)
          VALUES (:username, :password)");
        if($stmt->execute($item) && $stmt1->execute($credentials)){
          echo 'ok';
        }else{
          echo 'Registration Failed due to Error. Try again.';
        }
      }
    }
    catch(PDOException $e){
      //print exception error message
     echo $e->getMessage();
    }

  }

 ?>
