// pages/api/app-data.js
import crypto from 'crypto';
import { API_KEY, API_URL } from '@/utils/config';

export default async function handler(req, res) {
    try {
        const headers = {
            'X-API-Key': API_KEY,
            'Accept': 'application/json',
        };

        const [menuHeader, menuFooter, categories] = await Promise.all([
            fetch(`${API_URL}/menu/header`, { cache: 'no-store', headers }),
            fetch(`${API_URL}/menu/footer`, { cache: 'no-store', headers }),
            fetch(`${API_URL}/v1/elearning/categories`, { cache: 'no-store', headers }),
        ]);

        if (!menuHeader.ok) throw new Error('menu upstream failed');

        // Raw responses
        const rawMenu = menuHeader.ok ? await menuHeader.json().catch(() => ({})) : {};
        const rawFooter = menuFooter.ok ? await menuFooter.json().catch(() => ({})) : {};
        const rawCats = categories.ok ? await categories.json().catch(() => ({})) : {};
        const payload = {
            menu: {
                items: Array.isArray(rawMenu?.data) ? rawMenu.data : Array.isArray(rawMenu) ? rawMenu : [],
            },
            footer: {
                items: Array.isArray(rawFooter?.data) ? rawFooter.data : Array.isArray(rawFooter) ? rawFooter : [],
            },
            categories: {
                tree: Array.isArray(rawCats?.tree) ? rawCats.tree : Array.isArray(rawCats) ? rawCats : [],
            },
            ts: Date.now(),
        };

        const etag = `"${crypto.createHash('md5').update(JSON.stringify(payload)).digest('hex')}"`;
        if (req.headers['if-none-match'] === etag) {
            res.statusCode = 304;
            return res.end();
        }

        res.setHeader('Cache-Control', 'public, s-maxage=300, stale-while-revalidate=1200');
        res.setHeader('ETag', etag);

        return res.status(200).json(payload);
    } catch (e) {
        res.setHeader('Cache-Control', 'no-store');
        return res.status(200).json({
            menu: { items: [] },
            footer: { items: [] },
            categories: { tree: [] },
            _error: 'fallback',
            ts: Date.now(),
        });
    }
}
