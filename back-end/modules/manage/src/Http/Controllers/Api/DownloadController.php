<?php

namespace Modules\Manage\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Manage\Http\Resources\FileDetailResource;
use Newnet\Cms\Utils\EloquentUtils;
use Modules\Manage\Http\Resources\FileCategoryResource;
use Modules\Manage\Http\Resources\FileDocumentResource;
use Modules\Manage\Models\File;
use Modules\Manage\Models\FileCategory;
use Modules\Manage\Services\DownloadService;
use Newnet\Seo\Models\Ads;

class DownloadController extends Controller
{
  protected DownloadService $downloadService;

  public function __construct(DownloadService $downloadService)
  {
    $this->downloadService = $downloadService;
  }

  public function getAllDocuments()
  {
    $keyword = request('keyword');
    if ($keyword) {
      return FileDocumentResource::collection($this->downloadService->search($keyword));
    }
    $documentsCategory = FileCategory::whereIsActive(true)->orderBy('id', 'DESC')->get();
    $files = File::whereIsActive(true)->orderBy('id', 'DESC')->paginate(12);
    return [
      'fileCategories' => FileCategoryResource::collection($documentsCategory),
      'files' => FileDocumentResource::collection($files)->response()->getData(true),
    ];
  }

  /**
   * Get files based on the type
   */
  public function getDocuments($type)
  {
    return [
      'fileCategories' => FileCategoryResource::collection($this->downloadService->getFileCategory($type)),
      'files' => FileDocumentResource::collection($this->downloadService->getFiles($type))->response()->getData(true),
    ];
  }

  public function getDocumentsInCategory($categoryId)
  {
    $query = request('query');
    if($query && $categoryId == 'null') {
      $documentsCategory = FileCategory::whereIsActive(true)->orderBy('id', 'DESC')->get();
      $files = File::whereIsActive(true)->where('name', 'like', "%$query%")->orderBy('id', 'DESC')->paginate(12);
      return [
        'fileCategories' => FileCategoryResource::collection($documentsCategory),
        'files' => FileDocumentResource::collection($files)->response()->getData(true),
      ];
    }
    if (!is_numeric($categoryId)) {
      // Get files by category slug
      $documentsCategory = FileCategory::whereIsActive(true)->orderBy('id', 'DESC')->get();
      $categories = $documentsCategory->filter(function ($item) use ($categoryId) {
        return $item->seourl->request_path === $categoryId;
      });
      if (empty($categories)) {
        return response()->json([
          'message' => 'Category not found',
        ]);
      }
      $category = $categories->first();
      $files = $category->files()->orderBy('id', 'DESC')->paginate(12);
      return [
        'fileCategories' => FileCategoryResource::collection($documentsCategory),
        'files' => FileDocumentResource::collection($files)->response()->getData(true),
      ];
    }
    $category = FileCategory::find($categoryId);
    return FileDocumentResource::collection($category->files()->orderBy('id', 'DESC')->get());
  }

  public function getRelatedFiles($fileId)
  {
    $file = File::find($fileId);
    $catIds = $file->file_categories->pluck('id')->toArray();
    $relatedFiles = File::whereHas('file_categories', function ($query) use ($catIds) {
      $query->whereIn('id', $catIds);
    })->where('id', '!=', $file->id)->orderBy('id', 'DESC')->paginate(15);
    return FileDocumentResource::collection($relatedFiles);
  }

  /**
   * Retrieve the details of a document based on the provided slug.
   *
   * @param string $slug The unique identifier for the document.
   * @return FileDetailResource The response containing the document details.
   */
  public function getDocumentDetail($slug)
  {
    $item = EloquentUtils::transferModel($slug);
    return new FileDetailResource($item);
  }

  public function downloadFile($fileId)
  {
    $file = File::find($fileId);
    $file->increment('donwload_count');
    return response()->download($file->file_path);
  }

  /**
   * Check the provided code from the request.
   *
   * @param \Illuminate\Http\Request $request The incoming HTTP request containing the code to be checked.
   * @return \Illuminate\Http\Response The response indicating the result of the code check.
   */
  public function checkCodeDownload(Request $request)
  {
    $code = $request->input('code');
    $fileId = $request->input('file_id');
    $file = File::find($fileId);
    if ($file->download_code === $code) {
      $ads = Ads::whereCode($code)->first();
      if (!$ads) {
        return response()->json([
          'message' => 'Mã code không tồn tại hoặc đã hết hạn',
          'status' => 'error'
        ]);
      }
      if (!$ads->isValid()) {
        return response()->json([
          'message' => 'Mã code đã hết hạn',
          'status' => 'error'
        ]);
      }
      return response()->json([
        'status' => 200,
        'message' => 'Thành công',
        'metadata' => [
          'content' => $ads->content,
          'name' => $ads->name,
          'title' => $ads->title,
        ],
      ]);
    }
    return response()->json([
      'message' => 'Mã code không tồn tại hoặc đã hết hạn',
      'status' => 'error'
    ]);
  }
}
