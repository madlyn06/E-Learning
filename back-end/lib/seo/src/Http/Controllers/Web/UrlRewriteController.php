<?php

namespace Newnet\Seo\Http\Controllers\Web;

use App;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Newnet\Seo\Models\PreRedirect;
use Newnet\Seo\Models\Url;
use Newnet\Seo\Repositories\ErrorRedirectRepositoryInterface;
use Newnet\Seo\Repositories\UrlRepositoryInterface;
use Route;

class UrlRewriteController extends Controller
{
    /**
     * @var UrlRepositoryInterface
     */
    protected $urlRepository;

    /**
     * @var ErrorRedirectRepositoryInterface
     */
    protected $errorRedirectRepository;

    public function __construct(UrlRepositoryInterface $urlRepository, ErrorRedirectRepositoryInterface $errorRedirectRepository)
    {
        $this->urlRepository = $urlRepository;
        $this->errorRedirectRepository = $errorRedirectRepository;
    }

    public function __invoke(Request $request, $path = '')
    {
        $path = ltrim(rtrim($path, '/'), '/') ?: '/';

        if ($path == '/' && ($homePage = $this->homePage())) {
            return $homePage;
        }

        try {
            $targetPath = $this->getTargetPath($path);

            return $this->handleRealRoute($targetPath);
        } catch (Exception $e) {

        }

        try {
            $url = ltrim($request->getRequestUri(), '/');

            $errorRedirect = $this->errorRedirectRepository->findBy('from_path', $url);

            return redirect($errorRedirect->to_url, $errorRedirect->status_code);
        } catch (Exception $e) {

        }

        return abort(404);
    }

    protected function handleRealRoute($targetPath)
    {
        $params = request()->input();
        $params['skip'] = 'rewrite';

        return Route::dispatchToRoute(Request::create($targetPath, 'GET', $params, $_COOKIE));
    }

    protected function homePage()
    {
        $homeUrl = setting('seo_home_url');
        if ($homeUrl) {
            return $this->handleRealRoute($homeUrl);
        }

        if (view()->exists('index')) {
            return view('seo::web.index');
        }

        return null;
    }

    protected function getTargetPath($path)
    {
        $matchRequestPath = $this->urlRepository->whereMathRequestPath($path);

        /** @var Url $urlRewrite */
        $urlRewrite = $matchRequestPath->where('locale', App::getLocale())->first();

        if (!$urlRewrite) {
            $urlRewrite = $matchRequestPath->first();
        }

        if (!$urlRewrite) {
            throw new ModelNotFoundException();
        }

        return $urlRewrite->target_path;
    }

    public function checkPreRedirect($targetPath) {
        $preRedirect = PreRedirect::where('from_path', ltrim($targetPath, '/'))->first();
        if ($preRedirect) {
            return response()->json([
                'success' => true,
                'url' => $preRedirect->to_url,
                'status_code' => $preRedirect->status_code,
            ]);
        }
        return response()->json([
            'success' => false,
        ]);
    }
}
