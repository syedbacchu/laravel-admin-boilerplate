<?php

namespace App\Http\Controllers\Admin\Attribute;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attribute\AttributeValueCreateRequest;
use App\Http\Services\AttributeValue\AttributeValueServiceInterface;
use App\Http\Services\Response\ResponseService;
use App\Support\DataListManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AttributeValueController extends Controller
{
    protected AttributeValueServiceInterface $attribute;

    public function __construct(AttributeValueServiceInterface $attribute)
    {
        $this->attribute = $attribute;
    }

    public function index(Request $request)
    {
        $data['pageTitle'] = __('Attribute Value List');
        if ($request->ajax()) {
            return DataListManager::dataTableHandle(
                request: $request,
                dataProvider: function ($request) {
                    return $this->attribute
                        ->getDataTableData($request)['data']['data'];
                },
                columns: [
                    'icon' => fn ($item) => $item->icon
                    ? '<img src="' . $item->icon . '" class="h-12 w-12 rounded object-cover">'
                    : '-',

                    'type' => fn ($item) => optional($item->attribute)->name ?? '-',

                    'status_toggle' => fn ($item) =>
                    toggle_column(
                        route('attribute.value.publish'),
                        $item->id,
                        $item->status === 1
                    ),

                    'actions' => function ($item) {
                        $buttons = [
                            edit_column(route('attribute.value.edit', $item->id)),
                            delete_column(route('attribute.value.delete', $item->id)),
                        ];

                        return action_buttons($buttons);
                    },
                ],
                rawColumns: ['icon', 'status_toggle', 'actions']
            );
        }

        return ResponseService::send([
            'data' => $data,
        ], null, \App\Http\Services\Response\Viewed::get('attribute_value', 'list'));
    }

    public function create(Request $request)
    {
        $setup = $this->attribute->createData($request)['data'];

        $data['pageTitle'] = __('Create Attribute Value');
        $data['function_type'] = 'create';
        $data['attributes'] = $setup['attributes'] ?? [];

        return ResponseService::send([
            'data' => $data,
        ], null, \App\Http\Services\Response\Viewed::get('attribute_value', 'create'));
    }

    public function store(AttributeValueCreateRequest $request): RedirectResponse
    {
        $response = $this->attribute->storeOrUpdate($request);
        return ResponseService::send([
            'response' => $response,
        ], successRoute: 'attribute.value.list');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $response = $this->attribute->editData($id);
        if ($response['success'] === false) {
            return ResponseService::send();
        }

        $setup = $this->attribute->createData(request())['data'];
        $item = $response['data'];

        $data['pageTitle'] = __('Update Attribute Value');
        $data['function_type'] = 'update';
        $data['item'] = $item;
        $data['attributes'] = $setup['attributes'] ?? [];

        return ResponseService::send([
            'data' => $data,
        ], null, \App\Http\Services\Response\Viewed::get('attribute_value', 'create'));
    }

    public function update(AttributeValueCreateRequest $request, string $id): RedirectResponse
    {
        $request->merge(['edit_id' => $id]);
        $response = $this->attribute->storeOrUpdate($request);

        return ResponseService::send([
            'response' => $response,
        ], successRoute: 'attribute.value.list');
    }

    public function destroy(string $id): RedirectResponse
    {
        $response = $this->attribute->deleteData($id);
        return ResponseService::send([
            'response' => $response,
        ], successRoute: 'attribute.value.list');
    }

    public function attributeValueStatus(Request $request): JsonResponse
    {
        try {
            $response = $this->attribute->publish($request->id, $request->status);
            return response()->json($response);
        } catch (\Exception $e) {
            logStore('attribute.value.Status', $e->getMessage());
            return response()->json(['success' => false, 'message' => somethingWrong()]);
        }
    }
}
