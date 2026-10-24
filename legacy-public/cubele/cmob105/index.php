<!DOCTYPE HTML>
<html lang="ja">

<head>
  <?php
  $product_type = '半固体モバイルバッテリー';
  $pageConfig = [
    'noindex_flag' => false,
    'canonical_url' => 'https://inbes.jp/cubele/cmob105/',
    'title' => '半固体モバイルバッテリー | Cubele(キューブル)',
    'meta_keywords' => $product_type,
    'meta_robots' => $product_type,
    'meta_description' => '「燃えない、爆発しない」次世代の安全性を。従来の液系リチウムイオン電池の弱点を克服した半固体バッテリーが登場。',
    'css_link' => '/cubele/cmob105/css/cuble_cmobs105.css'
  ];
  include $_SERVER['DOCUMENT_ROOT'] . '/inc/head.php';
  ?>

</head>

<body>
  <?php
  $headerConfig = [
    'logo_image' => '/cubele/cmob105/img/header_logo.svg',
    'product_name' => '半固体モバイルバッテリー',
    'product_modelNumber' => 'CMOB105',
    'product_brief' => '20W 5000mAh',
    'product_janCode' => '4580401390642',
    'product_price' => 'メーカー希望小売価格7,980円（税込）'
  ];
  $headerNavMenu = [
    [
      "text" => "特長",
      "url"  => "#feature"
    ],
    [
      "text" => "製品仕様",
      "url"  => "#specs"
    ],
    [
      "text" => "ダウンロード",
      "url"  => "#d_load"
    ]
  ];
  include $_SERVER['DOCUMENT_ROOT'] . '/inc/header.php';
  ?>

  <article>
    <section id="about">
      <div class="about_box">
        <div class="fadeUpTrigger">
          <h2></h2>
          <h3>次世代の安心を<br>薄型・軽量の半固体モバイルバッテリー</h3>
          <!--img src="./img/cmob105_dummy.png" alt=""-->
          <div id="model-container"></div>

          <p>CMOB105は、安全性と携帯性を両立させた次世代の半固体モバイルバッテリーです。薄型かつ軽量な設計ながら、5,000mAhの容量と最大20Wの高出力を実現しており、スマートフォンやタブレットへの急速充電が可能です。信頼性の高い半固体リチウムイオン電池を採用しており、日常の持ち運びからビジネスシーンまで幅広く活用いただけます。</p>
        </div>
      </div>
    </section>


  </article>
  <article id="msmp">
    <h2><span class="msmp_subheader"><?php echo $headerConfig['product_name']; ?></span><br><?php echo $headerConfig['product_modelNumber']; ?></h2>
    <p>希望小売価格 <span class="price_decoration">7,980円</span> （税込）</p>
  </article>
  <article>
    <section id="feature">
      <h2>特長</h2>
      <div class="flex-column">
      </div>
      <div class="direct">
        <div class="fadeUp">
          <h3>発火リスクがほとんどない半固体バッテリーを採用</h3>
          <p>従来の電解液をゲル状の半固体にしたことで、内部ショートなどの火花が生じても発火・燃焼しにくい高い安全性を実現しました。</p>
        </div>
        <div class="fadeUp">
          <h3>急速充電・高出力</h3>
          <p>USB-PD（Power Delivery）に対応し、最大20Wの出力でデバイスをスピーディーに充電します。</p>
        </div>
        　<div class="fadeUp">
          <h3>小型軽量</h3>
          <p>厚さわずか9.2mm、質量は約110gと非常にコンパクトで、バッグやポケットに入れてもかさばりません。</p>
        </div>
        <div class="fadeUp">
          <h3>小出力対応</h3>
          <p>スマートウォッチなどの入力電流が小さいデバイスでも、オートパワーオフを回避して最後までしっかり充電できる専用モードを搭載しています。</p>
        </div>
        <div class="fadeUp">
          <h3>直感的な残量表示</h3>
          <p>4つのLEDインジケーターにより、バッテリー残量を5段階（点滅含む）で一目で確認できます。</p>
        </div>

      </div>
    </section>
  </article>
  <article>
    <section id="specs">
      <h2>製品仕様</h2>
      <div>
        <table class="spec_tbl">
          <tr class="thead">
            <th>型式</th>
            <th><?php echo $headerConfig['product_modelNumber']; ?></th>
          </tr>
          <tr>
            <th>JAN</th>
            <th><?php echo $headerConfig['product_janCode']; ?></th>
          </tr>
          <tr>
            <td>定格電圧</td>
            <td>5V / 2,600mAh</td>
          </tr>
          <tr>
            <td>バッテリー容量</td>
            <td>5,000mAh 3.7V (18.5Wh)</td>
          </tr>
          <tr>
            <td>バッテリータイプ</td>
            <td>半固体リチウムイオン電池</td>
          </tr>
          <tr>
            <td>USB(PD)出力</td>
            <td>5V⎓3A / 9V⎓2.22A (最大20W)</td>
          </tr>
          <tr>
            <td>USB(PD-PPS)出力</td>
            <td>5V~11V⎓1.3A (最大15W)</td>
          </tr>
          <tr>
            <td>USB(PD)入力</td>
            <td>5V⎓2.7A / 9V⎓1.5A (13.5W)</td>
          </tr>
          <tr>
            <td>外形寸法</td>
            <td>114×74×9.2mm</td>
          </tr>
          <tr>
            <td>質量</td>
            <td>約110g</td>
          </tr>
        </table>
      </div>
    </section>
  </article>
  <article>
    <section id="price">
      <p class="price_product_price">直販価格 <span class="price_decoration">7,980円</span> (税込)</p>
      <p>※商品価格の他に別途送料(1000円)がかかります。</p>

      <a href="https://forms.gle/mffA2YPUdrqPDpWm7" class="purchase-button">
        <p>お求めはこちらから</p>
      </a>
      <a class="price_link" target="_blank" href="https://inbes.jp/tokushohou.php">特定商法に基づく表記</a>
    </section>
  </article>
  <article>
    <section id="d_load">
      <h2>ダウンロード</h2>
      <div class="d_list">
        <!--div class="catalog_d">
			  <h4><img src="images/i_cat.svg" alt=""/>カタログ<span>Catalog</span></h4>
			  <ul>
				  <li><a href="download/ibpl_series_leaflet_240401.pdf" download>製品カタログ(8.4MB)</a></li>
			  </ul>
		  </div-->

        <div class="manual_d">
          <h4>
            <!--img src="images/i_man.svg" alt=""/-->
            取扱説明書<span>Manual</span>
          </h4>
          <ul>
            <li><a href="download/CMOB105_manual_0427.pdf" download>CMOB105 説明書(1.0MB)</a></li>
          </ul>
        </div>
      </div>
    </section>
  </article>
  <?php
  include $_SERVER['DOCUMENT_ROOT'] . '/inc/footer.php';
  $footerScriptsConfig = [
    'use_swiper' => false,
    'use_modalVideo' => false,
  ];
  include $_SERVER['DOCUMENT_ROOT'] . '/inc/footer_scripts.php';
  ?>
  <script async src="/js/meshopt_decoder.js"></script>
  <script type="module" src="/js/model-viewer.min.js"></script>
  <script type="module">
    // MeshoptDecoder の読み込みを待つヘルパー関数
    function waitForMeshopt() {
      return new Promise((resolve) => {
        if (window.MeshoptDecoder) {
          window.MeshoptDecoder.ready.then(() => resolve(window.MeshoptDecoder));
        } else {
          const interval = setInterval(() => {
            if (window.MeshoptDecoder) {
              clearInterval(interval);
              window.MeshoptDecoder.ready.then(() => resolve(window.MeshoptDecoder));
            }
          }, 50);
        }
      });
    }


    async function forceLoad() {
      // model-viewer と MeshoptDecoder の準備を「並列（同時）」で待つ
      const [_, decoder] = await Promise.all([
        customElements.whenDefined('model-viewer'),
        waitForMeshopt()
      ]);

      const ModelViewerElement = customElements.get('model-viewer');
      ModelViewerElement.meshoptDecoderLocation = "/js/meshopt_decoder.js";

      const container = document.getElementById('model-container');
      const viewer = document.createElement('model-viewer');
      viewer.setAttribute('camera-controls', ''); // コントロール
      viewer.setAttribute('environment-image', 'neutral');
      viewer.setAttribute('touch-action', 'pan-y');
      viewer.setAttribute('interaction-prompt', 'auto');
      viewer.setAttribute('ar', '');
      viewer.setAttribute('loading', 'lazy');
      viewer.setAttribute('shadow-intensity', '1');
      viewer.setAttribute('shadow-softness', '0.7');
      viewer.setAttribute('exposure', '1');
      // 位置移動＝パン操作を禁止
      viewer.setAttribute('disable-pan', '');
      viewer.setAttribute('camera-orbit', '0deg 90deg 100%');
      viewer.setAttribute('min-camera-orbit', 'auto -20deg 85%');
      viewer.setAttribute('max-camera-orbit', 'auto 180deg 100%');
      viewer.setAttribute('camera-target', '0m 0m 0m');
      viewer.setAttribute('field-of-view', '35deg');
      viewer.setAttribute('min-field-of-view', '35deg');
      viewer.setAttribute('max-field-of-view', '35deg');
      viewer.setAttribute('auto-rotate', '');
      viewer.setAttribute('auto-rotate-delay', '5000');
      viewer.setAttribute('rotation-per-second', '30deg');
      viewer.setAttribute('orientation', '-80deg 0deg -135deg');
      /*
      TOP:-60deg 0deg -120deg
      Type A: -90deg 0deg -135deg
      Type B: -80deg 0deg -135deg
      */

      //viewer.setAttribute('poster', './img/cmob105_dummy.webp'); //ポスター
      /*
      const mv = document.querySelector('model-viewer');
mv.setAttribute('orientation', '-60deg 0deg -120deg');
mv.updateFraming();
      */

      viewer.style.width = '100%';
      viewer.style.height = '80svh';


      viewer.src = "/assets/models/cmob105-meshopt-20261024.glb";
      /*
            viewer.addEventListener('load', () => {
              console.log("モデルの読み込みが完了しました。マテリアルを解析します。");

              // 1. まず、含まれているマテリアルの名前をコンソールに全部出す（確認用）
              viewer.model.materials.forEach((mat, index) => {
                console.log(`インデックス[${index}]: マテリアル名 = 「${mat.name}」`);
              });

              // 2. 特定の名前のマテリアルを探して、金属感と荒さを変更する
              // ※ 'Your_Material_Name' を、Blender等で設定した実際のマテリアル名に書き換えてください
              const targetMaterial = viewer.model.materials.find(
                mat => mat.name === ''
              );

              if (targetMaterial) {
                // 金属度をマックス（1.0）に
                targetMaterial.pbrMetallicRoughness.setMetallicFactor(1.0);
                // 粗さを設定（0.0でピカピカ鏡面、1.0で完全なつや消し。好みの数値に調整してください）
                targetMaterial.pbrMetallicRoughness.setRoughnessFactor(0.8);

                (async () => {
                  try {
                    // 1. 凹凸を表現するためのノーマルマップ画像を読み込む（※パスは環境に合わせて変更してください）
                    const texture = await viewer.createTexture('./img/rough_metal_normal.png');

                    if (texture) {
                      // 2. マテリアルの正常マップ（normalTexture）にセット
                      targetMaterial.normalTexture.setTexture(texture);

                      // 3. 凹凸の強さを調整（初期値は1.0。数値を大きくするとデコボコが強調されます）
                      // ※ model-viewerのバージョンによっては scale.setScale() や直接代入の場合があります
                      if (targetMaterial.normalTexture.scale) {
                        targetMaterial.normalTexture.scale.setScale(1.5);
                      }

                      console.log(`マテリアル「${targetMaterial.name}」にざらざら質感を適用しました！`);
                    }
                  } catch (error) {
                    console.error("ノーマルマップの読み込みまたは適用に失敗しました:", error);
                  }
                })();
                console.log(`マテリアル「${targetMaterial.name}」の質感を変更しました！`);
              } else {
                console.warn("指定した名前のマテリアルが見つかりませんでした。上記の一覧を確認してください。");
              }
            });*/

      container.appendChild(viewer);
      console.log("Model-viewer instance created with decoder.");
    }

    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', forceLoad);
    } else {
      forceLoad();
    }
  </script>
  <script>
    /* Activeフラグ
    const container = document.getElementById('model-container');
    container.addEventListener('click', (e) => {
      e.stopPropagation();
      container.classList.add('active');
    });
    document.addEventListener('click', () => {
      container.classList.remove('active');
    });
    */
  </script>
</body>

</html>