<?php
function connectToDB($user = 'u20490', $pass = '3080615', $dbname = 'u20490'){
  try {
    $db = new PDO(
      'mysql:host=localhost;dbname=' . $dbname,
      $user,
      $pass,
      array(PDO::ATTR_PERSISTENT => true)
    );
    return $db;
  } catch (PDOException $e) {
    return $e->getMessage();
  }
}
