<?php

namespace App\Http\Services\Home;

use App\Http\Services\Project\ProjectService;
use App\Http\Services\Service\ServiceService;
use App\Http\Services\Testimonial\TestimonialService;
use App\Http\Services\Stat\StatService;
use Illuminate\Http\Request;

class HomeService
{

    public function getHomeData($request) {
        $data = [];
        $data['service_list'] = app(ServiceService::class)->getPublicServiceList($request)['data']['data'];
        $data['project_list'] = app(ProjectService::class)->getPublicProjectList($request)['data']['data'];
        $data['testimonial_list'] = app(TestimonialService::class)->getPublicList($request)['data']['data'];
        $data['stat_list'] = app(StatService::class)->getPublicList($request)['data']['data'];

        return sendResponse(true, __('Data get successfully.'), $data);
    }
}
