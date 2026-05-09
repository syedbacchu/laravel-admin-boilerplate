<?php

namespace App\Http\Controllers\Admin\AboutCompany;

use App\Enums\SliderSiteType;
use App\Http\Controllers\Controller;
use App\Http\Requests\AboutCompany\AboutCompanyUpdateRequest;
use App\Http\Services\AboutCompany\AboutCompanyServiceInterface;
use App\Http\Services\Response\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class AboutCompanyController extends Controller
{
    protected AboutCompanyServiceInterface $service;

    public function __construct(AboutCompanyServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): View
    {
        $selectedSiteType = (int) $request->get('site_type', SliderSiteType::GENERAL->value);
        $response = $this->service->getAboutCompanyData($selectedSiteType);
        $data = $response['data'];

        return view(viewss('about-company', 'form'), [
            'pageTitle' => __('About Company'),
            'data' => $data,
            'siteTypes' => SliderSiteType::getSliderSiteTypeArray(),
            'selectedSiteType' => $selectedSiteType,
        ]);
    }

    public function update(AboutCompanyUpdateRequest $request)
    {
        $response = $this->service->updateAboutCompany($request);

        return ResponseService::send([
            'response' => $response,
        ], 'about-company.edit', null, ['site_type' => $request->site_type], 'about-company.edit');
    }
}
