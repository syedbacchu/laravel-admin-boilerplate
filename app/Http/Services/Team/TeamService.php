<?php

namespace App\Http\Services\Team;

use App\Enums\StatusEnum;
use App\Http\Requests\Team\TeamCreateRequest;
use App\Http\Services\BaseService;
use App\Models\Team;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TeamService extends BaseService implements TeamServiceInterface
{
    protected TeamRepositoryInterface $teamRepository;

    public function __construct(TeamRepositoryInterface $repository)
    {
        parent::__construct($repository);
        $this->teamRepository = $repository;
    }

    public function storeOrUpdate(TeamCreateRequest $request): array
    {
        $editId = $request->edit_id;

        $data = [
            'name' => $request->name,
            'slug' => $this->generateUniqueSlug($request->name, $editId),
            'email' => $request->email,
            'phone' => $request->phone,
            'designation' => $request->designation,
            'bio' => $request->bio,

            'image' => $request->image,
            'cover_image' => $request->cover_image,

            'facebook_url' => $request->facebook_url,
            'twitter_url' => $request->twitter_url,
            'linkedin_url' => $request->linkedin_url,
            'instagram_url' => $request->instagram_url,
            'github_url' => $request->github_url,
            'youtube_url' => $request->youtube_url,

            'join_date' => $request->join_date,

            'status' => $request->status ?? StatusEnum::ACTIVE,
            'is_featured' => $request->is_featured ?? 0,
            'site_type' => $request->site_type ?? 1,
        ];

        if ($editId) {
            $data['updated_by'] = auth()->id();
            $this->teamRepository->update($editId, $data);

            return $this->sendResponse(true, 'Updated successfully');
        }

        $data['created_by'] = auth()->id();
        $this->teamRepository->create($data);

        return $this->sendResponse(true, 'Created successfully');
    }

    public function deleteData($id): array
    {
        $item = $this->teamRepository->find($id);
        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        $this->teamRepository->delete($id);
        return $this->sendResponse(true, __('Data deleted successfully'));
    }

    public function publish($id, $status): array
    {
        $item = $this->teamRepository->find($id);
        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        $this->teamRepository->update($id, ['status' => (int) $status]);
        return $this->sendResponse(true, __('Status updated successfully'));
    }

    public function getDataTableData($request): array
    {
        $data = $this->teamRepository->dataList($request);
        return $this->sendResponse(true, __('Data get successfully.'), $data);
    }

    public function editData($id): array
    {
        $item = $this->teamRepository->find($id);
        if (!$item) {
            return $this->sendResponse(false, __('Data not found'));
        }

        return $this->sendResponse(true, '', $item);
    }

    public function createData(Request $request): array
    {
        $categories = ServiceCategory::query()
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name']);

        return $this->sendResponse(true, '', [
            'categories' => $categories,
        ]);
    }

    public function getPublicList(Request $request): array
    {
        $request->merge(['status' => $request->status ?? 1]);
        $data = $this->teamRepository->dataList($request);
        return $this->sendResponse(true, __('Data get successfully.'), $data);
    }

    public function getPublicDetails(string $identifier): array
    {
        $item = $this->teamRepository->findPublicByIdentifier($identifier);

        if (!$item) {
            return $this->sendResponse(false, __('Team not found'), [], 404, __('Team not found'));
        }

        return $this->sendResponse(true, __('Team details'), $item);
    }

    protected function generateUniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: 'team';

        if ($ignoreId) {
            $current = Team::query()->find($ignoreId, ['id', 'slug']);
            if ($current && $current->slug === $base) {
                return $base;
            }
        }

        return make_unique_slug($base, 'teams');
    }
}
