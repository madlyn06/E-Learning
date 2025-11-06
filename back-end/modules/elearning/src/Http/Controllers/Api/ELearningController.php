<?php

declare(strict_types=1);

namespace Modules\Elearning\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;

class ELearningController extends BaseController
{
    public function ping(): JsonResponse
    {
        return $this->successResponse('pong', 'Ping success', 200);
    }
}
