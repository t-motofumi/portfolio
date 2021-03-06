<?php

  error_reporting(E_ALL); //E_STRICレベル以外のエラーを報告する
  ini_set('display_errors','On'); //画面にエラーを表示させるか

  //1.POST送信されていた場合
  if(!empty($_POST)){


    //エラーメッセージを定数に設定
    define('MSG01','入力必須です');
    define('MSG02','Emailの形式で入力してください');
    define('MSG03','パスワード（再入力）が合っていません');
    define('MSG04','半角英数字のみご利用いただけます');
    define('MSG05','6文字以上で入力してください');

    //配列$err_msgを用意
    $err_msg = array();

    if(empty($_POST['email'])){
      $err_msg['email'] = MSG01;
    }
    if(empty($_POST['pass'])){
      $err_msg['pass'] = MSG01;
    }
    if(empty($_POST['pass_re'])){
      $err_msg['pass_re'] = MSG01;
    }


    if(empty($err_msg)){

      //変数にユーザー情報を代入
      $email = $_POST['email'];
      $pass = $_POST['pass'];
      $pass_re = $_POST['pass_re'];

      //3.email形式でない場合
      if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9¥._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9¥._-]+)+$/", $email)){
        $err_msg['email'] = MSG02;
      }

      //4.パスワードとパスワード再入力が合ってない場合
      if($pass !== $pass_re){
        $err_msg['pass'] = MSG03;
      }

      if(empty($err_msg)){

        //5.パスワードとパスワード再入力が半角英数字でない場合
        if(!preg_match("/^[a-zA-Z0-9]+$/", $pass)){
          $err_msg['pass'] = MSG04;
        }else if(mb_strlen($pass) < 6){
        //6.パスワードとパスワード再入力が6文字以上でない場合
          $err_msg['pass'] = MSG05;
        }

        if(empty($err_msg)){

          //DBへの接続準備
          $dsn = 'mysql:dbname=toresuku;host=localhost;charset=utf8';
          $user = 'root';
          $password = 'root';
          $options = array(
                  // SQL実行失敗時に例外をスロー
                  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                  // デフォルトフェッチモードを連想配列形式に設定
                  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                  // バッファードクエリを使う(一度に結果セットを全て取得し、サーバー負荷を軽減)
                  // SELECTで得た結果に対してもrowCountメソッドを使えるようにする
                  PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
              );
          // PDOオブジェクト生成（DBへ接続）
          $dbh = new PDO($dsn, $user, $password, $options);

          //SQL文(クエリー作成)
          $stmt = $dbh->prepare('INSERT INTO users (email, pass, login_time) VALUES (:email,:pass,:login_time)');

          //プレースホルダーに値をセットし、SQL文を実行
          $stmt->execute(array(':email' => $email, ':pass' => $pass, ':login_time' => date('Y-m-d H:i:s')));


          $alert = "<script type='text/javascript'>alert('会員登録完了しました。');</script>";
          echo $alert;

          //header("Location:mypage.php"); //マイページへ
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
    <?php require('header.php'); ?>
    <!-- トップバナー -->
    <img src="jpg/top-baner.jpg" id="top-baner">

    <!-- メインコンテンツ -->
    <main class="main">

    <!-- ABOUT -->
    <section class="about">
      <div class="site-width">
        <h2 class="title">TORE-SUKUとは？</h2>
          <p class="headerText">必要な時に必要なだけ借りることができる</br>筋トレ器具の
            <span class="marker">サブスクリプションサービスです</span>
          </p>

          <ul>
            <li>
              <div class="img-container">
                <img alt="ジムに行くよりも安く始められる" src="png/money.png">
              </div>
                <h3>ジムに行くよりも<br>
                  <span class="marker">安くはじめられる</span></br>
                </h3>
                <p>月々500円から利用できるので、ジムに通う費用を抑えられる</p>
            </li>
            <li>
              <div class="img-container">
                <img alt="いつでも交換できる" src="png/exchange1.png">
              </div>
                <h3>いつでも
                  <span class="marker">交換できる</span></br>
                </h3>
                <p>筋力がつくにつれ負荷を増やしたり、別のトレーニングがしたい時にいつでも交換可能</p>
            </li>
            <li>
              <div class="img-container">
                <img alt="汚れや傷がついても大丈夫" src="png/break.png">
              </div>
                <h3>汚れや傷がついても
                  <span class="marker">大丈夫</span></br>
                </h3>
                <p>不注意によって汚れや傷がついても、追加料金不要。安心してトレーニングに集中できます</p>
            </li>
            <li>
              <div class="img-container">
                <img alt="注文してから返却までスマホひとつ" src="png/tell.png">
              </div>
                <h3>注文してから返却まで<br>
                  <span class="marker">スマホひとつ</span></br>
                </h3>
                <p>商品と配送希望日を選ぶだけ。返品・交換もスマホでOK！</p>
            </li>
          </ul>
        </div>
      </section>

      <!-- 商品一覧 -->
      <section class="product-list site-width">
        <h2 class="title">商品ランキング</h2>
          <ul class="product-list__body">
            <li class="product-list-item">
                <a href="index.php" class="product-list-item__body unlink">
                  <div data-v-2e232051 class="badge">
                    <span data-v-2e232051 class="triangle Ranking"></span>
                    <span data-v-2e232051 class="label">No.1</span>
                  </div>
                    <div class="product-list-item__img">
                      <div class="img-container">
                          <img src="https://thumbnail.image.rakuten.co.jp/@0_mall/bodymaker/cabinet/h/hnmdst250_01.jpg" width="100%" height="280px">
                      </div>
                    </div>
                    <div class="product-list-item__detail">
                      <h2 class="product-list-item__name">ハンマートーンダンベルセット５０ｋｇ</h2>
                      <h3 class="product-list-item__brand">セット内容：1.25kg×4／2.5kg×8／5kg×4<br>※セットの5kgプレートは穴あきタイプとなります。<br>※スクリューシャフト2.5kg(40cm)×2本（カラー付）<br>※シャフト穴：φ29mm</h3>
                      <div class="product-list-item__price">
                        <span>500</span>円/月
                      </div>
                    </div>

                </a>
            </li>
            <li class="product-list-item">
                <a href="" class="product-list-item__body unlink">
                  <div data-v-2e232051 class="badge">
                    <span data-v-2e232051 class="triangle Ranking"></span>
                    <span data-v-2e232051 class="label">No.2</span>
                  </div>
                    <div class="product-list-item__img">
                      <div class="img-container">
                          <img src="https://shop.r10s.jp/wildfit/cabinet/slpl/pl9021.jpg" width="100%" height="100%">
                      </div>
                    </div>
                    <div class="product-list-item__detail">
                      <h2 class="product-list-item__name">マルチプレス（250ポンド）</h2>
                      <h3 class="product-list-item__brand">サイズ  L：1924mm X W：1325mm X H：1518mm<br>総重量：約111.5kg</h3>
                        <div class="product-list-item__price">
                          <span>2000</span>円/月
                        </div>
                    </div>
                </a>
            </li>
            <li class="product-list-item">
                <a href="" class="product-list-item__body unlink">
                  <div data-v-2e232051 class="badge">
                    <span data-v-2e232051 class="triangle Ranking"></span>
                    <span data-v-2e232051 class="label">No.3</span>
                  </div>
                    <div class="product-list-item__img">
                      <div class="img-container">
                          <img src="https://shop.r10s.jp/super-sports/cabinet/img57191560.jpg" width="100%" height="100%">
                      </div>
                    </div>
                    <div class="product-list-item__detail">
                      <h2 class="product-list-item__name">IROTEC（アイロテック）マスキュラーセットR140</h2>
                      <h3 class="product-list-item__brand">プレス・スクワットサポートとセイフティーバーは5cm刻みで調整でき、ベンチポジションはフラット・インクライン・デクラインまでの7段階調整が可能<br>ラックサイズ：W116×D145×H219cm<br>ラック重量：82kg</h3>
                      <div class="product-list-item__price">
                        <span>2000</span>円/月
                      </div>
                    </div>

                </a>
            </li>
            <li class="product-list-item">
                <a href="" class="product-list-item__body unlink">
                  <div data-v-2e232051 class="badge">
                    <span data-v-2e232051 class="triangle Ranking"></span>
                    <span data-v-2e232051 class="label">No.4</span>
                  </div>
                    <div class="product-list-item__img">
                      <div class="img-container">
                          <img src="https://shop.r10s.jp/wildfit/cabinet/img56306706.jpg" alt="" width="100%" height="100%">
                      </div>
                    </div>
                    <div class="product-list-item__detail">
                      <h2 class="product-list-item__name">アップライトロー【impulse/インパルス】</h2>
                      <h3 class="product-list-item__brand">サイズ<br>D：1761mm×W：1457mm×H：1652m<br>総重量：約167.5kg<br>耐荷重：約300kg</h3>
                        <div class="product-list-item__price">
                          <span>1500</span>円/月
                        </div>
                    </div>

                </a>
            </li>
            <li class="product-list-item">
                <a href="" class="product-list-item__body unlink">
                  <div data-v-2e232051 class="badge">
                    <span data-v-2e232051 class="triangle Ranking"></span>
                    <span data-v-2e232051 class="label">No.5</span>
                  </div>
                    <div class="product-list-item__img">
                      <div class="img-container">
                          <img src="https://shop.r10s.jp/body-d/cabinet/training/d-008.jpg" width="100%" height="280px">
                      </div>
                    </div>
                    <div class="product-list-item__detail">
                      <h2 class="product-list-item__name">懸垂トレーニングのハイエンドモデル</h2>
                      <h3 class="product-list-item__brand">懸垂・腕立て伏せなどのトレーニングが可能。身長に合わせ高さ調節も5段階でき、安定性・耐久性・機能性もバツグン！<br>全体：W64×D70×H196〜216 cm（高さ調節可）/ 5cm間隔5段階<br>重量：20kg<br>耐荷重量：90kg</h3>
                      <div class="product-list-item__price">
                        <span>2000</span>円/月
                      </div>
                    </div>

                </a>
            </li>
            <li class="product-list-item">
                <a href="" class="product-list-item__body unlink">
                  <div data-v-2e232051 class="badge">
                    <span data-v-2e232051 class="triangle Ranking"></span>
                    <span data-v-2e232051 class="label">No.6</span>
                  </div>
                    <div class="product-list-item__img">
                      <div class="img-container">
                          <img src="https://shop.r10s.jp/wildfit/cabinet/kakutougi/ybace_red.jpg" width="100%" height="280px">
                      </div>
                    </div>
                    <div class="product-list-item__detail">
                      <h2 class="product-list-item__name">Yベースサンドバッグセット Φ40X100cm 赤</h2>
                      <h3 class="product-list-item__brand">サンドバッグは、しっかりと詰めていますので、長期間使用しても、形が変形しません。（写真のプレートは別売です。）<br>サイズ  Φ40×100cm</h3>
                        <div class="product-list-item__price">
                          <span>2000</span>円/月
                        </div>
                    </div>

                </a>
            </li>
            <li class="product-list-item">
                <a href="" class="product-list-item__body unlink">
                  <div data-v-2e232051 class="badge">
                    <span data-v-2e232051 class="triangle Ranking"></span>
                    <span data-v-2e232051 class="label">No.7</span>
                  </div>
                    <div class="product-list-item__img">
                      <div class="img-container">
                          <img src="https://shop.r10s.jp/wildfit/cabinet/it95/sit9522.jpg" width="100%" height="100%">
                      </div>
                    </div>
                    <div class="product-list-item__detail">
                      <h2 class="product-list-item__name">ラットプルダウンバーティカルロー（200ポンド）</h2>
                      <h3 class="product-list-item__brand">サイズ<br>L：1900mm X W：900mm X H：2230mm<br>総重量：約119.5kg</h3>
                      <div class="product-list-item__price">
                        <span>2000</span>円/月
                      </div>
                    </div>

                </a>
            </li>
            <li class="product-list-item">
                <a href="" class="product-list-item__body unlink">
                  <div data-v-2e232051 class="badge">
                    <span data-v-2e232051 class="triangle Ranking"></span>
                    <span data-v-2e232051 class="label">No.8</span>
                  </div>
                    <div class="product-list-item__img">
                      <div class="img-container">
                          <img src="https://shop.r10s.jp/look-it/cabinet/03810234/40-dk-b50.jpg" width="100%" height="280px">
                      </div>
                    </div>
                    <div class="product-list-item__detail">
                      <h2 class="product-list-item__name">家庭用アップライトバイク DK-B50</h2>
                      <h3 class="product-list-item__brand">本体サイズ(cm)<br>W53 × L110 × H140<br>本体重量(kg)：34<br>負荷方式：マグネット式負荷(手動式8段会)<br>使用電源：単四乾電池2本(マンガン推奨)<br>連続使用時間：60分</h3>
                        <div class="product-list-item__price">
                          <span>2000</span>円/月
                        </div>
                    </div>

                </a>
            </li>
          </ul>
        </section>

        <!-- 人気商品一覧 -->
        <section class="product-list site-width">
          <h2 class="title">新着商品</h2>

            <ul class="product-list__body">

              <li class="product-list-item">
                  <a href="" class="product-list-item__body unlink">
                    <div data-v-2e232051 class="badge">
                      <span data-v-2e232051 class="triangle New"></span>
                      <span data-v-2e232051 class="label">New</span>
                    </div>
                      <div class="product-list-item__img">
                        <div class="img-container">
                            <img src="https://shop.r10s.jp/mckey/cabinet/blue10/5866assg181006-300a.jpg" width="100%" height="280px">
                        </div>
                      </div>
                      <div class="product-list-item__detail">
                        <h2 class="product-list-item__name">バーンマシン スピードバッグ</h2>
                        <h3 class="product-list-item__brand">両腕でボクシングのスピードバッグを打つように回すトレーニング器具。重さ5.5キロ<br>バーンマシンは、360度グリップの回転が可能で、コアストレングス強化にも最適です。ひねりのある動きに加え加重し、普段ダンベル運動だけでは体感できないトレーニングが可能。</h3>
                      <div class="product-list-item__price">
                        <span>500</span>円/月
                      </div>
                    </div>
                  </a>
              </li>
              <li class="product-list-item">
                  <a href="" class="product-list-item__body unlink">
                    <div data-v-2e232051 class="badge">
                      <span data-v-2e232051 class="triangle New"></span>
                      <span data-v-2e232051 class="label">New</span>
                    </div>
                      <div class="product-list-item__img">
                        <div class="img-container">
                            <img src="https://image.rakuten.co.jp/padddlebox/cabinet/compass1586414359.jpg" width="100%" height="280px">
                        </div>
                      </div>
                      <div class="product-list-item__detail">
                        <h2 class="product-list-item__name">フィットネスバイク ミニスピン</h2>
                        <h3 class="product-list-item__brand">ミニフィットネスバイクはハンドルフリーであるため、運動中の上半身の揺れが体幹部（コア）を刺激するしくみです。<br>更に、コードレス設計と、高い静音性も備えており、ご家庭でのご使用にピッタリ
                        <br>サイズ：幅34cm×奥行37.8cm×高さ34cm<br>重量：約6kg</h3>
                          <div class="product-list-item__price">
                            <span>700</span>円/月
                          </div>
                      </div>

                  </a>
              </li>
              <li class="product-list-item">
                  <a href="" class="product-list-item__body unlink">
                    <div data-v-2e232051 class="badge">
                      <span data-v-2e232051 class="triangle New"></span>
                      <span data-v-2e232051 class="label">New</span>
                    </div>
                      <div class="product-list-item__img">
                        <div class="img-container">
                            <img src="https://shop.r10s.jp/wildfit/cabinet/02540673/sif-latm.jpg" width="100%" height="280px">
                        </div>
                      </div>
                      <div class="product-list-item__detail">
                        <h2 class="product-list-item__name">ラットマシン</h2>
                        <h3 class="product-list-item__brand">上半身のトレーニングには欠かせない、逆三角ボディを!!<br>組み立てサイズ(最大)：<br>
                          L：1452mm X W：1224mm X H：2129mm<br>総重量：約55.5kg</h3>
                          <div class="product-list-item__price">
                            <span>1000</span>円/月
                          </div>
                      </div>

                  </a>
              </li>
              <li class="product-list-item">
                  <a href="" class="product-list-item__body unlink">
                    <div data-v-2e232051 class="badge">
                      <span data-v-2e232051 class="triangle New"></span>
                      <span data-v-2e232051 class="label">New</span>
                    </div>
                      <div class="product-list-item__img">
                        <div class="img-container">
                            <img src="https://shop.r10s.jp/wildfit/cabinet/02540673/if-fid.jpg" width="100%" height="280px">
                        </div>
                      </div>
                      <div class="product-list-item__detail">
                        <h2 class="product-list-item__name">マルチパーパスベンチ
                        </h2>
                        <h3 class="product-list-item__brand">バリエーションを求めるならこのベンチ!! デクライン、フラット、インクラインが可能！<br>サイズ<br>L：1581mm X W：654mm X H：515mm<br>総重量：約33kg
                        </h3>
                          <div class="product-list-item__price">
                            <span>2980</span>円/月
                          </div>
                      </div>

                  </a>
              </li>

            </ul>
          </section>

          <!-- ニュース・お知らせ -->
          <section class="newsArea">
              <h2 class="title">お知らせ</h2>
                <dl>
                  <dt>2019年 12月10日</dt>
                  <dd>
                      <a href="index.php">現在入会金無料キャンペーン実施中</a>
                  </dd>
                  <dt>2019年 12月20日</dt>
                  <dd>
                      <a href="index.php">商品交換手数料無料</a>
                  </dd>
                  <dt>2019年 12月23日</dt>
                  <dd>
                      <a href="index.php">年末年始休暇のお知らせ</a>
                  </dd>
                  <dt>2019年 12月10日</dt>
                  <dd>
                      <a href="index.php">現在入会金無料キャンペーン実施中</a>
                  </dd>
                  <dt>2019年 12月20日</dt>
                  <dd>
                      <a href="index.php">商品交換手数料無料</a>
                  </dd>
                  <dt>2019年 12月23日</dt>
                  <dd>
                      <a href="index.php">年末年始休暇のお知らせ</a>
                  </dd>
                </dl>
          </section>
          <!-- Q＆A -->
          <section class="faqArea">
            <div class="site-width">
                <h2 class="title">よくあるご質問</h2>

                <dl class="faq">
                    <dt>申込方法を教えてください</dt>
                    <dd>
                      下記の３ステップでお申し込みいただけます。<br>
                      1. 画面右上のログインボタンを押してマイページを開いてください。<br>
                      2. 検索ボタンで希望の商品を選択して、カートに入れて申込ボタンを押してください。<br>
                      3. 最終確認画面で内容を確認し、注文確定ボタンを押したら申込完了になります<br>
                      <br>
                      <span>※初めての方はまず会員登録してください</span>
                    </dd>
                </dl>
                <dl class="faq">
                    <dt>レンタル期間について知りたいです</dt>
                    <dd>
                      ３ヶ月からお好きな期間でご利用いただけます<br>
                      ご注文の際に特に期間を指定していただく必要はなく、毎月自動で更新が行われます。<br>
                    </dd>
                </dl>
                <dl class="faq">
                    <dt>注文から商品が届くまでの期間は？</dt>
                    <dd>
                      商品とお住まいの地域によりますが、最短７日でお届けが可能です。<br>
                    </dd>
                </dl>
                <dl class="faq">
                    <dt>レンタルする商品は新品ですか？</dt>
                    <dd>
                      シェアリングサービスの性質上、セカンドハンド品をお届けする可能性がございます。<br>
                      <br>
                      <span>※もちろん商品には消毒消臭をして、安全を確認した上でお届けいたします。</span>
                    </dd>
                </dl>
                <dl class="faq">
                    <dt>商品の交換は可能ですか？</dt>
                    <dd>
                      はい。可能です。support@kinniku-box.comに、決済日の７日前までにご連絡くだい。<br>
                      <br>
                      (例)500円のダンベルを25日締めでレンタルしていた場合<br>
                      ・3月18日に500円デスク→5000円のベンチプレスへ交換を希望<br>
                      ・3月25日に交換品(5000円のベンチプレスが届いた場合)は、<br>
                      500円の課金は3月25日分まで<br>
                      5000円商品の課金は3月26日より開始<br>
                      <br>
                      なお、交換前のご利用期間によって手数料が発生いたします。
                    </dd>
                </dl>
                <a href="index.php" class="button ghost">その他</a>
            </div>
          </section>

          <!-- 利用方法 -->
          <section class="useArea">
            <div class="site-width">

              <div class="useareaIntro">
                <h2 class="title">利用方法</h2>
                <p>注文してから返却まで<br><span class="marker">全てが簡単</span></p>
              </div>
                  <ul>
                      <li>
                        <div class="img-container">
                          <img alt="ネットで注文" src="jpg/tyumon.jpg">
                        </div>
                          <div class="useAreaText">
                            <h3>ネットで注文</h3>
                            <p>スマホやパソコンから、商品と配送希望日を選んで注文。</p>
                          </div>
                      </li>
                      <li>
                        <div class="img-container">
                          <img alt="配送・設置" src="jpg/take.jpg">
                        </div>
                          <div class="useAreaText">
                            <h3>配送・設置</h3>
                            <p>専門業者がご自宅までお届け。商品の搬入は全ておまかせ</p>
                          </div>
                      </li>
                      <li>
                          <div class="img-container">
                            <img alt="ご利用" src="jpg/use.jpg">
                          </div>
                          <div class="useAreaText">
                            <h3>ご利用</h3>
                            <p>購入した商品と同じように普段の生活にご利用いただけます</p>
                          </div>
                      </li>
                      <li>
                          <div class="img-container">
                            <img alt="交換・返送" src="jpg/take-out.jpg">
                          </div>
                          <div class="useAreaText">
                            <h3>交換・返送</h3>
                            <p>交換や返却の際も、搬送は専門業者におまかせ</p>
                          </div>
                      </li>
                  </ul>
              </div>


          </section>
        </main>




          <!-- フッター読込み -->
          <?php require('footer.php'); ?>

      </div>
      <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous">
      </script>

      <script>
        // JQueryでヘッダー固定
        $(function() {
          // jQueryは変数に入れてキャッシュ化すると速くなる
          var $win = $(window),
              $app = $('app'),
              $header = $('.header'),
              $headerLogo = $('.header-logo'),
              $loginButton = $('.header-button'),
              $nav = $('.top-nav'),
              $loginBtn = $('.header-button__body'),
              $main = $('.main'),
              $topBaner = $('#top-baner'),
            // 各要素の高さを変数に代入
              headerHeight = $header.outerHeight(),
              useareaHeight = $('.useArea').outerHeight(),
              documentHeight = $(document).height(),
              banerPos = $topBaner.offset().top,
              イベント発火時に
              fixedClass = 'is-fixed',
              hideClass = 'is-hide',
              animationClass = 'is-animation';

        // スクロールした高さと、画面の高さ＋スクロールした高さを合わせてscrollPosに格納
        $win.on('load scroll', function() {
          var value = $(this).scrollTop(),
              scrollPos = $win.height() + value;

            // スクロールした量が、トップバナー画像の高さを超えたら、固定
              if ( value > banerPos ) {
                $([$header[0],$headerLogo[0],$loginButton[0],$nav[0]]).addClass(fixedClass);

                $app.css('margin-top', headerHeight);
              } else {
                $([$header[0],$headerLogo[0],$loginButton[0],$nav[0]]).removeClass(fixedClass);
                $app.css('margin-top', '0');
              }

            // 固定したヘッダーがフッターまでスクロールすると消える
              if( documentHeight - scrollPos <= useareaHeight){
                  $header.addClass(hideClass);
                  $header.removeClass(fixedClass);
              } else {
                  $header.removeClass(hideClass);
              }
            });
          });



      </script>



  </body>
</html>
