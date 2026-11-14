<?php

namespace App\Http\Middleware {

    use Illuminate\Routing\Attributes\Controllers\Authorize;
    use Symfony\Component\HttpFoundation\Response;
    use App\Http\Attributes\AuthorizeController;
    use Illuminate\Support\Facades\Gate;
    use Illuminate\Http\Request;
    use ReflectionMethod;
    use ReflectionClass;
    use Closure;

    class AuthorizeResourceControllers
    {
        /** @var array|string[] $abilities */
        protected array $abilities = [
            'index'   => 'viewAny',
            'show'    => 'view',
            'store'   => 'create',
            'update'  => 'update',
            'destroy' => 'delete',
            'restore' => 'restore',
        ];

        /**
         * Handle the incoming request
         *
         * @param Request $request
         * @param Closure $next
         *
         * @return Response
         */
        public function handle(Request $request, Closure $next): Response
        {
            $route = $request->route();

            $controller = $route->getController();

            $method = $route->getActionMethod();

            // Check for Laravel's native Authorize attribute.
            $reflection = new ReflectionMethod($controller, $method);

            if ($reflection->getAttributes(Authorize::class)) {
                return $next($request);
            }

            $reflection = new ReflectionClass($controller);

            $attributes = $reflection->getAttributes(AuthorizeController::class);

            if (empty($attributes)) {
                return $next($request);
            }

            $attribute = $attributes[0]->newInstance();

            $ability = $this->abilities[$method] ?? null;

            if (!$ability) {
                return $next($request);
            }

            $argument = in_array($method, ['index', 'store'])
                ? $attribute->model
                : $route->parameter($attribute->parameter);

            Gate::authorize($ability, $argument);

            return $next($request);
        }
    }
}
