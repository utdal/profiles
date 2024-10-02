<?php

namespace App\Http\Controllers;

use App\Helpers\Semester;
use App\Insights\StudentApplications\StudentDataInsight;
use App\StudentData;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\Request;

class InsightsController extends Controller
{
    /**
     * Controller constructor. Middleware can be defined here.
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('can:viewAdminIndex,App\LogEntry')->only('index');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): ViewContract
    {

        $schools_options = StudentDataInsight::getLabels('school')->toArray();
        $semesters_options = StudentDataInsight::getLabels('semester')->toArray();
        $semesters_selected = Semester::removeFutureSemesters($semesters_options);
        $title = StudentDataInsight::convertParameterstoTitle($semesters_options, $schools_options);

        return view('insights.index', compact('schools_options', 'semesters_options', 'semesters_selected', 'title'));
    }
}
