export type Product = {
  tag: string;
  name?: string;
  nameLang?: "ja" | "en";
  models?: string[];
  image: string;
  imageAlt: string;
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
    eyebrow: "Power & Charging",
    title: "電源・充電関連",
    products: [
      {
        tag: "ポータブル電源",
        name: "POWERBANK CUBE NEO",
        nameLang: "en",
        models: ["HBP-201S21W"],
        image: "/assets/products/hbp201s21w_banner.png",
        imageAlt: "POWERBANK CUBE NEOのポータブル電源",
        description: "ソーラーパネルも付属する、特定販売先専用モデルのポータブル電源。日常から非常時まで、電源まわりの安心を支える製品です。",
        url: "https://inbes.jp/cubele/hbp201s21w/",
        linkLabel: "製品ページへ"
      },
      {
        tag: "ポータブル電源",
        name: "POWERBANK CUBE",
        nameLang: "en",
        models: ["HBP-80S21W"],
        image: "/assets/products/hbp80s21w_baner.jpg",
        imageAlt: "POWERBANK CUBEのポータブル電源",
        description: "手のひらサイズの安心をテーマにした、軽量コンパクトなポータブル電源です。",
        url: "https://inbes.jp/products.php",
        linkLabel: "製品ページへ"
      },
      {
        tag: "ポータブル電源",
        name: "Cubele",
        nameLang: "en",
        models: ["IBPL-301C", "IBPL-601C", "IBPL-1501C"],
        image: "/assets/products/ibpl.jpg",
        imageAlt: "Cubele IBPLシリーズのポータブル電源",
        description: "落ち着いた配色とシンプルなデザインで、日常空間になじむポータブル電源シリーズです。",
        url: "https://inbes.jp/cubele/",
        linkLabel: "製品ページへ"
      },
      {
        tag: "モバイルバッテリー",
        name: "半固体モバイルバッテリー",
        models: ["CMOB105"],
        image: "/assets/products/cmob105_thumnail.jpg",
        imageAlt: "半固体モバイルバッテリーの製品写真",
        description: "20W出力、5000mAhの薄型・軽量モバイルバッテリー。携帯性と安心感を重視した電源製品です。",
        url: "https://inbes.jp/cubele/cmob105/",
        linkLabel: "製品ページへ"
      },
      {
        tag: "Cubele専用ソーラーパネル",
        name: "IBPSシリーズ",
        models: ["IBPS-201W", "IBPS-101W", "IBPS-60W"],
        image: "/assets/products/ibps_benner.jpg",
        imageAlt: "Cubele専用ソーラーパネルの製品写真",
        description: "ポータブル電源を支える専用ソーラーパネル。防災や屋外利用を補助します。",
        url: "https://inbes.jp/cubele/ibps/",
        linkLabel: "製品ページへ"
      },
      {
        tag: "ソーラー充電器",
        name: "ポータブルソーラー充電器",
        models: ["HBPS2-21W"],
        image: "/assets/products/ibps221w_benner.jpg",
        imageAlt: "ポータブルソーラー充電器の製品写真",
        description: "電源がない場所でもUSBポートに直接つなげる、折りたたみ式のポータブルソーラー充電器です。",
        url: "https://inbes.jp/cubele/hbps2-21w/",
        linkLabel: "製品ページへ"
      }
    ]
  },
  {
    id: "lifestyle",
    eyebrow: "Lifestyle",
    title: "生活家電・生活用品",
    products: [
      {
        tag: "コンパクトマルチ乾燥機",
        name: "ドライスマート",
        models: ["MD-1500"],
        image: "/assets/products/drysmart_thumnail.jpg",
        imageAlt: "ドライスマートの製品写真",
        description: "布団の暖め、衣類や靴の乾燥まで対応する、小型でパワフルなコンパクト乾燥機です。",
        url: "https://inbes.jp/products.php",
        linkLabel: "製品ページへ"
      },
      {
        tag: "洗浄用品",
        name: "高圧洗浄ホース",
        models: ["PWH15"],
        image: "/assets/products/pwh15.jpg",
        imageAlt: "高圧洗浄ホースの使用イメージ",
        description: "電源を使わず、6種類の噴射パターンで車、窓、床、水やりなどに使える高圧洗浄ホースです。",
        url: "https://inbes.jp/products/pwh15/",
        linkLabel: "製品ページへ"
      }
    ]
  },
  {
    id: "smartphone-pc",
    eyebrow: "Digital Accessories",
    title: "スマートフォン・PC周辺機器",
    products: [
      {
        tag: "スマートフォン用ジンバル",
        name: "Smart Grip",
        nameLang: "en",
        models: ["BRN-SG2"],
        image: "/assets/products/brn_sg2_banner_876_492.png",
        imageAlt: "Smart Gripのスマートフォン用ジンバル",
        description: "手ブレを抑えた滑らかな映像撮影ができる、スマートフォン用の折りたたみ式3軸ジンバルです。",
        url: "https://inbes.jp/bulenu/smartgrip2/",
        linkLabel: "製品ページへ"
      },
      {
        tag: "サブモニター",
        name: "CHOIMONI",
        nameLang: "en",
        models: ["VM-784"],
        image: "/assets/products/choimoni_banner_876_492.png",
        imageAlt: "CHOIMONIのサブモニター",
        description: "縦横どちらでも使える、SNSやチャット、補助画面の確認に便利な小型サブモニターです。",
        url: "https://inbes.jp/products/vm784/",
        linkLabel: "製品ページへ"
      }
    ]
  },
  {
    id: "car-products",
    eyebrow: "Automotive",
    title: "カー用品・車載機器",
    products: [
      {
        tag: "前後2カメラドライブレコーダー",
        models: ["IDR-06R"],
        image: "/assets/products/idr06r_876_492_1.png",
        imageAlt: "前後2カメラドライブレコーダーの使用イメージ",
        description: "対角135度の広視野角で、車両の前後を記録する2カメラタイプのドライブレコーダーです。",
        url: "https://inbes.jp/products/idr06r/",
        linkLabel: "製品ページへ"
      },
      {
        tag: "デジタルミラー型ドライブレコーダー",
        models: ["IDR-07MR"],
        image: "/assets/products/idr07mr_876_492_1.png",
        imageAlt: "デジタルミラー型ドライブレコーダーの製品イメージ",
        description: "前後2つのカメラで車両周辺を記録する、デジタルミラー型のドライブレコーダーです。",
        url: "https://inbes.jp/products/idr07mr/",
        linkLabel: "製品ページへ"
      },
      {
        tag: "カーエアコン用品",
        name: "KAZEKONU",
        nameLang: "en",
        models: ["KZ70", "KZ135"],
        image: "/assets/products/kazekonu_thumnail.jpg",
        imageAlt: "KAZEKONUの車内設置イメージ",
        description: "車内エアコンの直風をやわらげ、自然な風に近づけるカー用品です。",
        url: "https://inbes.jp/products/kazekonu/",
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
        tag: "双方向マルチ翻訳機",
        name: "talkbot.",
        nameLang: "en",
        models: ["ITB1W"],
        image: "/assets/products/tb.jpg",
        imageAlt: "talkbot.の翻訳機",
        description: "Wi-Fiに接続して使う双方向翻訳機。最大78言語の翻訳に対応します。",
        url: "https://inbes.jp/products/talkbot/",
        linkLabel: "製品ページへ"
      }
    ]
  },
  {
    id: "security",
    eyebrow: "Security",
    title: "防犯・監視機器",
    products: [
      {
        tag: "録画機器",
        name: "AHD SDレコーダー",
        models: ["ASR12"],
        image: "/assets/products/asr12_438_246.jpg",
        imageAlt: "AHD SDレコーダーの製品写真",
        description: "AHD 2.0に対応した小型SDカードレコーダー。フルハイビジョン録画に対応します。",
        url: "https://inbes.jp/products/asr12.php",
        linkLabel: "製品ページへ"
      }
    ]
  }
];
