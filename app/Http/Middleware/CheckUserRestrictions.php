<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRestrictions
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return $next($request);
        }

        $user = auth()->user();

        // Проверяем, не истекло ли ограничение
        if ($user->restriction_until && now()->greaterThan($user->restriction_until)) {
            // Сбрасываем все ограничения
            $user->update([
                'can_post_products' => true,
                'can_post_services' => true,
                'can_create_events' => true,
                'can_post_forum' => true,
                'can_comment' => true,
                'restriction_reason' => null,
                'restriction_until' => null,
                'restricted_by' => null,
            ]);
        }

        return $next($request);
    }
}