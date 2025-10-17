<?php

namespace Newnet\Cms\Services\PostImporter;

use Illuminate\Pipeline\Pipeline;
use Newnet\Cms\Services\PostImporter\Pipes\CreateCategory;
use Newnet\Cms\Services\PostImporter\Pipes\CreatePost;
use Newnet\Cms\Services\PostImporter\Pipes\CreateTag;

class PostImportPipeline
{
    public function handle(array $data): mixed
    {
        return app(Pipeline::class)
            ->send($data)
            ->through([
                CreateCategory::class,
                CreateTag::class,
                CreatePost::class,
            ])->thenReturn();
    }
}
