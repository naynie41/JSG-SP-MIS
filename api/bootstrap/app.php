<?php

declare(strict_types=1);

use App\Http\Middleware\AssignCorrelationId;
use App\Http\Middleware\CheckPermission;
use App\Http\Middleware\EnforceIdleTimeout;
use App\Http\Middleware\SecurityHeaders;
use App\Support\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Correlation id first (so it is available to everything), security
        // headers on every API response.
        $middleware->api(prepend: [
            AssignCorrelationId::class,
        ], append: [
            SecurityHeaders::class,
        ]);

        // Route middleware aliases: Sanctum ability checks + idle-timeout guard.
        $middleware->alias([
            'abilities' => CheckAbilities::class,
            'ability' => CheckForAnyAbility::class,
            'idle.timeout' => EnforceIdleTimeout::class,
            'permission' => CheckPermission::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Render all API exceptions through the standard error envelope.
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson()
        );

        $exceptions->render(function (ValidationException $e, Request $request) {
            if (! ($request->is('api/*') || $request->expectsJson())) {
                return null;
            }

            $details = [];
            foreach ($e->errors() as $field => $messages) {
                foreach ((array) $messages as $message) {
                    $details[] = ['field' => $field, 'message' => $message];
                }
            }

            return ApiResponse::error('VALIDATION_ERROR', 'The request is invalid.', $details, 422);
        });

        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if (! ($request->is('api/*') || $request->expectsJson())) {
                return null;
            }

            return ApiResponse::error('UNAUTHENTICATED', 'Authentication is required.', [], 401);
        });

        // Policy/Gate denials surface as AuthorizationException, which Laravel
        // converts to AccessDeniedHttpException before render callbacks run.
        $exceptions->render(function (AccessDeniedHttpException $e, Request $request) {
            if (! ($request->is('api/*') || $request->expectsJson())) {
                return null;
            }

            return ApiResponse::error('FORBIDDEN', 'You do not have permission to perform this action.', [], 403);
        });

        $exceptions->render(function (ThrottleRequestsException $e, Request $request) {
            if (! ($request->is('api/*') || $request->expectsJson())) {
                return null;
            }

            return ApiResponse::error('TOO_MANY_REQUESTS', 'Too many attempts. Please try again later.', [], 429);
        });

        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if (! ($request->is('api/*') || $request->expectsJson())) {
                return null;
            }

            return ApiResponse::error('NOT_FOUND', 'The requested resource was not found.', [], 404);
        });

        $exceptions->render(function (HttpExceptionInterface $e, Request $request) {
            if (! ($request->is('api/*') || $request->expectsJson())) {
                return null;
            }

            return ApiResponse::error('HTTP_ERROR', $e->getMessage() ?: 'Request could not be processed.', [], $e->getStatusCode());
        });
    })->create();
