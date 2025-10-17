<?php


namespace Newnet\Contact\Http\Controllers\Admin;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Newnet\Contact\Repositories\NewsletterRepositoryInterface;

class NewsletterController extends Controller
{
    /**
     * @var NewsletterRepositoryInterface
     */
    protected $newsletterRepository;
    public function __construct(NewsletterRepositoryInterface $newsletterRepository)
    {
        $this->newsletterRepository = $newsletterRepository;
    }

    public function index(Request $request){
        $items = $this->newsletterRepository->paginate($request->input('max', 20));

        return view('contact::admin.newsletter.index', compact('items'));
    }

}
