<?php

namespace App\Exceptions;

use Adldap\Auth\BindException;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Sentry\State\Scope;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthenticationException::class,
        AuthorizationException::class,
        BindException::class,   // LDAP binding exceptions
        HttpException::class,
        ModelNotFoundException::class,
        TokenMismatchException::class,
        ValidationException::class,
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
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $e
     * @return void
     */
    public function report(Throwable $e)
    {
        if ($this->shouldReport($e)) {
            // Send reports to Sentry.io
            if ($this->shouldReportToSentry()) {
                $this->reportToSentry($e);
            }
        }

        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return SymfonyResponse|JsonResponse;
     */
    public function render($request, Throwable $e)
    {
        // Show the LDAP error view if we can't bind to the LDAP server
        if ($e instanceof BindException) {
            return response()->view('errors.ldap', [], 500);
        }

        if ($request->is('api/*')) {
            if ($e instanceof AuthenticationException) {
                return response()->json([
                    'errors' => ["Unauthorized."],
                ], Response::HTTP_UNAUTHORIZED);
            }
            if ($e instanceof ModelNotFoundException) {
                /** @var string */
                $modelname = $e->getModel();
                $model_basename = str_replace('App\\', '', $modelname);

                return response()->json([
                    'errors' => ["Entry for {$model_basename} not found"]
                ], Response::HTTP_NOT_FOUND);
            }
            if ($e instanceof ValidationException) {
                return response()->json([
                    'errors' => $e->errors()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }

        return parent::render($request, $e);
    }

    /**
     * Determine if the exception should be reported to Sentry.
     * 
     * @return bool
     */
    protected function shouldReportToSentry()
    {
        return app()->bound('sentry') && !config('app.debug');
    }

    /**
     * Report the exception to Sentry.io
     * 
     * @param  Throwable $e
     * @return void
     */
    protected function reportToSentry(Throwable $e)
    {
        /** @var \Sentry\State\Hub */
        $sentry = app('sentry');

        if (auth()->check()) {
            \Sentry\configureScope(function (Scope $scope): void {
                $user = auth()->user();
                $scope->setUser([
                    'id' => $user->id,
                    'username' => $user->name,
                    'email' => $user->email,
                ]);
            });
        }

        // $sentry->setEnvironment(app()->environment());
        // $sentry->setRelease($this->revision());

        $sentry->captureException($e);
    }

    /**
     * Get the current revision of the application.
     * 
     * @return string|null
     */
    protected function revision()
    {
        return $this->revisionFromFile() ? : $this->revisionFromGit();
    }

    /**
     * Get the current revisions of the application from a file.
     * 
     * @return string|null
     */
    protected function revisionFromFile()
    {
        $revision_file = base_path() . '/REVISION';

        if (file_exists($revision_file)) {
            return trim(file_get_contents($revision_file));
        }

        return null;
    }

    /**
     * Get the current revision of the application from git.
     * 
     * @return string|null
     */
    protected function revisionFromGit()
    {
        $pwd = getcwd();
        chdir(base_path());
        $output = shell_exec('git rev-parse --verify HEAD');
        chdir($pwd);

        return trim($output);
    }
}
