export type DiscontinuedProductImage = {
  src: string;
  alt: string;
};

export type DiscontinuedProduct = {
  tag: string;
  name?: string;
  nameLang?: "ja" | "en";
  models?: string[];
  images?: DiscontinuedProductImage[];
  url?: string;
};

export type DiscontinuedProductCategory = {
  id: string;
  eyebrow: string;
  title: string;
  products: DiscontinuedProduct[];
};

export const discontinuedProductCategories: DiscontinuedProductCategory[] = [
  {
    id: "power",
    eyebrow: "Power",
    title: "ポータブル電源",
    products: [
      {
        tag: "ポータブル電源",
        name: "Cubele（キューブル）",
        models: ["IBP-200S", "IBP-500S"],
        images: [
          {
            src: "/assets/products/discontinued/ibp200-500.jpg",
            alt: "Cubele IBP-200SとIBP-500Sの製品写真"
          }
        ],
        url: "https://inbes.jp/cubele/ibp200_500/"
      }
    ]
  },
  {
    id: "camera",
    eyebrow: "Camera",
    title: "カメラ・撮影機器",
    products: [
      {
        tag: "スマートフォン用3軸ジンバル",
        name: "Smart Grip",
        nameLang: "en",
        models: ["BRN-SG1"],
        images: [
          {
            src: "/assets/products/discontinued/brn-sg1.png",
            alt: "Smart Grip BRN-SG1の製品写真"
          }
        ],
        url: "https://inbes.jp/bulenu/smartgrip/"
      },
      {
        tag: "3軸ジンバル・ポケットカメラ",
        name: "PocketCam4K",
        nameLang: "en",
        models: ["BRN-PC4K1"],
        images: [
          {
            src: "/assets/products/discontinued/brn-pc4k1.png",
            alt: "PocketCam4K BRN-PC4K1の製品写真"
          }
        ],
        url: "https://inbes.jp/bulenu/pocketcam4k/"
      }
    ]
  },
  {
    id: "drive-recorder",
    eyebrow: "Drive Recorder",
    title: "ドライブレコーダー",
    products: [
      {
        tag: "フルハイビジョンドライブレコーダー",
        models: ["IDR-01"],
        images: [
          {
            src: "/assets/products/discontinued/idr01.jpg",
            alt: "フルハイビジョンドライブレコーダー IDR-01の製品写真"
          }
        ],
        url: "https://inbes.jp/products/idr01.html"
      },
      {
        tag: "フルハイビジョンドライブレコーダー",
        models: ["IDR-02", "IDR-03R"],
        images: [
          {
            src: "/assets/products/discontinued/idr02.png",
            alt: "フルハイビジョンドライブレコーダー IDR-02の製品写真"
          },
          {
            src: "/assets/products/discontinued/idr03r.png",
            alt: "フルハイビジョンドライブレコーダー IDR-03Rの製品写真"
          }
        ],
        url: "https://inbes.jp/products/idr0203r/"
      },
      {
        tag: "超高画質ドライブレコーダー",
        models: ["IDR-04RS"],
        images: [
          {
            src: "/assets/products/discontinued/idr04rs.png",
            alt: "超高画質ドライブレコーダー IDR-04RSの製品写真"
          }
        ],
        url: "https://inbes.jp/products/idr04rs/"
      },
      {
        tag: "ハイビジョンドライブレコーダー",
        name: "ドラネコ"
      }
    ]
  },
  {
    id: "security",
    eyebrow: "Security",
    title: "防犯・監視機器",
    products: [
      {
        tag: "SDモーションレコーダー",
        models: ["SR10"],
        images: [
          {
            src: "/assets/products/discontinued/sr10.jpg",
            alt: "SDモーションレコーダー SR10の製品写真"
          }
        ],
        url: "https://inbes.jp/products/sr10.html"
      },
      {
        tag: "SDモーションレコーダー",
        models: ["SR11"],
        images: [
          {
            src: "/assets/products/discontinued/sr11.jpg",
            alt: "SDモーションレコーダー SR11の製品写真"
          }
        ],
        url: "https://inbes.jp/products/sr11.html"
      },
      {
        tag: "ワイヤレスカメラシステム",
        models: ["IW30"],
        images: [
          {
            src: "/assets/products/discontinued/iw30.jpg",
            alt: "ワイヤレスカメラシステム IW30の製品写真"
          }
        ],
        url: "https://inbes.jp/products/iw30.html"
      },
      {
        tag: "スマートフォン専用モーション録画カメラ",
        models: ["LA02W"],
        images: [
          {
            src: "/assets/products/discontinued/la02w.jpg",
            alt: "スマートフォン専用モーション録画カメラ LA02Wの製品写真"
          }
        ],
        url: "https://inbes.jp/products/la02w.html"
      },
      {
        tag: "スマートフォン専用ネットワークカメラ",
        name: "ルックアフター",
        models: ["LA01"],
        images: [
          {
            src: "/assets/products/discontinued/la01.jpg",
            alt: "ネットワークカメラ ルックアフター LA01の製品写真"
          }
        ],
        url: "https://inbes.jp/products/la01.html"
      }
    ]
  }
];
