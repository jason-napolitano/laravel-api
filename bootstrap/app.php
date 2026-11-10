<?php

use App\Http\Middleware\AuthorizeResourceControllers;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Configuration;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: [
            __DIR__ . '/../routes/guarded.php',
            __DIR__ . '/../routes/auth.php',
        ]
    )
    ->withMiddleware(function (Configuration\Middleware $middleware): void {
        $middleware->alias([
            'authorize-resources' => AuthorizeResourceControllers::class,
        ]);
    })
    ->withExceptions(function (Configuration\Exceptions $exceptions): void {
        // convert all errors to JSON
        $exceptions->shouldRenderJsonWhen(
            fn(Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        // format error message returned by the response
        $exceptions->respond(function (Response $response) {
            $content = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR) ?? [];

            $data = [
                'message' => $content['message'] ?? Response::$statusTexts[$response->getStatusCode()] ?? 'An unexpected error occurred.',
                'status' => $response->getStatusCode(),
            ];

            // include validation errors when available
            if (!empty($content['errors'])) {
                $data['errors'] = $content['errors'];
            }

            return response()->json($data, $response->getStatusCode());
        });
    })->create();
