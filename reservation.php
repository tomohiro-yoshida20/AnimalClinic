<?php

    // 他ページから遷移してきたとき $_POST はない
    session_start();

    // functions.php の読み込み
    include('account/functions.php');
    
    // DB接続
    require('db/db_connect.php');
    
    // 画面遷移用のflag
    // 0：ログイン画面
    // 1：予約状況画面
    // 2：新規登録画面
    // 3：新規予約登録確認画面
    $flag = 0;
    
    // ログインボタン押下時の処理
    if (isset($_POST['login'])) {
        
        // 打ち込んだ入力項目↓電話番号(prymary にしてもいいかも)とパスワード
        // tel は数値 int は最大数ではじかれるためつけない
        // ログイン を実行してpostで来た場合はセッションに入れてセット
        // session がある場合は ユーザーアカウントセッション情報をセット
        if (($_POST['tel'] != null) && ($_POST['password'] != null) ) {
            $input_tel      = $_POST['tel'];
            $input_password = $_POST['password'];
        } else {
            $input_tel      = $_SESSION['user']['tel'];
            $input_password = $_SESSION['user']['password'];
        }

        // 入力項目から特定のアカウントを検索 => $result_user 
        include('login.php');

        echo "<pre>";
        echo "ユーザーID：" . $result_user['password'];
        echo "</pre>";

        // result_user が 空でない なら予約確認画面へ
        if (!empty($result_user)) {
            $flag = 1;

            // ログインが成功した場合 session に 会員情報を格納
            $_SESSION['user'] = $result_user;
        }

        // ユーザーIDを用いて予約情報を検索する => $reservation
        include('res_search.php');

        // $reservation から予約確認画面 の項目を出力する
        include('res_confirmation.php');
        // $res_med, $check_in, $check_out

    
    }

    
    // セッションに会員情報が存在する場合は常に会員のご予約情報へとぶ
    if (!empty($_SESSION['user']['tel']) && !empty($_SESSION['user']['password'])) {
        $flag = 1;
    }

    // ログアウト => ログイン画面
    if (isset($_POST['logout'])) {

        // ログインセッションを削除してログイン画面へ
        unset($_SESSION);

        $flag = 0;

    }

    // 戻る 種別選択 => 予約状況
    if (isset($_POST['res_return'])) {

        // 予約状況
        $flag = 1;
        
    }


    // 新規登録
    // 予約状況 => 種別選択 / 登録詳細選択 => 種別選択
    if (isset($_POST['new_reserve']) || isset($_POST['new_res_return_kbn'])) {

        // 新規登録画面（種別選択）
        $flag = 2;
    }
        
    // 予約変更
    // 予約状況 => 予約変更
    if (isset($_POST['change']) ) {

        $flag = 6;
        
    }
    // 予約キャンセル
    // 予約状況 => 予約キャンセル
    if (isset($_POST['cancel']) ) {

        $flag = 7;
        
    }
    
    // 次へ 種別選択 => 登録詳細選択へ
    if (isset($_POST['new_res_next'])) {

        $flag = 3;        
    }
    
    // 戻る 登録詳細選択 => 種別選択
    if ( isset($_POST['new_res_return_kaku'])) {

        // 種別選択に戻る
        $flag = 2;
    }
    
    // 確認 登録詳細選択
    if (isset($_POST['new_res_confirm'])) {

        // エラーがある場合 => 登録詳細選択
        $flag = 3;

        // 予約可能日は 翌日以降から
        $res_day = date("Y-m-") . (date("d") + 1);
        $in_day  = date("Y-m-") . (date("d") + 1);
        // チェックアウト日はチェックイン日より後
        // $out_day = date("Y-m-") . (date("d") + 1);

        // バリデーションを行う
        // 日付を数値で取得
        $in_day  = (int)str_replace('-','',$_POST['in_day']); 
        $out_day = (int)str_replace('-','',$_POST['out_day']); 
        
        // ペットホテルの時のみ
        if($_POST['res_kbn'] == 1) {

            if ($in_day > $out_day) {
                $error_list[] = "チェックアウトの日付に誤りがあります。";
            }
    
            if (($in_day == $out_day) && $_POST['in_time'] >= $_POST['out_time']) {
                $error_list[] = "チェックアウトの時間に誤りがあります。";
            }
        }

        // error_list が 空 => 登録内容確認
        if (empty($error_list)) {
            $flag = 4;
        }
    }
    
    // 確認 予約登録確認 => 完了画面
    if (isset($_POST['new_res_submit'])) {

        // 新規予約完了
        $flag = 5;

        // 予約を reservations テーブルに登録
        include('res_insert.php');
        
    }

    echo "<pre>";
    echo "↓セッションの電話番号" . PHP_EOL;
    var_dump($_SESSION['user']['tel']);
    echo "↓セッションのパスワード" . PHP_EOL;
    var_dump($_SESSION['user']['password']);
    echo "↓セッションのアカウント名" . PHP_EOL;
    var_dump($_SESSION['user']['name']);
    echo "↓セッションの診察状況" . PHP_EOL;
    var_dump($_SESSION['res_med']);
    echo "↓セッションのcheck_in" . PHP_EOL;
    var_dump($_SESSION['check_in']);
    echo "↓セッションのcheck_out" . PHP_EOL;
    var_dump($_SESSION['check_out']);
    echo "</pre>";


    // セッションの予約情報がある場合は変数に格納して使う
    $res_med = $_SESSION['res_med'];
    $check_in = $_SESSION['check_in'];
    $check_out = $_SESSION['check_out'];

?>







<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>動物病院</title>

    <link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.18.1/build/cssreset/cssreset-min.css">
    <!-- fontawesome    -->
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width,initial-scale=1">
<!--     Bootstrap CSS -->
<!--    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">-->
    <!-- googleMaterials -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <!-- googlefonts -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+JP:wght@200;300;400;500;600;700;900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="img/logo.png">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="css/reservation.css">
</head>


<body id="body_res">
    <header>
        <div class="inner">
            <div id="logo">
                <a href="index.html">
                    <!--                        <img src="img/logo.png" alt="">-->
                </a>
            </div>
            <div id="title">
                <div id="title-inner"></div>
            </div>
            <div id="info">
                <div id="address">
                    <p>&nbsp;&nbsp;&nbsp;〒810-0001</p>
                    <p class="long_add">&nbsp;&nbsp;&nbsp;福岡県福岡市中央区天神３丁目４−８</p>
                </div>
                <div id="tel">
                    <p>&nbsp;&nbsp;&nbsp;TEL 0120-XXX-001</p>
                    <p>&nbsp;&nbsp;&nbsp;FAX 0120-XXX-002</p>
                </div>
            </div>
        </div>
        <div>
            <a href="index.html" class="title_humber"></a>
        </div>
    </header>
    <div class="btn-trigger clearfix" id="btn05">
        <span></span>
        <span></span>
        <span></span>
    </div>
    <nav class="pc">
        <ul>
            <li id="nav_guide"><a href="guide.html">診療案内</a></li>
            <li id="nav_facil"><a href="facil.html">施設案内</a></li>
            <li id="nav_care"><a href="care.html">スペシャルケア</a></li>
            <li id="nav_res"><a href="reservation.php">ご予約</a></li>
            <li id="nav_access"><a href="access.html">アクセス</a></li>
        </ul>
    </nav>
    <nav class="side">

        <ul>
            <li id="nav_guide_side"><a href="guide.html">診療案内</a></li>
            <li id="nav_facil_side"><a href="facil.html">院内設備</a></li>
            <li id="nav_care_side"><a href="care.html">スペシャルケア</a></li>
            <li id="nav_res_side"><a href="reservation.php">ご予約</a></li>
            <li id="nav_access_side"><a href="access.html">アクセス</a></li>
        </ul>
        <div id="info_side">
            <div id="address">
                <p>&nbsp;&nbsp;&nbsp;〒810-0001</p>
                <p class="long_add">&nbsp;&nbsp;&nbsp;福岡県福岡市中央区天神３丁目４−８</p>
            </div>
            <div id="tel">
                <p>&nbsp;&nbsp;&nbsp;TEL 0120-XXX-001</p>
                <p>&nbsp;&nbsp;&nbsp;FAX 0120-XXX-002</p>
            </div>
        </div>
    </nav>

    <!-- ここから予約コンテナ -->
    <div id="container">
        <h1>ご予約</h1>

        <!-- ログイン画面 -->
        <?php if($flag == 0): ?>
            <h2>会員ログイン</h2>
            <form action="" method="post">
                <div class="form-group">
                    <label for="tel">電話番号</label>
                    <input type="tel" class="form-control" id="tel" name="tel" maxlength='11' placeholder="" required>
                </div>
                <div class="form-group">
                    <label for="password">パスワード</label>
                    <input type="password" class="form-control" id="password" name="password" maxlength='8' placeholder="" required>
                </div>
                
                <!-- 会員情報が見つからない場合 -->
                <?php if(isset($_POST['login'])  && empty($result_user)): ?>
                    <div class="form-group">
                        <p style="color:red">会員情報が見つかりません。</p>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary bgc-main" name="login">ログイン</button>
                    <a href="./account/register.php">
                        <button class="btn btn-primary bgc-main" type="button">会員登録</button>
                    </a>
                </div>
            </form>
        <?php endif; ?>

        <!-- 予約状況画面 -->
        <?php if($flag == 1): ?>
            <h2>ご予約状況</h2>
            <form action="" method="post">
                <!-- 予約情報が存在する場合 -->
                <?php if( (!empty($res_med) && $res_med['year'] != 0) || ( ($check_in['year'] != 0) && ($check_out['year'] != 0)) ): ?>
                    <p><?php echo $_SESSION['name'] ?> 様</p>

                    <!-- 診察予約が存在する -->
                    <?php if(!empty($res_med) && $res_med['year'] != 0): ?>
                        <p>次回診察のご予約は<?php echo ($res_med['year'] . '年' . $res_med['month'] . '月' . $res_med['day'] . '日（' . $res_med['week'] . '）'); ?></p>
                        <p><?php echo ($res_med['hour'] . '時' . $res_med['minute'] . '分 から'); ?></p>
                        <p>承っております。</p>
                    <?php endif; ?>
                    <!-- ペットホテル予約が存在する -->
                    <?php if(($check_in['year'] != 0) && ($check_out['year'] != 0) ): ?>
                        <p>次回ペットホテルのご予約は</p>
                        <p>チェックイン　：<?php echo ($check_in['year'] . '年' . $check_in['month'] . '月' . $check_in['day'] . '日（' . $check_in['week'] . '）'); ?></p>
                        <p><?php echo ($check_in['hour'] . '時' . $check_in['minute'] . '分'); ?></p>
                        <p>チェックアウト：<?php echo ($check_out['year'] . '年' . $check_out['month'] . '月' . $check_out['day'] . '日（' . $check_out['week'] . '）'); ?></p>
                        <p><?php echo ($check_out['hour'] . '時' . $check_out['minute'] . '分'); ?></p>
                        <p>で承っております。</p>
                    <?php endif; ?>
                    <!-- 必ず表示 -->
                    <p>当日お気をつけてご来院ください。</p>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary bgc-main" name="change">ご予約変更</button>
                        <button type="submit" class="btn btn-primary bgc-main" name="cancel">ご予約キャンセル</button>
                        <button type="submit" class="btn btn-primary bgc-main" name="new_reserve">新規ご予約</button>
                        <button type="submit" class="btn btn-primary bgc-main" name="logout">ログアウト</button>
                    </div>
                <!-- 予約情報が存在しな場合 -->
                <?php else: ?>
                    <p><?php echo $_SESSION['name'] ?> 様</p>
                    <p>次回ご予約はいただいておりません。</p>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary bgc-main" name="new_reserve">新規ご予約</button>
                        <button type="submit" class="btn btn-primary bgc-main" name="logout">ログアウト</button>
                    </div>
                <?php endif; ?>
            </form>
        <?php endif; ?>

        <!-- 新規登録 種別選択 画面 -->
        <?php if($flag == 2): ?>
            <h2>新規ご予約</h2>
            <form action="" method="post">
                <div class="form-group">
                    <div class="text-left column">予約種別</div>
                    <div class="">
                        <input class="column form-check-input" type="radio" name="res_kbn" id="medical" value="0" checked>
                        <label class="form-check-label" for="medical">診察</label>
                        <input class="form-check-input" type="radio" name="res_kbn" id="hotel" value="1">
                        <label class="form-check-label" for="hotel">ペットホテル</label>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary bgc-main" name="new_res_next">次へ</button>
                    <button type="submit" class="btn btn-primary bgc-main" name="res_return">戻る</button>
                </div>
            </form>
        <?php endif; ?>

        <!-- 新規登録 予約詳細 画面 -->
        <?php if($flag == 3): ?>
            <h2>新規ご予約詳細</h2>
            <!-- バリデーション -->
            <?php if (!empty($error_list)):?>
                <p style="color: red">下記のエラーがあります。修正してください。</p>
                <ul>
                    <?php foreach($error_list as $error): ?>
                        <li style="color: red"><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif;?>
            <!-- バリデーションここまで -->
            <form action="" method="post">
                <div class="form-group">
                    <div class="text-left column">動物種別</div>
                    <div class="">
                        <input class="column form-check-input" type="radio" name="animal_kbn" id="dog" value="0" <?php if (empty($_POST['animal_kbn']) || ($_POST['animal_kbn'] == 0)) { echo 'checked'; } ?>>
                        <label class="form-check-label" for="dog" >犬</label>
                        <input class="form-check-input" type="radio" name="animal_kbn" id="cat" value="1" <?php if ($_POST['animal_kbn'] == 1) { echo 'checked'; } ?>>
                        <label class="form-check-label" for="cat">猫</label>
                        <input class="form-check-input" type="radio" name="animal_kbn" id="etc" value="2" <?php if ($_POST['animal_kbn'] == 2) { echo 'checked'; } ?>>
                        <label class="form-check-label" for="etc">その他</label>
                        <p>その他 をご選択の際はコメント欄に動物の種類をご入力ください。</p>
                    </div>
                </div>

                <!-- 診察の場合 -->
                <?php if ($_POST['res_kbn'] == 0): ?>
                    <div class="form-group">
                        <label for="res_day">ご予約日</label>
                        
                        <input type="date" class="form-control" name="res_day" id="res_day" min="<?php echo date("Y-m-") . (date("d") + 1);?>" value="<?php if(!empty($_POST['res_day'])){ echo $_POST['res_day'];} ?>" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="res_time">ご予約時刻</label>
                        <select name="res_time" id="res_time">
                            <option value=""      <?php if (empty($_POST['res_time'])) { echo 'selected'; } ?>>-</option>
                            <option value="0930"   <?php if ($_POST['res_time'] == 930) { echo 'selected'; } ?>>9:30</option>
                            <option value="1030"  <?php if ($_POST['res_time'] == 1030) { echo 'selected'; } ?>>10:30</option>
                            <option value="1130"  <?php if ($_POST['res_time'] == 1130) { echo 'selected'; } ?>>11:30</option>
                            <option value="1230"  <?php if ($_POST['res_time'] == 1230) { echo 'selected'; } ?>>12:30</option>
                            <option value="1330"  <?php if ($_POST['res_time'] == 1330) { echo 'selected'; } ?>>13:30</option>
                            <option value="1430"  <?php if ($_POST['res_time'] == 1430) { echo 'selected'; } ?>>14:30</option>
                            <option value="1530"  <?php if ($_POST['res_time'] == 1530) { echo 'selected'; } ?>>15:30</option>
                            <option value="1630"  <?php if ($_POST['res_time'] == 1630) { echo 'selected'; } ?>>16:30</option>
                        </select>
                    </div>
                <!-- ペットホテルの場合 -->
                <?php else: ?>
                    <div class="form-group">
                        <label for="in_day">チェックイン日</label>
                        <input type="date" class="form-control" name="in_day" id="in_day" min="<?php echo date("Y-m-") . (date("d") + 1);?>" value="<?php if(!empty($_POST['in_day'])){ echo $_POST['in_day'];} ?>" placeholder="">
                    </div>
                    <div class="form-group">
                    <label for="in_time">チェックイン時刻</label>
                        <select name="in_time" id="in_time">
                            <option value=""      <?php if (empty($_POST['in_time'])) { echo 'selected'; } ?>>-</option>
                            <option value="0930"  <?php if ($_POST['in_time'] == 930) { echo 'selected'; } ?>>9:30</option>
                            <option value="1030"  <?php if ($_POST['in_time'] == 1030) { echo 'selected'; } ?>>10:30</option>
                            <option value="1130"  <?php if ($_POST['in_time'] == 1130) { echo 'selected'; } ?>>11:30</option>
                            <option value="1230"  <?php if ($_POST['in_time'] == 1230) { echo 'selected'; } ?>>12:30</option>
                            <option value="1330"  <?php if ($_POST['in_time'] == 1330) { echo 'selected'; } ?>>13:30</option>
                            <option value="1430"  <?php if ($_POST['in_time'] == 1430) { echo 'selected'; } ?>>14:30</option>
                            <option value="1530"  <?php if ($_POST['in_time'] == 1530) { echo 'selected'; } ?>>15:30</option>
                            <option value="1630"  <?php if ($_POST['in_time'] == 1630) { echo 'selected'; } ?>>16:30</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="out_day">チェックアウト日</label>
                        <input type="date" class="form-control" name="out_day" id="out_day" min="<?php echo date("Y-m-") . (date("d") + 1);?>" value="<?php if(!empty($_POST['out_day'])){ echo $_POST['out_day'];} ?>" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="out_time">チェックアウト時刻</label>
                        <select name="out_time" id="out_time">
                            <option value=""      <?php if (empty($_POST['out_time'])) { echo 'selected'; } ?>>-</option>
                            <option value="0930"  <?php if ($_POST['out_time'] == 2001) { echo 'selected'; } ?>>9:30</option>
                            <option value="1030"  <?php if ($_POST['out_time'] == 2002) { echo 'selected'; } ?>>10:30</option>
                            <option value="1130"  <?php if ($_POST['out_time'] == 2003) { echo 'selected'; } ?>>11:30</option>
                            <option value="1230"  <?php if ($_POST['out_time'] == 2004) { echo 'selected'; } ?>>12:30</option>
                            <option value="1330"  <?php if ($_POST['out_time'] == 2005) { echo 'selected'; } ?>>13:30</option>
                            <option value="1430"  <?php if ($_POST['out_time'] == 2006) { echo 'selected'; } ?>>14:30</option>
                            <option value="1530"  <?php if ($_POST['out_time'] == 2007) { echo 'selected'; } ?>>15:30</option>
                            <option value="1630"  <?php if ($_POST['out_time'] == 2008) { echo 'selected'; } ?>>16:30</option>
                        </select>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <div class="form-group">
                        <label for="biko">ご要望やコメント（ペットホテル予約時には必須）</label>
                        <textarea class="form-control" name="biko" id="biko" rows="5"><?php echo $_POST['biko']; ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary bgc-main" name="new_res_confirm">確認</button>
                    <button type="submit" class="btn btn-primary bgc-main" name="new_res_return_kbn">戻る</button>
                </div>

                <!-- 情報保持 -->
                <input type="hidden" name="res_kbn" value="<?php echo $_POST['res_kbn']; ?>">
            </form>
        <?php endif; ?>

        <!-- 予約内容確認画面 -->
        <?php if($flag == 4): ?>
            <h2>ご予約内容確認</h2>

            ★確認画面★
            <br>


            <?php if($_POST['res_kbn'] == 0) { echo '●診察'; } else { echo '●ペットホテル'; }?>
            <br>
            動物種別：<?php if($_POST['animal_kbn'] == 0) { echo '犬'; } elseif($_POST['animal_kbn'] == 1) { echo '猫'; } else {echo 'その他';}  ?>
            <br>
            <!-- 診察の場合 -->
            <?php if ($_POST['res_kbn'] == 0): ?>
                ご予約日：<?php echo (h($_POST['res_day'])); ?>
                <br>
                ご予約時刻：<?php echo (h($_POST['res_time'])); ?>
            <!-- ペットホテルの場合 -->
            <?php else: ?>
                チェックイン日：<?php echo (h($_POST['in_day'])); ?>
                <br>
                チェックイン時刻：<?php echo (h($_POST['in_time'])); ?>
                <br>
                チェックアウト：<?php echo (h($_POST['out_day'])); ?>
                <br>
                チェックアウト時刻：<?php echo (h($_POST['out_time'])); ?>
            <?php endif; ?>
            <br>
            ご要望やコメント：<?php echo h($_POST['biko']); ?>
            <br>

            <form action="" method="post">
              
                <div class="form-group">
                    <button type="submit" class="btn btn-primary bgc-main" name="new_res_submit">送信</button>
                    <button type="submit" class="btn btn-primary bgc-main" name="new_res_return_kaku">戻る</button>
                </div>

                <!-- 情報保持 -->
                <input type="hidden" name="res_kbn" value="<?php echo $_POST['res_kbn']; ?>">
                <input type="hidden" name="animal_kbn" value="<?php echo $_POST['animal_kbn']; ?>">
                <input type="hidden" name="res_day" value="<?php echo $_POST['res_day']; ?>">
                <input type="hidden" name="res_time" value="<?php echo $_POST['res_time']; ?>">
                <input type="hidden" name="in_day" value="<?php echo $_POST['in_day']; ?>">
                <input type="hidden" name="in_time" value="<?php echo $_POST['in_time']; ?>">
                <input type="hidden" name="out_day" value="<?php echo $_POST['out_day']; ?>">
                <input type="hidden" name="out_time" value="<?php echo $_POST['out_time']; ?>">
                <input type="hidden" name="biko" value="<?php echo $_POST['biko']; ?>">
            </form>
        <?php endif; ?>

        <!-- 予約内容確認画面 -->
        <?php if($flag == 5): ?>
            <h2>ご予約内容確認</h2>

            ★ご予約完了
            <form action="" method="post">
                <div class="form-group">
                    <button type="submit" class="btn btn-primary bgc-main" name="login">予約内容確認画面へ</button>
                </div>
            </form>
        <?php endif; ?>
    </div>

    <!-- 予約コンテナここまで -->





    <footer>
        <ul id="f-sns" class="clearfix">
            <li><img src="img/sns/twitter.svg" alt="" style="width:40px;height:40px;"></li>
            <li><img src="img/sns/facebook.png" alt="" style="width:40px;height:40px;"></li>
        </ul>
        <div class="copyright">
            <p><small>Copyright 2021 ヒューリスアニマルクリニック All rights reserved.</small></p>
        </div>
    </footer>
    	
    <p id="page-top">
       <a href="#">
           <i class="fas fa-angle-up fa-2x"></i>    
        </a>
    </p>

    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/main.js"></script>


</body>

</html>
