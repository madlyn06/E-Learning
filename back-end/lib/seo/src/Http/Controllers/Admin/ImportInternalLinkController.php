<?php

namespace Newnet\Seo\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Newnet\AdminUi\Facades\AdminMenu;
use Newnet\Seo\Http\Requests\ImportInternalLinkRequest;
use Newnet\Seo\Jobs\ReplaceInternalLinksInPost;
use Newnet\Seo\Models\InternalLink;
use Newnet\Seo\SeoAdminMenuKey;

class ImportInternalLinkController extends Controller
{
    public function index()
    {
        AdminMenu::activeMenu(SeoAdminMenuKey::SEO_INTERAL_LINK);
        $item = null;
        return view('seo::admin.internal-link.import', compact('item'));
    }

    public function import(ImportInternalLinkRequest $request): RedirectResponse
    {
        $keywordsLinks = $request->keywords_links;
        $pairs = explode(';', $keywordsLinks);
        foreach ($pairs as $pair) {
            $pair = trim($pair);
            if (!$pair) continue;
    
            [$keyword, $url] = array_map('trim', explode(',', $pair));
    
            if ($keyword && $url) {
                InternalLink::create([
                    'name' => $keyword,
                    'value' => $url,
                ]);
            }
        }

        ReplaceInternalLinksInPost::dispatch();

        return back()->with('success', 'Đã import toàn bộ internal links!. Hệ thống đang xử lý cho bài viết.');
    }
}
