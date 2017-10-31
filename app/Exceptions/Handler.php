<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Database\QueryException;


class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,

    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
      // Jika authorize gate gagal
      if ($e instanceof AuthorizationException)
      {
        // struktur json
        // message dan HTTP code (403)
        return response()->json(['success' => false, 'status'=> 403, 'error' => ['exception' => 'Forbidden']], 403);
      }

      // Jika Route tidak ditemukan
      if ($e instanceof NotFoundHttpException)
      {
        return response()->json(['success' => false, 'status'=> 404, 'error' => ['exception' => 'Http not found']], 404);
      }

      // Jika Method tidak diijikan
      if ($e instanceof MethodNotAllowedHttpException)
      {
        return response()->json(['success' => false, 'status'=> 405, 'error' => ['exception' => 'Method not allowed']], 405);
      }

      // jika error query
      if ($e instanceof QueryException)
      {
        return response()->json(['success' => false, 'status'=> 500, 'error' => ['exception' => $e->getMessage()]], 500);
      }

      return parent::render($request, $e);
      // if ($e instanceof )


      // return response()->json(['success' => false, 'status'=> '500', 'error' => $e->getMessage()], 500);
    }
}
