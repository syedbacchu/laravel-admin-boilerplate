<?php

use App\Http\Services\Response\Viewed;
use Illuminate\Support\Facades\Log;
use App\Support\Settings;
use Illuminate\Support\Str;

function logStore($type, $text = '', $timestamp = true): void
{
    if(gettype($text) == 'array'){
        $text = json_encode($text);
    }
    if ($timestamp) {
        $datetime = date("d-m-Y H:i:s");
        $text = "$datetime, $type: $text \r\n\r\n";
    } else {
        $text = "$type\r\n\r\n";
    }
    Log::info($text);
}

function somethingWrong($text=null) {
    return isset($text) ? $text : __('Something went wrong');
}

function defaultInputIcon() {
    $html = "";
    $html .= '<div class="bg-[#eee] flex justify-center items-center ltr:rounded-l-md rtl:rounded-r-md px-3 border ltr:border-r-0 rtl:border-l-0 border-[#e0e6ed] dark:border-[#17263c] dark:bg-[#1b2e4b]">';
    $html .= '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 dark:text-gray-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">';
    $html .= '<path d="M12 20h9" /><path d="M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4 12.5-12.5z" /></svg>';
    $html .= '</div>';
    return $html;
}


if (! function_exists('action_buttons')) {
    /**
     * Render a group of action buttons.
     *
     * @param array $buttons
     * @return string
     */
    function action_buttons(array $buttons): string
    {
        return '<div class="flex gap-2 justify-center">'.implode('', $buttons).'</div>';
    }
}

if (! function_exists('edit_column')) {
    function edit_column(string $route, string $label = 'Edit'): string
    {
        return <<<HTML
        <a href="{$route}"
            title="{$label}"
            class="inline-flex items-center justify-center w-8 h-8 text-blue-600 hover:text-white hover:bg-blue-600 border border-blue-600 rounded-lg transition duration-200"
        >
            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-4 h-4"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15.232 5.232l3.536 3.536M9 11l6.232-6.232a2 2 0 112.828 2.828L11.828 13.828a2 2 0 01-.828.486L7 15l1.686-4a2 2 0 01.314-.768z" />
            </svg>
        </a>
        HTML;
    }
}



if (! function_exists('delete_column')) {
    function delete_column(string $route, string $label = 'Delete'): string
    {
        return <<<HTML
        <button type="button" title="{$label}"
            onclick="confirmDelete('{$route}')"
            class="inline-flex items-center px-3 py-1.5 text-sm font-semibold text-red-600 hover:text-white hover:bg-red-600 border border-red-600 rounded-lg transition duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4a1 1 0 011 1v1H9V4a1 1 0 011-1z" />
            </svg>
        </button>
        HTML;
    }
}

if (! function_exists('view_column')) {
    function view_column(string $route, string $label = 'View'): string
    {
        return <<<HTML
        <a href="{$route}"
            class="inline-flex items-center px-3 py-1.5 text-sm font-semibold text-gray-600 hover:text-white hover:bg-gray-600 border border-gray-600 rounded-lg transition duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7s-8.268-2.943-9.542-7z" />
            </svg>
            {$label}
        </a>
        HTML;
    }
}

if (! function_exists('status_column')) {
    function status_column(string $route, bool $isActive, string $labelActive = 'Active', string $labelInactive = 'Inactive'): string
    {
        if ($isActive) {
            return <<<HTML
            <a href="{$route}"
                class="inline-flex items-center px-3 py-1.5 text-sm font-semibold text-green-600 hover:text-white hover:bg-green-600 border border-green-600 rounded-lg transition duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 13l4 4L19 7" />
                </svg>
                {$labelActive}
            </a>
            HTML;
        }

        return <<<HTML
        <a href="{$route}"
            class="inline-flex items-center px-3 py-1.5 text-sm font-semibold text-yellow-600 hover:text-white hover:bg-yellow-600 border border-yellow-600 rounded-lg transition duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M18 12H6" />
            </svg>
            {$labelInactive}
        </a>
        HTML;
    }
}


if (!function_exists('toggle_column')) {
    function toggle_column(string $route, int $id, bool $status, string $class = 'toggle-switch'): string
    {
        $checked = $status ? 'checked' : '';

        return <<<HTML
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" class="sr-only peer {$class}" data-id="{$id}" data-url="{$route}" {$checked}>
                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4
                            peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer
                            dark:bg-gray-700 peer-checked:after:translate-x-full
                            peer-checked:after:border-white after:content-[''] after:absolute
                            after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300
                            after:border after:rounded-full after:h-5 after:w-5 after:transition-all
                            dark:border-gray-600 peer-checked:bg-blue-600"></div>
            </label>
        HTML;
    }
}

if (! function_exists('view_button')) {
    function view_button(int|string $id, string $label = 'View'): string
    {
        return <<<HTML
        <button
            type="button"
            class="inline-flex items-center justify-center w-8 h-8 text-green-600 hover:text-white hover:bg-green-600 border border-green-600 rounded-lg transition duration-200 view-details"
            data-id="{$id}"
            title="{$label}"
        >
            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-4 h-4"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
        </button>
        HTML;
    }
}

function viewss($type,$path) {
    return Viewed::get($type,$path);
}

function sendResponse(
    bool $success,
    string $message = "Invalid request",
    $data = [],
    int $status = 200,
    string $errorMessage = ""
    ) {
        return [
            'success' => $success,
            'message' => $message,
            'data' => $data,
            'status' => $status,
            'error_message' => $errorMessage,
        ];
    }

function sendApiResponse(
    bool $success,
    string $message = "Invalid request",
    $data = [],
    int $status = 200,
    string $errorMessage = "")
    {
        return response()->json([
            'success'       => $success ?? false,
            'message'       => $message ?? '',
            'data'          => $data ?? [],
            'status'        => $status ?? 200,
            'error_message' => $errorMessage ?? ($success ? '' : ($message ?? 'Error')),
        ], $status ?? 200);
    }

/**
 * @param int $a
 * @return string
 */
// random number
function randomNumber($a = 10)
{
    $x = '0123456789';
    $c = strlen($x) - 1;
    $z = '';
    for ($i = 0; $i < $a; $i++) {
        $y = rand(0, $c);
        $z .= substr($x, $y, 1);
    }
    return $z;
}

function settings(string $key = null, $default = null):mixed
{
    if ($key === null) {
        return Settings::all();
    }

    return Settings::get($key, $default);
}

function enum($enum): mixed
{
    return $enum->value;
}

function uploadImageFileInStorage($reqFile,$path,$oldImage = null){
    $service = new \Sdtech\FileUploaderLaravel\Service\FileUploadLaravelService();
    $response = $service->uploadImageInStorage($reqFile,$path,$oldImage);
    return $response;
}

function formatPermissionName(string $input): string
{
    return (string) Str::of($input)
        ->replaceMatches('/([a-z])([A-Z])/', '$1 $2')
        ->replace(['.', '_', '-'], ' ')
        ->replaceMatches('/\s+/', ' ')
        ->trim()
        ->title();
}


function make_unique_slug($title, $table_name = NULL, $column_name = 'slug')
{
    $table = array(
        'Š' => 'S', 'š' => 's', 'Đ' => 'Dj', 'đ' => 'dj', 'Ž' => 'Z', 'ž' => 'z', 'Č' => 'C', 'č' => 'c', 'Ć' => 'C', 'ć' => 'c',
        'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E',
        'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O',
        'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss',
        'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e',
        'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o',
        'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'ý' => 'y', 'þ' => 'b',
        'ÿ' => 'y', 'Ŕ' => 'R', 'ŕ' => 'r', '/' => '-', ' ' => '-'
    );

    // -- Remove duplicated spaces
    $stripped = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $title);

    // -- Returns the slug
    $slug = strtolower(strtr($title, $table));
    $slug = str_replace("?", "", $slug);
    if (isset($table_name)) {
        $item = DB::table($table_name)->where($column_name, $slug)->first();
        if (isset($item)) {
            $slug = setSlugAttribute($slug, $table_name, $column_name);
        }
    }

    return $slug;
}

function setSlugAttribute($value, $table, $column_name = 'slug')
{
    if (DB::table($table)->where($column_name, $value)->exists()) {
        return incrementSlug($value, $table, $column_name);
    }
    return $value;
}

function incrementSlug($slug, $table, $column_name = 'slug')
{
    $original = $slug;
    $count = 2;

    while (DB::table($table)->where($column_name, $slug)->exists()) {
        $slug = "{$original}-" . $count++;
    }

    return $slug;
}
