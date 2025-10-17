<?php

namespace Newnet\Cms\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Newnet\Cms\Http\Resources\KeywordableResource;
use Newnet\Cms\Models\Keyword;
use Newnet\Seo\Http\Resources\InternalLinkResource;
use Newnet\Seo\Models\InternalLink;

class KeywordController extends Controller
{
    public function getKeywords(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page', 20);
        $keywords = Keyword::whereHas('keywordables')
            ->with(['keywordables.keywordable'])
            ->paginate($perPage);

        $resKeywords = $keywords->pluck('keywordables')->flatten()->mapInto(KeywordableResource::class)->map->toArray(request())->values();
        $internalLinks = InternalLink::paginate($perPage);
        $internalLinkArrays = $internalLinks->getCollection() ->mapInto(InternalLinkResource::class)
        ->map->toArray(request());
        $merged = collect($resKeywords)->merge($internalLinkArrays)->values();

        return response()->json([
            'current_page' => $keywords->currentPage(),
            'total' => $keywords->total(),
            'per_page' => $keywords->perPage(),
            'data' => $merged
        ]);
    }
}
