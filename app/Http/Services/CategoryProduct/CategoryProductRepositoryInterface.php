<?php

namespace App\Http\Services\CategoryProduct;

interface CategoryProductRepositoryInterface
{
    public function dataList(\Illuminate\Http\Request $request): array;
    public function findCategoryWithProducts(string $id);
    public function getProductsByCategory(string $id): array;
    public function find(int $id, array $columns = ['*']): ?\Illuminate\Database\Eloquent\Model;
    public function update(int $id, array $data): bool;
}