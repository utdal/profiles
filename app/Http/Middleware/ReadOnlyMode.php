<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReadOnlyMode
{
    /**
     * Named routes that remain available (GET/HEAD only) in read-only mode.
     *
     * @var array<string>
     */
    public const ALLOWED_ROUTES = [
        'profiles.home',
        'profiles.index',
        'profiles.show',
        'profiles.by_id',
        'schools.show',
        'app.faq',
        'app.maintenance',
    ];

    /**
     * Query parameters that make an allowed route unavailable in read-only mode,
     * e.g. browsing all profiles is allowed but searching them is not.
     *
     * @var array<string, array<string>>
     */
    public const DISALLOWED_PARAMETERS = [
        'profiles.index' => ['search'],
    ];

    /**
     * Livewire components, and the methods that may be called on them, in read-only mode.
     *
     * @var array<string, array<string>>
     */
    public const ALLOWED_LIVEWIRE_METHODS = [
        'profile-data-card' => ['gotoPage', 'nextPage', 'previousPage', 'setPage'],
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!config('app.read_only')) {
            return $next($request);
        }

        // end any sessions that were logged in before read-only mode was enabled
        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        if ($this->isAllowedRoute($request) || $this->isAllowedLivewireRequest($request)) {
            return $next($request);
        }

        if ($request->isMethodSafe() && !$request->expectsJson()) {
            return redirect()->route('app.maintenance');
        }

        abort(503, 'This site is currently in read-only mode.');
    }

    /**
     * Whether the request is a GET/HEAD to an allowed named route, without any disallowed parameters.
     */
    protected function isAllowedRoute(Request $request): bool
    {
        $route_name = $request->route()?->getName();

        return $request->isMethodSafe()
            && in_array($route_name, static::ALLOWED_ROUTES, true)
            && !$request->anyFilled(static::DISALLOWED_PARAMETERS[$route_name] ?? []);
    }

    /**
     * Whether the request is a Livewire update that only calls allowed methods on an allowed component.
     */
    protected function isAllowedLivewireRequest(Request $request): bool
    {
        if ($request->route()?->getName() !== 'livewire.message') {
            return false;
        }

        // Livewire resolves the component from the payload fingerprint, not the URL
        $component = $request->input('fingerprint.name');
        $allowed_methods = static::ALLOWED_LIVEWIRE_METHODS[$component] ?? null;

        if ($allowed_methods === null || $request->route('name') !== $component) {
            return false;
        }

        $updates = $request->input('updates', []);

        return is_array($updates) && collect($updates)->every(fn ($update) =>
            ($update['type'] ?? null) === 'callMethod'
            && in_array($update['payload']['method'] ?? null, $allowed_methods, true)
        );
    }
}
