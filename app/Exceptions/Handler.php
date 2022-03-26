<?php

namespace App\Exceptions;

use App\Models\User;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        GeneralException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param \Exception $exception
     *
     * @throws \Exception
     *
     * @return mixed|void
     */
    public function report(Exception $exception)
    {
        if ($this->shouldReport($exception)) {
            // Send mail error to editor if production
            if (app()->environment('production') || true) {
                try {
                    User::find(1)->notify(new \App\Notifications\ExceptionOccurred(['message' => $exception->getMessage(), 'trace' => $exception->getTraceAsString(), 'user' => auth()->user() ? auth()->user() : User::find(1), 'url' => url()->current()]));
//                    if($mail = config('app.editor_alert_mail')) Mail::to($mail)->send(new ExceptionOccurred($exception));
                } catch (Exception $ex) {
                }
            }
        }

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception               $exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $exception)
    {
        /*
         * All instances of GeneralException redirect back with a flash message to show a bootstrap alert-error
         */
        if ($exception instanceof GeneralException) {
            if ($request->expectsJson()) {
                return response()->json(['message' => $exception->getMessage()], 500);
            }

            return redirect()->back()->withInput()->withFlashError($exception->getMessage());
        }

        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param \Illuminate\Http\Request                 $request
     * @param \Illuminate\Auth\AuthenticationException $exception
     *
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('login'));
    }
}
