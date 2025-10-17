<?php

namespace Newnet\Cms\Utils;

use Illuminate\Support\Facades\DB;
use Newnet\Cms\Actions\HandleRouteAction;
use Newnet\Core\Utils\Common;

class EloquentUtils
{
  public static function updateLatestItem($model, $field, $key)
  {
    $latestPost = $model::whereNotNull($field)->latest()->first();
    if ($latestPost) {
      DB::table('latest_items')->where(['name' => $key])->update([
        'value' => $latestPost->$field,
      ]);
    } else {
      DB::table('latest_items')->where(['name' => $key])->delete();
    }
  }

  public static function transferModel(string $slug)
  {
    $path = Common::convertSlug($slug);
    $path = ltrim(rtrim($path, '/'), '/') ?: '/';
    $targetPath = HandleRouteAction::getTargetPath($path);
    if (!$targetPath) {
      return response()->json([
        'message' => 'Page not found',
      ], 400);
    }
    $targetObject = (new ($targetPath->urlable_type)());
    return $targetObject->find($targetPath->urlable_id);
  }
}
