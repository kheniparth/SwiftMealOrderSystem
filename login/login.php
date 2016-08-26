<?php
 session_start();
 require_once '../dbconfig.php';

//check whether user name and password is set or not
if(isset($_POST['username'], $_POST['password']))
 {
  $username = trim($_POST['username']);
  $password = trim($_POST['password']);


  try{
    //create and fetch data from the database
   $stmt = $db_con->prepare("SELECT * FROM LogIn_details WHERE User_name=:username");
   $stmt->execute(array(":username"=>$username));
   $row = $stmt->fetch(PDO::FETCH_ASSOC);
   $count = $stmt->rowCount();
   //check if passwords are matching or not
   if($row['LogIn_password']==$password){
     //print ok if parsswords are same
    echo "ok";
    //add data to session
    $_SESSION['user_session'] = $row['LogIn_id'];
   }
   else if($row['LogIn_password']!=$password){
     echo "Username and Password does not match.";
   }else{
    // echo "email or password does not exist."; // wrong details
    header("Location: http://techmuzz.com/smos/login/index.php");
   }

  }catch(PDOException $e){
   echo $e->getMessage();
  }
}
?>
