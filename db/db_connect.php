<?php

define('DSN', 'mysql:host=mysql43.conoha.ne.jp;dbname=r66fr_db;charset=UTF8');
define('USER', 'r66fr_user');
define('PASSWORD', 'password$123');

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
