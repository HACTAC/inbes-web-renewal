<?php
// ファイルサイズ（MB）の計算
// 引数： String $filepath ファイルパス
// 返り値： String 
//         e.g.) (4.5MB) 
function inbes_filesize($filepath)
{
  if (file_exists($filepath)) {
    return "(" . number_format(filesize($filepath) / 1000000, 1) . "MB)";
  } else {
    return "";
  }
}
?>

<!DOCTYPE HTML>
<html lang="ja">

<head>
  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-5SQ5RBJXCT"></script>
  <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-5SQ5RBJXCT');
  </script>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>IDR-06R | 前後2カメラドライブレコーダー</title>
  <link rel="apple-touch-icon" sizes="180x180" href="https://inbes.jp/products/idr0203r/icons/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="https://inbes.jp/products/idr0203r/icons/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="https://inbes.jp/products/idr0203r/icons/favicon-16x16.png">
  <link rel="manifest" href="https://inbes.jp/products/idr0203r/icons/site.webmanifest">
  <link rel="mask-icon" href="https://inbes.jp/products/idr0203r/icons/safari-pinned-tab.svg" color="#000000">
  <link rel="shortcut icon" href="https://inbes.jp/products/idr0203r/icons/favicon.ico">
  <meta name="msapplication-TileColor" content="#000000">
  <meta name="msapplication-config" content="https://inbes.jp/products/idr0203r/icons/browserconfig.xml">
  <meta name="theme-color" content="#000000">
  <meta name="copyright" content="Copyright (C) INBES Co.,Ltd.">
  <meta name="Keywords" content="ドライブレコーダー,インベス,INBES">
  <meta name="ROBOTS" content="ドライブレコーダー,インベス,INBES">
  <meta name="Description" content="ミラーの裏にスッキリ収まるコンパクトな前後2カメラドライブレコーダー IDR-06R">
  <link href="/products/idr06r/css/idr06r.css?v=715cab8b728d" rel="stylesheet" type="text/css">
  <link href="css/idr06r_footer.css" rel="stylesheet" type="text/css">
  <link href="/assets/css/products/shared/fade-89772e9c1953.css" rel="stylesheet" type="text/css">
  <link href="../../css/g_navi.css" rel="stylesheet" type="text/css">
  <link href="/assets/css/products/shared/accordion-5b87886430fa.css" rel="stylesheet" type="text/css">
  <script type="text/javascript" src="../../js/jquery-3.7.0.min.js"></script>
  <script type="text/javascript" src="../../js/res_nav.js"></script>
  <script src="https://kit.fontawesome.com/628fbcc19c.js" crossorigin="anonymous"></script>
  <!-- Canonical設定 -->
  <link rel="canonical" href="https://inbes.jp/products/idr06r/">
  <!-- Canonical設定 -->
  <script>
    (function(d) {
      var config = {
          kitId: 'gtl0bzb',
          scriptTimeout: 3000,
          async: true
        },
        h = d.documentElement,
        t = setTimeout(function() {
          h.className = h.className.replace(/\bwf-loading\b/g, "") + " wf-inactive";
        }, config.scriptTimeout),
        tk = d.createElement("script"),
        f = false,
        s = d.getElementsByTagName("script")[0],
        a;
      h.className += " wf-loading";
      tk.src = 'https://use.typekit.net/' + config.kitId + '.js';
      tk.async = true;
      tk.onload = tk.onreadystatechange = function() {
        a = this.readyState;
        if (f || a && a != "complete" && a != "loaded") return;
        f = true;
        clearTimeout(t);
        try {
          Typekit.load(config)
        } catch (e) {}
      };
      s.parentNode.insertBefore(tk, s)
    })(document);
  </script>
  <script>
    $(() => {
      $('a[href^="#"]').click(function() {
        let speed = 500; // スクロールスピード(ミリ秒)
        let href = $(this).attr("href");
        let target = $(href == "#" || href == "" ? 'html' : href);
        let position = target.offset().top;
        $('body,html').animate({
          scrollTop: position
        }, speed, 'swing');
        return false;
      });
    });
  </script>
  <link rel="stylesheet" href="/assets/css/products/pages/products-idr06r-inline-1-f2566124caae.css">
</head>

<body>
  <header id="top">
    <div class="obi">
      <div class="head_waku"> <a href="#top"><img class="logo" src="img/logo.svg" alt="" /></a>
        <nav class="Nav" role="navigation" aria-label="メインメニュー">
          <p class="navbtn"><a href="javascript:void(0)"><span>メニューを開く</span></a></p>
          <ul>
            <li><a href="#point">特長</a></li>
            <li><a href="#rectime">録画時間</a></li>
            <li><a href="#specs">製品仕様</a></li>
            <li><a href="#option">オプション</a></li>
            <li><a href="#d_load">ダウンロード</a></li>
          </ul>
          <!-- /.Nav -->
        </nav>
      </div>
    </div>
    <h1>対角135°の広視野角<br>
      前後２カメラドライブレコーダー</h1>
    <div class="headvideo">
      <div class="youtube">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/yBVJv6HFKGU" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
      </div>
    </div>
    <div class="main_blk">
  </header>
  <article id="point">
    <section>
      <h2 class="fadein fadein-up">安心すればもっと楽しい<br>
        ドライブを見守るパートナー</h2>
      <div class="model">
        <figure class="fadein fadein-up"> <img src="img/idr06r.png" alt="" /> </figure>
        <div class="fadein fadein-up">
          <div class="p_name">
            <div>
              <h4>IDR-06R</h4>
              <p class="jancode">JAN 4580401390420</p>
            </div>
            <figure> <img src="./img/wideangle.svg" alt="" / style="border-radius: 0;"> </figure>
          </div>
          <h5>パッケージ内容</h5>
          <p>IDR-06R本体/本体取付ブラケット/リアカメラ（コード長約0.5m）/ リアカメラ用コード（約6.5m）/シガープラグコード（約3.5m）/取扱説明書・保証書</p>
        </div>
      </div>
    </section>
    <article class="image_bg_1">
      <div class="bg_filter">
        <section>
          <h2 class="fadein fadein-up">昼も夜も綺麗な映像</h2>
          <div class="eizo">
            <div class="fadein fadein-left"> <img src="./img/camera_1.png" alt="" />
              <h3>200万画素カメラ+フルHD録画</h3>
              <p>200万画素カメラの映像を1080p(1920x1080)で録画。走行中の映像を鮮明に記録することができます。※リアカメラはHD画質720p(1280x720)録画となります。</p>
            </div>
            <div class="fadein fadein-right"> <img src="./img/camera_2.png" alt="" />
              <h3>夜間でもキレイな映像</h3>
              <p>夜間や逆光、トンネルなどの明暗の差が大きい場所でも画像を補正しキレイな映像を映し出します。</p>
            </div>
          </div>
        </section>
      </div>
    </article>
    <article>
      <section>
        <h2 class="fadein fadein-up">エンジン始動で録画スタート、<br>
          重要な映像をしっかり記録。</h2>
        <div class="fanction">
          <div class="fanction_box fadein fadein-left"> <img src="img/rec.svg" alt="" />
            <h4>ループ録画</h4>
            <p>指定した時間(1/3/5分)に区切って録画データを保存。ストレージ容量が一杯の際は、上書きして最新データを残します。</p>
          </div>
          <div class="fanction_box fadein fadein-right"> <img src="img/gsensor.svg" alt="" />
            <h4>データ保護機能(Gセンサ)</h4>
            <p>内蔵のGセンサが衝撃を検知した際、データが上書きされないよう自動でファイルを保護します。</p>
          </div>
          <div class="fanction_box fadein fadein-left"> <img src="img/spc.svg" alt="" />
            <h4>スーパーキャパシタ搭載</h4>
            <p>耐衝突で突然電源が失われた際の非常電源として作動。ファイル破損を防ぎ、正常に保存完了させます。</p>
          </div>
          <div class="fanction_box fadein fadein-right"> <img src="img/noise.png" alt="" />
            <h4>他機器へのノイズ低減</h4>
            <p>地デジ・ナビ等の他の機器への悪影響を低減するEMIノイズ対策を行なっています。</p>
          </div>
          <div class="fanction_box fadein fadein-left"> <img src="img/led.jpg" alt="" />
            <h4>LED信号撮影</h4>
            <p>国内のLED信号に対応したフレームレートで表示(録画)します。
              ※LED信号撮影時は点滅表示となります。</p>
          </div>
          <div class="fanction_box fadein fadein-left"> <img src="img/bc_12v24v.svg" alt="" />
            <h4>12V/24V車対応</h4>
            <p>12V普通車はもちろん、24Vのトラックなどでもお使いいただけます。</p>
          </div>
        </div>
      </section>
    </article>
    <article>
      <div class="fix_compact fadein fadein-down">
        <div>
          <h2 class="fadein fadein-left">ミラーの裏に<br>
            スッキリと収まる<br>
            コンパクト設計</h2>
          <figure class="fadein fadein-right"> <img src="img/fix_compact.jpg" alt="" /> </figure>
        </div>
      </div>
      <section class="fix_method fadein fadein-up">
        <div>
          <h3>幅広い車種に取付可能</h3>
          <p>カメラレンズを上下に動かせるアングル調整機構を搭載し、より柔軟な設置に対応する自在ブラケットも付属。フロントガラスの角度を問わず、ほとんどの車種に取り付けが可能です。</p>
        </div>
        <figure> <img src="./img/at_2.png" alt="" />
          <figcaption>アングル調整(上下)</figcaption>
        </figure>
        <figure> <img src="./img/at_1.png" alt="" />
          <figcaption>自在ブラケット</figcaption>
        </figure>
      </section>
    </article>
    <article>
      <section id="rectime" class="spec_section">
        <h2 class="fadein fadein-up">録画時間目安</h2>
        <p class="pc_hide">※表は横にスクロールできます</p>
        <table class="recseat fadein fadein-up">
          <tr>
            <th colspan="2">録画解像度</th>
            <th>前方 FHD<br class="br_sp">
              (1920x1080)<br>
              後方 HD<br class="br_sp">
              (1280x720)</th>
            <th>前方 HD<br class="br_sp">
              (1280x720)<br>
              後方 HD<br class="br_sp">
              (1280x720)</th>
          </tr>
          <tr>
            <th rowspan="5">microSD</th>
            <th>8GB</th>
            <td>35分</td>
            <td>40分</td>
          </tr>
          <tr>
            <th>32GB</th>
            <td>2時間20分</td>
            <td>2時間45分</td>
          </tr>
          <tr>
            <th>64GB</th>
            <td>4時間45分</td>
            <td>5時間30分</td>
          </tr>
          <tr>
            <th>128GB</th>
            <td>9時間30分</td>
            <td>11時間</td>
          </tr>
        </table>
        <p class="fadein fadein-up">microSDカードはClass10以上8~128GBのものをご利用ください。<br>
          推奨仕様：SLCまたはMLC NAND、「高耐久」「ドライブレコーダー用」の記載があるもの。</p>
      </section>
      <section id="specs" class="spec_section">
        <h2 class="fadein fadein-up">製品仕様</h2>
        <table class="specseat fadein fadein-up">
          <tr>
            <th>型番</th>
            <td>IDR-06R</td>
            <td>IDR-06R専用リアカメラ</td>
          </tr>
          <tr>
            <th>撮像素子</th>
            <td clospan="2">200万画素</td>
          </tr>
          <tr>
            <th>視野角(対角)</th>
            <td colspan="2">対角 約135°/水平 約110° / 垂直 約60° </td>
          </tr>
          <tr>
            <th>液晶サイズ</th>
            <td>2.8型 IPSスクリーン</td>
            <td>ー</td>
          </tr>
          <tr>
            <th>記録媒体</th>
            <td>micro SD カード (8〜32GB)<br>
              Class10以上</td>
            <td>ー</td>
          </tr>
          <tr>
            <th>ファイル形式</th>
            <td colspan="2"> .MOV(H.264) </td>
          </tr>
          <tr>
            <th>内蔵バッテリー</th>
            <td colspan="2">スーパーキャパシタ</td>
          </tr>
          <tr>
            <th>録画解像度</th>
            <td>Full FHD(1920x1080) 27.5fps<br>
              HD(1280x720) 27.5fps</td>
            <td>HD(1280x720) 25fps</td>
          </tr>
          <tr>
            <th>上書き録画</th>
            <td>〇</td>
            <td rowspan="3">前方カメラの設定に準じる</td>
          </tr>
          <tr>
            <th>付加情報記録</th>
            <td>タイムスタンプ</td>
          </tr>
          <tr>
            <th>Gセンサ</th>
            <td>〇</td>
          </tr>
          <tr>
            <th>電源</th>
            <td>専用シガープラグ(DC5V)</td>
            <td>IDR-06R 本体から供給</td>
          </tr>
          <tr>
            <th>本体サイズ</th>
            <td>約89.5(幅)×41(奥行)×50(高)mm</td>
            <td>約31(幅)×40.5(奥行)×27(高)mm</td>
          </tr>
          <tr>
            <th>本体重量</th>
            <td>約82g</td>
            <td>約40g</td>
          </tr>
          <tr>
            <th>動作環境温度</th>
            <td colspan="2">-10〜60℃</td>
          </tr>
          <tr>
            <th>配線コード長</th>
            <td>シガープラグコード：約3.5m</td>
            <td>リアカメラ用コード：約7.5m(カメラ側 約0.5m+コード側約7m)</td>
          </tr>
        </table>
      </section>
      <article id="option" class="image_bg_1 bg">
        <div class="bg_filter">
          <section>
            <h2 class="fadein fadein-up">別売オプション</h2>
            <div class="option">
              <figure class="fadein fadein-left"> <img src="./img/ioppc02.png" alt="" /> </figure>
              <div class="fadein fadein-right">
                <h3>直接配線コード<small>(駐車監視対応)</small></h3>
                <h4>IOP-PC02</h4>
                <p class="jancode">JAN 4580401390468</p>
                <p class="acc_text">電源直結方式にて配線する場合に使用します。駐車監視機能を使用する場合も必要になります。</p>
                <p class="acc_dl_text"><a href="dl/IOP-PC02_manual.pdf" download=""><i class="fa-solid fa-file-pdf"></i>取扱説明書 (3.4MB)</a></p>
              </div>
            </div>
          </section>
        </div>
      </article>
      <?php
      $catalog_path = "dl/idr06r_leaflet.pdf";
      $manual_path = "dl/IDR-06R_manual_20230329.pdf";
      $photo_path = "dl/idr06r_materials.zip";
      ?>
      <section id="d_load" class="d_load">
        <h2 class="fadein fadein-up">ダウンロード</h2>
        <ul class="fadein fadein-up">
          <a href="<?php echo $catalog_path ?>" download>
            <li><img src="../../img/dl_icon/pdf.svg" alt="" />製品カタログ<br>
              <?php echo inbes_filesize($catalog_path); ?></li>
          </a> <a href="<?php echo $manual_path ?>" download>
            <li><img src="../../img/dl_icon/pdf.svg" alt="" />取扱説明書<br>
              <?php echo inbes_filesize($manual_path); ?></li>
          </a> <a href="<?php echo $photo_path ?>">
            <li><img src="../../img/dl_icon/zip.svg" alt="" />写真データ<br>
              <?php echo inbes_filesize($photo_path); ?></li>
          </a>
        </ul>
      </section>
    </article>
    <footer>
      <div>
        <p> </p>
        <dl>
          <dt>お買い上げの前に必ずお読みください<small>（免責事項・注意事項）</small></dt>
          <dd>本製品をご使用になる前に取扱説明書をよくお読みになり、内容をご理解の上ご利用ください。</dd>
          <dd>運転中は本機を操作しないで下さい。</dd>
          <dd>記録した映像は、事件、事故、裁判の際に必ずの証拠となるものではありません。また、使用目的や方法によって被写体のプライバシー、著作権、肖像権などの権利を侵害する可能性がありますので記録データの取り扱いには十分ご注意ください。 </dd>
          <dd>本製品は車載(屋内)専用品です。それ以外の用途では使用しないでください。</dd>
          <dd>本製品の内蔵バッテリーは「リチウムイオン電池」です。本製品を処分される際はお住まいの自治体の処分方法に則って適切に処分してください。</dd>
          <dd>内蔵バッテリーは消耗品ですが交換することはできません。充電しても機能が回復しない場合は製品自体の寿命です。寿命は使用状態などによって異なりますが概ね2〜3年程度を想定しております。</dd>
          <dd>保証規定は取扱説明書または保証書に記載されている内容に準じます。事前に確認されたい場合は当WEBサイト(https://inbes.jp)よりご確認、ダウンロードして頂けます。</dd>
          <dd>製品の外観・製品の外観、仕様、操作画面および価格等は予告無く変更される場合がございます。</dd>
        </dl>
      </div>
      <nav class="foot_nav">
        <p><a href="#point">特長</a></p>
        <p><a href="#rectime">録画時間</a></p>
        <p><a href="#specs">製品仕様</a></p>
        <p><a href="#option">オプション</a></p>
        <p><a href="#d_load">ダウンロード</a></p>
      </nav>
      <address>
        <a href="../../" target="_blank"><img src="../../img/inbes_logo.svg" alt="" />株式会社INBES(インベス)<br>
          〒206-0012 東京都多摩市貝取1510-1 永山JOYビル5F</a>
      </address>
      <p class="copylight">©2023 INBES Co.,Ltd.</p>
    </footer>

    <script type="module">
      // fadein.js
      // フェードイン Jクエリ
      $(window).scroll(function() {
        $('.fadein').each(function() {
          var elemPos = $(this).offset().top,
            scroll = $(window).scrollTop(),
            windowHeight = $(window).height();

          if (scroll > elemPos - windowHeight + 150) {
            $(this).addClass('scrollin');
          }
        });
      });
    </script>
</body>

</html>

