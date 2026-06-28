<?php

namespace App\Http\Middleware;

use App\Models\AuditLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuditRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->user()) {
            AuditLog::create([
                'user_id' => $request->user()->id,
                'action' => $request->method(),
                'resource_type' => $request->route()?->getName() ?? $request->path(),
                'resource_id' => $request->route('patient')?->id ?? $request->route('id'),
                'ip_address' => $request->ip(),
                'metadata' => [
                    'status' => $response->getStatusCode(),
                    'user_agent' => $request->userAgent(),
                ],
            ]);
        }

        return $response;
    }
}
