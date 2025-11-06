import { FRONTEND_URL } from '@/utils/config'
import apiFetch from '@/utils/request'

export default async function handler(req, res) {
  const urls = await apiFetch(`sitemap/all`)
  const sitemap = `<?xml version="1.0" encoding="UTF-8"?>
    <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
      ${urls?.data
        .map((item) => {
          return `
                    <url>
                        <loc>${FRONTEND_URL}/${item.loc}</loc>
                        <lastmod>${item.lastmod}</lastmod>
                        <changefreq>${item.changefreq}</changefreq>
                        <priority>${item.priority}</priority>
                    </url>
                `
        })
        .join('')}
    </urlset>
  `
  res.setHeader('Content-Type', 'application/xml')
  res.status(200).send(sitemap)
}
