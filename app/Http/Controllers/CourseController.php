<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Services\CourseService;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    protected $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    public function index()
    {
        $courseByCategory = $this->courseService->getCoursesGroupByCategory();

        return view('courses.index', compact('courseByCategory'));
    }

    public function detail(Course $course)
    {
        $course->load(['category', 'benefits', 'courseSections.sectionContents']);

        // return to course detail
    }

    public function join(Course $course)
    {
        $studentName = $this->courseService->enrollUser($course);
        $firstSectionAndContent = $this->courseService->getFirstSectionAndContent($course);

        // return to join
    }

    public function learning(Course $course, $contentSectionId, $sectionContentId)
    {
        $learningData = $this->courseService->getLearningData($course, $contentSectionId, $sectionContentId);

        // return to learning
    }

    public function learningFinished(Course $course)
    {
        // return to learning finished
    }

    public function searchCourse(Request $request)
    {
        $request->validate([
            'search' => 'required|string'
        ]);

        $keyword = $request->search;

        $courses = $this->courseService->searchCourses($keyword);

        // return to search course
    }
}
