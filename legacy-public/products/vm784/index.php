<?php
// ファイルサイズ（MB）の計算
// 返り値： String
function inbes_filesize( $filepath ) {
  if ( file_exists( $filepath ) ) {
    return "(" . number_format( filesize( $filepath ) / 1000000, 1 ) . "MB)";
  } else {
    return "";
  }
}
?>
<!DOCTYPE html>
<!-- saved from url=(0034)https://inbes.jp/products/idr04rs/ -->
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- favicon -->
<link rel="apple-touch-icon" sizes="180x180" href="https://inbes.jp/products/idr04rs/icons/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="https://inbes.jp/products/idr04rs/icons/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="https://inbes.jp/products/idr04rs/icons/favicon-16x16.png">
<link rel="manifest" href="https://inbes.jp/products/idr04rs/icons/site.webmanifest">
<link rel="mask-icon" href="https://inbes.jp/products/idr04rs/icons/safari-pinned-tab.svg" color="#000000">
<link rel="shortcut icon" href="https://inbes.jp/products/idr04rs/icons/favicon.ico">
<meta name="msapplication-TileColor" content="#000000">
<meta name="msapplication-config" content="https://inbes.jp/products/idr04rs/icons/browserconfig.xml">
<meta name="theme-color" content="#000000">
<!-- OGP -->
<meta property="og:title" content="VM784 | 7.8インチ縦横モニター">
<meta property="og:type" content="website">
<meta property="og:description" content="VM784は、縦置き・横置きで使える7.8インチのサブモニター。SNSのタイムライン確認やパソコンの画面拡張に活用できます。USB Type-C・HDMIの接続条件、仕様、取扱説明書をご案内します。">
<meta property="og:site_name" content="VM784 | 7.8インチ縦横モニター" />
<meta property="og:image" content="">
<meta property="og:url" content="https://inbes.jp/products/vm784/">
<meta name="twitter:card" content="summary">
<meta name="twitter:site" content="">
<meta name="twitter:title" content="VM784 | 7.8インチ縦横モニター">
<meta name="twitter:description" content="VM784は、縦置き・横置きで使える7.8インチのサブモニター。SNSのタイムライン確認やパソコンの画面拡張に活用できます。USB Type-C・HDMIの接続条件、仕様、取扱説明書をご案内します。">
<meta name="twitter:image" content="">
<title>VM784 | 7.8インチ縦横モニター </title>
<meta name="copyright" content="Copyright (C) INBES Co.,Ltd.">
<meta name="Keywords" content="7.8インチ縦横モニター,インベス,INBES">
<meta name="ROBOTS" content="7.8インチ縦横モニター,インベス,INBES">
<meta name="Description" content="VM784は、縦置き・横置きで使える7.8インチのサブモニター。SNSのタイムライン確認やパソコンの画面拡張に活用できます。USB Type-C・HDMIの接続条件、仕様、取扱説明書をご案内します。">
<link href="./css/vm784.css" rel="stylesheet" type="text/css">
<link href="./css/vm784_footer.css" rel="stylesheet" type="text/css">
<link href="./css/g_navi.css" rel="stylesheet" type="text/css">
<link href="./css/accordion.css" rel="stylesheet" type="text/css">
<link href="css/remodal.css" rel="stylesheet" type="text/css">
<link href="css/remodal-default-theme.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="./css/hose.css">
<script src="./js/jquery.min.js"></script>
<script type="text/javascript" src="./js/res_nav.js"></script>
<script type="text/javascript" src="./js/remodal.min.js"></script> 
<!-- Canonical設定 -->
<link rel="canonical" href="https://inbes.jp/products/vm784/">
<!-- Canonical設定 -->
<script>
    (function (d) {
      var config = {
          kitId: 'lmx4ipp',
          scriptTimeout: 3000,
          async: true
        },
        h = d.documentElement,
        t = setTimeout(function () {
          h.className = h.className.replace(/\bwf-loading\b/g, "") + " wf-inactive";
        }, config.scriptTimeout),
        tk = d.createElement("script"),
        f = false,
        s = d.getElementsByTagName("script")[0],
        a;
      h.className += " wf-loading";
      tk.src = 'https://use.typekit.net/' + config.kitId + '.js';
      tk.async = true;
      tk.onload = tk.onreadystatechange = function () {
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
    $(function () {
      $('a[href^="#"]').click(function () {
        var speed = 500; // スクロールスピード(ミリ秒)
        var href = $(this).attr("href");
        var target = $(href == "#" || href == "" ? 'html' : href);
        var position = target.offset().top;
        $('body,html').animate({
          scrollTop: position
        }, speed, 'swing');
        return false;
      });
    });
  </script>
</head>

<body class="pc">
<div class="body">
  <div class="obi">
    <div class="head_waku">
      <a href="#top">
      <img class="logo" src="./img/logo.svg" alt="">
      </a>
      <nav class="Nav" role="navigation" aria-label="メインメニュー">
        <p class="navbtn">
          <a href="javascript:void(0)">
          <span>
          メニューを閉じる
          </span>
          </a>
        </p>
        <ul style="display: none;">
          <li>
            <a href="#about">
            ちょいモニとは
            </a>
          </li>
          <li>
            <a href="#point">
            注目ポイント
            </a>
          </li>
          <li>
            <a href="#method">
            接続方法
            </a>
          </li>
          <li>
            <a href="#specs">
            付属品・仕様
            </a>
          </li>
          <li>
            <a href="#d_load">
            ダウンロード
            </a>
          </li>
        </ul>
        <!-- /.Nav -->
      </nav>
    </div>
  </div>
  <header id="top">
    <div class="mainBg">
      <div class="top_items">
        <div>
          <style>
            .play_button_link {
              position: absolute;
              top: 50%;
              left: 50%;
              transform: translate(-50%, -50%);
              z-index: 100;
            }
            .play_button_link img {
              width: 100%;
            }
          </style>
          
          <!-- ボタン -->
          <a href="#mov1" class="play_button_link">
          <img class="play_button" src="img/play2.svg" alt="">
          </a>
          <!-- モーダル -->
          <div class="remodal YouTube" data-remodal-id="mov1">
            <iframe style="width: 100%; height: auto; aspect-ratio: 16/9;" width="560" height="315" src="https://www.youtube.com/embed/GKgeL4OXeok" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
          </div>          
          <div class="choimoni_mainText">
            <img style="min-width: 169px" src="img/mainText.svg" alt="">
          </div>
          <p class="package_content">
            <span>
            パッケージ内容
            </span>
          </p>
          <p class="package_content">
            <span>
            モニター/モニタースタンド/USBケーブル/HDMIケーブル
            </span>
          </p>
          <!--p class="jancode"><span>JAN 4580401390444</span></p-->
        </div>
        <div style="text-align: center">
          <img src="img/t1.png" alt="">
        </div>
      </div>
    </div>
    <!--div class="mainView">
      <div>
        <div class="p_title">
          <h1>7.8インチ縦横型サブモニター</h1>
          <h2>「ちょいモニ」 VM-784</h2>
        </div>
        <figure class="main_photo"> <img src="img/main.png" alt=""> </figure>
      </div>
    </div-->
  </header>
  <div style="height: 40px;"></div>
  <article id="about" class="article_wrap">
    <!--div class="about about_lead_header">
      <h1>7.8inch Sub Monitor『CHOIMONI』</h1>
    </div-->
    <div class="about_lead_wrap">
      <div class="about about_lead_text">
        <p>気付くとTwitterを開いてる。。。</p>
        <p>タイムラインが気になって仕方がない。。。</p>
        <br>
        <p>そんな<em class="about_lead_em">"ツイ廃"</em>のあなたに贈る</p>
        <p>
          <span class="about_underline">
          7.8インチ縦横サブモニター
          </span>
        </p>
      </div>
      <div style="text-align: center">
        <img class="lazyload" data-src="img/main_naname.png" alt="">
      </div>
    </div>
    <div class="lead_images_wrapper">
      <div class="lead_image_1">
        <img class="lazyload" data-src="img/t.jpg" alt="">
      </div>
      <div class="lead_image_2">
        <img class="lazyload" data-src="img/y.jpg" alt="">
      </div>
    </div>
    
    <!--
    <div class="about about_image_1"> <img src="./img/about2_1.jpg" alt=""> </div>
    <div class="about about_text_1">
      <p>横長に<em class="about_lead_em">"ながーく"</em>表示</p>
      <p><span class="about_underline">見やすいディスプレイ</span></p>
    </div>
    <div class="about about_image_2"> <img src="./img/about2_2.png" alt=""> </div>
    <div class="about about_text_2">
      <p>"タイムラインを見やすく表示"</p>
      <p>ノートPCやデスクトップPCで使える、<br>
        TwitterやSNS確認用のサブモニター！</p>
    </div>
    <div class="about about_icon_1"> <img src="img/about_arrow.svg" alt=""> </div>
    <div class="about about_image_3"> <img src="./img/about3_1.png" alt=""> </div>
    <div class="about about_text_3">
      <p>"ケーブル1本で簡単セットアップ"</p>
      <p>USBケーブルType-C入力のケーブル1本で<br>
        お手持ちのPCに電源・映像を簡単接続！</p>
      <p style="font-size: 0.8em;">※映像出力 (DisplayPort over Alt Mode仕様) に対応している場合。</p>
    </div>
    <div class="about about_icon_1"> <img src="img/about_arrow.svg" alt=""> </div>
    <div class="about about_image_4"> <img src="./img/about4_1.png" alt=""> </div>
    <div class="about about_text_4">
      <h2>こんなお悩みありませんか？</h2>
      <div class="listspace">
        <ul>
          <li>デスクワーク中でも気付くと常にTwitterやSNSをチェックしている。</li>
          <li>他のウィンドウに邪魔されてタイムラインが見にくい。</li>
          <li>サブモニターは欲しいけどパソコンにケーブル接続するのが面倒</li>
          <li>デスクにスペースがなくコンパクトなモニターが欲しい。</li>
        </ul>
      </div>
    </div>
    <div class="about about_image_5"> <img src="./img/about4_2.png" alt=""> </div>
    <div class="about about_text_4">
      <p>そんなお悩みを<br>
        7.8インチ縦横サブモニター『ちょいモニ』が解決します！</p>
    </div>
    <div class="about about_icon_1"> <img src="img/about_arrow.svg" alt=""> </div>
    <div class="about about_image_6"> <img src="./img/about5.png" alt=""> </div>
    -->
  </article>
  <article id="point" class="article_wrap">
    <div style="height: 120px;"></div>
    <div class="about about_text_5">
      <h1 class="point_decoration">注目ポイント！</h1>
      <br>
      <h1 class="point_decoration">Point.1</h1>
      <h2>
        <span class="point_decoration">
        TwitterやSNS確認に最適
        </span>
      </h2>
    </div>
    <div class="about about_image_7">
      <img class="lazyload" data-src="./img/about6.png" alt="">
    </div>
    <div class="about about_icon_1">
      <img  class="lazyload" data-src="img/about_arrow.svg" alt="">
    </div>
    <div class="about about_image_8">
      <img  class="lazyload" data-src="./img/about7.png" alt="">
    </div>
    <div class="about about_text_6">
      <p>様々なSNSに対応</p>
      <p>常に<em class="about_lead_em">ながーく</em>表示</p>
    </div>
    <div class="about about_image_8">
      <img  class="lazyload" data-src="./img/about8.png" alt="">
    </div>
    <div style="height: 60px;"></div>
    <div class="about about_text_7">
      <h1 class="point_decoration">Point.2</h1>
      <h2>
        <span class="point_decoration">
        ケーブル1本で簡単セットアップ
        </span>
      </h2>
    </div>
    <div class="about about_image_9">
      <img class="pc_display lazyload" data-src="./img/about9.png" alt=""><img class="sp_display lazyload" data-src="./img/about9_sp.png"></div>
    <div class="about about_image_10">
      <img class="pc_display lazyload" data-src="./img/about10.png" alt=""><img class="sp_display lazyload" data-src="./img/about10_sp.png">
    </div>
    <!--div class="about about_image_11"> <img class="lazyload" data-src="./img/about11.png" alt=""> </div-->
    <div style="height: 60px;"></div>
    <div class="about about_text_8">
      <h1 class="point_decoration">Point.3</h1>
      <h2>
        <span class="point_decoration">
        コンパクト&軽量設計
        </span>
      </h2>
    </div>
    <div class="about about_image_12">
      <img class="lazyload" data-src="./img/about_12__2.png" alt="">
    </div>
    <div class="about about_image_13">
      <img class="lazyload" data-src="./img/about13.webp" alt="">
    </div>
    <div style="height: 60px;"></div>
    <div class="about about_text_8">
      <h1 class="point_decoration">Point.4</h1>
      <h2>
        <span class="point_decoration">
        "縦置き・横置き"自由に使える
        </span>
      </h2>
    </div>
    <div class="about about_image_14">
      <img class="lazyload" data-src="./img/about14.png" alt="">
    </div>
    <div class="about about_image_15">
      <img class="lazyload" data-src="./img/about15.png" alt="">
    </div>
    <div style="height: 60px;"></div>
    <!--
    <div class="about about_text_8">
      <h1 class="point_decoration">Point.5</h1>
      <h2><span class="point_decoration">縦横スタンド&接続ケーブル付属</span><br>
        届いてすぐに使用可能</h2>
    </div>
    <div class="about about_image_16"> <img src="./img/about16.png" alt=""> </div>
    -->
  </article>
  <article id="method" class="article_wrap">
    <div style="height: 120px;"></div>
    <div class="about guide_text_1">
      <h1 class="point_decoration">接続方法</h1>
    </div>
    <div class="about guise_image_1">
      <p>付属のUSBケーブルType-Cでご使用のノートPCやディスクトップPCに接続し、ディスプレイ設定を行うだけでご使用いただけます。</p>
      <img class="lazyload" data-src="./img/guide_1.webp" alt="">
      <p>お使いのパソコンが、USBType-C端子からの映像出力（Displayport over Alt Mode 仕様）に対応している場合、付属のUSBケーブルの接続だけで使用できます。</p>
    </div>
    <div class="about guide_image_2">
      <img class="lazyload" data-src="./img/guide_2.webp" alt="">
      <p>HDMI端子から映像を出力する場合、付属のHDMIケーブルの他に市販のUSBケーブル（Type-A / Type-C）を電源供給のために本商品のUSB-C端子へ接続する必要があります。（1.5A 以上の電流を出力可能なUSB電源またはパソコンのUSBポートと接続してください。）※USBケーブル（Type-A/Type-C）は市販品をお使いください。</p>
    </div>
    <div class="about guide_image_3">
      <h3>【パソコン設定方法】</h3>
      <img class="lazyload" data-src="./img/guide_3.jpg" alt="">
    </div>
    <div class="about guide_image_4">
      <img class="lazyload" data-src="./img/guide_4.webp" alt="">
      <ol>
        <li>本商品を接続後、パソコンのデスクトップを右クリックし 【ディスプレイ設定】を選択します。</li>
        <li>ディスプレイ設定の画面が開いたら、下方へスクロールし、「マルチディスプレイ」の設定を、「表示画面を拡張する」に変更してください。</li>
        <li>設定画面の一番上へ戻り、「ディスプレイの配置を変更する」の「識別」ボタンを押し、本品のモニターに大きく表示される白色の数字を読み取ってください。</li>
        <li>図中の２をドラッグして動かし、設置したモニターの配置関係と同じになるように図中のモニターの配置を揃えてください。 本品を横長にして設置した場合は、図中で２が選択された状態にして、この図のやや下の方にある「画面の向き」を横または横（反対向き）から表示が正しくなる方を選択してください。縦長配置で上下反対に置く場合は縦（反対向き）を選択してください。</li>
      </ol>
    </div>
    <div class="about">
      <img class="lazyload" data-src="./img/hyou.jpg.webp" alt="">
    </div>
  </article>
  <article id="specs" class="article_wrap">
    <div style="height: 120px;"></div>
    <div class="about spec_text_1">
      <h1 class="point_decoration">付属品・仕様</h1>
    </div>
    <div class="option_wrap">
      <h3 class="option_header">付属品</h3>
      <figure>
        <img class="lazyload" data-src="./img/spec_1.png" alt="">
        <figcaption>モニター、モニタースタンド、USBケーブル、HDMIケーブル</figcaption>
      </figure>
    </div>
    <div class="about specifications_wrap">
      <h3 class="specifications_header">製品仕様</h3>
      <table class="specifications_table">
        <tbody>
          <tr>
            <td>ディスプレイサイズ</td>
            <td>7.84インチ IPS</td>
          </tr>
          <tr>
            <td>解像度</td>
            <td>400×1280</td>
          </tr>
          <tr>
            <td>画像ピッチ</td>
            <td>0.1485×0.1485 (mm)</td>
          </tr>
          <tr>
            <td>視野角</td>
            <td>上下170° / 左右170°</td>
          </tr>
          <tr>
            <td>コントラスト</td>
            <td>800:1</td>
          </tr>
          <!--tr>
            <td>最大輝度</td>
            <td>300cd/m2</td>
          </tr-->
          <tr>
            <td>リフレッシュレート</td>
            <td>60Hz</td>
          </tr>
          <tr>
            <td>スピーカー</td>
            <td>なし</td>
          </tr>
          <tr>
            <td>電源入力</td>
            <td>USB Type-C (5V 1.5A)</td>
          </tr>
          <tr>
            <td>本体寸法</td>
            <td>W74×H211×D15 (mm) </td>
          </tr>
          <tr>
            <td>本体質量</td>
            <td>174g</td>
          </tr>
          <tr>
            <td>付属品</td>
            <td>モニター、モニタースタンド、 USB ケーブル、HDMI ケーブル、 取扱説明書</td>
          </tr>
          <tr>
            <td>保証期間</td>
            <td>１年間（付属品は除く）</td>
          </tr>
        </tbody>
      </table>
    </div>
  </article>
  
  <!-- 開発ストーリー
    <div style="height: 120px;"></div>
    <div class="about story_text_1">
      <h1 class="point_decoration">開発ストーリー</h1>
    </div>
    <div>
      <p>7.8インチ縦横サブモニター「ちょいモニ」は、Twitter等のSNSを常に視認したいユーザー様へ向けてよりコンパクトかつ取り付けも簡単に出来たらと思い開発をさせて頂きました。<br>
      ここ近年ノートPC及びディスクトップPCの仕様がTYPE-C出力に変わりつつある中、配線１本で簡単接続が出来るよう開発し製品化いたしました。</p>
    </div>
    -->
  <article class="bg_func" id="point">
  </article>
  
  <!-- ダウンロードエリア -->
  <article class="article_wrap">
    <section id="d_load" class="d_load">
      <h2 class="point_decoration">ダウンロード</h2>
      <!--p style="text-align: center">只今準備中です。</p-->
      <ul>
        <!--a href="./dl/VM784_manual.pdf" download>
          <li><img src="./img/pdf.svg" alt="">製品カタログ<br><br>
            (2.9MB)</li>
        </a-->
        <a href="./dl/VM784_manual.pdf" download>
        <li><img src="./img/pdf.svg" alt="">取扱説明書<br>
          <br>
          (1MB)</li>
        </a>
        <a href="./dl/vm784_photo.zip" download>
        <li><img src="./img/zip.svg" alt="">商品写真<br>
          <br>
          <?php echo inbes_filesize('./dl/vm784_photo.zip'); ?></li>
        </a>
      </ul>
    </section>
  </article>
  <!-- /ダウンロードエリア -->
  <!-- footer -->
  <footer>
    <div>
      <p>
      </p>
      <dl>
        <dt>お買い上げの前に必ずお読みください<small>（免責事項・注意事項）</small></dt>
        <dd>本製品をご使用になる前に取扱説明書をよくお読みになり、内容をご理解の上ご利用ください。</dd>
        <dd>USBケーブルType-C1本での接続は、ご使用のPCが映像出力（Displayport over Alt Mode 仕様）に対応している場合です。</dd>
        <dd>アプリによって画面が対応しないことがあります。</dd>
        <!--dd>本商品はMacintoshではご使用できません。</dd>
        <dd>本商品はwindows10・11専用のサブモニターです。</dd-->
        <dd>本機の動作環境につきましては製品仕様欄のOS毎の動作確認結果をご覧ください。</dd>
        <dd>本商品を使用中に発生した物品の破損は、保証の対象外となります。</dd>
        <dd>お客様の過失、故意による故障、破損、改造は、保証の対象外となります。</dd>
        <dd>正しく使用されなかった場合の事故については、弊社は責任を負いかねます。</dd>
        <dd>製品仕様は、改善のため予告なく変更される場合があります。そのため、説明書に記載されている内容と異なる場合があります。</dd>
      </dl>
    </div>
    <nav class="foot_nav">
      <p>
        <a href="#about">
        ちょいモニとは
        </a>
      </p>
      <p>
        <a href="#point">
        注目ポイント
        </a>
      </p>
      <p>
        <a href="#method">
        接続方法
        </a>
      </p>
      <p>
        <a href="#specs">
        付属品・仕様
        </a>
      </p>
      <p>
        <a href="#d_load">
        ダウンロード
        </a>
      </p>
    </nav>
    <address>
    <a href="https://inbes.jp/" target="_blank">
    <img src="./img/inbes_logo.svg" alt="">株式会社INBES(インベス)<br>
    〒206-0012 東京都多摩市貝取1510-1 永山JOYビル5F
    </a>
    </address>
    <p class="copylight">©2020 INBES Co.,Ltd.</p>
  </footer>
  <!-- /footer -->
</div>
<script src="https://cdn.jsdelivr.net/npm/lazyload@2.0.0-rc.2/lazyload.min.js"></script>
<script>
    lazyload();
</script>
</body>
</html>