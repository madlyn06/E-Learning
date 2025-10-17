<?php

namespace Newnet\Seo\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Newnet\Seo\Models\Ads;
use Newnet\Seo\Service\AdsService;

class AdsController extends Controller
{
  private AdsService $adsService;

  public function __construct(AdsService $adsService)
  {
    $this->adsService = $adsService;
  }

  public function checkCode(Request $request)
  {
    return $this->adsService->checkCode($request->code, $request->hashed);
  }

  public function getContent($id)
  {
    $ads = Ads::whereId($id)->whereIsActive(true)->first();
    if (!$ads) {
      return response()->json([
        'message' => '<p style="margin-left: 1px">Mã code không chính xác hoặc đã hết hạn.</p>',
        'statusCode' => 400,
      ]);
    }
    if ($ads->isValid()) {
      $ads->update([
        'count' => $ads->count + 1,
      ]);

      $adsItem = null;
      if ($ads->adsItems->isNotEmpty()) {
        $adsItem = $ads->adsItems->random();
      }
      return response()->json([
        'statusCode' => 200,
        'message' => 'Thành công',
        'content' => view('seo::web.page-guide', compact('ads', 'adsItem'))->render(),
      ]);
    }
    return response()->json([
      'message' => '<p style="margin-left: 1px">Mã code không chính xác hoặc đã hết hạn.</p>',
      'statusCode' => 400,
    ]);
  }
}
