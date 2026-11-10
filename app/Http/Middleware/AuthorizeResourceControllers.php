<?php

namespace App\Http\Middleware {

    use Symfony\Component\HttpFoundation\Response;
    use App\Http\Attributes\AuthorizeMany;
    use Illuminate\Support\Facades\Gate;
    use Illuminate\Http\Request;
    use ReflectionClass;
    use Closure;

    class AuthorizeResourceControllers
    {
        /** @var array|string[] $abilities */
        protected array $abilities = [
            'index'   => 'viewAny',
            'show'    => 'view',
            'create'  => 'create',
            'store'   => 'create',
            'edit'    => 'update',
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

            $reflection = new ReflectionClass($controller);

            $attributes = $reflection->getAttributes(AuthorizeMany::class);

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
