<?php

namespace App\Http\Services\CategoryProduct;

interface CategoryProductServiceInterface
{
    public function getDataTableData($request): array;
    public function getCategoryDetails(string $id): array;
    public function publish($id, $status): array;
    public function searchProducts($request): array;
    public function addProductToCategory($request): array;
    public function removeProductFromCategory($request): array;
    public function updateProductSortOrder($request): array;
}