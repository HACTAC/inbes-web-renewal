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
  ["ご相談", "作りたい商品がある場合も、探すところから始めたい場合も対応します。"],
  ["商品化プラン", "商品や販売方法に合わせた進め方をご提案します。"],
  ["試作・確認", "仕様や品質を確認し、販売に向けた調整を行います。"],
  ["量産・輸入", "量産、品質管理、輸入など販売開始までの準備を進めます。"],
  ["納品", "商品を納品し、販売開始へつなげます。"],
  ["継続サポート", "追加生産や改良など、継続的な商品展開もサポートします。"]
];
