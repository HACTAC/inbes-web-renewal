<head>
<?php
$head_path = $_SERVER['DOCUMENT_ROOT'] . '/inc/head/';
include $head_path . 'ga.php';           // GoogleAnalytics
include $head_path . 'meta_core.php';    // 描画メタ設定
    ?>
<?php 
    if (isset($pageConfig['noindex_flag']) && $pageConfig['noindex_flag'] === true): ?>
        <meta name="robots" content="noindex">
    <?php endif; ?>
<title><?php echo $pageConfig['title'] ?? 'Cubele(キューブル)'; ?></title>
<?php
include $head_path . 'icon.php';         // アイコン
include $head_path . 'meta_info.php';    // メタ情報
include $head_path . 'css_link.php';  // CSS
    ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> 
<script type="text/javascript" src="js/res_nav.js"></script> 
<script type="text/javascript" src="js/animation.js"></script> 
<script type="text/javascript" src="js/remodal.js"></script> 
<script type="text/javascript" src="js/simplebox.min.js" ></script>
<!-- Canonical設定 -->
<link rel="canonical" href="<?php echo htmlspecialchars($pageConfig['canonical_url'] ?? 'https://inbes.jp/cubele/cmob105', ENT_QUOTES, 'UTF-8'); ?>">
<!-- Canonical設定 --> 
<!-- ogp -->
<meta property="og:url" content="<?php echo htmlspecialchars($pageConfig['canonical_url'] ?? 'https://inbes.jp/cubele/', ENT_QUOTES, 'UTF-8'); ?>"/>
<meta property="og:type" content="product"/>
<meta property="og:title" content="ポータブル電源 Cubele(キューブル)"/>
<meta property="og:description" content="毎日使うものだから主張しすぎないものを選びたい。Cubeleは落ち着いた配色とシンプルなデザインでいつもの風景に寄り添うように調和する快適な電源ライフをサポートします。"/>
<meta property="og:site_name" content="ポータブル電源 Cubele(キューブル)"/>
<meta property="og:image" content=""/>
<!-- /ogp --> 
<!-- pagemap -->
<PageMap>
  <DataObject type="thumbnail">
    <Attribute name="src" value=""/>
    <Attribute name="width" value="105"/>
    <Attribute name="height" value="105"/>
  </DataObject>
</PageMap>
<!-- /pagemap --> 
<script type="text/javascript" src="js/typekit.js"></script> 
<script>
$(function(){
$('a[href^="#"]').click(function() {
var speed = 500; // スクロールスピード(ミリ秒)
var href= $(this).attr("href");
var target = $(href == "#" || href == "" ? 'html' : href);
var position = target.offset().top;
$('body,html').animate({scrollTop:position}, speed, 'swing');
return false;
});
});
</script>
</head>