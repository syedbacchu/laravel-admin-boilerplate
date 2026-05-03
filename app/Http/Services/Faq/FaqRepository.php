<?php

namespace App\Http\Services\Faq;

use App\Http\Repositories\BaseRepository;
use App\Models\Faq;
use App\Support\DataListManager;
use Illuminate\Database\Eloquent\Model;

class FaqRepository extends BaseRepository implements FaqRepositoryInterface
{
    public function __construct(Faq $model)
    {
        parent::__construct($model);
    }

    public function faqList($request): array
    {
        return DataListManager::list(
            request: $request,
            query: Faq::query()->with([
                'category:id,name',
            ]),
            searchable: [
                'question',
                'answer',
            ],
            filters: [
                'status' => [
                    'column' => 'status',
                ],
                'site_type' => [
                    'column' => 'site_type',
                ],
                'category_id' => [
                    'column' => 'category_id',
                ],
            ],
            select: [
                'id',
                'category_id',
                'question',
                'answer',
                'attestment',
                'sort_order',
                'site_type',
                'status',
                'created_at',
            ],
        );
    }

    public function findPublicFaqByIdentifier(string $identifier): ?Faq
    {
        return Faq::where('id', $identifier)
            ->where('status', 1)
            ->with(['category:id,name,slug'])
            ->first();
    }


    public function createFaq(array $data): Model
    {
        return $this->create($data);
    }

    public function delete($id): bool
    {
        $faq = Faq::find($id);
        if ($faq) {
            return $faq->delete(); // true/false
        }
        return false;
    }
}
