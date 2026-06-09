<?php

namespace App\Http\Services\Response;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse as HttpWebResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Route;
use Throwable;

class ResponseService
{
    public static function send(
        array $payload = [],
        ?string $route = null,
        Factory|View|string|null $view = null,
        array $routeQuery = [],
        ?string $successRoute = null
    ): mixed {
        $request = app('request');
        $isApi = $request->is('api/*') || $request->wantsJson() || $request->expectsJson();

        // Extract nested response if exists
        $res = $payload['response'] ?? $payload;

        $success = $res['success'] ?? null;
        $message = $res['message'] ?? '';
        $data = $res['data'] ?? [];
        if(isset($payload['data'])) {
            $data['data'] = $payload['data'];
        }
        $status = $res['status'] ?? 200;
        $error = $res['error_message'] ?? '';

        // 🔹 API response
        if ($isApi) {
            return self::makeApiResponse($res);
        }



        // 🔹 If success=false -> redirect back + dismiss
        if ($success === false) {
            if ($route && Route::has($route)) {
                return redirect()
                    ->route($route, $routeQuery)
                    ->withInput()
                    ->with('dismiss', $message ?: __('Failed'));
            }

            return redirect()->back()
                ->withInput()
                ->with('dismiss', $message ?: __('Failed'));
        }
        // 🔹 If view provided -> render view
        if ($view) {
            return view($view, $data);
        }

        // 🔹 If success=true + route exists -> redirect to route
        if ($success === true && $successRoute && Route::has($successRoute)) {
            return redirect()
                ->route($successRoute, $routeQuery)
                ->with('success', $message ?: __('Success'));
        }

        // 🔹 If success=true but no route -> redirect back
        if ($success === true && !$successRoute) {
            return redirect()->back()->with('success', $message ?: __('Success'));
        }

        // 🔹 Fallback (nothing provided)
        return redirect()->back()->with('dismiss', __('Unexpected response.'));
    }

    protected static function makeApiResponse(array $res): JsonResponse
    {
        $response = response()->json([
            'success'       => $res['success'] ?? false,
            'message'       => $res['message'] ?? '',
            'data'          => $res['data'] ?? [],
            'status'        => $res['status'] ?? 400,
            'error_message' => $res['error_message'] ?? ($res['success'] ? '' : ($res['message'] ?? 'Error')),
            'timestamp'     => now()->toIso8601String(), // Add timestamp to prevent caching
        ], $res['status'] ?? 400);

        // Aggressive cache busting headers
        return $response
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate, max-age=0, private')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0')
            ->header('X-Accel-Expires', '0') // Nginx specific
            ->header('Last-Modified', gmdate('D, d M Y H:i:s') . ' GMT'); // Always set current time
    }

    public static function exception(Throwable $e)
    {
        $res = [
            'success' => false,
            'message' => 'Something went wrong!',
            'data' => [],
            'status' => 500,
            'error_message' => $e->getMessage(),
        ];

        if (function_exists('logStore')) {
            logStore(__METHOD__ . ' at line ' . $e->getLine(), $e->getMessage());
        }

        $isApi = app('request')->is('api/*') || app('request')->wantsJson() || app('request')->expectsJson();

        if ($isApi) {
            return self::makeApiResponse($res);
        }

        return redirect()->back()->with('dismiss', __('Something went wrong! Please try again.'));
    }
}
