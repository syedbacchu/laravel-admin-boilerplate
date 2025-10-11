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
        if ($request->ajax()) {
            return $this->getDataTableSlider();
        }

        return view('admin.app.app_slider.index');;
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
        return view('admin.app.app_slider.create', $data);
    }

    public function store(SliderCreateRequest $request): RedirectResponse {
        try {
            $response = $this->service->storeOrUpdateSlider($request);
            if ($response['success']) {
                return redirect()->route('appSlider.list')->with('success',$response['message']);
            } else {
                return redirect()->back()->with('dismiss',$response['message']);
            }
        } catch (\Exception $e) {
            logStore('SliderCreate',$e->getMessage());
            return redirect()->back()->with('dismiss',somethingWrong());
        }
    }

    public function edit($id)
    {
        $data['pageTitle'] = __('Update App Slider');
        $data['type'] = SliderTypeEnum::APP;
        $data['function_type'] = 'update';
        $data['item'] = $this->service->getById($id);
        if ($data['item']) {
            return view('admin.app.app_slider.create', $data);
        } else {
            return redirect()->back()->with('dismiss',__('Data not found'));
        }
    }


    public function update(SliderCreateRequest $request): RedirectResponse {
        try {
            $response = $this->service->storeOrUpdateSlider($request);
            if ($response['success']) {
                return redirect()->back()->with('success',$response['message']);
            } else {
                return redirect()->back()->with('dismiss',$response['message']);
            }
        } catch (\Exception $e) {
            logStore('Slider update',$e->getMessage());
            return redirect()->back()->with('dismiss',somethingWrong());
        }
    }

    public function destroy($id): RedirectResponse {
        try {
            $response = $this->service->deleteSlider($id);
            if ($response['success']) {
                return redirect()->back()->with('success',$response['message']);
            } else {
                return redirect()->back()->with('dismiss',$response['message']);
            }
        } catch (\Exception $e) {
            logStore('Slider destroy',$e->getMessage());
            return redirect()->back()->with('dismiss',somethingWrong());
        }
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
