<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Response;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($this->isApiRequest($request)) {
            return $this->handleApiExceptions($exception);
        }

        $functionName = $this->getFunctionFromTrace($exception);
        $lineNumber = $exception->getLine();

        logStore($functionName . ' at line ' . $lineNumber, $exception->getMessage());

        // For web routes, show a friendly error page
        // return response()->view('errors.custom', [], Response::HTTP_INTERNAL_SERVER_ERROR);
        if (app()->environment('production')) {
            return response()->view('errors.custom', [], Response::HTTP_INTERNAL_SERVER_ERROR);
        } else {
            return parent::render($request, $exception); // Keep default in local
        }

    }

    protected function handleApiExceptions(Throwable $exception)
    {
         if ($exception instanceof \Illuminate\Auth\AuthenticationException) {

            return response()->json([
                'status' => Response::HTTP_UNAUTHORIZED,
                'success' => false,
                'message' => '',
                'errorMessage' => $exception->getMessage(),
                'data' => [],
            ], Response::HTTP_UNAUTHORIZED);
        }

        if ($exception instanceof ValidationException) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'success' => false,
                'message' => '',
                'errorMessage' => $exception->getMessage(),
                'data' => [],
            ], Response::HTTP_BAD_REQUEST);
        }

        // Handle other types of exceptions for APIs

        if ($exception instanceof ThrottleRequestsException) {
            return response()->json([
                'status' => Response::HTTP_TOO_MANY_REQUESTS,
                'success' => false,
                'message' => 'Too Many Requests. Please try again later.',
                'errorMessage' => $exception->getMessage(),
                'data' => [],
            ], Response::HTTP_TOO_MANY_REQUESTS);
        }


        $functionName = $this->getFunctionFromTrace($exception);
        $lineNumber = $exception->getLine();

        // Log the error with the function name and line number
        logStore($functionName . ' at line ' . $lineNumber, $exception->getMessage());

        return response()->json([
            'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            'success' => false,
            'errorMessage' => $exception->getMessage(),
            'message' => 'Something went wrong',
            'data' => [],
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    protected function isApiRequest($request)
    {
        return $request->is('api/*') || $request->wantsJson() || $request->expectsJson();
    }

    protected function getFunctionFromTrace(Throwable $exception)
    {
        foreach ($exception->getTrace() as $trace) {
            if (isset($trace['class']) && str_starts_with($trace['class'], 'App\\')) {
                return $trace['function'] ?? 'N/A';
            }
        }
        return $exception->getTrace()[0]['function'] ?? 'N/A';
    }
}
