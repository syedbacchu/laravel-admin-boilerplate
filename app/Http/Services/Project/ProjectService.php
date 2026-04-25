<?php

namespace App\Http\Services\Project;

use App\Http\Requests\Project\ProjectCreateRequest;
use App\Http\Services\BaseService;
use App\Models\Project;
use App\Models\ProjectCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectService extends BaseService implements ProjectServiceInterface
{
    protected ProjectRepositoryInterface $projectRepository;

    public function __construct(ProjectRepositoryInterface $repository)
    {
        parent::__construct($repository);
        $this->projectRepository = $repository;
    }

    public function storeOrUpdateProject(ProjectCreateRequest $request): array
    {
        $editId = $request->edit_id;

        $data = [
            'title' => $request->title,
            'slug' => $this->generateUniqueSlug($request->slug ?: $request->title, $editId ? (int) $editId : null),
            'short_description' => $request->short_description,
            'description' => $request->description,
            'project_url' => $request->project_url,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'thumbnail' => $request->thumbnail,
            'gallery' => $request->gallery ? json_encode($request->gallery) : null,
            'category_id' => $request->category_id ?: null,
            'project_status' => $request->project_status ?? 'ongoing',
            'sort_order' => $request->sort_order ?? 0,
            'is_featured' => $request->is_featured ?? 0,
            'status' => $request->status ?? 1,
            'meta_title' => $request->meta_title,
            'meta_keywords' => $request->meta_keywords,
            'meta_description' => $request->meta_description,
            'meta_image' => $request->meta_image,
        ];

        if ($editId) {
            $item = $this->projectRepository->find($editId);
            if (!$item) {
                return $this->sendResponse(false, __('Data not found'));
            }

            $data['updated_by'] = auth()->id() ?? null;
            $this->projectRepository->update($item->id, $data);
            $item = $this->projectRepository->find($item->id);
            $message = __('Project updated successfully');
        } else {
            $data['added_by'] = auth()->id() ?? null;
            $item = $this->projectRepository->createProject($data);
            $message = __('Project created successfully');
        }

        return $this->sendResponse(true, $message, $item->fresh(['category']));
    }

    public function deleteProject($id): array
    {
        $item = $this->projectRepository->find($id);
        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        $this->projectRepository->delete($id);
        return $this->sendResponse(true, __('Data deleted successfully'));
    }

    public function publishProject($id, $status): array
    {
        $item = $this->projectRepository->find($id);
        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        $this->projectRepository->update($id, ['status' => (int) $status]);
        return $this->sendResponse(true, __('Status updated successfully'));
    }

    public function getDataTableData($request): array
    {
        $data = $this->projectRepository->projectList($request);
        return $this->sendResponse(true, __('Data get successfully.'), $data);
    }

    public function projectEditData($id): array
    {
        $item = $this->projectRepository->find($id);
        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        return $this->sendResponse(true, '', $item->load(['category:id,name']));
    }

    public function projectCreateData($request): array
    {
        $categories = ProjectCategory::query()
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name']);

        return $this->sendResponse(true, '', [
            'categories' => $categories,
        ]);
    }

    public function getPublicProjectList(Request $request): array
    {
        $request->merge(['status' => $request->status ?? 1]);
        $data = $this->projectRepository->projectList($request);
        return $this->sendResponse(true, __('Data get successfully.'), $data);
    }

    public function getPublicProjectDetails(string $identifier): array
    {
        $item = $this->projectRepository->findPublicProjectByIdentifier($identifier);

        if (!$item) {
            return $this->sendResponse(false, __('Project not found'), [], 404, __('Project not found'));
        }

        return $this->sendResponse(true, __('Project details'), $item);
    }

    protected function generateUniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: 'project';

        if ($ignoreId) {
            $current = Project::query()->find($ignoreId, ['id', 'slug']);
            if ($current && $current->slug === $base) {
                return $base;
            }
        }

        return make_unique_slug($base, 'projects');
    }
}
