<?php

namespace App\Http\Services\FaqCategory;

use App\Http\Repositories\BaseRepository;
use App\Models\FaqCategory;
use App\Support\DataListManager;
use Illuminate\Database\Eloquent\Model;

class FaqCategoryRepository extends BaseRepository implements FaqCategoryRepositoryInterface
{
    public function __construct(FaqCategory $model)
    {
        parent::__construct($model);
    }

    public function faqCategoryList($request): array
    {
        return DataListManager::list(
            request: $request,
            query: FaqCategory::query()->with([
                'addedBy:id,name',
                'updatedBy:id,name'
            ]),
            searchable: [
                'name',
            ],
            filters: [
                'status' => [
                    'column' => 'status',
                ],
            ],
            select: [
                'id',
                'name',
                'description',
                'image',
                'sort_order',
                'status',
                'site_type',
                'added_by',
                'updated_by',
                'created_at',
            ],
        );
    }

    public function findPublicFaqCategoryByIdentifier(string $identifier): ?FaqCategory
    {
        return FaqCategory::where('id', $identifier)
            ->where('status', 1)
            ->first();
    }

    public function getCategoryWithFaqs(string $identifier): ?FaqCategory
    {
        return FaqCategory::where('id', $identifier)
            ->where('status', 1)
            ->with(['faqs' => function($query) {
                $query->where('status', 1)
                    ->orderBy('sort_order')
                    ->select('id', 'category_id', 'question', 'answer', 'sort_order');
            }])
            ->first();
    }


    public function createFaqCategory(array $data): Model
    {
        return $this->create($data);
    }
}
