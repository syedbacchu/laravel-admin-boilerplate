<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Http\Services\Response\ModelScannerService;
use App\Http\Services\Response\ResponseService;
use Illuminate\Http\Request;

class CustomFieldController extends Controller
{
    public function index() {
        $data['pageTitle'] = __('Custom Fields');
        $data['models'] = ModelScannerService::getModels([
            'CustomField','CustomFieldValue','AdminActivityLog','AdminSettings','AuditLog','FileSystem','UserVerificationCode'
        ]);

        return ResponseService::send([
            'data' => $data,
        ], view: viewss('custom','index'));
    }
}
