<?php

namespace Modules\Manage\Services;

use Modules\Manage\Models\File;
use Modules\Manage\Models\FileCategory;

class ManageService
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

  /**
   * @param string $type
   * @param integer $itemOnPage
   * @return FileCategory
   */
  public function getLatestDocuments($type, $itemOnPage = 10)
  {
    return File::whereDocType($type)->orderBy('id', 'DESC')->take($itemOnPage)->get();
  }

  public function search($keyword)
  {
    return File::where('name', 'like', '%'.$keyword.'%')->get();
  }
}
