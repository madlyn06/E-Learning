<?php

namespace Newnet\Cms\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Newnet\Cms\Exporter\ExportManager;
use Newnet\Cms\Exporter\PostExporter;
use Newnet\Cms\Factory\ExporterFactory;
use Newnet\Cms\Utils\ConvertUtil;

class ExportController extends Controller
{
  public function index()
  {
    $columnsPost = ConvertUtil::getPostColumns();
    $columnsCCategory = ConvertUtil::getCategoryColumns();
    $columnsECategory = ConvertUtil::getCategoryColumns();
    $columnsProduct = ConvertUtil::getProductColumns();
    $columnsOrder = ConvertUtil::getOrderColumns();
    $columnsDownload = ConvertUtil::getDownloadColumns();
    $item = null;

    return view('cms::admin.export.index', compact(
      'columnsPost',
      'columnsCCategory',
      'columnsECategory',
      'columnsProduct',
      'columnsOrder',
      'columnsDownload',
      'item',
    ));
  }

  public function export(Request $request, ExportManager $exportManager)
  {
    $type = $request->input('data_type');
    $columns = $request->input('columns');
    $fileType = $request->input('file_type');
    if (empty($type)) {
      return redirect()->back()->with('error', __('Vui lòng chọn loại dữ liệu để xuất file'));
    }
    if ($type != 'all' && empty($columns)) {
      return redirect()->back()->with('error', __('Vui lòng chọn các trường để xuất file'));
    }
    if (empty($fileType)) {
      return redirect()->back()->with('error', __('Vui lòng chọn loại file để xuất'));
    }

    $availableColumns = $exportManager->getColumns($type);
    $columns = array_keys($columns);

    $selectedColumns = array_intersect($columns, array_keys($availableColumns));

    if (empty($selectedColumns)) {
      return redirect()->back()->with('error', 'Columns không hợp lệ');
    }

    return $exportManager->export($type, $selectedColumns);
  }
}
