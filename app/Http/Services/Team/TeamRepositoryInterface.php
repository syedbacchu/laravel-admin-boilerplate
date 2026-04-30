<?php

namespace App\Http\Services\Team;

use App\Http\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface TeamRepositoryInterface extends BaseRepositoryInterface
{
    public function dataCreate(array $data): Model;
    public function dataList(Request $request): array;
    public function findPublicByIdentifier(string $identifier): ?Model;
}
