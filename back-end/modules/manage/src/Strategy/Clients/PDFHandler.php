<?php

namespace Modules\Manage\Strategy\Clients;


use Modules\Manage\Strategy\FileHandlerInterface;

class PDFHandler implements FileHandlerInterface
{
  public function preview($file)
  {
    $pdfPath = $file->getPath();

    return view('pdf.preview', compact('pdfPath'));
  }
}
