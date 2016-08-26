<?php
session_start();
//include database configuration file
include_once '../dbconfig.php';

//this function will get all the feedbacks related to current user
function getAllFeedbacks(){
  $result = [];
   try{
     //get global database connection object
     global $db_con;

     //prepare and execute data fetching query
     $stmt = $db_con->prepare("SELECT * FROM Feedback_details WHERE Cust_id=:uid");
     $stmt->execute(array(":uid"=>$_SESSION['user_session']));
     while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
       //attach all rows to an array
       $result[] = $row;
     }
   //print the JSON format of the array
   echo json_encode($result);
   }catch(PDOException $e){
    //print the error if occurs
    echo $e->getMessage();
   }
}

//this function will get the count all feedbacks related to the current user
function getAllFeedbackCount(){
  //create and initialise an array to hold the counts
  $result = array("Disapproved"=> "0", "Approved" => "0", "Total" => "0");
   try{
     //get global database connection object
     global $db_con;

     //prepare and execute data fetching query
     $stmt = $db_con->prepare("SELECT * FROM Feedback_details WHERE Cust_id=:uid");
     $stmt->execute(array(":uid"=>$_SESSION['user_session']));
     //iterate through all the fetched records
     while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
       //increase approved count if status is true
       if($row['Status']==true){
         $result['Approved']++;
       }else{
         //else increase Disapproved count
         $result['Disapproved']++;
       }
   }
   //add both approved and Disapproved counts to get total feedbacks
   $result['Total'] = $result['Disapproved']+ $result['Approved'];

   //print JSON format of array
   echo json_encode($result);
   }
   catch(PDOException $e){
     //print error if any occurs
    echo $e->getMessage();
   }
}

//this function will update feedback data in the database
function updateFeedback(){
  //check if feedback_id id set or not
  if(isset($_POST['feedback_id'])){
    //extract id from the string
   $array = explode("-", $_POST['feedback_id']);
   $id = $array[1];
  }
  //generate item array for prepared statement
  $item = array(":uid" => $_SESSION['user_session'], ":id"=>$id);

  try{
    // get global database connection object
    global $db_con;
    //execute update query by prepared statement
    $stmt = $db_con->prepare("UPDATE Feedback_details SET
      Status = !Status WHERE Feedback_id = :id AND Cust_id=:uid");
    if($stmt->execute($item)){
      //print ok if update query executed successfully
      echo 'ok';
    }
  }
  catch(PDOException $e){
    //print the error msg if any occured
   echo $e->getMessage();
  }
}

//this function deletes the feedback from the database
function deleteFeedback(){
  //chech whether id is set or not and set if it is
  if(isset($_POST['feedback_id'])){
   $array = explode("-", $_POST['feedback_id']);
   $id = $array[1];
 }

  //generate item array for prepared statement
  $item = array(":uid" => $_SESSION['user_session'], ":id"=>$id);

  try{
    //global database connection object
    global $db_con;
    //execute delete query
    $stmt = $db_con->prepare("DELETE FROM Feedback_details WHERE Feedback_id = :id AND Cust_id=:uid");
    if($stmt->execute($item)){
      //print ok if delete query executed successfully
      echo 'ok';
    }
  }
  catch(PDOException $e){
    //print error message if any occured
   echo $e->getMessage();
  }
}

//check current session by user
if(!isset($_SESSION['user_session'])){
  //navigate to the login page if session is not set
 header("Location: http:techmuzz.com/smos/login/index.php");
}else{
  //check function varible in data
  if(isset($_POST['function'])){
    $function = $_POST['function'];
    //call respective function by value of function varible
    switch ($function) {
      case 'getAllFeedbacks':
        getAllFeedbacks();
        break;
      case 'getAllFeedbackCount':
        getAllFeedbackCount();
        break;
      case 'updateFeedback':
        updateFeedback();
        break;
      case 'deleteFeedback':
        deleteFeedback();
        break;
    }
  }
}

?>
