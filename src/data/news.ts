export type NewsSection = {
  heading?: string;
  paragraphs: string[];
};

export type NewsItem = {
  slug: string;
  date: string;
  dateTime: string;
  category: string;
  title: string;
  summary: string;
  sections: NewsSection[];
  relatedLink?: {
    label: string;
    href: string;
  };
};

export const getNewsHref = (item: Pick<NewsItem, "slug">) => `/news/${item.slug}/`;

export const newsItems: NewsItem[] = [
  {
    slug: "corporate-site-renewal",
    date: "2026.10.09",
    dateTime: "2026-10-09",
    category: "お知らせ",
    title: "コーポレートサイト リニューアルのお知らせ",
    summary: "2026年10月9日、INBESのコーポレートサイトをリニューアルしました。自社製品と商品化支援の情報を、より分かりやすくご覧いただけます。",
    sections: [
      {
        paragraphs: [
          "日頃より株式会社INBESのウェブサイトをご覧いただき、ありがとうございます。2026年10月9日、コーポレートサイトをリニューアルしました。",
          "自社製品をお探しの方と、商品化支援をご検討の方が、それぞれ必要な情報へ進みやすい構成へ見直しました。製品情報やサポートのご案内に加え、商品化の進め方や開発実績もご紹介しています。"
        ]
      },
      {
        heading: "今後の情報発信について",
        paragraphs: [
          "今後も、製品情報や商品化支援、サポートに関するご案内を本サイトで発信してまいります。引き続きよろしくお願いいたします。"
        ]
      }
    ]
  },
  {
    slug: "capital-increase-2025",
    date: "2025.10.01",
    dateTime: "2025-10-01",
    category: "お知らせ",
    title: "資本金増資のご案内",
    summary: "事業拡大および財務基盤の強化を目的として、資本金を1,000万円から5,000万円へ増資しました。",
    sections: [
      {
        paragraphs: [
          "平素は格別のお引き立てを賜り、厚く御礼申し上げます。",
          "当社では、今後の事業拡大および財務基盤の強化を目的として、下記の通り資本金の増資を実施いたしましたので、ご報告申し上げます。"
        ]
      },
      {
        heading: "増資の概要",
        paragraphs: [
          "増資実施日：2025年10月29日",
          "増資前の資本金：1,000万円",
          "増資後の資本金：5,000万円"
        ]
      },
      {
        paragraphs: [
          "今回の増資により、より一層のサービス向上と安定した事業運営を図ってまいります。今後とも変わらぬご支援、ご愛顧を賜りますようお願い申し上げます。"
        ]
      }
    ],
    relatedLink: {
      label: "資本金増資のご案内（PDF）",
      href: "https://inbes.jp/news/pdf/202510_Capital-Increase.pdf"
    }
  }
];
