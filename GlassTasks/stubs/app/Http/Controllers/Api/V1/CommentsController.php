<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function index(Task $task)
    {
        $this->authorize('view', $task);
        return response()->json(['data' => $task->comments()->with('user')->latest()->paginate(50)]);
    }

    public function store(Request $request, Task $task)
    {
        $this->authorize('update', $task);
        $data = $request->validate(['body' => ['required', 'string']]);
        $comment = $task->comments()->create([
            'user_id' => $request->user()->id,
            'body' => $data['body'],
        ]);
        return response()->json(['data' => $comment->load('user')]);
    }
}