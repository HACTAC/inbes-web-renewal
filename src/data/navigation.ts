export const businessNavigation = [
  { label: "商品化支援", href: "/services/" },
  { label: "進め方", href: "/process/" },
  { label: "開発実績", href: "/works/" },
  { label: "会社概要", href: "/company/" },
  { label: "お問い合わせ", href: "/contact/business/", emphasis: true }
];

export const productNavigation = [
  { label: "製品一覧", href: "/products/#lineup" },
  { label: "サポート", href: "/products/#support" },
  { label: "お問い合わせ", href: "/contact/support/", emphasis: true }
];

export const modeSwitch = {
  business: { label: "製品をお探しの方", href: "/products/" },
  products: { label: "商品化をご相談の方", href: "/services/" }
};

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
