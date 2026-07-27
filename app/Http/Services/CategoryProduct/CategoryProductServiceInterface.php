<?php

namespace App\Http\Services\CategoryProduct;

interface CategoryProductServiceInterface
{
    public function getDataTableData($request): array;
    public function getCategoryDetails(string $id): array;
    public function publish($id, $status): array;
}