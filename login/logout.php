<?php
 session_start();
 unset($_SESSION['user_session']);

//distroy current session
 if(session_destroy())
 {
   echo "ok";
  header("Location: http://techmuzz.com/smos/login/index.php");
 }
?>
