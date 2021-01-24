<?php

  // 予約状況取得クラス
  class Reservation {
      public $year;
      public $month;
      public $day;
      public $week;
      public $time;
      public function __construct($reservation) {
          $this->year = substr($reservation['from_day'], 0 , 4);
      }
      public function getYear() {
          return $this->year;
      }
  }
  echo "<pre>";
  var_dump($reservation);
  echo "</pre>";

  if ($reservation[0]['res_kbn'] == 0) {
    // ０：診察予約 
    $res_med = $reservation[0]; 
    // １：ペットホテル予約
    $res_hotel = $reservation[1];   
  } else {
    // 
    $res_med; 
    $res_hotel = $reservation[0]; 
  }

  // 診察
  // 年
  $res_med['year']  = (int)substr($res_med['from_day'], 0 , 4);
  // 月 （例 20200102 => 01 を切り取る => 0 埋めを除く => 1）
  $res_med['month'] = (int)ltrim(substr($res_med['from_day'], 4 , 2),0);
  // 日 （例 20200102 => 02 を切り取る => 0 埋めを除く => 2）
  $res_med['day']   = (int)ltrim(substr($res_med['from_day'], 6 , 2),0);
  // 曜日
  $weeks = ['日','月','火','水','木','金'];
  // 予約日から日付を作成
  $timestamp = mktime(0,0,0,$res_med['month'],$res_med['day'],$res_med['year']);
  // 曜日を取得（日:0  月:1  火:2  水:3  木:4  金:5  土:6）
  // 曜日番号を取得
  $week_key = date('w', $timestamp);
  // $week_key をキーに曜日を取得
  $res_med['week'] = $weeks[$week_key];
  // 時間
  // $res_med['hour'] = ltrim(substr($res_med['from_time'], 0, 2),0);
  // $res_med['minute'] = ltrim(substr($res_med['from_time'], 2, 2),0);
  if (($res_med['from_time']) < 1000) {
    $res_med['hour']   = (int)substr($res_med['from_time'], 0, 1);
    $res_med['minute'] = (int)substr($res_med['from_time'], 2, 2);
  } else {
    $res_med['hour']   = (int)substr($res_med['from_time'], 0, 2);
    $res_med['minute'] = (int)substr($res_med['from_time'], 2, 2);
  }
  // if ($res_med['minute'] == '') {
  //     $res_med['minute'] = '00';
  // } 

  // セッションとして 診察予約情報を入れなおす
  $_SESSION['res_med'] = $res_med;



  // チェックイン
  // 年
  $check_in['year']  = substr($res_hotel['from_day'], 0 , 4);
  // 月 （例 20200102 => 01 を切り取る => 0 埋めを除く => 1）
  $check_in['month'] = ltrim(substr($res_hotel['from_day'], 4 , 2),0);
  // 日 （例 20200102 => 02 を切り取る => 0 埋めを除く => 2）
  $check_in['day']   = ltrim(substr($res_hotel['from_day'], 6 , 2),0);
  // 曜日
  $weeks = ['日','月','火','水','木','金'];
  // 予約日から日付を作成
  $timestamp = mktime(0,0,0,$res_hotel['month'],$res_hotel['day'],$res_hotel['year']);
  // 曜日を取得（日:0  月:1  火:2  水:3  木:4  金:5  土:6）
  // 曜日番号を取得
  $week_key = date('w', $timestamp);
  // $week_key をキーに曜日を取得
  $check_in['week'] = $weeks[$week_key];
  // 時間
  // $check_in['hour'] = ltrim(substr($res_hotel['to_time'], 0, 2),0);
  // $check_in['minute'] = ltrim(substr($res_hotel['to_time'], 2, 2),0);
  $check_in['hour']   = substr($res_hotel['from_time'], 0, 2);
  $check_in['minute'] = substr($res_hotel['from_time'], 2, 2);
  // if ($check_in['minute'] == '') {
  //     $check_in['minute'] = '00';
  // } 

  // チェックアウト
  // 年
  $check_out['year'] = substr($res_hotel['to_day'], 0 , 4);
  // 月 （例 20200102 => 01 を切り取る => 0 埋めを除く => 1）
  $check_out['month'] = ltrim(substr($res_hotel['to_day'], 4 , 2),0);
  // 日 （例 20200102 => 02 を切り取る => 0 埋めを除く => 2）
  $check_out['day'] = ltrim(substr($res_hotel['to_day'], 6 , 2),0);
  // 曜日
  $weeks = ['日','月','火','水','木','金'];
  // 予約日から日付を作成
  $timestamp = mktime(0,0,0,$res_hotel['month'],$res_hotel['day'],$res_hotel['year']);
  // 曜日を取得（日:0  月:1  火:2  水:3  木:4  金:5  土:6）
  // 曜日番号を取得
  $week_key = date('w', $timestamp);
  // $week_key をキーに曜日を取得
  $check_out['week'] = $weeks[$week_key];
  // 時間
  // $check_out['hour'] = ltrim(substr($res_hotel['to_time'], 0, 2),0);
  // $check_out['minute'] = ltrim(substr($res_hotel['to_time'], 2, 2),0);
  $check_out['hour'] = substr($res_hotel['to_time'], 0, 2);
  $check_out['minute'] = substr($res_hotel['to_time'], 2, 2);
  // if ($check_out['minute'] == '') {
  //     $check_out['minute'] = '00';
  // } 

  // セッションとして ペットホテル予約情報を入れなおす
  $_SESSION['check_in'] = $check_in;

  $_SESSION['check_out'] = $check_out;
  
