<?php

namespace App\Http\Controllers\Admin\AboutCompany;

use App\Http\Controllers\Controller;
use App\Http\Requests\AboutCompany\AboutCompanyUpdateRequest;
use App\Http\Services\AboutCompany\AboutCompanyServiceInterface;
use App\Http\Services\Response\ResponseService;
use Illuminate\Http\Request;

class AboutCompanyController extends Controller
{
    protected AboutCompanyServiceInterface $service;

    public function __construct(AboutCompanyServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $response = $this->service->getAboutCompanyData();
        $data = $response['data'];

        $data['pageTitle'] = __('About Company');

        return ResponseService::send([
            'data' => $data,
        ], view: viewss('about-company', 'form'));
    }

    public function update(AboutCompanyUpdateRequest $request)
    {
        $response = $this->service->updateAboutCompany($request);

        return ResponseService::send([
            'response' => $response,
        ], 'about-company.edit', null, [], 'about-company.edit');
    }
}

