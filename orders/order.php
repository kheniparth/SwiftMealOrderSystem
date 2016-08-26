<?php
session_start();
include_once '../dbconfig.php';

  //get total order count
  function getOrderCount(){
    $todayDate = date("Y-m-d");
    $result = array("Pending"=> "0", "Completed" => "0", "Today's" => "0");
     try{
       //get database connection object
       global $db_con;
       $stmt = $db_con->prepare("SELECT * FROM Order_details WHERE Cust_id=:uid");
       $stmt->execute(array(":uid"=>$_SESSION['user_session']));
       while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
         //increase pending order count if status is true
         if($row['status']==true && $result["Today's"]>0){
           $result['Pending']++;
         }else if($row['status']==true && $result["Today's"]<=0){
           //update all pending orders of yesterday
           $stmt1 = $db_con->prepare("UPDATE Order_details SET status=0 WHERE Order_id=:order_id");
           $stmt1->execute(array(":order_id"=>$row['Order_id']));
         }
         $dt = new DateTime($row['Order_date']);
         if ($dt->format('Y-m-d') == $todayDate) {
           $result["Today's"]++;
         }
     }

     $result['Completed']=$result["Today's"]-$result['Pending'];
     //print JSON output of result array
     echo json_encode($result);
     }
     catch(PDOException $e){
      echo $e->getMessage();
     }
  }

  //get all orders
  function getAllOrders(){
    $result = [];
     try{
       global $db_con;

       $stmt = $db_con->prepare("SELECT * FROM Order_details WHERE Cust_id=:uid");
       $stmt->execute(array(":uid"=>$_SESSION['user_session']));
       while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
         $totalItems = 0;
         $totalCost = 0;
         //get curresponding order items of current orders
         $stmt1 = $db_con->prepare("SELECT * FROM Order_items WHERE Order_id=:id");
         $stmt1->execute(array(":id"=>$row['Order_id']));

         while($row1=$stmt1->fetch(PDO::FETCH_ASSOC)){
           $totalItems = $totalItems + $row1['Quantity'];
           $totalCost = $totalItems +  $row1['Amount'];
         }
         $row['totalItems'] = $totalItems;
         $row['totalCosts'] = $totalCost;
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

  //get order count by hours of the day
  function getHourlyOrderCount(){
    $todayDate = date("Y-m-d");
    $result = [];
     try{
       global $db_con;

       $stmt = $db_con->prepare("SELECT * FROM Order_details WHERE Cust_id=:uid");
       $stmt->execute(array(":uid"=>$_SESSION['user_session']));
       while($row=$stmt->fetch(PDO::FETCH_ASSOC)){

         //get only orders which are created today
         $dt = new DateTime($row['Order_date']);
         if ($dt->format('Y-m-d') == $todayDate) {
           for($i=1; $i<=24; $i++){
             if($dt->format('H') == $i){
               $result[$i]++;
             }
           }
         }
     }
     //print JSON output
     echo json_encode($result);
     }
     catch(PDOException $e){
       //print exception error message
      echo $e->getMessage();
     }
  }

  //get orders day wise for current month
  function getDailyOrderCount(){
    $todayDate = date("Y-m-d");
    $result = [];
     try{
       global $db_con;
       //create and execute select query
      $stmt = $db_con->prepare("SELECT * FROM Order_details WHERE Cust_id=:uid AND MONTH(Order_date) = MONTH(CURDATE())");

       $stmt->execute(array(":uid"=>$_SESSION['user_session']));
       while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
          $dt = new DateTime($row['Order_date']);
          $result[$dt->format('d')]++;
     }
     //get JSON output of result array
     echo json_encode($result);
     }
     catch(PDOException $e){
       //print exception error message
      echo $e->getMessage();
     }
  }

  //get total orders vs current month orders
  function getMonthByTotalOrders(){
    $todayDate = date("Y-m-d");
    $result = [];
     try{
      global $db_con;
      $stmt = $db_con->prepare("SELECT * FROM Order_details WHERE Cust_id=:uid AND MONTH(Order_date) = MONTH(CURDATE())");
      $stmt->execute(array(":uid"=>$_SESSION['user_session']));
      $row=$stmt->fetch(PDO::FETCH_ASSOC);
      $result['current_month']=count($row);

      $stmt = $db_con->prepare("SELECT * FROM Order_details WHERE Cust_id=:uid");
      $stmt->execute(array(":uid"=>$_SESSION['user_session']));
      $row=$stmt->fetch(PDO::FETCH_ASSOC);

      $result['total']=count($row);
      //get JSON output of result array
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
      $function = $_POST['function'];
      //call curresponding function from the value of function
      switch ($function) {
        case 'getOrderCount':
          getOrderCount();
          break;
        case 'getHourlyOrderCount':
          getHourlyOrderCount();
          break;
        case 'getDailyOrderCount':
          getDailyOrderCount();
          break;
        case 'getAllOrders':
          getAllOrders();
          break;
        case 'getMonthByTotalOrders':
          getMonthByTotalOrders();
          break;
      }
    }
  }

?>
