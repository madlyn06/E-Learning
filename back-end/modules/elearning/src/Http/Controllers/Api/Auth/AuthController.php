<?php

declare(strict_types=1);

namespace Modules\Elearning\Http\Controllers\Api\Auth;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Elearning\Http\Requests\Auth\ForgotPasswordRequest;
use Modules\Elearning\Http\Requests\Auth\RegisterRequest;
use Modules\Elearning\Http\Requests\Auth\LoginRequest;
use Modules\Elearning\Http\Resources\UserResource;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(\Modules\Elearning\Services\AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        try {
            $result = $this->authService->register($request->only(['name', 'email', 'password']));
            return response()->json([
                'message' => 'User successfully registered',
                'token' => $result['token'],
                'token_type' => 'bearer',
                'user' => new UserResource($result['user']),
            ], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Failed to register user: ' . $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login($request->only(['email', 'password']), $request);
        if (isset($result['error'])) {
            return response()->json([
                'status' => false,
                'message' => $result['error']
            ], $result['code']);
        }
        return response()->json([
            'message' => 'User successfully logged in',
            'token' => $result['token'],
            'token_type' => 'bearer',
            'user' => new UserResource($result['user']),
        ], Response::HTTP_OK);
    }

    public function forgotPassword(ForgotPasswordRequest $request): \Illuminate\Http\JsonResponse
    {
        $result = $this->authService->forgotPassword($request->only(['email']));
        if (isset($result['error'])) {
            return response()->json([
                'message' => $result['error']
            ], $result['code']);
        }
        return response()->json($result, Response::HTTP_OK);
    }

    public function resetPassword(\Modules\Elearning\Http\Requests\Auth\ResetPasswordRequest $request): JsonResponse
    {
        $result = $this->authService->resetPassword($request->only(['email', 'token', 'password', 'password_confirmation']));
        if (isset($result['error'])) {
            return response()->json([
                'message' => $result['error']
            ], $result['code']);
        }
        return response()->json($result, Response::HTTP_OK);
    }
}
