<?php

  $sql = "insert into reservations (
    id,
    res_kbn,
    saishin_kbn,
    animal_kbn,
    from_day,
    to_day,
    from_time,
    to_time,
    biko,
    update_day, 
    update_time, 
    new_day, 
    new_time
  ) values (
    :id,
    :res_kbn,
    :saishin_kbn,
    :animal_kbn,
    :from_day,
    :to_day,
    :from_time,
    :to_time,
    :biko,
    :update_day, 
    :update_time, 
    :new_day, 
    :new_time
  )";

  // 各種データ設定
  $id          = (int)$_SESSION['user']['id'];

  // 診察かペットホテルかはpostで前のページから引っ張ってくる
  $res_kbn     = (int)$_POST['res_kbn'];

  // 初めての場合 $saishin_kbn = 0;
  // 2回目以降はセッションから情報をとってきて　+　１　する
//  echo "res_kbn 診察 は" . $_SESSION['res_med']['saishin_kbn'];
//  echo "res_kbn ホテル は" . $_SESSION['res_hotel']['saishin_kbn'];
//  echo "user_id は" . $id;
  if($res_kbn == 0) {
    // 診察
    if ($_SESSION['res_med']['saishin_kbn'] == null) {
      $saishin_kbn = 0;
    } else {  
      $saishin_kbn = $_SESSION['res_med']['saishin_kbn'] + 1;
    }
    
  } else {
    // ペットホテルホテル
    if ($_SESSION['res_hotel']['saishin_kbn'] == null) {
      $saishin_kbn = 0;
    } else {  
      $saishin_kbn = $_SESSION['res_hotel']['saishin_kbn'] + 1;
    }

  }
//  echo "saishin_kbn は" . $saishin_kbn;
//　$saishin_kbn = $_SESSION['res_med']['saishin_kbn'];
  // 初めてではない最新の場合
//  echo '最新区分は⇒';
//  var_dump($saishin_kbn);
//  echo 'idは⇒';
//  var_dump($id);
//  var_dump($_SESSION['res_med']);
//  var_dump('1');

  $animal_kbn  = (int)$_POST['animal_kbn'];

  // 診察の場合
  if ($res_kbn == 0) {
    $from_day    = (int)str_replace('-','', $_POST['res_day']);
    $to_day      = 0;
    $from_time   = (int)$_POST['res_time'];
    $to_time     = 0;

  // ペットホテルの場合
  } else {
    $from_day    = (int)str_replace('-','', $_POST['in_day']);
    $to_day      = (int)str_replace('-','', $_POST['out_day']);
    $from_time   = (int)$_POST['in_time'];
    $to_time     = (int)$_POST['out_time'];
  }

  $biko        = $_POST['biko'];
  $update_day  = 0;
  $update_time = 0;
  $new_day     = (int)(date("Y") . date("m") . date("d"));
  $new_time    = (int)(date("h") . date("i") . date("s"));

  // 設定データ代入
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':id'          , $id,          PDO::PARAM_INT);
  $stmt->bindValue(':res_kbn'     , $res_kbn,     PDO::PARAM_INT);
  $stmt->bindValue(':saishin_kbn' , $saishin_kbn, PDO::PARAM_INT);
  $stmt->bindValue(':animal_kbn'  , $animal_kbn,  PDO::PARAM_INT);
  $stmt->bindValue(':from_day'    , $from_day,    PDO::PARAM_INT);
  $stmt->bindValue(':to_day'      , $to_day,      PDO::PARAM_INT);
  $stmt->bindValue(':from_time'   , $from_time,   PDO::PARAM_INT);
  $stmt->bindValue(':to_time'     , $to_time,     PDO::PARAM_INT);
  $stmt->bindValue(':biko'        , $biko,        PDO::PARAM_STR);
  $stmt->bindValue(':update_day'  , $update_day,  PDO::PARAM_INT);
  $stmt->bindValue(':update_time' , $update_time, PDO::PARAM_INT);
  $stmt->bindValue(':new_day'     , $new_day,     PDO::PARAM_INT);
  $stmt->bindValue(':new_time'    , $new_time,    PDO::PARAM_INT);
  // 予約登録処理実行
   $stmt->execute();

//  echo "<pre>";
//  echo "インサート情報";
//  var_dump($id);
//  var_dump($res_kbn);
//  echo "最新区分 =>";
//  var_dump($saishin_kbn);
//  var_dump($animal_kbn);
//  var_dump($from_day);
//  var_dump($to_day);
//  var_dump($from_time);
//  var_dump($to_time);
//  var_dump($biko);
//  var_dump($update_day);
//  var_dump($update_time);
//  var_dump($new_day);
//  var_dump($new_time);
//  echo "</pre>";