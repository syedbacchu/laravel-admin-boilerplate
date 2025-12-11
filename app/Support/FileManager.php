<?php


namespace App\Support;

use App\Models\FileSystem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Sdtech\FileUploaderLaravel\Service\FileUploadLaravelService;

class FileManager
{
    private $service;

    public function __construct()
    {
        $this->service = new FileUploadLaravelService();
    }

    public static function createFile($data, $userId)
    {
        FileSystem::create([
            'filename' => $data['file_name'],
            'original_name' => $data['original_name'],
            'type' => $data['file_ext_original'],
            'extension' => $data['file_ext'],
            'size' => $data['size'],
            'path' => $data['path'],
            'full_url' => $data['file_url'],
            'dimensions' => isset($data['dimensions'])
                ? $data['dimensions']['width'] . 'x' . $data['dimensions']['height']
                : null,

            'alt_text' => $data['file_name'],
            'title' => $data['file_name'],
            'description' => $data['file_name'],
            'seo_keywords' => $data['file_name'],
            'seo_title' => $data['file_name'],
            'seo_description' => $data['file_name'],
            'uploaded_by' => $userId,
        ]);
    }

    public static function uploadFilePublic(UploadedFile $file, string $folder = 'uploads', ?string $oldFile = null)
    {
        $self = new self();
        $response = $self->service->uploadImageInPublic($file, $folder, $oldFile);
        $user = Auth::user();
        if ($response ['success'] == false) {
            return $response;
        } else {
            $data = $response['data'];
            self::createFile($data, $user->id);
            return sendResponse(true, __('File successfully uploaded.'), $data);
        }
    }

    public static function uploadFileStorage(UploadedFile $file, string $folder = 'uploads', ?string $oldFile = null)
    {
        $self = new self();
        $response = $self->service->uploadImageInStorage($file, $folder, $oldFile);
        $user = Auth::user();
        if ($response ['success'] == false) {
            return $response;
        } else {
            $data = $response['data'];
            self::createFile($data, $user->id);
            return sendResponse(true, __('File successfully uploaded.'), $data);
        }
    }

    public static function list($request): array
    {
        $totalPages = 0;
        $totalCount = 0;
        $settingPerPage = 10;
        $page = isset($request->page) ? intval($request->page) : 1;
        $perPage = isset($request->per_page) ? intval($request->per_page) : $settingPerPage;
        $orderBy = isset($request->orderBy) ? $request->orderBy : 'desc';
        $column = isset($request->orderColumn) ? $request->orderColumn : 'id';
        $search = $request->get('search', null);
        $userId = $request->get('userId', null);

        $query = FileSystem::query();

        $query->select('*');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('filename', 'like', '%' . $search . '%')
                    ->orWhere('original_name', 'like', '%' . $search . '%');
            });
        }

        if ($userId) {
            $query->where('uploaded_by', $userId);
        }

        $countQuery = clone $query;
        $totalCount = $countQuery->count();
        $totalPages = ceil($totalCount / $perPage);

        if (isset($request->list_size) && $request->list_size === 'web') {
            $items = $query->orderBy($column, $orderBy)->paginate($settingPerPage);
        } elseif (isset($request->list_size) && $request->list_size === 'all') {
            $items = $query->orderBy($column, $orderBy)->get();
        } elseif (isset($request->list_size) && $request->list_size === 'ajax') {
            $items = $query->orderBy($column, $orderBy);
        } else {
            $items = $query->skip(($page - 1) * $perPage)
                ->take($perPage)
                ->orderBy($column, $orderBy)
                ->get();
        }

        $data = [
            'total_count' => $totalCount,
            'total_page' => $totalPages,
            'per_page' => $perPage,
            'current_page' => $page,
            'data' => $items,
        ];

        return $data;
    }
}
