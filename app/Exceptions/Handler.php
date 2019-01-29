<?php

namespace App\Exceptions;

use Adldap\Auth\BindException;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        if ($this->shouldReport($exception)) {
            // Send reports to Sentry.io
            if ($this->shouldReportToSentry()) {
                $this->reportToSentry($exception);
            }
        }

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        // Show the LDAP error view if we can't bind to the LDAP server
        if ($exception instanceof BindException) {
            return response()->view('errors.ldap', [], 500);
        }

        return parent::render($request, $exception);
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
     * @param  Exception $e
     * @return void
     */
    protected function reportToSentry(Exception $e)
    {
        $sentry = app('sentry');

        $user_context = ['id' => null];
        if (auth()->check()) {
            $user = auth()->user();
            $user_context = [
                'id' => $user->id,
                'username' => $user->name,
                'email' => $user->email,
            ];
        }

        $sentry->setEnvironment(app()->environment());
        $sentry->setRelease($this->revision());
        $sentry->user_context($user_context);

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
