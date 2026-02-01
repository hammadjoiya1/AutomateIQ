<?php

namespace App\Http\Middleware;

use App\Models\AdminAuditLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuditLogger
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $user = $request->user();
        if (!$user || $user->role !== 'admin') {
            return $response;
        }

        if (!in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            return $response;
        }

        AdminAuditLog::create([
            'user_id' => $user->id,
            'action' => $request->route()?->getName() ?? 'admin_action',
            'route_name' => $request->route()?->getName(),
            'method' => $request->method(),
            'path' => $request->path(),
            'payload' => $request->except(['password', 'password_confirmation']),
            'ip_address' => $request->ip(),
        ]);

        return $response;
    }
}
