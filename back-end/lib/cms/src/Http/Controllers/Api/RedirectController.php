<?php

namespace Newnet\Cms\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Newnet\Seo\Models\ErrorRedirect;

class RedirectController extends Controller
{
    public function checkStatus(Request $request)
    {
        $slug = $request->query('slug');
        if (!$slug) {
            return response()->json([], 400);
        }
        $cacheKey = "redirect_rule:$slug";
        $rule = Cache::remember($cacheKey, 300, function () use ($slug) {
            return ErrorRedirect::where('from_path', $slug)->first();
        });
        if ($rule) {
            return response()->json([
                'from' => $rule->from_path,
                'to' => $rule->to_url,
                'status' => $rule->status_code,
            ]);
        }

        return response()->json([], 204);
    }
}
