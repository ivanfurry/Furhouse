<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaster
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->isMaster()) {
            abort(403, 'Доступ запрещён. Требуются права мастера.');
        }

        return $next($request);
    }
}