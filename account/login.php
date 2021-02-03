<?php



  // 入力項目を条件に users テーブルからアカウントレコードを取得（検索するアカウントは １件 なので fetch ）
  $sql = "select * from users where tel = ? and password = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(1, $input_tel, PDO::PARAM_STR);
  $stmt->bindValue(2, $input_password, PDO::PARAM_STR);
  $stmt->execute();

  // アカウント情報の検索結果を $result_user へ格納
  $result_user = $stmt->fetch(PDO::FETCH_ASSOC);
  // var_dump($result_user['tel']);