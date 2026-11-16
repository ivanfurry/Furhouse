<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckOrganizer
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->isOrganizer()) {
            abort(403, 'Доступ запрещён. Требуются права организатора.');
        }

        return $next($request);
    }
}