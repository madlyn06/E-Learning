<?php

declare(strict_types=1);

namespace Modules\Elearning\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Elearning\Models\Course;
use Modules\Elearning\Models\Wishlist;
use Modules\Elearning\Http\Resources\CourseResource;

class WishlistController extends Controller
{
    /**
     * Display a listing of user's wishlisted courses.
     */
    public function index()
    {
        $user = Auth::user();
        $wishlisted = Course::whereHas('wishlists', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with('user')->paginate(10);

        return CourseResource::collection($wishlisted);
    }

    /**
     * Add a course to user's wishlist.
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:elearning_courses,id',
        ]);

        $user = Auth::user();
        $course = Course::findOrFail($request->course_id);
        
        // Check if already in wishlist
        $existing = Wishlist::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->exists();
            
        if ($existing) {
            return response()->json([
                'message' => 'Course already in wishlist'
            ], 409);
        }
        
        // Add to wishlist
        $wishlist = new Wishlist();
        $wishlist->user_id = $user->id;
        $wishlist->course_id = $course->id;
        $wishlist->save();
        
        // Dispatch event for course wishlisted
        // We're not using it here directly to avoid type issues with User model
        // The observer in the Wishlist model will handle this
        
        return response()->json([
            'message' => 'Course added to wishlist',
            'data' => new CourseResource($course)
        ], 201);
    }

    /**
     * Remove a course from user's wishlist.
     */
    public function remove(Request $request, Course $course)
    {
        $user = Auth::user();
        
        $deleted = Wishlist::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->delete();
            
        if ($deleted) {
            return response()->json([
                'message' => 'Course removed from wishlist'
            ]);
        }
        
        return response()->json([
            'message' => 'Course not found in wishlist'
        ], 404);
    }

    /**
     * Check if a course is in the user's wishlist.
     */
    public function check(Request $request, Course $course)
    {
        $user = Auth::user();
        
        $exists = Wishlist::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->exists();
            
        return response()->json([
            'wishlisted' => $exists
        ]);
    }
}
