<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (Throwable $e, $request) {
            if ($e instanceof HttpExceptionInterface) {
                return response()->view(
                    'errors::' . $e->getStatusCode(),
                    [
                        'exception' => $e,
                    ],
                    $e->getStatusCode(),
                    $e->getHeaders()
                );
            }

            return null;
        });
    }
}
