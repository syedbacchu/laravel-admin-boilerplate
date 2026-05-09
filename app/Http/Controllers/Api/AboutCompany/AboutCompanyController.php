<?php

namespace App\Http\Controllers\Api\AboutCompany;

use App\Enums\SliderSiteType;
use App\Http\Controllers\Controller;
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
        $siteType = (int) $request->get('site_type', SliderSiteType::GENERAL->value);
        $response = $this->service->getAboutCompanyData($siteType);

        if (isset($response['data'])) {
            if (is_object($response['data']) && method_exists($response['data'], 'toArray')) {
                $response['data'] = $response['data']->toArray();
            } elseif (is_object($response['data'])) {
                $response['data'] = (array) $response['data'];
            }

            $response['data']['site_type'] = (int) ($response['data']['site_type'] ?? $siteType);
        }

        return ResponseService::send($response);
    }
}
