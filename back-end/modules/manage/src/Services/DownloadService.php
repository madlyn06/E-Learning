<?php

namespace Modules\Manage\Services;

use Modules\Manage\Models\File;
use Modules\Manage\Models\FileCategory;

class DownloadService
{
  /**
   * Get file categories
   * @param string $type
   */
  public function getFileCategory($type)
  {
    return FileCategory::whereCategoryType($type)->get();
  }

    /**
   * Get files based on type
   * @param string $type
   */
  public function getFiles($type)
  {
    return File::whereDocType($type)->orderBy('id', 'DESC')->paginate(setting('item_on_page', 10));
  }

  public function search($keyword)
  {
    return File::where('name', 'like', '%'.$keyword.'%')->get();
  }
}
