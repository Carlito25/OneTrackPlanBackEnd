<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContentPlanner;
use App\Http\Requests\ContentPlannerRequest;
use Illuminate\Support\Facades\DB;
class ContentPlannerController extends Controller
{
    public function index()
    {
        $content = ContentPlanner::where('status', 'Draft')
        ->orWhere('status', 'Scheduled')
        ->get();

        return response()->json($content);
    }

    public function getPublishedContent(){
        $content = ContentPlanner::where('status', 'Published')
        ->get();

        return response()->json($content);
    }

    public function store(ContentPlannerRequest $request)
    {
        try {
            $cont = ContentPlanner::updateOrCreate(
                ['id' => $request['content_id']],
                [
                    'date' => $request['date'],
                    'category' => $request['category'],
                    'description' => $request['description'],
                    'status' => $request['status'],
                    'channels' => $request['channels'],
                    'notes' => $request['notes'],
                ]
            );
            if ($cont->wasRecentlyCreated) {
                return response()->json([
                    'status' => "Created",
                    'message' => "Content Successfully Created"
                ]);
            } else {
                return response()->json([
                    'status' => "Updated",
                    'message' => "Content Successfully Updated"
                ]);
            }
        } catch (\Throwable $th) {
            info($th->getMessage());
        }
    }

    public function show(ContentPlanner $contentplanner){
        $contentplanner = ContentPlanner::where('id', $contentplanner->id)
        ->first();

        return response()->json($contentplanner);
    }

    public function destroy(ContentPlanner $contentplanner)
    {
        try {
            $contentplanner->delete();
            return response()->json('Expense deleted successfully');
        } catch (\Throwable $th) {
            info($th->getMessage());
            return response()->json('Error deleting expense', 500);
        }
    }

    public function getDraftContent(){
        $draftCount =  DB::table('contentplanners')
        ->whereNull('deleted_at')
        ->where('status', 'Draft')
        ->count();

        return $draftCount;
    }

    public function getScheduledContent(){
        $scheduledCount =  DB::table('contentplanners')
        ->whereNull('deleted_at')
        ->where('status', 'Scheduled')
        ->count();

        return $scheduledCount;
    }
}


