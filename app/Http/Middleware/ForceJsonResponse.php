<?php

namespace App\Http\Middleware {

    use Symfony\Component\HttpFoundation\Response;
    use Illuminate\Http\Request;
    use Closure;

    class ForceJsonResponse
    {
        /**
         * Handle an incoming request.
         *
         * @param Closure(Request): (Response) $next
         */
        public function handle(Request $request, Closure $next): Response
        {
            $request->headers->set('Accept', 'application/json');
            return $next($request);
        }
    }
}
