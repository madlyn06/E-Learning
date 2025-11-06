<?php

namespace Newnet\Contact\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Newnet\Contact\Http\Requests\NewsletterRequest;
use Newnet\Contact\Models\Newsletter;

class NewsletterController extends Controller
{
    /**
     * Subscribe to newsletter
     *
     * @param NewsletterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function subscribe(NewsletterRequest $request)
    {
        try {
            Newsletter::updateOrCreate(
                ['email' => $request->email],
                $request->all()
            );
            
            return response()->json([
                'success' => true,
                'message' => __('contact::message.newsletter_success')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Unsubscribe from newsletter
     *
     * @param NewsletterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unsubscribe(NewsletterRequest $request)
    {
        try {
            $newsletter = Newsletter::where('email', $request->email)->first();
            
            if ($newsletter) {
                // Update to blacklist or delete
                $newsletter->update(['black_book' => true]);
            }
            
            return response()->json([
                'success' => true,
                'message' => __('contact::message.unsubscribe_success')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
