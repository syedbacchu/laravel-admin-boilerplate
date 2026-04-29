<?php

namespace App\Http\Services\Stat;

use App\Http\Requests\Stat\StatCreateRequest;
use App\Http\Services\BaseServiceInterface;
use Illuminate\Http\Request;

interface StatServiceInterface extends BaseServiceInterface
{
    public function storeOrUpdate(StatCreateRequest $request): array;
    public function deleteData($id): array;
    public function publish($id, $status): array;
    public function getDataTableData(Request $request): array;
    public function editData($id): array;
    public function createData(Request $request): array;
    public function getPublicList(Request $request): array;
    public function getPublicDetails(string $identifier): array;
}
