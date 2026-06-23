<?php

namespace App\Http\Services\Home;

use App\Http\Services\Feature\FeatureService;
use App\Http\Services\Products\ProductsService;
use App\Http\Services\Project\ProjectService;
use App\Http\Services\Service\ServiceService;
use App\Http\Services\Testimonial\TestimonialService;
use App\Http\Services\Stat\StatService;
use App\Models\District;
use App\Models\Thana;
use App\Support\DataListManager;
use Illuminate\Http\Request;

class HomeService
{

    public function getHomeData($request) {
        $data = [];
        $data['service_list'] = app(ServiceService::class)->getPublicServiceList($request)['data']['data'];
        $data['feature_list'] = app(FeatureService::class)->getPublicFeatureList($request)['data']['data'];
        $data['project_list'] = app(ProjectService::class)->getPublicProjectList($request)['data']['data'];
        $data['testimonial_list'] = app(TestimonialService::class)->getPublicList($request)['data']['data'];
        $data['stat_list'] = app(StatService::class)->getPublicList($request)['data']['data'];
        $data['product_list'] = app(ProductsService::class)->getHomeProductList($request)['data']['data'];

        return sendResponse(true, __('Data get successfully.'), $data);
    }

    public function getDistrictList($request) {
        $data = DataListManager::list(
            request: $request,
            query: District::query(),
            searchable: [
                'code',
                'name',
            ],
            filters: [
                'status' => [
                    'column' => 'status',
                ],
                'division_code' => [
                    'column' => 'division_code',
                ],
            ],
            select: [
                'id',
                'code',
                'name',
                'division_code',
                'status',
            ],
        );
        return sendResponse(true, __('Data get successfully.'), $data);
    }

    public function getThanaList($request) {
        $data = DataListManager::list(
            request: $request,
            query: Thana::query(),
            searchable: [
                'code',
                'name',
            ],
            filters: [
                'status' => [
                    'column' => 'status',
                ],
                'district_code' => [
                    'column' => 'district_code',
                ],
            ],
            select: [
                'id',
                'code',
                'name',
                'district_code',
                'status',
            ],
        );
        return sendResponse(true, __('Data get successfully.'), $data);
    }
}
