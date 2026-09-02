User-agent: *
Disallow: /

Allow: /games/*/*$
Allow: /developers/*/*$

# Явно закрываем "похожие" по структуре маршруты
# (у них тоже вида /games/x/y или /developers/x/y,
# поэтому без этих правил они попали бы под Allow выше)
Disallow: /games/*/latest$
Disallow: /developers/*/latest$
Disallow: /developers/*/*/games$

Sitemap: {{ url('/sitemap.xml') }}
