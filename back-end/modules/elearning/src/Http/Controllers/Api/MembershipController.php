<?php

declare(strict_types=1);

namespace Modules\Elearning\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Modules\Elearning\Http\Requests\Membership\RegisterMembershipRequest;
use Modules\Elearning\Services\MembershipService;
use Symfony\Component\HttpFoundation\Response;

class MembershipController extends Controller
{
    protected $membershipService;

    public function __construct(MembershipService $membershipService)
    {
        $this->membershipService = $membershipService;
    }

    public function register(RegisterMembershipRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $membership = $this->membershipService->register($validated);
        return response()->json([
            'message' => 'Membership registered successfully',
            'membership' => $membership,
        ], Response::HTTP_CREATED);
    }
}
