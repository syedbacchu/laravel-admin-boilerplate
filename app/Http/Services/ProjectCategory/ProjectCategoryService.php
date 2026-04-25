<?php

namespace App\Http\Services\ProjectCategory;

use App\Http\Requests\Project\ProjectCategoryCreateRequest;
use App\Http\Services\BaseService;
use App\Models\ProjectCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectCategoryService extends BaseService implements ProjectCategoryServiceInterface
{
    protected ProjectCategoryRepositoryInterface $projectCategoryRepository;

    public function __construct(ProjectCategoryRepositoryInterface $repository)
    {
        parent::__construct($repository);
        $this->projectCategoryRepository = $repository;
    }

    public function storeOrUpdateProjectCategory(ProjectCategoryCreateRequest $request): array
    {
        $editId = $request->edit_id;

        $data = [
            'name' => $request->name,
            'slug' => $this->generateUniqueSlug($request->slug ?: $request->name, $editId ? (int) $editId : null),
            'description' => $request->description,
            'icon' => $request->icon,
            'image' => $request->image,
            'sort_order' => $request->sort_order ?? 0,
            'is_featured' => $request->is_featured ?? 0,
            'status' => $request->status ?? 1,
        ];

        if ($editId) {
            $item = $this->projectCategoryRepository->find($editId);
            if (!$item) {
                return $this->sendResponse(false, __('Data not found'));
            }

            $data['updated_by'] = auth()->id() ?? null;
            $this->projectCategoryRepository->update($item->id, $data);
            $item = $this->projectCategoryRepository->find($item->id);
            $message = __('Project category updated successfully');
        } else {
            $data['added_by'] = auth()->id() ?? null;
            $item = $this->projectCategoryRepository->createProjectCategory($data);
            $message = __('Project category created successfully');
        }

        return $this->sendResponse(true, $message, $item);
    }

    public function deleteProjectCategory($id): array
    {
        $item = $this->projectCategoryRepository->find($id);
        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        if ($item->projects()->count() > 0) {
            return $this->sendResponse(false, __('Cannot delete category with existing projects'));
        }

        $this->projectCategoryRepository->delete($id);
        return $this->sendResponse(true, __('Data deleted successfully'));
    }

    public function publishProjectCategory($id, $status): array
    {
        $item = $this->projectCategoryRepository->find($id);
        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        $this->projectCategoryRepository->update($id, ['status' => (int) $status]);
        return $this->sendResponse(true, __('Status updated successfully'));
    }

    public function getDataTableData($request): array
    {
        $data = $this->projectCategoryRepository->projectCategoryList($request);
        return $this->sendResponse(true, __('Data get successfully.'), $data);
    }

    public function projectCategoryEditData($id): array
    {
        $item = $this->projectCategoryRepository->find($id);
        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        return $this->sendResponse(true, '', $item);
    }

    public function projectCategoryCreateData($request): array
    {
        return $this->sendResponse(true, '', []);
    }

    public function getPublicProjectCategoryList(Request $request): array
    {
        $request->merge(['status' => $request->status ?? 1]);
        $data = $this->projectCategoryRepository->projectCategoryList($request);
        return $this->sendResponse(true, __('Data get successfully.'), $data);
    }

    public function getPublicProjectCategoryDetails(string $identifier): array
    {
        $item = $this->projectCategoryRepository->findPublicProjectCategoryByIdentifier($identifier);

        if (!$item) {
            return $this->sendResponse(false, __('Project category not found'), [], 404, __('Project category not found'));
        }

        return $this->sendResponse(true, __('Project category details'), $item->load(['projects:id,title,slug,thumbnail,category_id']));
    }

    protected function generateUniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: 'project-category';

        if ($ignoreId) {
            $current = ProjectCategory::query()->find($ignoreId, ['id', 'slug']);
            if ($current && $current->slug === $base) {
                return $base;
            }
        }

        return make_unique_slug($base, 'project_categories');
    }
}
