export const businessNavigation = [
  { label: "商品化支援", href: "/services/" },
  { label: "開発実績", href: "/works/" },
  { label: "会社概要", href: "/company/" },
  { label: "お問い合わせ", href: "/contact/", emphasis: true }
];

export const productNavigation = [
  { label: "製品情報", href: "/products/" },
  { label: "修理・サポート", href: "/contact/" },
  { label: "お問い合わせ", href: "/contact/", emphasis: true }
];

export const modeSwitch = {
  business: { label: "製品をお探しの方へ", href: "/products/" },
  products: { label: "商品化をご相談の方へ", href: "/services/" }
};

export const navigation = [
  { label: "自社製品", href: "/products/" },
  { label: "商品化支援", href: "/services/" },
  { label: "開発実績", href: "/works/" },
  { label: "会社概要", href: "/company/" },
  { label: "お問い合わせ", href: "/contact/", emphasis: true }
];

export const footerNavigation = [
  ...navigation,
  { label: "プライバシーポリシー", href: "/privacy/" },
  { label: "クッキーポリシー", href: "/cookies/" }
];
