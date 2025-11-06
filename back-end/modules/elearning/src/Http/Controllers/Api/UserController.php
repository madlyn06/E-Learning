<?php

declare(strict_types=1);

namespace Modules\Elearning\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Modules\Elearning\Http\Requests\TeacherApplicationRequest;
use Modules\Elearning\Http\Requests\UpdateUserSettingsRequest;
use Modules\Elearning\Http\Requests\UserRequest;
use Modules\Elearning\Http\Resources\CourseResource;
use Modules\Elearning\Http\Resources\UserResource;
use Modules\Elearning\Models\User;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function profile(Request $request)
    {
        return response()->json([
            'user' => new UserResource($request->user()),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function updateProfile(UserRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();

        if (!empty($data['password'])) {
            if (!Hash::check($data['current_password'], $user->password)) {
                return response()->json([
                    'message' => 'Current password is incorrect'
                ], 422);
            }

            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user,
        ]);
    }

    public function verifyAccount(Request $request)
    {
        $user = $request->user();
        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Account already verified',
            ], Response::HTTP_UNAUTHORIZED);
        }
        $user->markEmailAsVerified();
        return response()->json([
            'message' => 'Account verified successfully',
            'user' => $user,
        ]);
    }

    public function resendEmailVerify(Request $request)
    {
        $email = $request->input('email');
        $user = User::where('email', $email)->first();
        if ($user && $user->hasVerifiedEmail()) {
            return response()->json(['error' => 'Email already verified', 'code' => Response::HTTP_UNAUTHORIZED]);
        }

        $token = $user->createToken('authToken')->plainTextToken;

        event(new \Modules\Elearning\Events\UserRegistered($user, $token));

        return response()->json([
            'message' => 'Email verify resend successfully',
        ]);
    }

    /**
     * Apply to become a teacher
     */
    public function applyTeacher(TeacherApplicationRequest $request)
    {
        $user = $request->user();

        // Check if already a teacher or has pending application
        if ($user->is_teacher) {
            return response()->json([
                'message' => 'You are already a teacher',
            ], 422);
        }

        if ($user->teacher_status === 'pending') {
            return response()->json([
                'message' => 'Your application is already pending review',
            ], 422);
        }

        // Update teacher-related fields
        $user->teaching_experience = $request->teaching_experience;
        $user->education_background = $request->education_background;
        $user->certificates = $request->certificates;
        $user->teaching_categories = $request->teaching_categories;
        $user->bio = $request->bio;
        $user->video_intro = $request->video_intro;
        $user->profile_image = $request->profile_image;
        $user->teacher_status = 'pending';
        $user->save();

        // TODO: Send notification to admin about new teacher application

        return response()->json([
            'message' => 'Teacher application submitted successfully',
            'user' => new UserResource($user),
        ]);
    }

    /**
     * Get teacher application status
     */
    public function getTeacherStatus(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'status' => $user->teacher_status,
            'is_teacher' => $user->is_teacher,
            'rejection_reason' => $user->rejection_reason,
            'applied_at' => $user->updated_at,
            'approved_at' => $user->teacher_approved_at,
        ]);
    }

    /**
     * Update user settings
     */
    public function updateSettings(UpdateUserSettingsRequest $request)
    {
        $user = $request->user();

        // Update settings
        if ($request->has('settings')) {
            $user->settings = $request->settings;
        }

        // Update default language
        if ($request->has('default_language')) {
            $user->default_language = $request->default_language;
        }

        $user->save();

        return response()->json([
            'message' => 'Settings updated successfully',
            'settings' => $user->settings,
            'default_language' => $user->default_language,
        ]);
    }

    /**
     * Get user's taught courses (as teacher)
     */
    public function getTaughtCourses(Request $request)
    {
        $user = $request->user();

        if (!$user->is_teacher) {
            return response()->json([
                'message' => 'You are not a teacher',
            ], 403);
        }

        $courses = $user->teacherCourses()->paginate(10);

        return CourseResource::collection($courses);
    }

    /**
     * Get user's enrolled courses (as student)
     */
    public function getEnrolledCourses(Request $request)
    {
        $user = $request->user();
        $courses = $user->enrolledCourses()->paginate(10);

        return CourseResource::collection($courses);
    }

    /**
     * Get public profile of a teacher
     */
    public function getTeacherProfile($id)
    {
        $teacher = User::where('id', $id)
            ->where('is_teacher', true)
            ->where('teacher_status', 'approved')
            ->firstOrFail();

        // Get courses taught by this teacher
        $courses = $teacher->teacherCourses()
            ->where('is_published', true)
            ->where('is_enable', true)
            ->paginate(10);

        return response()->json([
            'teacher' => new UserResource($teacher),
            'courses' => CourseResource::collection($courses),
        ]);
    }
}
