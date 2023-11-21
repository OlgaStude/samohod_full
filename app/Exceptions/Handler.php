<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
        $this->renderable(function (AuthenticationException $e, $request) {
            if ($request->is('api-samohod/*')) {
                return response()->json([
                    'warning' => [
                        "code" => 403,
                        'message' => 'Гостевой доступ запрещен'
                    ]


                ], 403)->header('status', '403');
            }
        });
        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('api-samohod/*')) {
                return response()->json([
                    'code' => '404',
                    'message' => 'Не найдено'
                ], 404)->header('status', '404');
            }
        });
        $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
            if ($request->is('api-samohod/*')) {
                return response()->json([
                    'code' => '404',
                    'message' => 'Не найдено'
                ], 404)->header('status', '404');
            }
        });
    }
}
