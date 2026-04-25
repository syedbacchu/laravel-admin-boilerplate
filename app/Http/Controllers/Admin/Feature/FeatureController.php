<?php

namespace App\Http\Controllers\Admin\Feature;

use App\Http\Controllers\Controller;
use App\Http\Requests\Feature\FeatureCreateRequest;
use App\Http\Services\Feature\FeatureServiceInterface;
use App\Http\Services\Response\ResponseService;
use App\Support\DataListManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FeatureController extends Controller
{
    protected FeatureServiceInterface $service;

    public function __construct(FeatureServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $data['pageTitle'] = __('Feature List');
        if ($request->ajax()) {
            return DataListManager::dataTableHandle(
                request: $request,
                dataProvider: function ($request) {
                    return $this->service
                        ->getDataTableData($request)['data']['data'];
                },
                columns: [
                    'title' => fn ($item) => $item->title,
                    'slug' => fn ($item) => $item->slug,
                    'category' => fn ($item) => $item->category?->name ?: '-',
                    'thumbnail' => fn ($item) => $item->thumbnail
                        ? '<img src="' . $item->thumbnail . '" alt="' . $item->title . '" class="h-12 w-12 object-cover rounded">'
                        : '-',
                    'image' => fn ($item) => $item->image
                        ? '<img src="' . $item->image . '" alt="' . $item->title . '" class="h-12 w-12 object-cover rounded">'
                        : '-',
                    'link' => fn ($item) => $item->link
                        ? '<a href="' . $item->link . '" target="_blank" class="text-blue-600 hover:underline">' . Str::limit($item->link, 30) . '</a>'
                        : '-',
                    'status_toggle' => fn ($item) =>
                    toggle_column(
                        route('feature.publish'),
                        $item->id,
                        $item->status === 1
                    ),

                    'actions' => function ($item) {
                        $buttons = [
                            edit_column(route('feature.edit', $item->id)),
                            delete_column(route('feature.delete', $item->id)),
                        ];

                        return action_buttons($buttons);
                    },
                ],
                rawColumns: ['thumbnail', 'image', 'link', 'status_toggle', 'actions']
            );
        }

        return ResponseService::send([
            'data' => $data,
        ], null, \App\Http\Services\Response\Viewed::get('feature', 'list'));
    }

    public function create(Request $request)
    {
        $setup = $this->service->featureCreateData($request)['data'];

        $data['pageTitle'] = __('Create Feature');
        $data['function_type'] = 'create';
        $data['categories'] = $setup['categories'];

        return ResponseService::send([
            'data' => $data,
        ], null, \App\Http\Services\Response\Viewed::get('feature', 'create'));
    }

    public function store(FeatureCreateRequest $request): RedirectResponse
    {
        $response = $this->service->storeOrUpdateFeature($request);
        return ResponseService::send([
            'response' => $response,
        ], successRoute: 'feature.list');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $response = $this->service->featureEditData($id);
        if ($response['success'] === false) {
            return ResponseService::send();
        }

        $setup = $this->service->featureCreateData(request())['data'];
        $item = $response['data'];

        $data['pageTitle'] = __('Update Feature');
        $data['function_type'] = 'update';
        $data['item'] = $item;
        $data['categories'] = $setup['categories'];
        $data['selectedCategoryId'] = $item->category_id;

        return ResponseService::send([
            'data' => $data,
        ], null, \App\Http\Services\Response\Viewed::get('feature', 'create'));
    }

    public function update(FeatureCreateRequest $request, string $id): RedirectResponse
    {
        $request->merge(['edit_id' => $id]);
        $response = $this->service->storeOrUpdateFeature($request);

        return ResponseService::send([
            'response' => $response,
        ], successRoute: 'feature.list');
    }

    public function destroy(string $id): RedirectResponse
    {
        $response = $this->service->deleteFeature($id);
        return ResponseService::send([
            'response' => $response,
        ], successRoute: 'feature.list');
    }

    public function featureStatus(Request $request): JsonResponse
    {
        try {
            $response = $this->service->publishFeature($request->id, $request->status);
            return response()->json($response);
        } catch (\Exception $e) {
            logStore('featureStatus', $e->getMessage());
            return response()->json(['success' => false, 'message' => somethingWrong()]);
        }
    }
}
