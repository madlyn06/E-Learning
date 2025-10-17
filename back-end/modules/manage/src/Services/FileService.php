<?php

namespace Modules\Manage\Services;

use GuzzleHttp\Client;
use Modules\Manage\Exceptions\FileException;
use Newnet\Media\Models\Media;
use Swagger\Client\Api\ConvertDocumentApi;
use Swagger\Client\Configuration;

class FileService
{
  public function convertDocToDocx(Media $file)
  {
    if ($file->mime_type == 'application/msword') {
      $docInputFilePath = $file->getFullPath();
      $fileName = pathinfo($file->file_name, PATHINFO_FILENAME);
      $outputDocxFilePath = $file->filesystem()->path($file->getDirectory()) . '/' . $fileName . '.docx';

      $config = Configuration::getDefaultConfiguration()->setApiKey('Apikey', config('app.api_key_convert_file'));
      $apiInstance = new ConvertDocumentApi(new Client(), $config);
      try {
        $result = $apiInstance->convertDocumentDocToDocx($docInputFilePath);
        file_put_contents($outputDocxFilePath, $result);
        $file->update([
          'file_name' => $fileName . '.docx',
          'mime_type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ]);
      } catch (FileException $e) {
        echo 'Exception when calling ConvertDocumentApi->convertDocumentDocxToPdf: ', $e->getMessage(), PHP_EOL;
        exit;
      }
    }
  }
}
