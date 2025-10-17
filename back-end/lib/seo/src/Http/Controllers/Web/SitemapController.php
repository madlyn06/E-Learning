<?php

namespace Newnet\Seo\Http\Controllers\Web;

use Illuminate\Routing\Controller;
use Newnet\Seo\Models\Url;

class SitemapController extends Controller
{
    public function __invoke()
    {
        $urls = Url::with('urlable')->get();

        return response(view('seo::sitemap', compact('urls'))->render(), 200, [
            'content-type' => 'application/xml',
        ]);
    }
}
