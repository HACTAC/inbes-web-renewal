export const businessNavigation = [
  { label: "商品化支援", href: "/services/" },
  { label: "進め方", href: "/process/" },
  { label: "開発実績", href: "/works/" },
  { label: "会社概要", href: "/company/" },
  { label: "お問い合わせ", href: "/contact/business/", emphasis: true }
];

export const productNavigation = [
  { label: "製品一覧", href: "/products/" },
  { label: "サポート", href: "/contact/support/", emphasis: true }
];

export const sharedNavigation = [
  { label: "お知らせ", href: "/news/" },
  { label: "会社概要", href: "/company/" },
  { label: "お問い合わせ", href: "/contact/", emphasis: true }
];

export const audienceNavigation = [
  { mode: "products", label: "個人のお客様", href: "/products/" },
  { mode: "business", label: "法人のお客様", href: "/services/" }
] as const;

export const navigation = [
  { label: "自社製品", href: "/products/" },
  { label: "商品化支援", href: "/services/" },
  { label: "進め方", href: "/process/" },
  { label: "開発実績", href: "/works/" },
  { label: "会社概要", href: "/company/" },
  { label: "お問い合わせ", href: "/contact/", emphasis: true }
];

export const footerNavigation = [
  ...navigation,
  { label: "プライバシーポリシー", href: "/privacy/" },
  { label: "クッキーポリシー", href: "/cookies/" }
];
