export type Product = {
  title: string;
  tag: string;
  description: string;
  url: string;
  linkLabel: string;
};

export type ProductCategory = {
  id: string;
  eyebrow: string;
  title: string;
  products: Product[];
};

export const productCategories: ProductCategory[] = [
  {
    id: "power",
    eyebrow: "Power",
    title: "ポータブル電源/モバイルバッテリー",
    products: [
      {
        tag: "ポータブル電源",
        title: "POWERBANK CUBE NEO HBP-201S21W",
        description: "ソーラーパネルも付属する、特定販売先専用モデルのポータブル電源。日常から非常時まで、電源まわりの安心を支える製品です。",
        url: "https://inbes.jp/cubele/hbp201s21w/",
        linkLabel: "製品ページへ"
      },
      {
        tag: "ポータブル電源",
        title: "Cubele HBP-80S21W",
        description: "手のひらサイズの安心をテーマにした、軽量コンパクトなポータブル電源です。",
        url: "https://inbes.jp/products.php",
        linkLabel: "製品情報へ"
      },
      {
        tag: "ポータブル電源",
        title: "Cubele IBPL-301C / 601C / 1501C",
        description: "落ち着いた配色とシンプルなデザインで、日常空間になじむポータブル電源シリーズです。",
        url: "https://inbes.jp/cubele/",
        linkLabel: "製品ページへ"
      },
      {
        tag: "モバイルバッテリー",
        title: "半固体モバイルバッテリー CMOB105",
        description: "20W出力、5000mAhの薄型・軽量モバイルバッテリー。携帯性と安心感を重視した電源製品です。",
        url: "https://inbes.jp/products.php",
        linkLabel: "製品情報へ"
      },
      {
        tag: "ソーラーパネル",
        title: "専用ソーラーパネル IBPS-201W / 101W / 60W",
        description: "ポータブル電源を支える専用ソーラーパネル。防災や屋外利用を補助します。",
        url: "https://inbes.jp/cubele/ibps/",
        linkLabel: "製品ページへ"
      },
      {
        tag: "ソーラー充電器",
        title: "ポータブルソーラー充電器 HBPS2-21W",
        description: "電源がない場所でもUSBポートに直接つなげる、折りたたみ式のポータブルソーラー充電器です。",
        url: "https://inbes.jp/cubele/hbps2-21w/",
        linkLabel: "製品ページへ"
      }
    ]
  },
  {
    id: "dryer",
    eyebrow: "Dryer",
    title: "コンパクトマルチ乾燥機",
    products: [
      {
        tag: "生活家電",
        title: "ドライスマート MD-1500",
        description: "布団の暖め、衣類や靴の乾燥まで対応する、小型でパワフルなコンパクト乾燥機です。",
        url: "https://inbes.jp/products.php",
        linkLabel: "製品情報へ"
      }
    ]
  },
  {
    id: "camera",
    eyebrow: "Camera",
    title: "3軸ジンバル・ポケットカメラ",
    products: [
      {
        tag: "スマートフォン周辺",
        title: "Smart Grip BRN-SG2",
        description: "手ブレを抑えた滑らかな映像撮影ができる、スマートフォン用の折りたたみ式3軸ジンバルです。",
        url: "https://inbes.jp/bulenu/smartgrip2/",
        linkLabel: "製品ページへ"
      }
    ]
  },
  {
    id: "drive-recorder",
    eyebrow: "Drive Recorder",
    title: "ドライブレコーダー",
    products: [
      {
        tag: "車載機器",
        title: "前後2カメラドライブレコーダー IDR-06R",
        description: "対角135度の広視野角で、車両の前後を記録する2カメラタイプのドライブレコーダーです。",
        url: "https://inbes.jp/products/idr06r/",
        linkLabel: "製品ページへ"
      },
      {
        tag: "車載機器",
        title: "デジタルミラー型ドライブレコーダー IDR-07MR",
        description: "前後2つのカメラで車両周辺を記録する、デジタルミラー型のドライブレコーダーです。",
        url: "https://inbes.jp/products/idr07mr/",
        linkLabel: "製品ページへ"
      }
    ]
  },
  {
    id: "car-goods",
    eyebrow: "Car Goods",
    title: "カー用品",
    products: [
      {
        tag: "カー用品",
        title: "KAZEKONU",
        description: "車内エアコンの直風をやわらげ、自然な風に近づけるカー用品です。",
        url: "https://inbes.jp/products.php",
        linkLabel: "製品情報へ"
      }
    ]
  },
  {
    id: "pc-accessory",
    eyebrow: "PC Accessory",
    title: "PC周辺機器",
    products: [
      {
        tag: "サブモニター",
        title: "7.8インチサブモニター VM-784",
        description: "縦横どちらでも使える、SNSやチャット、補助画面の確認に便利な小型サブモニターです。",
        url: "https://inbes.jp/products/vm784/",
        linkLabel: "製品ページへ"
      }
    ]
  },
  {
    id: "translator",
    eyebrow: "Translator",
    title: "翻訳機",
    products: [
      {
        tag: "翻訳機",
        title: "双方向マルチ翻訳機 talkbot.",
        description: "Wi-Fiに接続して使う双方向翻訳機。最大78言語の翻訳に対応します。",
        url: "https://inbes.jp/products/talkbot/",
        linkLabel: "製品ページへ"
      }
    ]
  },
  {
    id: "cleaning",
    eyebrow: "Cleaning",
    title: "高圧洗浄ホース",
    products: [
      {
        tag: "洗浄用品",
        title: "電源不要 高圧洗浄ホース PWH15",
        description: "電源を使わず、6種類の噴射パターンで車、窓、床、水やりなどに使える高圧洗浄ホースです。",
        url: "https://inbes.jp/products/pwh15/",
        linkLabel: "製品ページへ"
      }
    ]
  },
  {
    id: "security",
    eyebrow: "Security",
    title: "防犯・監視 セキュリティ機器",
    products: [
      {
        tag: "録画機器",
        title: "AHD/CVBS ハイブリッドSDレコーダー ASR12",
        description: "AHD 2.0に対応した小型SDカードレコーダー。フルハイビジョン録画に対応します。",
        url: "https://inbes.jp/products/asr12.php",
        linkLabel: "製品ページへ"
      }
    ]
  }
];
