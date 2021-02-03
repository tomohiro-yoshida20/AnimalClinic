<?php
    ini_set('display_errors',1);
    include('common/notice_remove.php');

    // functions.php の読み込み
    include('common/functions.php');

    // DB接続
    require('db/db_connect.php');

    // 画面遷移用のflag
    // 0：登録フォーム
    // 1：確認画面
    // 2：完了画面
    $flag = 0;
    
    // 「登録フォーム」より「確認」ボタン押下時
    if (isset($_POST['confirm'])) {

        // バリデーションを行う
        require('common/validation.php');

        // error_list が 空 なら確認画面へ
        if (empty($error_list)) {
            $flag = 1;
        }
    }

    // 「確認画面」より「戻る」ボタン押下時
    if (isset($_POST['back'])) {

        // 「登録フォーム」へ戻る
        $flag = 0;
    }

    // 「確認画面」より「送信」ボタン押下時
    if (isset($_POST['submit'])) {

        // 「完了画面」へ
        $flag = 2;

        // INSERT 実行
        $sql = 
        "insert into users (
            password    ,
            name        ,
            birth_day   ,
            sex         ,
            post_number ,
            address     ,
            tel         ,
            email       ,
            biko        ,
            update_day  ,
            update_time ,
            new_day     ,
            new_time    
        ) values (
            :password    ,
            :name        ,
            :birth_day   ,
            :sex         ,
            :post_number ,
            :address     ,
            :tel         ,
            :email       ,
            :biko        ,
            :update_day  ,
            :update_time ,
            :new_day     ,
            :new_time
        )";

        // 各種データ設定
        $password    = $_POST['password'];
        $name        = $_POST['name'];
        $birth_day   = (int)($_POST['year'] . $_POST['month'] . $_POST['day']);
        $sex         = (int)$_POST['sex'];
        $post_number = (int)$_POST['post_number'];
        $address     = $_POST['address'];
        // 電話番号はint桁数を超えるため文字列で送信
        $tel         = $_POST['tel'];
        $email       = $_POST['email'];
        $biko        = "";
        $update_day  = "0";
        $update_time = "0";
        $new_day     = date("Y") . date("m") . date("d");
        $new_time    = date("h") . date("i") . date("s");
        
        // 上記設定データを代入
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':password'    , $password,    PDO::PARAM_STR);
        $stmt->bindValue(':name'        , $name,        PDO::PARAM_STR);
        $stmt->bindValue(':birth_day'   , $birth_day,   PDO::PARAM_INT);
        $stmt->bindValue(':sex'         , $sex,         PDO::PARAM_INT);
        $stmt->bindValue(':post_number' , $post_number, PDO::PARAM_INT);
        $stmt->bindValue(':address'     , $address,     PDO::PARAM_STR);
        $stmt->bindValue(':tel'         , $tel,         PDO::PARAM_STR);
        $stmt->bindValue(':email'       , $email,       PDO::PARAM_STR);
        $stmt->bindValue(':biko'        , $biko,        PDO::PARAM_STR);
        $stmt->bindValue(':update_day'  , $update_day,  PDO::PARAM_INT);
        $stmt->bindValue(':update_time' , $update_time, PDO::PARAM_INT);
        $stmt->bindValue(':new_day'     , $new_day,     PDO::PARAM_INT);
        $stmt->bindValue(':new_time'    , $new_time,    PDO::PARAM_INT);

        //処理実行
       $stmt->execute();
    }
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>動物病院</title>
    <link rel="stylesheet" href="css/reset.css">
    <!-- fontawesome    -->
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width,initial-scale=1">
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
            <a href="index.php" class="title_humber"></a>
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
        <h1>会員登録</h1>

        <!-- 確認画面 -->
        <?php if($flag == 1): ?>
            ★確認★<br>
            <br>
            名前：<?php echo h($_POST['name']); ?>
            <br>
            生年月日：<?php echo (h($_POST['year']) . '年' . h($_POST['month']) . '月' . h($_POST['day']) . '日'); ?>
            <br>
            性別：<?php if (h($_POST['sex']) == 0) { echo '男';} else { echo '女';} ?>
            <br>
            郵便番号：<?php echo h($_POST['post_number']); ?>
            <br>
            住所：<?php echo h($_POST['address']); ?>
            <br>
            電話番号：<?php echo h($_POST['tel']); ?>
            <br>
            メールアドレス（任意）：<?php echo h($_POST['email']); ?>
            <br>
            パスワード：<?php echo h($_POST['password']); ?>
            <br>

            <form action="" method="post">
                <div class="form-group">
                    <button type="submit" class="btn btn-primary bgc-main" name="submit">送信する</button>
                    <button type="submit" class="btn btn-primary bgc-main" name="back">戻る</button>
                </div>

                <!-- 「戻る」ボタン押下時のinput保持 -->
                <input type="hidden" name="name" value="<?php echo $_POST['name']; ?>">
                <input type="hidden" name="year" value="<?php echo $_POST['year']; ?>">
                <input type="hidden" name="month" value="<?php echo $_POST['month']; ?>">
                <input type="hidden" name="day" value="<?php echo $_POST['day']; ?>">
                <input type="hidden" name="sex" value="<?php echo $_POST['sex']; ?>">
                <input type="hidden" name="post_number" value="<?php echo $_POST['post_number']; ?>">
                <input type="hidden" name="address" value="<?php echo $_POST['address']; ?>">
                <input type="hidden" name="tel" value="<?php echo $_POST['tel']; ?>">
                <input type="hidden" name="email" value="<?php echo $_POST['email']; ?>">
                <input type="hidden" name="password" value="<?php echo $_POST['password']; ?>">
            </form>
        <?php endif; ?>

        <!-- 完了画面 -->
        <?php if($flag == 2): ?>
            ★完了★<br>
            <br>
            会員登録が完了しました。
            <div class="form-group">
                <a href="reservation.php">
                    <button class="btn btn-primary bgc-main" type="button">ログイン画面へ</button>
                </a>
            </div>
        <?php endif; ?>
        
        <!-- 入力フォーム -->
        <?php if($flag == 0): ?>
            <br>
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
            <!-- 入力フォーム -->
            <form action="" method="post">
                <div class="form-group">
                    <label for="name">氏名</label>
                    <input type="text" class="form-control" name="name" placeholder="" value="<?php echo $_POST['name']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="">生年月日</label>
                    <select name="year" required>
                        <option value=""   <?php if (empty($_POST['year'])) { echo 'selected'; } ?>>-</option>
                        <option value="1920"  <?php if ($_POST['year'] == 1920) { echo 'selected'; } ?>>1920</option>
                        <option value="1921"  <?php if ($_POST['year'] == 1921) { echo 'selected'; } ?>>1921</option>
                        <option value="1922"  <?php if ($_POST['year'] == 1922) { echo 'selected'; } ?>>1922</option>
                        <option value="1923"  <?php if ($_POST['year'] == 1923) { echo 'selected'; } ?>>1923</option>
                        <option value="1924"  <?php if ($_POST['year'] == 1924) { echo 'selected'; } ?>>1924</option>
                        <option value="1925"  <?php if ($_POST['year'] == 1925) { echo 'selected'; } ?>>1925</option>
                        <option value="1926"  <?php if ($_POST['year'] == 1926) { echo 'selected'; } ?>>1926</option>
                        <option value="1927"  <?php if ($_POST['year'] == 1927) { echo 'selected'; } ?>>1927</option>
                        <option value="1928"  <?php if ($_POST['year'] == 1928) { echo 'selected'; } ?>>1928</option>
                        <option value="1929"  <?php if ($_POST['year'] == 1929) { echo 'selected'; } ?>>1929</option>
                        <option value="1930"  <?php if ($_POST['year'] == 1930) { echo 'selected'; } ?>>1930</option>
                        <option value="1931"  <?php if ($_POST['year'] == 1931) { echo 'selected'; } ?>>1931</option>
                        <option value="1932"  <?php if ($_POST['year'] == 1932) { echo 'selected'; } ?>>1932</option>
                        <option value="1933"  <?php if ($_POST['year'] == 1933) { echo 'selected'; } ?>>1933</option>
                        <option value="1934"  <?php if ($_POST['year'] == 1934) { echo 'selected'; } ?>>1934</option>
                        <option value="1935"  <?php if ($_POST['year'] == 1935) { echo 'selected'; } ?>>1935</option>
                        <option value="1936"  <?php if ($_POST['year'] == 1936) { echo 'selected'; } ?>>1936</option>
                        <option value="1937"  <?php if ($_POST['year'] == 1937) { echo 'selected'; } ?>>1937</option>
                        <option value="1938"  <?php if ($_POST['year'] == 1938) { echo 'selected'; } ?>>1938</option>
                        <option value="1939"  <?php if ($_POST['year'] == 1939) { echo 'selected'; } ?>>1939</option>
                        <option value="1940"  <?php if ($_POST['year'] == 1940) { echo 'selected'; } ?>>1940</option>
                        <option value="1941"  <?php if ($_POST['year'] == 1941) { echo 'selected'; } ?>>1941</option>
                        <option value="1942"  <?php if ($_POST['year'] == 1942) { echo 'selected'; } ?>>1942</option>
                        <option value="1943"  <?php if ($_POST['year'] == 1943) { echo 'selected'; } ?>>1943</option>
                        <option value="1944"  <?php if ($_POST['year'] == 1944) { echo 'selected'; } ?>>1944</option>
                        <option value="1945"  <?php if ($_POST['year'] == 1945) { echo 'selected'; } ?>>1945</option>
                        <option value="1946"  <?php if ($_POST['year'] == 1946) { echo 'selected'; } ?>>1946</option>
                        <option value="1947"  <?php if ($_POST['year'] == 1947) { echo 'selected'; } ?>>1947</option>
                        <option value="1948"  <?php if ($_POST['year'] == 1948) { echo 'selected'; } ?>>1948</option>
                        <option value="1949"  <?php if ($_POST['year'] == 1949) { echo 'selected'; } ?>>1949</option>
                        <option value="1950"  <?php if ($_POST['year'] == 1950) { echo 'selected'; } ?>>1950</option>
                        <option value="1951"  <?php if ($_POST['year'] == 1951) { echo 'selected'; } ?>>1951</option>
                        <option value="1952"  <?php if ($_POST['year'] == 1952) { echo 'selected'; } ?>>1952</option>
                        <option value="1953"  <?php if ($_POST['year'] == 1953) { echo 'selected'; } ?>>1953</option>
                        <option value="1954"  <?php if ($_POST['year'] == 1954) { echo 'selected'; } ?>>1954</option>
                        <option value="1955"  <?php if ($_POST['year'] == 1955) { echo 'selected'; } ?>>1955</option>
                        <option value="1956"  <?php if ($_POST['year'] == 1956) { echo 'selected'; } ?>>1956</option>
                        <option value="1957"  <?php if ($_POST['year'] == 1957) { echo 'selected'; } ?>>1957</option>
                        <option value="1958"  <?php if ($_POST['year'] == 1958) { echo 'selected'; } ?>>1958</option>
                        <option value="1959"  <?php if ($_POST['year'] == 1959) { echo 'selected'; } ?>>1959</option>
                        <option value="1960"  <?php if ($_POST['year'] == 1960) { echo 'selected'; } ?>>1960</option>
                        <option value="1961"  <?php if ($_POST['year'] == 1961) { echo 'selected'; } ?>>1961</option>
                        <option value="1962"  <?php if ($_POST['year'] == 1962) { echo 'selected'; } ?>>1962</option>
                        <option value="1963"  <?php if ($_POST['year'] == 1963) { echo 'selected'; } ?>>1963</option>
                        <option value="1964"  <?php if ($_POST['year'] == 1964) { echo 'selected'; } ?>>1964</option>
                        <option value="1965"  <?php if ($_POST['year'] == 1965) { echo 'selected'; } ?>>1965</option>
                        <option value="1966"  <?php if ($_POST['year'] == 1966) { echo 'selected'; } ?>>1966</option>
                        <option value="1967"  <?php if ($_POST['year'] == 1967) { echo 'selected'; } ?>>1967</option>
                        <option value="1968"  <?php if ($_POST['year'] == 1968) { echo 'selected'; } ?>>1968</option>
                        <option value="1969"  <?php if ($_POST['year'] == 1969) { echo 'selected'; } ?>>1969</option>
                        <option value="1970"  <?php if ($_POST['year'] == 1970) { echo 'selected'; } ?>>1970</option>
                        <option value="1971"  <?php if ($_POST['year'] == 1971) { echo 'selected'; } ?>>1971</option>
                        <option value="1972"  <?php if ($_POST['year'] == 1972) { echo 'selected'; } ?>>1972</option>
                        <option value="1973"  <?php if ($_POST['year'] == 1973) { echo 'selected'; } ?>>1973</option>
                        <option value="1974"  <?php if ($_POST['year'] == 1974) { echo 'selected'; } ?>>1974</option>
                        <option value="1975"  <?php if ($_POST['year'] == 1975) { echo 'selected'; } ?>>1975</option>
                        <option value="1976"  <?php if ($_POST['year'] == 1976) { echo 'selected'; } ?>>1976</option>
                        <option value="1977"  <?php if ($_POST['year'] == 1977) { echo 'selected'; } ?>>1977</option>
                        <option value="1978"  <?php if ($_POST['year'] == 1978) { echo 'selected'; } ?>>1978</option>
                        <option value="1979"  <?php if ($_POST['year'] == 1979) { echo 'selected'; } ?>>1979</option>
                        <option value="1980"  <?php if ($_POST['year'] == 1980) { echo 'selected'; } ?>>1980</option>
                        <option value="1981"  <?php if ($_POST['year'] == 1981) { echo 'selected'; } ?>>1981</option>
                        <option value="1982"  <?php if ($_POST['year'] == 1982) { echo 'selected'; } ?>>1982</option>
                        <option value="1983"  <?php if ($_POST['year'] == 1983) { echo 'selected'; } ?>>1983</option>
                        <option value="1984"  <?php if ($_POST['year'] == 1984) { echo 'selected'; } ?>>1984</option>
                        <option value="1985"  <?php if ($_POST['year'] == 1985) { echo 'selected'; } ?>>1985</option>
                        <option value="1986"  <?php if ($_POST['year'] == 1986) { echo 'selected'; } ?>>1986</option>
                        <option value="1987"  <?php if ($_POST['year'] == 1987) { echo 'selected'; } ?>>1987</option>
                        <option value="1988"  <?php if ($_POST['year'] == 1988) { echo 'selected'; } ?>>1988</option>
                        <option value="1989"  <?php if ($_POST['year'] == 1989) { echo 'selected'; } ?>>1989</option>
                        <option value="1990"  <?php if ($_POST['year'] == 1990) { echo 'selected'; } ?>>1990</option>
                        <option value="1991"  <?php if ($_POST['year'] == 1991) { echo 'selected'; } ?>>1991</option>
                        <option value="1992"  <?php if ($_POST['year'] == 1992) { echo 'selected'; } ?>>1992</option>
                        <option value="1993"  <?php if ($_POST['year'] == 1993) { echo 'selected'; } ?>>1993</option>
                        <option value="1994"  <?php if ($_POST['year'] == 1994) { echo 'selected'; } ?>>1994</option>
                        <option value="1995"  <?php if ($_POST['year'] == 1995) { echo 'selected'; } ?>>1995</option>
                        <option value="1996"  <?php if ($_POST['year'] == 1996) { echo 'selected'; } ?>>1996</option>
                        <option value="1997"  <?php if ($_POST['year'] == 1997) { echo 'selected'; } ?>>1997</option>
                        <option value="1998"  <?php if ($_POST['year'] == 1998) { echo 'selected'; } ?>>1998</option>
                        <option value="1999"  <?php if ($_POST['year'] == 1999) { echo 'selected'; } ?>>1999</option>
                        <option value="2000"  <?php if ($_POST['year'] == 2000) { echo 'selected'; } ?>>2000</option>
                        <option value="2001"  <?php if ($_POST['year'] == 2001) { echo 'selected'; } ?>>2001</option>
                        <option value="2002"  <?php if ($_POST['year'] == 2002) { echo 'selected'; } ?>>2002</option>
                        <option value="2003"  <?php if ($_POST['year'] == 2003) { echo 'selected'; } ?>>2003</option>
                        <option value="2004"  <?php if ($_POST['year'] == 2004) { echo 'selected'; } ?>>2004</option>
                        <option value="2005"  <?php if ($_POST['year'] == 2005) { echo 'selected'; } ?>>2005</option>
                        <option value="2006"  <?php if ($_POST['year'] == 2006) { echo 'selected'; } ?>>2006</option>
                        <option value="2007"  <?php if ($_POST['year'] == 2007) { echo 'selected'; } ?>>2007</option>
                        <option value="2008"  <?php if ($_POST['year'] == 2008) { echo 'selected'; } ?>>2008</option>
                        <option value="2009"  <?php if ($_POST['year'] == 2009) { echo 'selected'; } ?>>2009</option>
                        <option value="2010"  <?php if ($_POST['year'] == 2010) { echo 'selected'; } ?>>2010</option>
                    </select>　年
                    <select name="month" required>
                        <option value=""   <?php if (empty($_POST['month'])) { echo 'selected'; } ?>>-</option>
                        <option value="1"  <?php if ($_POST['sex'] == 1) { echo 'selected'; } ?>>1</option>
                        <option value="2"  <?php if ($_POST['sex'] == 2) { echo 'selected'; } ?>>2</option>
                        <option value="3"  <?php if ($_POST['sex'] == 3) { echo 'selected'; } ?>>3</option>
                        <option value="4"  <?php if ($_POST['sex'] == 4) { echo 'selected'; } ?>>4</option>
                        <option value="5"  <?php if ($_POST['sex'] == 5) { echo 'selected'; } ?>>5</option>
                        <option value="6"  <?php if ($_POST['sex'] == 6) { echo 'selected'; } ?>>6</option>
                        <option value="7"  <?php if ($_POST['sex'] == 7) { echo 'selected'; } ?>>7</option>
                        <option value="8"  <?php if ($_POST['sex'] == 8) { echo 'selected'; } ?>>8</option>
                        <option value="9"  <?php if ($_POST['sex'] == 9) { echo 'selected'; } ?>>9</option>
                        <option value="10" <?php if ($_POST['sex'] == 10) { echo 'selected'; } ?>>10</option>
                        <option value="11" <?php if ($_POST['sex'] == 11) { echo 'selected'; } ?>>11</option>
                        <option value="12" <?php if ($_POST['sex'] == 12) { echo 'selected'; } ?>>12</option>
                    </select>　月
                    <select name="day" required>
                        <option value=""   <?php if (empty($_POST['day'])) { echo 'selected'; } ?>>-</option>
                        <option value="1"  <?php if ($_POST['day'] == 1) { echo 'selected'; } ?>>1</option>
                        <option value="2"  <?php if ($_POST['day'] == 2) { echo 'selected'; } ?>>2</option>
                        <option value="3"  <?php if ($_POST['day'] == 3) { echo 'selected'; } ?>>3</option>
                        <option value="4"  <?php if ($_POST['day'] == 4) { echo 'selected'; } ?>>4</option>
                        <option value="5"  <?php if ($_POST['day'] == 5) { echo 'selected'; } ?>>5</option>
                        <option value="6"  <?php if ($_POST['day'] == 6) { echo 'selected'; } ?>>6</option>
                        <option value="7"  <?php if ($_POST['day'] == 7) { echo 'selected'; } ?>>7</option>
                        <option value="8"  <?php if ($_POST['day'] == 8) { echo 'selected'; } ?>>8</option>
                        <option value="9"  <?php if ($_POST['day'] == 9) { echo 'selected'; } ?>>9</option>
                        <option value="10"  <?php if ($_POST['day'] == 10) { echo 'selected'; } ?>>10</option>
                        <option value="11"  <?php if ($_POST['day'] == 11) { echo 'selected'; } ?>>11</option>
                        <option value="12"  <?php if ($_POST['day'] == 12) { echo 'selected'; } ?>>12</option>
                        <option value="13"  <?php if ($_POST['day'] == 13) { echo 'selected'; } ?>>13</option>
                        <option value="14"  <?php if ($_POST['day'] == 14) { echo 'selected'; } ?>>14</option>
                        <option value="15"  <?php if ($_POST['day'] == 15) { echo 'selected'; } ?>>15</option>
                        <option value="16"  <?php if ($_POST['day'] == 16) { echo 'selected'; } ?>>16</option>
                        <option value="17"  <?php if ($_POST['day'] == 17) { echo 'selected'; } ?>>17</option>
                        <option value="18"  <?php if ($_POST['day'] == 18) { echo 'selected'; } ?>>18</option>
                        <option value="19"  <?php if ($_POST['day'] == 19) { echo 'selected'; } ?>>19</option>
                        <option value="20"  <?php if ($_POST['day'] == 20) { echo 'selected'; } ?>>20</option>
                        <option value="21"  <?php if ($_POST['day'] == 21) { echo 'selected'; } ?>>21</option>
                        <option value="22"  <?php if ($_POST['day'] == 22) { echo 'selected'; } ?>>22</option>
                        <option value="23"  <?php if ($_POST['day'] == 23) { echo 'selected'; } ?>>23</option>
                        <option value="24"  <?php if ($_POST['day'] == 24) { echo 'selected'; } ?>>24</option>
                        <option value="25"  <?php if ($_POST['day'] == 25) { echo 'selected'; } ?>>25</option>
                        <option value="26"  <?php if ($_POST['day'] == 26) { echo 'selected'; } ?>>26</option>
                        <option value="27"  <?php if ($_POST['day'] == 27) { echo 'selected'; } ?>>27</option>
                        <option value="28"  <?php if ($_POST['day'] == 28) { echo 'selected'; } ?>>28</option>
                        <option value="29"  <?php if ($_POST['day'] == 29) { echo 'selected'; } ?>>29</option>
                        <option value="30"  <?php if ($_POST['day'] == 30) { echo 'selected'; } ?>>30</option>
                        <option value="31"  <?php if ($_POST['day'] == 31) { echo 'selected'; } ?>>31</option>
                    </select>　日
                </div>
                <div class="form-group">
                    <label for="sex">性別</label>
                    <input type="radio" name="sex" value="0" <?php if (empty($_POST['sex'])) { echo 'checked'; } else { if($_POST['sex'] == 0) { echo 'checked'; }}; ?>>男
                    <input type="radio" name="sex" value="1" <?php if ($_POST['sex'] == 1) { echo 'checked'; } ?>>女
                </div>
                <div class="form-group">
                    <label for="post_number">郵便番号</label>
                    <input type="number" class="form-control" name="post_number" id="post_number" maxlength="7" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" value="<?php echo $_POST['post_number']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="address">住所</label>
                    <input type="address" class="form-control" name="address" id="address" placeholder="" value="<?php echo $_POST['address']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="tel">電話番号</label>
                    <input type="tel" class="form-control" name="tel" id="tel" maxlength="11" placeholder="" value="<?php echo $_POST['tel']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">メールアドレス（任意）</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="" value="<?php echo $_POST['email']; ?>">
                </div>
                <div class="form-group">
                    <label for="password">パスワード</label>
                    <input type="password" class="form-control" name="password" id="password" maxlength="8" placeholder="" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary bgc-main" name="confirm">確認する</button>
                    <a href="reservation.php">
                        <button class="btn btn-primary bgc-main" type="button">キャンセル</button>
                    </a>
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
            <p><small>&copy;2021 ヒューリスアニマルクリニック All rights reserved.</small></p>
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