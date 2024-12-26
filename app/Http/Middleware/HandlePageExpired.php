<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandlePageExpired
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        if($response->getStatusCode() === 419) {
            return redirect()
                ->route('login')
                ->with('info', 'Sesi anda telah berakhir, silahkan login kembali.');
        }

        return $response;
    }
}
