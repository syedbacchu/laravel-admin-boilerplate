<?php

namespace App\Http\Services\AttributeValue;

use App\Http\Requests\Attribute\AttributeValueCreateRequest;
use App\Http\Services\BaseServiceInterface;
use Illuminate\Http\Request;

interface AttributeValueServiceInterface extends BaseServiceInterface
{
    public function storeOrUpdate(AttributeValueCreateRequest $request): array;
    public function deleteData($id): array;
    public function publish($id, $status): array;
    public function getDataTableData(Request $request): array;
    public function editData($id): array;
    public function createData(Request $request): array;
    public function getPublicList(Request $request): array;
    public function getPublicDetails(string $identifier): array;
}
