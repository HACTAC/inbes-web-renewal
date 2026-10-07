export const workSupportIcons = {
  企画: "light-bulb",
  工場選定: "industry",
  設計: "design-pencil",
  評価: "clipboard-check",
  認証: "badge-check",
  量産: "settings",
  輸入: "delivery-truck",
  品質管理: "shield-check"
} as const;

export type WorkSupport = keyof typeof workSupportIcons;

export type Work = {
  id: string;
  title: string;
  category: string;
  description: string;
  supports: WorkSupport[];
  highlight?: string;
  image?: string;
  imageScale?: number;
};

export const works: Work[] = [
  {
    id: "portable-power-station",
    title: "小型ポータブル電源",
    category: "バッテリー・電源関連",
    description: "コンパクトで高安全性のポータブル電源。企画から量産・輸入まで一貫して対応しています。",
    supports: ["企画", "工場選定", "評価", "認証", "量産", "輸入"]
  },
  {
    id: "semi-solid-mobile-battery",
    image: "/assets/works/semi-solid-mobile-battery-v2.webp",
    title: "半固体モバイルバッテリー",
    category: "バッテリー・電源関連",
    description: "次世代電池を採用したモバイルバッテリー。工場選定から量産まで対応。",
    supports: ["企画", "工場選定", "評価", "認証", "量産", "輸入"]
  },
  {
    id: "folding-solar-panel",
    title: "折りたたみ式ソーラーパネル",
    category: "バッテリー・電源関連",
    description: "防災・アウトドアに活躍するソーラーパネル。商品企画から量産・輸入まで対応。",
    supports: ["企画", "工場選定", "評価", "認証", "量産", "輸入"]
  },
  {
    id: "digital-mirror-drive-recorder",
    imageScale: 0.85,
    title: "デジタルインナーミラー型ドライブレコーダー",
    category: "カー用品・車載機器",
    description: "自社企画をベースにOEM専用モデルを開発。日本市場向けに仕様を最適化。",
    supports: ["企画", "評価", "認証", "量産", "輸入"]
  },
  {
    id: "low-cost-drive-recorder",
    imageScale: 0.7,
    title: "ローコストタイプドライブレコーダー",
    category: "カー用品・車載機器",
    description: "お客様の価格要求に合わせ、品質とコストのバランスを考慮して商品化。",
    supports: ["工場選定", "評価", "認証", "輸入", "品質管理"]
  },
  {
    id: "compact-drive-recorder",
    imageScale: 0.6,
    title: "超小型ドライブレコーダー",
    category: "カー用品・車載機器",
    description: "ミラー裏に収まるコンパクト設計。小型ながら必要機能を搭載。",
    supports: ["企画", "工場選定", "設計", "評価", "量産"]
  },
  {
    id: "rear-camera-drive-recorder",
    image: "/assets/works/rear-camera-drive-recorder-v3.webp",
    title: "リアカメラ付きドライブレコーダー",
    category: "カー用品・車載機器",
    description: "目標販売価格から逆算して商品を企画。コストを抑えた大ロット生産を実現。",
    supports: ["企画", "工場選定", "評価", "量産", "輸入"]
  },
  {
    id: "display-audio",
    image: "/assets/works/display-audio-v2.webp",
    imageScale: 0.85,
    title: "ディスプレイオーディオ",
    category: "カー用品・車載機器",
    description: "海外展示会で製品を探索し、日本向け仕様への変更や専用部品の製作まで対応。",
    supports: ["工場選定", "設計", "評価", "量産", "輸入"]
  },
  {
    id: "mobile-holder",
    image: "/assets/works/mobile-holder-v2.webp",
    imageScale: 0.95,
    title: "車載用モバイルホルダー",
    category: "カー用品・車載機器",
    description: "量販店向けに特徴ある車載ホルダーを企画。工場探索から量産まで対応。",
    supports: ["企画", "工場選定", "評価", "量産", "輸入"]
  },
  {
    id: "construction-site-camera",
    image: "/assets/works/construction-site-camera-v2.webp",
    title: "工事現場向け屋外カメラ",
    category: "防犯・監視機器",
    description: "お客様専用の完全特注品として新規開発。電源のない環境での長時間撮影を実現。",
    supports: ["企画", "設計", "評価", "量産", "品質管理"],
    highlight: "完全特注開発"
  },
  {
    id: "action-camera",
    imageScale: 0.7,
    title: "防水・防塵アクションカメラ",
    category: "スマートフォン・PC周辺機器",
    description: "海外メーカーと連携し、日本市場向けに商品化。評価・検証から量産まで対応。",
    supports: ["工場選定", "設計", "評価", "量産", "輸入"]
  },
  {
    id: "sd-recorder",
    image: "/assets/works/sd-recorder-v2.webp",
    title: "監視カメラ・SDカードレコーダー",
    category: "防犯・監視機器",
    description: "海外メーカーの窓口として、製品調達・生産管理・輸入・国内供給まで対応。",
    supports: ["工場選定", "量産", "輸入", "品質管理"]
  },
  {
    id: "vacuum-rice-container",
    image: "/assets/works/vacuum-rice-container-v3.webp",
    title: "真空米びつ",
    category: "生活家電",
    description: "市場性を確認したお客様からの依頼を受け、工場探索から金型対応まで実施。",
    supports: ["企画", "工場選定", "設計", "認証", "量産", "輸入"]
  },
  {
    id: "cooking-pot",
    image: "/assets/works/cooking-pot-v2.webp",
    title: "全自動調理ポット",
    category: "生活家電",
    description: "輸入ルートの再構築からPSE対応、取扱説明書・レシピ整備まで対応。",
    supports: ["設計", "評価", "認証", "量産", "輸入"]
  },
  {
    id: "multi-dryer",
    image: "/assets/works/multi-dryer-v2.webp",
    title: "マルチ乾燥機",
    category: "生活家電",
    description: "国内では珍しいマルチタイプの乾燥機を海外で探索し、日本向けに商品化。",
    supports: ["企画", "工場選定", "評価", "認証", "量産"]
  },
  {
    id: "vibration-machine",
    imageScale: 0.85,
    title: "振動フィットネスマシン",
    category: "生活家電",
    description: "テレビ通販向け専用商品としてOEM開発。目標価格に合わせた提案から納品まで対応。",
    supports: ["企画", "工場選定", "評価", "量産", "輸入"]
  },
  {
    id: "high-pressure-hose",
    image: "/assets/works/high-pressure-hose-v2.webp",
    title: "高圧洗浄ホース",
    category: "生活家電",
    description: "自社企画製品をベースにテレビ通販向けOEMとして展開。量産・輸入まで対応。",
    supports: ["企画", "工場選定", "評価", "量産", "輸入"],
    highlight: "自社企画→OEM"
  },
  {
    id: "bidirectional-translator",
    image: "/assets/works/bidirectional-translator-v2.webp",
    title: "双方向翻訳機",
    category: "スマートフォン・PC周辺機器",
    description: "1万円以下で販売できる翻訳機の開発を支援。2社へのOEM供給を行い、約半年で6万台以上を販売。",
    supports: ["企画", "工場選定", "設計", "量産", "輸入"],
    highlight: "約6か月で6万台以上"
  }
];
