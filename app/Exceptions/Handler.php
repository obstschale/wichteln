<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable $throwable
     * @return void
     */
    public function report(\Throwable $throwable)
    {
        parent::report($throwable);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Throwable $throwable
     * @return \Illuminate\Http\Response
     */
    public function render($request, \Throwable $throwable)
    {
        // If the request wants JSON (AJAX doesn't always want JSON)
        //        if ($request->wantsJson()) {
        //            // Define the response
        //            $response = [
        //                'errors' => 'Sorry, something went wrong.'
        //            ];
        //
        //            // If the app is in debug mode
        //            if (config('app.debug')) {
        //                // Add the exception class name, message and stack trace to response
        //                $response['exception'] = get_class($exception); // Reflection might be better here
        //                $response['message'] = $exception->getMessage();
        //                $response['trace'] = $exception->getTrace();
        //            }
        //
        //            // Default response of 400
        //            $status = 400;
        //
        //            // If this exception is an instance of HttpException
        //            if ($exception instanceof HttpException) {
        //                // Grab the HTTP status code from the Exception
        //                $status = $exception->getStatusCode();
        //            } elseif ($exception instanceof ModelNotFoundException) {
        //                $status = 404;
        //                $response['errors'] = 'Not Found.';
        //            } elseif ($exception instanceof AuthorizationException) {
        //                $status = 403;
        //                $response['errors'] = 'You don\'t have permission to do this.';
        //            } elseif ($exception instanceof AuthenticationException) {
        //                $status = 401;
        //                $response['errors'] = 'Unauthenticated request.';
        //            }

        // Return a JSON response with the response array and status code
        //            return response()->json($response, $status);
        //        }
        return parent::render($request, $throwable);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Illuminate\Auth\AuthenticationException $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest('login');
    }
}
