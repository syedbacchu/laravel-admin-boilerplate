<?php

namespace App\Http\Services\Project;

use App\Http\Repositories\BaseRepositoryInterface;

interface ProjectRepositoryInterface extends BaseRepositoryInterface
{
    public function createProject(array $data): \Illuminate\Database\Eloquent\Model;
    public function projectList(\Illuminate\Http\Request $request): array;
    public function findPublicProjectByIdentifier(string $identifier): ?\App\Models\Project;
}
