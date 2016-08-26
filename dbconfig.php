<?php
//server and database information
$servername = "localhost";
$username = “xxxxxxxxxx”;
$password = “xxxxx”;
$dbname = “xxxxxxxxx”;

try{
  //create database connection object
 $db_con = new PDO("mysql:host={$servername};dbname={$dbname}",$username,$password);
 $db_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e){
  //print exception error message
 echo $e->getMessage();
}

?>
