<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $tasks = Task::query()
            ->where(function($qb) use ($q) {
                $qb->where('title', 'like', "%$q%")
                   ->orWhere('description', 'like', "%$q%");
            })
            ->limit(50)
            ->get();
        return response()->json(['data' => $tasks]);
    }
}