<?php

namespace Newnet\Zone\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Newnet\Zone\Jobs\ZoneImportJob;
use Newnet\Zone\Http\Requests\ImportRequest;

class ImportController extends Controller
{
    public function index()
    {
        return view('zone::admin.import.index');
    }

    public function store(ImportRequest $request)
    {
        $file = $request->file('file');
        $fileStorage = $file->move(storage_path('app/tmp'), 'zone-import-'.uniqid().'.'.$file->getClientOriginalExtension());

        dispatch(new ZoneImportJob($fileStorage->getRealPath()));

        return redirect()
            ->route('zone.admin.import.index')
            ->with('success', __('zone::import.notification.imported'));
    }
}
