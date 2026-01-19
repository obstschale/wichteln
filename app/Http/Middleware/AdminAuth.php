<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $adminPassword = config('app.admin_password');

        if (empty($adminPassword)) {
            abort(403, 'Admin access is disabled.');
        }

        if ($request->session()->get('admin_authenticated')) {
            return $next($request);
        }

        if ($request->isMethod('post') && $request->input('password') === $adminPassword) {
            $request->session()->put('admin_authenticated', true);

            return redirect()->route('admin.dashboard');
        }

        if ($request->isMethod('get') && !$request->session()->get('admin_authenticated')) {
            return response()->view('admin.login', [], 401);
        }

        return response()->view('admin.login', ['error' => 'Invalid password'], 401);
    }
}
