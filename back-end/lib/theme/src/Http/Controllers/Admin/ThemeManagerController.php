<?php

namespace Newnet\Theme\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Newnet\Theme\Facades\Theme;

class ThemeManagerController extends Controller
{
    public function index()
    {
        $path = public_path('themes');
        if (File::isDirectory($path)) {
            $folders = File::directories($path);
        } else {
            $folders = [];
        }

        $items = [];

        foreach ($folders as $folder) {
            $fileJson = $folder . '/theme.json';
            $themeFolderName = File::basename($folder);

            try {
                if (File::exists($fileJson)) {
                    $reading = File::get($fileJson);
                    $themeData = json_decode($reading);

                    if (!empty($themeData->admin) || preg_match('/-admin$/', $folder)) {
                        continue;
                    }

                    $thumb = null;
                    if (!empty($themeData->screenshot)) {
                        $thumb = asset("themes/{$themeFolderName}/{$themeData->screenshot}");
                    } else if (File::exists($folder.'/screenshot.png')) {
                        $thumb = asset("themes/{$themeFolderName}/screenshot.png");
                    }

                    $item = [
                        'name' => $themeFolderName,
                        'description' => $themeData->description ?? Str::ucfirst($themeData->name),
                        'active' => Theme::current() == $themeFolderName,
                        'thumb' => $thumb,
                    ];

                    $items[] = $item;
                }
            } catch (Exception $e) {
                Log::error('LoadThemeError', [
                    'message' => $e->getMessage(),
                    'line' => $e->getLine(),
                    'file' => $e->getFile(),
                ]);
            }
        }

        return view('theme::admin.theme-manager.index', compact('items'));
    }

    public function activate(Request $request)
    {
        $themeName = $request->input('theme_name');

        setting()->set('theme_name', $themeName);

        return back()->with(
            'success',
            __('theme::theme-manager.notification.activated', ['name' => $themeName])
        );
    }
}
