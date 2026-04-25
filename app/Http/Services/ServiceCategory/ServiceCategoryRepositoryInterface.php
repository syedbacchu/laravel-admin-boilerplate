<?php

namespace App\Http\Services\ServiceCategory;

use App\Http\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface ServiceCategoryRepositoryInterface extends BaseRepositoryInterface
{
    public function createServiceCategory(array $data): Model;
    public function serviceCategoryList(Request $request): array;
}
