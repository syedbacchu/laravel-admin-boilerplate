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
                'tagline',
            ],

            filters: [
                'status' => [
                    'column' => 'status'
                ],
                'type' => [
                    'column' => 'type'
                ],
            ],

            select: [
                'id',
                'photo',
                'site_type',
                'title',
                'subtitle',
                'tagline',
                'status',
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

    public function findPublicByIdentifier(string $identifier): ?Slider
    {
        return Slider::query()
            ->where('published', 1)
            ->where(function ($query) use ($identifier) {
                $query->where('id', $identifier);
            })
            ->first();
    }
}
