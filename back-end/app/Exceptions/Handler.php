<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            return $this->handleApiException($request, $e);
        }

        return parent::render($request, $e);
    }

    /**
     * Handle API exceptions and return consistent JSON responses.
     *
     * @param Request $request
     * @param Throwable $e
     * @return JsonResponse
     */
    private function handleApiException(Request $request, Throwable $e): JsonResponse
    {
        $statusCode = 500;
        $message = 'Internal server error';
        $errors = null;

        if ($e instanceof ValidationException) {
            $statusCode = 422;
            $message = 'Validation failed';
            $errors = $e->errors();
        } elseif ($e instanceof AuthenticationException) {
            $statusCode = 401;
            $message = 'Unauthenticated';
        } elseif ($e instanceof AuthorizationException) {
            $statusCode = 403;
            $message = 'Forbidden';
        } elseif ($e instanceof ModelNotFoundException) {
            $statusCode = 404;
            $message = 'Resource not found';
        } elseif ($e instanceof NotFoundHttpException) {
            $statusCode = 404;
            $message = 'Route not found';
        } elseif ($e instanceof MethodNotAllowedHttpException) {
            $statusCode = 405;
            $message = 'Method not allowed';
        } elseif ($e instanceof QueryException) {
            $statusCode = 500;
            $message = 'Database error';
            
            // Log the actual error for debugging
            \Log::error('Database error: ' . $e->getMessage());
        } elseif ($e instanceof HttpException) {
            $statusCode = $e->getStatusCode();
            $message = $e->getMessage() ?: 'HTTP error';
        }

        // Log unexpected errors
        if ($statusCode === 500) {
            \Log::error('Unexpected error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        $response = [
            'success' => false,
            'message' => $message,
            'timestamp' => now()->toISOString(),
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        // Include debug information in development
        if (config('app.debug')) {
            $response['debug'] = [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTrace()
            ];
        }

        return response()->json($response, $statusCode);
    }
}
