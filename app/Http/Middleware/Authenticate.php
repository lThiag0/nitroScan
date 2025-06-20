<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('login');
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Usuário não autenticado.',
        ], 401);
    }
}
