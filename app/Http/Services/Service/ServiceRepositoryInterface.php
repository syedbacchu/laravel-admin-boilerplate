<?php

namespace App\Http\Services\Service;

use App\Http\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface ServiceRepositoryInterface extends BaseRepositoryInterface
{
    public function createService(array $data): Model;
    public function serviceList(Request $request): array;
    public function findPublicServiceByIdentifier(string $identifier): ?Model;
}
