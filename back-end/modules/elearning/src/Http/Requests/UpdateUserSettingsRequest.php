<?php

namespace Modules\Elearning\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Users can update their own settings
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'settings' => 'required|array',
            'settings.notifications' => 'sometimes|array',
            'settings.notifications.email' => 'sometimes|boolean',
            'settings.notifications.push' => 'sometimes|boolean',
            'settings.notifications.course_updates' => 'sometimes|boolean',
            'settings.notifications.wishlist' => 'sometimes|boolean',
            'settings.privacy' => 'sometimes|array',
            'settings.privacy.show_profile' => 'sometimes|boolean',
            'settings.privacy.show_courses' => 'sometimes|boolean',
            'settings.learning' => 'sometimes|array',
            'settings.learning.auto_play_videos' => 'sometimes|boolean',
            'default_language' => 'sometimes|string|min:2|max:10',
        ];
    }
}
