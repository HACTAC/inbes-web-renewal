export type ServiceField = {
  title: string;
  description: string;
  image: string;
  imageAlt: string;
};

export const fields: ServiceField[] = [
  {
    title: "バッテリー・電源関連",
    description: "モバイルバッテリー、ポータブル電源、ソーラーパネル、ACアダプター、充電機器など。",
    image: "/assets/services/fields/battery-power-v1.png",
    imageAlt: "ポータブル電源、ソーラーパネル、充電機器"
  },
  {
    title: "生活家電",
    description: "小型生活家電、乾燥機、高圧洗浄機など。",
    image: "/assets/services/fields/home-appliances-v1.png",
    imageAlt: "室内で衣類を乾燥させる生活家電"
  },
  {
    title: "スマートフォン・PC周辺機器",
    description: "ジンバル、サブモニター、翻訳機、各種周辺機器など。",
    image: "/assets/services/fields/mobile-pc-accessories-v1.png",
    imageAlt: "パソコン、モバイルモニター、ジンバルなどの周辺機器"
  },
  {
    title: "カー用品・車載機器",
    description: "ドライブレコーダー、車載アクセサリー、カーエレクトロニクス製品など。",
    image: "/assets/services/fields/automotive-electronics-v1.png",
    imageAlt: "ドライブレコーダー、デジタルミラー、車載充電機器"
  },
  {
    title: "防犯・監視機器",
    description: "防犯カメラ、録画機器、カメラモジュール、セキュリティ機器など。",
    image: "/assets/services/fields/security-surveillance-v1.png",
    imageAlt: "屋外用防犯カメラと監視映像用録画機器"
  },
  {
    title: "その他エレクトロニクス製品",
    description: "用途や販売先に合わせた各種電気製品、電子機器、部品など。",
    image: "/assets/services/fields/other-electronics-v1.png",
    imageAlt: "電子基板、カメラモジュール、筐体などの電子部品"
  }
];

export const processSteps = [
  ["ご相談・要件整理", "用途、価格、販売先、希望時期などを伺い、必要な工程と支援範囲を整理します。"],
  ["製品・技術の選定", "企画や用途に合うベース製品・技術を探し、商品化の可能性を検討します。"],
  ["国内販売条件の確認", "法令・許認可、国内試験、品質基準、表示や輸入条件を確認します。"],
  ["カスタマイズ・試作", "仕様、機能、意匠、付属品などを調整し、試作を通して仕上がりを確認します。"],
  ["量産・輸入", "量産、品質管理、輸送・輸入手配を進め、国内への入荷まで管理します。"],
  ["納品・販売準備", "パッケージ、説明書、販促物などを整え、販売できる状態で納品します。"]
];
