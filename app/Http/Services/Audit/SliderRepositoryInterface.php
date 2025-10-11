<?php

namespace App\Http\Services\Slider;

use App\Http\Repositories\BaseRepositoryInterface;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface SliderRepositoryInterface extends BaseRepositoryInterface
{
    public function getDataTableQuery($type): Builder;
    public function createSlider(array $data): Model;
    // public function forceDelete(int $id): bool;
    // public function findBySku(string $sku): ?Model;
    // public function getActiveProducts(): Collection;
    // public function searchByName(string $name): Collection;
    // public function getProductsByCategory(int $categoryId): Collection;
    // public function getLowStockProducts(int $threshold = 10): Collection;
}
