<?php

namespace App\Http\Controllers\Admin\Attribute;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attribute\AttributeCreateRequest;
use App\Http\Services\Attribute\AttributeServiceInterface;
use App\Http\Services\Response\ResponseService;
use App\Support\DataListManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    protected AttributeServiceInterface $attribute;

    public function __construct(AttributeServiceInterface $attribute)
    {
        $this->attribute = $attribute;
    }

    public function index(Request $request)
    {
        $data['pageTitle'] = __('Attribute Type List');
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

                    'status_toggle' => fn ($item) =>
                    toggle_column(
                        route('attribute.publish'),
                        $item->id,
                        $item->status === 1
                    ),

                    'actions' => function ($item) {
                        $buttons = [
                            edit_column(route('attribute.edit', $item->id)),
                            delete_column(route('attribute.delete', $item->id)),
                        ];

                        return action_buttons($buttons);
                    },
                ],
                rawColumns: ['icon', 'status_toggle', 'actions']
            );
        }

        return ResponseService::send([
            'data' => $data,
        ], null, \App\Http\Services\Response\Viewed::get('attribute_type', 'list'));
    }

    public function create(Request $request)
    {
        $setup = $this->attribute->createData($request)['data'];

        $data['pageTitle'] = __('Create Attribute');
        $data['function_type'] = 'create';

        return ResponseService::send([
            'data' => $data,
        ], null, \App\Http\Services\Response\Viewed::get('attribute_type', 'create'));
    }

    public function store(AttributeCreateRequest $request): RedirectResponse
    {
        $response = $this->attribute->storeOrUpdate($request);
        return ResponseService::send([
            'response' => $response,
        ], successRoute: 'attribute.list');
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

        $data['pageTitle'] = __('Update Attribute');
        $data['function_type'] = 'update';
        $data['item'] = $item;

        $values = \App\Models\AttributeValue::where('type_id', $id)->get();

        $data['pageTitle'] = __('Update Attribute');
        $data['function_type'] = 'update';
        $data['item'] = $item;
        $data['values'] = $values;

        return ResponseService::send([
            'data' => $data,
        ], null, \App\Http\Services\Response\Viewed::get('attribute_type', 'create'));
    }

    public function update(AttributeCreateRequest $request, string $id): RedirectResponse
    {
        $request->merge(['edit_id' => $id]);
        $response = $this->attribute->storeOrUpdate($request);

        return ResponseService::send([
            'response' => $response,
        ], successRoute: 'attribute.list');
    }

    public function destroy(string $id): RedirectResponse
    {
        $response = $this->attribute->deleteData($id);
        return ResponseService::send([
            'response' => $response,
        ], successRoute: 'attribute.list');
    }

    public function attributeStatus(Request $request): JsonResponse
    {
        try {
            $response = $this->attribute->publish($request->id, $request->status);
            return response()->json($response);
        } catch (\Exception $e) {
            logStore('attributeStatus', $e->getMessage());
            return response()->json(['success' => false, 'message' => somethingWrong()]);
        }
    }
}
