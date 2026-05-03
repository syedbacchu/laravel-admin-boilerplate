<?php

namespace App\Http\Controllers\Api\AboutCompany;

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
        $response = $this->service->getAboutCompanyData();
        return ResponseService::send($response);
    }
}

