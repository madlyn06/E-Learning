<?php

namespace Modules\Elearning\Support;

use Modules\Elearning\Models\Course;

class CourseHelper
{
    public static function calculateTotalHourInCourse(Course $course)
    {
        $sections = $course->sections()->with('lessons')->get();
        $arrayTimes = [];
        foreach ($sections as $section) {
            $lessons = $section->lessons;
            foreach ($lessons as $lesson) {
                if ($lesson->type === 'video') {
                    array_push($arrayTimes, $lesson->duration_minutes);
                }
            }
        }
        if (count($arrayTimes) === 0) return '00:00:00';
        $hh = 0;
        $mm = 0;
        $ss = 0;
        foreach ($arrayTimes as $time) {
            sscanf($time, '%d:%d:%d', $hours, $mins, $secs);
            $hh += $hours;
            $mm += $mins;
            $ss += $secs;
        }

        $mm += floor($ss / 60);
        $ss %= 60;
        $hh += floor($mm / 60);
        $mm %= 60;
        return sprintf('%02d:%02d:%02d', $hh, $mm, $ss);
    }
}
