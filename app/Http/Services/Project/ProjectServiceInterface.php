<?php

namespace App\Http\Services\Project;

use App\Http\Requests\Project\ProjectCreateRequest;
use App\Http\Services\BaseServiceInterface;
use Illuminate\Http\Request;

interface ProjectServiceInterface extends BaseServiceInterface
{
    public function storeOrUpdateProject(ProjectCreateRequest $request): array;
    public function deleteProject($id): array;
    public function publishProject($id, $status): array;
    public function getDataTableData(Request $request): array;
    public function projectEditData($id): array;
    public function projectCreateData(Request $request): array;
    public function getPublicProjectList(Request $request): array;
    public function getPublicProjectDetails(string $identifier): array;
}
