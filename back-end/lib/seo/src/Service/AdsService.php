<?php

namespace Newnet\Seo\Service;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Newnet\Seo\Models\Ads;

class AdsService
{
  public function checkCode($code, $hashed)
  {
    if (!$code || !$hashed) {
      return response()->json([
        'message' => 'Vui lòng nhập mã code.',
        'statusCode' => 400,
      ], Response::HTTP_BAD_REQUEST);
    }
    $ads = Ads::whereCode($code)->whereIsActive(true)->first();
    if (!$ads) {
      return response()->json([
        'message' => 'Mã code không chính xác hoặc đã hết hạn.',
        'statusCode' => 400,
      ], Response::HTTP_BAD_REQUEST);
    }
    if (!Hash::check($code, $hashed)) {
      return response()->json([
        'message' => 'Mã code không chính xác, vui lòng kiểm tra lại.',
        'statusCode' => 400,
      ], Response::HTTP_BAD_REQUEST);
    }
    if ($this->isValid($ads)) {
      $ads->update([
        'count' => $ads->count + 1,
      ]);
      return response()->json([
        'statusCode' => 200,
        'message' => 'Thành công',
        'content' => $ads->content,
      ]);
    }
    return response()->json([
      'message' => 'Mã code không chính xác hoặc đã hết hạn.',
      'statusCode' => 400,
    ], Response::HTTP_BAD_REQUEST);
  }

  private function isValid(Ads $ads)
  {
    if (!$ads->valid_from && !$ads->valid_to) {
      return true;
    }
    if ($ads->valid_from && !$ads->valid_to) {
      if (now() > $ads->valid_from) {
        return true;
      }
      return false;
    }
    if (!$ads->valid_from && $ads->valid_to) {
      if (now() < $ads->valid_to) {
        return true;
      }
      return false;
    }

    if ($ads->valid_from && $ads->valid_to) {
      if (now() > $ads->valid_from &&  now() < $ads->valid_to) {
        return true;
      }
      return false;
    }
    return false;
  }
}
