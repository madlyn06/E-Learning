<?php

namespace Modules\Manage\Strategy\Clients;

use Modules\Manage\Strategy\FileHandlerInterface;
use PhpOffice\PhpWord\IOFactory;

class DocxHandler implements FileHandlerInterface
{
  public function read($file)
  {
    // Code để đọc file PDF
  }

  public function write($file, $data)
  {
    // Code để viết vào file PDF
  }

  public function preview($file)
  {
    $phpWord = IOFactory::load($file->getPath());
    $htmlWriter = IOFactory::createWriter($phpWord, 'HTML');
    $html = $htmlWriter->saveHTML();
    return view('manage::api.docx', ['html' => $html]);
  }
}
