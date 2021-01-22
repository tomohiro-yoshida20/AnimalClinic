<?php

    // 他ページから遷移してきたとき $_POST はない

    // functions.php の読み込み
    include('account/functions.php');

    // DB接続
    require('db/db_connect.php');

    // 画面遷移用のflag
    // 0：ログイン画面
    // 1：予約状況画面
    // 2：完了画面
    $flag = 0;

    // ログインボタン押下時の処理
    if (isset($_POST['login'])) {

        
        
        // 打ち込んだ入力項目↓電話番号(prymary にしてもいいかも)とパスワード
        // tel は数値 int は最大数ではじかれるためつけない
        $input_tel      = $_POST['tel'];
        $input_password = $_POST['password'];

        // 入力項目を条件に users テーブルからアカウントレコードを取得（検索するアカウントは １件 なので fetch ）
        $sql = "select * from users where tel = ? and password = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(1, $input_tel, PDO::PARAM_STR);
        $stmt->bindValue(2, $input_password, PDO::PARAM_STR);
        $stmt->execute();

        // アカウント情報の検索結果を $result_user へ格納
        $result_user = $stmt->fetch(PDO::FETCH_ASSOC);
        // var_dump($result_user['tel']);




        
        // 検索結果のユーザーID
        $user_id = (int)$result_user['id'];

        // 検索結果アカウントから アカウントの 'id' をキーに予約情報を取得する ⇒ reservations テーブル
        // 予約は最新のものを 診察 ペットホテル それぞれで取得し、降順で並べる ０：診察 １：ペットホテル
        $sql = "select * from reservations A
                        where id = ?
                          and A.saishin_kbn in (select MAX(B.saishin_kbn)
                                                 from reservations B
                                             group by B.res_kbn
                                             )
                        order by A.res_kbn";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(1, $user_id , PDO::PARAM_INT);
        $stmt->execute();

        // 予約情報の検索結果を $reservation に格納
        $reservation = $stmt->fetchAll();



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

        // ０：診察予約 
        $res_med = $reservation[0];    
        // １：ペットホテル予約
        $res_hotel = $reservation[1];

        // 診察
        // 年
        $res_med['year'] = substr($res_med['from_day'], 0 , 4);
        // 月 （例 20200102 => 01 を切り取る => 0 埋めを除く => 1）
        $res_med['month'] = ltrim(substr($res_med['from_day'], 4 , 2),0);
        // 日 （例 20200102 => 02 を切り取る => 0 埋めを除く => 2）
        $res_med['day'] = ltrim(substr($res_med['from_day'], 6 , 2),0);
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
        $res_med['hour'] = ltrim(substr($res_med['from_time'], 0, 2),0);
        $res_med['minute'] = ltrim(substr($res_med['from_time'], 2, 2),0);
        // $res_med['hour'] = ltrim(substr($res_med['from_time'], 0, 2),0);
        // $res_med['minute'] = ltrim(substr($res_med['from_time'], 2, 2),0);
        $res_med['hour'] = substr($res_med['from_time'], 0, 2);
        $res_med['minute'] = substr($res_med['from_time'], 2, 2);
        // if ($res_med['minute'] == '') {
        //     $res_med['minute'] = '00';
        // } 

        // チェックイン
        // 年
        $check_in['year'] = substr($res_hotel['from_day'], 0 , 4);
        // 月 （例 20200102 => 01 を切り取る => 0 埋めを除く => 1）
        $check_in['month'] = ltrim(substr($res_hotel['from_day'], 4 , 2),0);
        // 日 （例 20200102 => 02 を切り取る => 0 埋めを除く => 2）
        $check_in['day'] = ltrim(substr($res_hotel['from_day'], 6 , 2),0);
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
        $check_in['hour'] = substr($res_hotel['from_time'], 0, 2);
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
        var_dump($check_out['minute']);
        

        // 予約情報の検索結果を出力
        echo "<pre>";
        var_dump($timestamp);
        var_dump(date('Y年m月d日h時i分s秒w', $timestamp));
        var_dump($res_med['week']);
        echo "</pre>";
        





        // error_list が 空 なら予約確認画面へ
        if (!empty($result_user)) {
            $flag = 1;
        }
    }




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
            <li id="nav_res"><a href="reservation.html">ご予約</a></li>
            <li id="nav_access"><a href="access.html">アクセス</a></li>
        </ul>
    </nav>
    <nav class="side">

        <ul>
            <li id="nav_guide_side"><a href="guide.html">診療案内</a></li>
            <li id="nav_facil_side"><a href="facil.html">院内設備</a></li>
            <li id="nav_care_side"><a href="care.html">スペシャルケア</a></li>
            <li id="nav_res_side"><a href="reservation.html">ご予約</a></li>
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
                
                <!-- 会員情報の存在チェック -->
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
            <h2>予約状況</h2>
            <p><?php echo $result_user['name'] ?> 様</p>
            <p>次回診察のご予約は<?php echo ($res_med['year'] . '年' . $res_med['month'] . '月' . $res_med['day'] . '日（' . $res_med['week'] . '）'); ?></p>
            <p><?php echo ($res_med['hour'] . '時' . $res_med['minute'] . '分 から'); ?></p>
            <p>承っております。</p>
            
            <p>次回ペットホテルのご予約は</p>
            <p>チェックイン　：<?php echo ($check_in['year'] . '年' . $check_in['month'] . '月' . $check_in['day'] . '日（' . $check_in['week'] . '）'); ?></p>
            <p><?php echo ($check_in['hour'] . '時' . $check_in['minute'] . '分'); ?></p>

            <p>チェックアウト：<?php echo ($check_out['year'] . '年' . $check_out['month'] . '月' . $check_out['day'] . '日（' . $check_out['week'] . '）'); ?></p>
            <p><?php echo ($check_out['hour'] . '時' . $check_out['minute'] . '分'); ?></p>
            <p>で承っております。</p>
            <p>当日お気をつけてご来院ください。</p>
            <div class="form-group">
                <button type="submit" class="btn btn-primary bgc-main" name="change">予約変更</button>
                <button type="submit" class="btn btn-primary bgc-main" name="cancel">予約キャンセル</button>
            </div>
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
