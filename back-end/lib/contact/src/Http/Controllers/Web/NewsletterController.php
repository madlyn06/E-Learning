<?php


namespace Newnet\Contact\Http\Controllers\Web;


use Illuminate\Http\Client\Request;
use Illuminate\Routing\Controller;
use Newnet\Contact\Http\Requests\NewsletterRequest;
use Newnet\Contact\Repositories\NewsletterRepositoryInterface;

class NewsletterController extends Controller
{

    protected $newsletterRepository;

    public function __construct(NewsletterRepositoryInterface $newsletterRepository)
    {
        $this->newsletterRepository = $newsletterRepository;
    }


    public function newsletter(NewsletterRequest $request)
    {
        $res = $this->newsletterRepository->create($request->all());
        return redirect()->route('contact.web.newsletter.index');
    }

    public function index()
    {
        return view('contact::web.newsletter.thank-you');
    }


    public function ajaxNewsletter(NewsletterRequest $request)
    {
        $this->newsletterRepository->updateOrCreate(
            [ 'email' => $request->email],
            $request->all(),
        );
        return response()->json([
            'success' => true,
        ]);
    }
}
