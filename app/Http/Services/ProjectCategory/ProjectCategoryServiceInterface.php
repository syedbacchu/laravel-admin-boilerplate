<?php

namespace App\Http\Services\ProjectCategory;

use App\Http\Requests\Project\ProjectCategoryCreateRequest;
use App\Http\Services\BaseServiceInterface;
use Illuminate\Http\Request;

interface ProjectCategoryServiceInterface extends BaseServiceInterface
{
    public function storeOrUpdateProjectCategory(ProjectCategoryCreateRequest $request): array;
    public function deleteProjectCategory($id): array;
    public function publishProjectCategory($id, $status): array;
    public function getDataTableData(Request $request): array;
    public function projectCategoryEditData($id): array;
    public function projectCategoryCreateData(Request $request): array;
    public function getPublicProjectCategoryList(Request $request): array;
    public function getPublicProjectCategoryDetails(string $identifier): array;
}
