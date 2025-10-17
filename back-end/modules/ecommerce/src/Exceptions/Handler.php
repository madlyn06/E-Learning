<?php

namespace Modules\Ecommerce\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Throwable;
use Modules\Ecommerce\Exceptions\CartException;
use Modules\Ecommerce\Exceptions\CouponException;

class Handler extends ExceptionHandler
{
  /**
   * A list of the exception types that are not reported.
   *
   * @var array
   */
  protected $dontReport = [
    // Add your custom exceptions here if you don't want them to be reported
    CartException::class,
    CouponException::class,
    OrderException::class,
    ProductException::class,
  ];

  /**
   * Render an exception into an HTTP response.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Throwable  $exception
   * @return \Illuminate\Http\Response
   */
  public function render($request, Throwable $exception)
  {
    if (
      $exception instanceof CartException ||
      $exception instanceof CouponException ||
      $exception instanceof OrderException ||
      $exception instanceof ProductException
    ) {
      return response()->json([
        'status' => false,
        'message' => $exception->getMessage()
      ], Response::HTTP_BAD_REQUEST);
    }

    return parent::render($request, $exception);
  }
}
