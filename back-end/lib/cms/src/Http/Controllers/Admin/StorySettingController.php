<?php

namespace Newnet\Cms\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Newnet\Cms\Events\StorySettingEvent;
use Newnet\Setting\Http\Controllers\Admin\SettingController;

class StorySettingController extends SettingController
{
    protected $view = 'cms::admin.story-setting.index';

    public function save(Request $request)
    {
        $data = $request->except(['_token']);
        if (!isset($request->story_audio)) {
            $data['story_audio'] = null;
        }
        setting($data);

        event(new StorySettingEvent('created', auth('admin')->user()->id));

        return back()->with('success', __('admin::setting.notification.updated'));
    }
}
