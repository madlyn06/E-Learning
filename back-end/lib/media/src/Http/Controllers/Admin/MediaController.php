<?php

namespace Newnet\Media\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Newnet\Media\Helpers\MediaHelper;
use Newnet\Media\MediaUploader;
use Newnet\Media\Models\Media;
use Newnet\Media\Repositories\MediableRepositoryInterace;
use Newnet\Media\Repositories\MediaRepositoryInterface;
use Newnet\Media\Resources\FroalaMediaResource;

class MediaController extends Controller
{
    /**
     * @var MediaRepositoryInterface
     */
    private $mediaRepository;

    /**
     * @var MediableRepositoryInterace
     */
    private $mediableRepositoryInterace;

    public function __construct(MediaRepositoryInterface $mediaRepository, MediableRepositoryInterace $mediableRepositoryInterace)
    {
        $this->mediaRepository = $mediaRepository;
        $this->mediableRepositoryInterace = $mediableRepositoryInterace;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, MediaHelper $mediaHelper)
    {
        $medias = $this->mediaRepository->paginate(150);
        $mediables = $this->mediableRepositoryInterace->getAll();
        $allMonths = $mediaHelper->handleRemoveDuplicate($medias, 'created_at', true);
        $mediables = $mediaHelper->handleRemoveDuplicate($mediables, 'mediable_type', false);

        if ($request->ajax()) {
            $mode = $request->mode;
            $view = view("media::admin.ajax-result", compact('medias', 'mode'))->render();
            return response()->json(['result' => $view, 'status' => 'C200']);
        }

        return view('media::admin.index', compact('medias', 'allMonths', 'mediables'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, MediaUploader $mediaUploader)
    {
        try {
            DB::beginTransaction();
            if ($files = $request->file('image-upload')) {
                foreach ($files as $file) {
                    $maxUploadFileSize = setting('media_max_file_size') ?? null;
                    if ($maxUploadFileSize) {
                        $maxUploadFileSize = $maxUploadFileSize * 1024 * 1024;
                    }
                    if ($maxUploadFileSize && $file->getSize() > $maxUploadFileSize) {
                        DB::rollBack();
                        return redirect()->back()->with('errors', 'Dung lượng file quá lớn. Vui lòng upload file có dung lượng nhỏ hơn ' . $maxUploadFileSize / 1024 / 1024 . 'MB');
                    }
                    $media = $mediaUploader->setFile($file)->upload();
                }
            }
            DB::commit();
            return redirect()->back()->with('success', 'Uploaded Successfully!');
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json(['error' => $exception->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function edit($id)
    {
        /** @var Media $file */
        $file = $this->mediaRepository->getById($id);
        $modelAttached = $file->mediables;
        if ($file->isOfType('image')) {
            $src = $file->getUrl();
        } elseif ($file->type == 'video') {
            $src = asset('vendor/media/images/types/video.png');
        } elseif ($file->type == 'audio') {
            $src = asset('vendor/media/images/types/mp3.png');
        } else {
            $src = asset('vendor/media/images/types/text.png');
        }

        return response()->json(['file' => $file, 'src' => $src, 'modelAttached' => $modelAttached]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $media = $this->mediaRepository->find($id);
        if ($request->name != $media->name) {
            $dataUpdate['name'] = $request->name;
        }

        $imageName = $request->name;
        $isExisted = Media::where('name', $imageName)->where('id', '!=', $id);
        if ($isExisted->count() > 0) {
            return response()->json([
                'message' => 'The image name has been exist, please use another',
                'error' => true
            ], 400);
        }
        $extension = $media->getExtensionAttribute();
        $fileName = "$imageName.$extension";
        //Rename the file
        $arrImage = explode('/', $media->getPath());
        unset($arrImage[count($arrImage) - 1]);
        $folder = implode('/', $arrImage);
        Storage::disk($media->disk)->move($media->getPath(), $folder . '/' . $fileName);

        $media->update([
            'name' => $imageName,
            'alt' => $request->alt,
            'description' => $request->description,
            'file_name' => $fileName
        ]);
        return response()->json([
            'message' => 'Updated image successfully',
            'error' => false,
        ], 200);
    }

    public function search(Request $request)
    {
        $mode = $request->mode;
        $value = $request->value;
        $field = $request->field;
        $medias = $this->mediaRepository->search($field, $value);
        if (count($medias) > 0) {
            $view = view("media::admin.ajax-result", compact('medias', 'mode'))->render();
            return response()->json(['result' => $view, 'status' => 'C200']);
        }
        return response()->json(['result' => [], 'status' => 'C404']);
    }

    public function sort(Request $request)
    {
        $mode = $request->mode;
        $data = $request->value;
        $data = explode('-', $data);
        $medias = $this->mediaRepository->sort($data[0], $data[1]);
        if (count($medias) > 0) {
            $view = view("media::admin.ajax-result", compact('medias', 'mode'))->render();
            return response()->json(['result' => $view, 'status' => 'C200']);
        }
        return response()->json(['result' => [], 'status' => 'C404']);
    }

    public function destroy(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            $media = $this->mediaRepository->getById($id);
            $result = $media->filesystem()->delete($media->url);
            if ($result) {
                $media->delete();
            }
        }
        Session::flash('success', __('media::media.notification.deleted'));
        return response()->json(['success' => 200]);
    }

    public function froalaLoadImages(Request $request)
    {
        $items = $this->mediaRepository->all();

        FroalaMediaResource::withoutWrapping();
        $data = FroalaMediaResource::collection($items ?? []);

        return response()->json($data);
    }

    public function ajaxMedia(Request $request, MediaHelper $mediaHelper)
    {
        $medias = $this->mediaRepository->paginate(20);
        $mediables = $this->mediableRepositoryInterace->getAll();
        $mediaHelper->handleRemoveDuplicate($medias, 'created_at', true);
        $mediables = $mediaHelper->handleRemoveDuplicate($mediables, 'mediable_type', false);

        $mode = $request->mode;
        $view = view("media::form.result", compact('medias', 'mode'))->render();
        return response()->json(['result' => $view, 'status' => 'C200']);
    }

    public function storeAjax(Request $request, MediaUploader $mediaUploader)
    {
        try {
            $media = new Media();
            DB::beginTransaction();
            if ($files = $request->file('image-upload')) {
                foreach ($files as $file) {
                    $maxUploadFileSize = setting('media_max_file_size') ?? null;
                    if ($maxUploadFileSize) {
                        $maxUploadFileSize = $maxUploadFileSize * 1024 * 1024;
                    }
                    if ($maxUploadFileSize && $file->getSize() > $maxUploadFileSize) {
                        DB::rollBack();
                        return response()->json([
                            'message' => 'Dung lượng file quá lớn. Vui lòng upload file có dung lượng nhỏ hơn ' . $maxUploadFileSize / 1024 / 1024 . 'MB',
                            'status' => 400
                        ]);
                    }
                    $media = $mediaUploader->setFile($file)->upload();
                }
            }
            DB::commit();
            return $media;
        } catch (\Exception $exception) {
            DB::rollBack();
            logger('Error upload file::: ', [$exception->getMessage()]);
            return false;
        }
    }
}
