<?php

namespace App\Http\Services\Team;

use App\Http\Requests\Team\TeamCreateRequest;
use App\Http\Services\BaseServiceInterface;
use Illuminate\Http\Request;

interface TeamServiceInterface extends BaseServiceInterface
{
    public function storeOrUpdate(TeamCreateRequest $request): array;
    public function deleteData($id): array;
    public function publish($id, $status): array;
    public function getDataTableData(Request $request): array;
    public function editData($id): array;
    public function createData(Request $request): array;
    public function getPublicList(Request $request): array;
    public function getPublicDetails(string $identifier): array;
}
