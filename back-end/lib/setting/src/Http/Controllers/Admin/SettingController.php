<?php

namespace Newnet\Setting\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Newnet\Setting\Events\SettingEvent;

abstract class SettingController extends Controller
{
    protected $view;

    public function index()
    {
        $setting = setting()->all();
        $item = json_decode(json_encode($setting));

        return view($this->view, compact('item'));
    }

    public function save(Request $request)
    {
        setting($request->except(['_token']));

        event(new SettingEvent('saved'));

        return back()->with('success', __('admin::setting.notification.updated'));
    }
}
