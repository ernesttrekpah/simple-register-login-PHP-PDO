<?php
require_once ('config.php');
// Database Connection with PDO

try {
  $conn=new PDO("mysql:host={$dbConfig['server']}; 
  dbname={$dbConfig['database']}",
   $dbConfig['user'], $dbConfig['password']);

   $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   return $conn;

} catch (PDOException $ex) {
  die('Error occurred:'.$ex->getMessage());
}
