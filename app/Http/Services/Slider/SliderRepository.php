<?php

namespace App\Http\Services\Slider;

use App\Http\Repositories\BaseRepository;
use App\Models\Slider;
use App\Support\DataListManager;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class SliderRepository extends BaseRepository implements SliderRepositoryInterface
{
    public function __construct(Slider $model)
    {
        parent::__construct($model);
    }

    public function dataList($request): array
    {
        return DataListManager::list(
            request: $request,
            query: Slider::query(),

            searchable: [
                'title',
                'offer',
            ],

            filters: [
                'published' => [
                    'column' => 'published'
                ],
                'type' => [
                    'column' => 'type'
                ],
            ],

            select: [
                'id',
                'photo',
                'position',
                'title',
                'subtitle',
                'offer',
                'published',
                'link',
                'type',
                'serial',
            ],
        );
    }

    public function createSlider(array $data): Model
    {
        return $this->create($data);
    }


    // public function forceDelete(int $id): bool
    // {
    //     $record = $this->model->withTrashed()->find($id);
    //     if ($record) {
    //         return $record->forceDelete();
    //     }
    //     return false;
    // }

    // public function restore(int $id): bool
    // {
    //     $record = $this->model->withTrashed()->find($id);
    //     if ($record && $record->trashed()) {
    //         return $record->restore();
    //     }
    //     return false;
    // }
    // public function findBySku(string $sku): ?Model
    // {
    //     return $this->model->where('sku', $sku)->first();
    // }

    // public function getActiveProducts(): Collection
    // {
    //     return $this->model->active()->get();
    // }

    // public function searchByName(string $name): Collection
    // {
    //     return $this->model->where('name', 'LIKE', "%{$name}%")->get();
    // }

    // public function getProductsByCategory(int $categoryId): Collection
    // {
    //     return $this->model->where('category_id', $categoryId)->get();
    // }

    // public function getLowStockProducts(int $threshold = 10): Collection
    // {
    //     return $this->model->where('stock_quantity', '<=', $threshold)
    //                       ->where('stock_quantity', '>', 0)
    //                       ->get();
    // }

    // // Override update method to work with Model instance for consistency
    // public function updateModel(Model $model, array $data): bool
    // {
    //     return $model->update($data);
    // }

    // // Override delete method to work with Model instance for consistency
    // public function deleteModel(Model $model): bool
    // {
    //     return $model->delete();
    // }
}
