<?php

namespace Modules\Manage\Strategy;

use Modules\Manage\Strategy\Clients\DocHandler;
use Modules\Manage\Strategy\Clients\DocxHandler;
use Modules\Manage\Strategy\Clients\ExcelHandler;
use Modules\Manage\Strategy\Clients\PDFHandler;
use Modules\Manage\Strategy\Clients\XlsxHandler;

class FileHandlerFactory
{
  public static function createHandler($file)
  {
    if (self::isPDF($file)) {
      return new PDFHandler();
    } elseif (self::isDOCX($file)) {
      return new DocxHandler();
    } elseif (self::isDOC($file)) {
      return new DocHandler();
    }  elseif (self::isXLSX($file)) {
      return new XlsxHandler();
    }elseif (self::isExcel($file)) {
      return new ExcelHandler();
    }
  }

  private static function isPDF($file)
  {
    return $file->mime_type == 'application/pdf';
  }

  private static function isDOCX($file)
  {
    return $file->mine_type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
  }

  private static function isXLSX($file)
  {
    return $file->mine_type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
  }

  private static function isDOC($file)
  {
    return $file->mime_type == 'application/msword';
  }

  private static function isExcel($file)
  {
    return $file->mime_type == 'application/vnd.ms-excel';
  }
}
