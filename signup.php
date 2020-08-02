<?php

//共通変数・関数ファイルを読込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　ユーザー登録ページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//post送信されていた場合
if(!empty($_POST)){

  //変数にユーザー情報を代入
  $email = $_POST['email'];
  $pass = $_POST['pass'];
  $pass_re = $_POST['pass_re'];

  //未入力チェック
  validRequired($email, 'email');
  validRequired($pass, 'pass');
  validRequired($pass_re, 'pass_re');

  if(empty($err_msg)){

    //emailの形式チェック
    validEmail($email, 'email');
    validMaxLen($email, 'email');
    validEmailDup($email, 'email');

    if(empty($err_msg)){

      //例外処理
      try {
        // DBへ接続
        $dbh = dbConnect();
        // SQL文作成
        $sql = 'INSERT INTO users (email,password,login_time,create_date) VALUES(:email,:pass,:login_time,:create_date)';
        $data = array(
                ':email' => $email,
                ':pass' => password_hash($pass, PASSWORD_DEFAULT),
                ':login_time' => date('Y-m-d H:i:s'),
                ':create_data' => date('Y-m-d H:i:s')
              );
        // クエリ実行
        $stmt = queryPost($dbh, $sql, $data);

        // クエリ成功の場合
        if($stmt){
          //ログイン有効期限(デフォルトを1時間とする)
          $sesLimit = 60*60;
          // 最終ログイン日時を現在日時に
          $_SESSION['login_date'] = time();
          $_SESSION['login_limit'] = $sesLimit;
          // ユーザーIDを格納
          $_SESSION['user_id'] = $dbh->lastInsertId();

          debug('セッション変数の中身：'.print_r($_SESSION,true));

          header("Location:mypage.php"); //マイページへ
        }else{
          error_log('クエリに失敗しました。');
          $err_msg['common'] = MSG07;
        }
      } catch (Exception $e) {
        error_log('エラー発生：' . $e->getMessage());
        $err_msg['common'] = MSG07;
      }
    }
  }
}
?>

<!-- ヘッド読込み -->
<?php require('head.php'); ?>

<body class="index">

  <div class="app">

    <!-- ヘッダー 読込み -->
    <?php require('header.php');?>

    <!-- ユーザー登録 -->
    <div class="loginBox">
    <div class="registBox">
      <form method="post" class="form">
        <h2 class="title">会員登録</h2>
        <div class="area-msg">
          <?php
            if(!empty($err_msg['common'])) echo $err_msg['common'];
          ?>
        </div>
        <label class="<?php if(!empty($err_msg['email'])) echo 'err'; ?>">
          <input type="text" name="email" placeholder="Email" value="<?php if(!empty($_POST['email'])) echo $_POST['email']; ?>">
        </label>
        <div class="area-msg">
          <?php
            if(!empty($err_msg['email'])) echo $err_msg['email'];
           ?>
        </div>
        <label class="<?php if(!empty($err_msg['pass'])) echo 'err'; ?>">
          <input type="password" name="pass" placeholder="パスワード" value="<?php if(!empty($_POST['pass'])) echo $_POST['pass']; ?>">
        </label>
        <div class="area-msg">
          <?php
            if(!empty($err_msg['pass'])) echo $err_msg['pass'];
          ?>
        </div>
        <label class="<?php if(!empty($err_msg['pass_re'])) echo 'err'; ?>">
          <input type="password" name="pass_re" placeholder="パスワード(再入力)" value="<?php if(!empty($_POST['pass_re'])) echo $_POST['pass_re']; ?>">
        </label>
        <div class="btn-container">
          <input type="submit" class="btn btn-mid" value="マイページへ">
        </div>
      </form>

    </div>
  </div>

  <!-- フッター読込み -->
  <?php require('footer.php'); ?>

  </body>
</html>
