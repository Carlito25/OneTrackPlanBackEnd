<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContentPlanner;
use App\Http\Requests\ContentPlannerRequest;

class ContentPlannerController extends Controller
{
    public function index()
    {
        $content = ContentPlanner::get();

        return response()->json($content);
    }
}
