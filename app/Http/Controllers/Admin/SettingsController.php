<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.shipping_configuration.configuration.index');
    }
    public function create()
    {
        return view('admin.shipping_configuration.configuration.create');
    }

    public function edit($id)
    {
        $data['edit_id'] = $id; // Assuming you need to pass the ID to the view
        return view('admin.shipping_configuration.configuration.edit', $data);
    }

    public function generalSetting(Request $request)
    {
        return view('admin.setup_configurations.general_settings.index');
    }
    public function featuresActivation(Request $request)
    {
        return view('admin.setup_configurations.features_activation.index');
    }
    public function smtpSettings(Request $request)
    {
        return view('admin.setup_configurations.smtp_settings.index');
    }
    public function paymentMethod(Request $request)
    {
        return view('admin.setup_configurations.payment_method.index');
    }
    public function fileSystem(Request $request)
    {
        return view('admin.setup_configurations.file_system.index');
    }
    public function socialLogin(Request $request)
    {
        return view('admin.setup_configurations.social_login.index');
    }
    public function googleAnalytics(Request $request)
    {
        return view('admin.setup_configurations.google_analytics.index');
    }
    public function facebookChat(Request $request)
    {
        return view('admin.setup_configurations.facebook_chat.index');
    }
    public function googleRecaptcha(Request $request)
    {
        return view('admin.setup_configurations.google_recaptcha.index');
    }
    public function googleMap(Request $request)
    {
        return view('admin.setup_configurations.google_map.index');
    }
    public function googleFirebase(Request $request)
    {
        return view('admin.setup_configurations.google_firebase.index');
    }
    public function header(Request $request)
    {
        return view('admin.website_setup.header.index');
    }
    public function footer(Request $request)
    {
        return view('admin.website_setup.footer.index');
    }
    public function pages(Request $request)
    {
        return view('admin.website_setup.pages.index');
    }
    public function appearance(Request $request)
    {
        return view('admin.website_setup.appearance.index');
    }
    public function seo(Request $request)
    {
        return view('admin.marketing.seo.index');
    }
}





