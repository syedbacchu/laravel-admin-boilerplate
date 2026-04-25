<?php

namespace App\Http\Services\ProjectCategory;

use App\Http\Repositories\BaseRepositoryInterface;

interface ProjectCategoryRepositoryInterface extends BaseRepositoryInterface
{
    public function createProjectCategory(array $data): \Illuminate\Database\Eloquent\Model;
    public function projectCategoryList(\Illuminate\Http\Request $request): array;
    public function findPublicProjectCategoryByIdentifier(string $identifier): ?\App\Models\ProjectCategory;
}
