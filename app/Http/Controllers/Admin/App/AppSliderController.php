<?php

namespace App\Http\Controllers\Admin\App;

use App\Http\Controllers\Controller;
use App\Http\Services\Slider\SliderServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;
use App\Enums\SliderTypeEnum;
use App\Http\Requests\Slider\SliderCreateRequest;
use App\Http\Services\Response\ResponseService;
use Illuminate\Http\RedirectResponse;

class AppSliderController extends Controller
{
    protected SliderServiceInterface $service;

    public function __construct(SliderServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): View|JsonResponse
    {
        $data['title'] = __('Slider');
        if ($request->ajax()) {
            return $this->getDataTableSlider();
        }

        return ResponseService::send([
            'data' => $data,
        ], view: viewss('slider','list'));
    }

    protected function getDataTableSlider(): JsonResponse
    {
        $query = $this->service->getDataTableData(SliderTypeEnum::APP);

        return DataTables::eloquent($query)
            ->addColumn('photo', function ($item) {
                if ($item->photo) {
                    return '<img width="100" alt="banner" src="'.$item->photo.'"></img>';
                } else {
                    return 'N/A';
                }
            })
            ->addColumn('published', function ($item) {
                return toggle_column(
                    route('appSlider.publish'),
                    $item->id,
                    $item->published == 1
                );
            })

            ->addColumn('actions', function ($item) {
                return action_buttons([
                    edit_column(route('appSlider.edit', $item->id)),
                    delete_column(route('appSlider.delete', $item->id)),
                ]);
            })
            ->rawColumns(['photo', 'actions','published'])
            ->make(true);
    }

    public function create()
    {
        $data['pageTitle'] = __('Create App Slider');
        $data['type'] = SliderTypeEnum::APP;
        $data['function_type'] = 'create';

        return ResponseService::send([
            'data' => $data,
        ], view: viewss('slider','create'));
    }

    public function store(SliderCreateRequest $request): RedirectResponse {
//        dd($request->all());
        $response = $this->service->storeOrUpdateSlider($request);
        return ResponseService::send([
            'response' => $response,
        ], successRoute: 'appSlider.list');
    }

    public function edit($id)
    {
        $data['pageTitle'] = __('Update App Slider');
        $data['type'] = SliderTypeEnum::APP;
        $data['function_type'] = 'update';
        $data['item'] = $this->service->getById($id);
        if (!$data['item'] ) {
            return ResponseService::send();
        }
        return ResponseService::send([
            'data' => $data,
        ], view: viewss('slider','create'));
    }


    public function update(SliderCreateRequest $request): RedirectResponse {

        $response = $this->service->storeOrUpdateSlider($request);
        return ResponseService::send([
            'response' => $response,
        ]);
    }

    public function destroy($id): RedirectResponse {
        $response = $this->service->deleteSlider($id);
        return ResponseService::send([
            'response' => $response,
        ]);
    }

    public function publish(Request $request): JsonResponse {
        try {
            $response = $this->service->publishSlider($request->id,$request->status);
            return response()->json($response);
        } catch (\Exception $e) {
            logStore('Slider destroy',$e->getMessage());
            return response()->json(['success'=>false,'message'=>somethingWrong()]);
        }
    }


}
