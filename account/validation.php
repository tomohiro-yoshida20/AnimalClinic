<?php

// required で制御
// if (empty($_POST['name'])) {
//   $error_list[] = "「氏名」が未入力です。入力してください。";
// }

// required で制御
// if (($_POST['year'] == '') || ($_POST['month'] == '') || ($_POST['day']) == '') {
//   $error_list[] = "「生年月日」が正しく入力されていません。入力してください。";
// }

if (strlen($_POST['post_number']) < 7) {
  $error_list[] = "「郵便番号」が正しく入力されていません。";
}

// required で制御
// if (empty($_POST['address'])) {
//   $error_list[] = "「住所」が未入力です。入力してください。";
// }

if (strlen($_POST['tel']) < 10) {
  $error_list[] = "「電話番号」が正しく入力されていません。";
}

if (strlen($_POST['password']) < 8) {
  $error_list[] = "「パスワード」が正しく入力されていません。";
}    