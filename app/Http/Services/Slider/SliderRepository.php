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

    // public function dataList($request): array
    // {
    //     // Force fresh query with no cache
    //     $query = Slider::query()->latest();

    //     // Add conditions
    //     if ($request->has('status')) {
    //         $query->where('status', $request->status);
    //     }

    //     if ($request->has('type')) {
    //         $query->where('type', $request->type);
    //     }

    //     if ($request->has('site_type')) {
    //         $query->where('site_type', $request->site_type);
    //     }

    //     // Get fresh data without any cache
    //     $data = $query->get();

    //     return [
    //         'total_count' => $data->count(),
    //         'data' => $data,
    //     ];
    // }

    public function dataList($request): array
    {
        return DataListManager::list(
            request: $request,
            query: Slider::query(),

            searchable: [
                'title',
                'subtitle',
            ],

            filters: [
                'status' => [
                    'column' => 'status'
                ],
                'type' => [
                    'column' => 'type'
                ],
                'site_type' => [
                    'column' => 'site_type'
                ],
            ],

            select: [
                'photo',
                'title',
                'subtitle',
                'description',
                'tagline',
                'status',
                'link',
                'mobile_banner',
                'type',
                'serial',
                'video_link',
                'page',
                'cta_button',
                'stat',
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
            ->where('status', 1)
            ->where(function ($query) use ($identifier) {
                $query->where('id', $identifier);
            })
            ->first();
    }
}
