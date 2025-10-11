<?php

namespace App\Http\Services\Slider;

use App\Http\Requests\Slider\SliderCreateRequest;
use App\Http\Services\BaseServiceInterface;
use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;

interface SliderServiceInterface extends BaseServiceInterface
{

    public function getDataTableData($type): Builder; // For DataTable - Service provides this
    public function storeOrUpdateSlider(SliderCreateRequest $request): array; // For store producty
    public function deleteSlider($id): array; // For delete producty
    public function publishSlider($id,$status): array; // For status producty


    // public function getAllProducts(): Collection;
    // public function getProduct(int $id): Product;
    // public function createProduct(array $data): Product;
    // public function updateProduct(int $id, array $data): Product;
    // public function deleteProduct(int $id): bool;
    // public function restoreProduct(int $id): bool;
    // public function handleImageUpload(?UploadedFile $image, ?string $oldImage = null): ?string;
    // public function generateSku(): string;
    // public function validateUniqueSku(string $sku, ?int $exceptId = null): bool;
    // public function searchProducts(string $query): Collection;
    // public function getProductsByCategory(int $categoryId): Collection;
    // public function getLowStockAlert(int $threshold = 10): Collection;

    // public function getActiveProducts(): Collection;
}
