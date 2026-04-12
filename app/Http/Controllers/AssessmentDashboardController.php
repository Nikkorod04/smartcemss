<?php

namespace App\Http\Controllers;

use App\Models\Community;
use Illuminate\Http\Request;

class AssessmentDashboardController extends Controller
{
    public function index(Request $request)
    {
        $communityId = $request->get('community_id');
        $quarter = $request->get('quarter');
        $year = $request->get('year', 2025);

        $communities = Community::limit(20)->get();

        return view('assessments.dashboard', [
            'communities' => $communities,
            'selectedCommunity' => $communityId,
            'selectedQuarter' => $quarter,
            'selectedYear' => $year,
        ]);
    }
}
