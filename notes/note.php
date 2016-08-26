<?php
session_start();
include_once '../dbconfig.php';

  //this function will get all the notes
  function getAllNotes(){
    $result = [];
     try{
       //get global database connection object
       global $db_con;
       //create and execute fetch query
       $stmt = $db_con->prepare("SELECT * FROM Note_details WHERE Cust_id=:uid");
       $stmt->execute(array(":uid"=>$_SESSION['user_session']));
       while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
         //attach data in result array
         $result[] = $row;

       }
       //print JSON output
     echo json_encode($result);
     }
     catch(PDOException $e){
       //print exception error message
      echo $e->getMessage();
     }
  }

  //get total not count function
  function getAllNoteCount(){

    $result = array("Created"=> "0");
     try{
       global $db_con;
       $stmt = $db_con->prepare("SELECT * FROM Note_details WHERE Cust_id=:uid");
       $stmt->execute(array(":uid"=>$_SESSION['user_session']));
       $row=$stmt->fetch(PDO::FETCH_ASSOC);
     $result['Created'] = count($row);
     echo json_encode($result);
     }
     catch(PDOException $e){
      echo $e->getMessage();
     }

  }

  //this function accepts data from form and update note data in database
  function updateNote(){
    if(isset($_POST['note_id'])){
     $id = $_POST['note_id'];
   }
   if(isset($_POST['note'])){
     $note = $_POST['note'];
   }
    $item = array(":uid" => $_SESSION['user_session'], ":id"=>$id, ":note"=>$note);

    try{
      global $db_con;
      $stmt = $db_con->prepare("UPDATE Note_details SET
        Note_text = :note WHERE Note_id = :id AND Cust_id=:uid");
      if($stmt->execute($item)){
        echo 'ok';
      }
    }
    catch(PDOException $e){
     echo $e->getMessage();
    }
  }

  //this function deletes a note from database
  function deleteNote(){
    if(isset($_POST['note_id'])){
      $id = $_POST['note_id'];
   }
    $item = array(":uid" => $_SESSION['user_session'], ":id"=>$id);

    try{
      global $db_con;
      $stmt = $db_con->prepare("DELETE FROM Note_details WHERE Note_id = :id AND Cust_id=:uid");
      if($stmt->execute($item)){
        echo 'ok';
      }
    }
    catch(PDOException $e){
     echo $e->getMessage();
    }
  }

    //this function adds an note in the database
    function addNote(){

      if(isset($_POST['note'])){
        $note = $_POST['note'];
      }


      $item = array(":note"=>$note, ":user_id"=>$_SESSION['user_session']);

      try{
        global $db_con;
        $stmt = $db_con->prepare("INSERT INTO Note_details (Note_text, Cust_id) VALUES (:note, :user_id)");
        if($stmt->execute($item)){
          echo 'ok';
        }
      }
      catch(PDOException $e){
       echo $e->getMessage();
      }

    }

    //check session is set or not
  if(!isset($_SESSION['user_session'])){
   header("Location: http:techmuzz.com/smos/login/index.php");
  }else{
    //call respective function from the value of function variable
    if(isset($_POST['function'])){
      $function = $_POST['function'];
      switch ($function) {
        case 'getAllNotes':
          getAllNotes();
          break;
        case 'getAllNoteCount':
          getAllNoteCount();
          break;
        case 'deleteNote':
          deleteNote();
          break;
        case 'addNote':
          addNote();
          break;
      }
    }
  }

?>
