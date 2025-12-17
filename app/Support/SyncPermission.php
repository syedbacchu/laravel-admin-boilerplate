<?php


namespace App\Support;

use Illuminate\Support\Facades\Route;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SyncPermission
{

    public static function sync(Request $request): array
    {
        $guard = $request->is('api/*') ? 'api' : 'web';

        $routes = Route::getRoutes();

        foreach ($routes as $route) {

            $name = $route->getName();

            // Skip unnamed routes
            if (!$name || !str_contains($name, '.')) {
                continue;
            }

            // Optional: Only admin routes
//            if (!str_starts_with($route->uri(), 'admin')) {
//                continue;
//            }

            if (collect($route->middleware())->contains('skip.permission')) {
                continue;
            }

            [$module] = explode('.', $name);

            Permission::firstOrCreate(
                [
                    'slug'  => $name,
                    'guard' => $guard,
                ],
                [
                    'name'  => formatPermissionName($name),
                    'module' => $module,
                ]
            );
        }

        return sendResponse(true, __('Permissions synced successfully.'));
    }

}
