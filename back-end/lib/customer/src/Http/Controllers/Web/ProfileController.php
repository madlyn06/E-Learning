<?php

namespace Newnet\Customer\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Newnet\Customer\Http\Requests\CustomerRequest;
use Newnet\Customer\Repositories\CustomerRepositoryInterface;
use Newnet\Media\MediaUploader;

class ProfileController extends Controller
{
    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;
    private $mediaUploader;

    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        MediaUploader $mediaUploader
    ) {
        $this->middleware(['auth:customer', 'verified']);
        $this->mediaUploader = $mediaUploader;
        $this->customerRepository = $customerRepository;
    }

    public function profile()
    {
        return view('customer::web.customer.profile');
    }

    public function update(CustomerRequest $request)
    {
        $this->customerRepository->updateById($request->except(['email', 'avatar']), auth('sanctum')->user()->id);
        if ($request->hasFile('avatar')) {
            if (explode('/', $request->file('avatar')->getClientMimeType())[0] == 'image') {
                $avatar = $this->mediaUploader->setFile($request->file('avatar'))->upload();
                $this->customerRepository->updateById(['avatar' => [$avatar->id]], auth("customer")->user()->id);
            }
        }
        return redirect()
            ->back()->with('success', __('customer::customer.notification.updated'));
    }
}
