<?php

declare(strict_types=1);

namespace Modules\Elearning\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Elearning\Http\Resources\UserResource;
use Modules\Elearning\Models\User;

class TeacherController extends Controller
{
    /**
     * Get all teacher applications
     */
    public function getApplications(Request $request)
    {
        $status = $request->query('status', 'pending');
        
        $applications = User::where('teacher_status', $status)
            ->orderBy('updated_at', 'desc')
            ->paginate(15);
            
        return UserResource::collection($applications);
    }
    
    /**
     * Approve a teacher application
     */
    public function approveApplication(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        if ($user->teacher_status !== 'pending') {
            return response()->json([
                'message' => 'This application is not pending approval',
            ], 422);
        }
        
        $user->teacher_status = 'approved';
        $user->is_teacher = true;
        $user->teacher_approved_at = now();
        $user->save();
        
        // TODO: Send notification to user about approved application
        
        return response()->json([
            'message' => 'Teacher application approved successfully',
            'user' => new UserResource($user),
        ]);
    }
    
    /**
     * Reject a teacher application
     */
    public function rejectApplication(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|min:10',
        ]);
        
        $user = User::findOrFail($id);
        
        if ($user->teacher_status !== 'pending') {
            return response()->json([
                'message' => 'This application is not pending approval',
            ], 422);
        }
        
        $user->teacher_status = 'rejected';
        $user->rejection_reason = $request->rejection_reason;
        $user->save();
        
        // TODO: Send notification to user about rejected application
        
        return response()->json([
            'message' => 'Teacher application rejected successfully',
            'user' => new UserResource($user),
        ]);
    }
    
    /**
     * Get all teachers
     */
    public function getTeachers(Request $request)
    {
        $teachers = User::where('is_teacher', true)
            ->where('teacher_status', 'approved')
            ->orderBy('name')
            ->paginate(15);
            
        return UserResource::collection($teachers);
    }
    
    /**
     * Disable a teacher account
     */
    public function disableTeacher(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|min:10',
        ]);
        
        $user = User::findOrFail($id);
        
        if (!$user->is_teacher || $user->teacher_status !== 'approved') {
            return response()->json([
                'message' => 'This user is not an approved teacher',
            ], 422);
        }
        
        $user->is_enable = false;
        $user->rejection_reason = $request->reason;
        $user->save();
        
        // TODO: Send notification to teacher about account disabling
        
        return response()->json([
            'message' => 'Teacher account disabled successfully',
            'user' => new UserResource($user),
        ]);
    }
}
