<?php

define('DSN', 'mysql:host=localhost;dbname=animal_clinic;charset=UTF8;port=8889');
define('USER', 'root');
define('PASSWORD', 'root');

try {
  
  // DB接続
  $pdo = new PDO(DSN,USER,PASSWORD);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
 catch (PDOException $e) {

  // エラーが発生場合処理を中止
  echo 'エラー発生： ' . $e->getMessage();
  die();

}
