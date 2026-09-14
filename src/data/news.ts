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

// 仮原稿。公開前に日付と本文を実際のお知らせへ差し替える。
export const newsItems: NewsItem[] = [
  {
    slug: "corporate-site-renewal",
    date: "2026.09.13",
    dateTime: "2026-09-13",
    category: "お知らせ",
    title: "コーポレートサイト リニューアルのお知らせ",
    summary: "INBESの事業と製品情報をより分かりやすくお伝えするため、コーポレートサイトをリニューアルしました。",
    sections: [
      {
        paragraphs: [
          "株式会社INBESのコーポレートサイトをリニューアルしました。",
          "自社製品をお探しの方と、商品化支援をご検討の方が、それぞれ必要な情報へ進みやすい構成へ見直しています。"
        ]
      },
      {
        heading: "今後の情報発信について",
        paragraphs: [
          "製品情報、商品化支援の実績、サポートに関するご案内などを、本サイトで随時お知らせします。"
        ]
      }
    ]
  },
  {
    slug: "product-information-update",
    date: "2026.09.13",
    dateTime: "2026-09-13",
    category: "製品情報",
    title: "自社製品の情報を更新しました",
    summary: "製品一覧ページの掲載情報を更新し、製品を用途別に探しやすく整理しました。",
    sections: [
      {
        paragraphs: [
          "ポータブル電源、生活家電、スマートフォン周辺機器、車載機器など、INBESの自社製品をカテゴリ別にご覧いただけます。",
          "各製品カードから、詳細な仕様や販売情報を掲載した製品ページへ進めます。"
        ]
      }
    ],
    relatedLink: {
      label: "製品一覧を見る",
      href: "/products/"
    }
  },
  {
    slug: "development-works-update",
    date: "2026.09.13",
    dateTime: "2026-09-13",
    category: "商品化支援",
    title: "商品化支援の開発実績を更新しました",
    summary: "商品化支援で対応した実績と、各案件の支援内容を更新しました。",
    sections: [
      {
        paragraphs: [
          "商品の仕様整理、ローカライズ、ブランド開発、パッケージ、輸入支援など、案件ごとの取り組みを掲載しています。",
          "実際の商品化でどのような支援が可能か、ご検討時の参考としてご覧ください。"
        ]
      }
    ],
    relatedLink: {
      label: "開発実績を見る",
      href: "/works/"
    }
  }
];
