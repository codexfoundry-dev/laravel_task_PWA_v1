<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class ProjectsController extends Controller
{
    public function index(Request $request)
    {
        $projects = Project::with('owner')->whereHas('members', function($q) use ($request){
            $q->where('user_id', $request->user()->id);
        })->orWhere('owner_id', $request->user()->id)->paginate(20);
        return response()->json(['data' => $projects]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:20'],
        ]);
        $project = Project::create([
            'name' => $data['name'],
            'color' => $data['color'] ?? '#60a5fa',
            'owner_id' => $request->user()->id,
        ]);
        $project->members()->attach($request->user()->id);
        return response()->json(['data' => $project], Response::HTTP_CREATED);
    }

    public function show(Project $project)
    {
        $this->authorize('view', $project);
        return response()->json(['data' => $project->load('members', 'owner')]);
    }

    public function update(Request $request, Project $project)
    {
        $this->authorize('update', $project);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:20'],
        ]);
        $project->update($data);
        return response()->json(['data' => $project]);
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);
        $project->delete();
        return response()->noContent();
    }

    public function invite(Request $request, Project $project)
    {
        $this->authorize('update', $project);
        $data = $request->validate(['email' => ['required', 'email']]);
        // Simplified invite: auto-add if user exists
        $user = User::whereEmail($data['email'])->first();
        if ($user) {
            $project->members()->syncWithoutDetaching($user->id);
        }
        return response()->json(['status' => 'ok']);
    }

    public function join(Request $request, Project $project)
    {
        $project->members()->syncWithoutDetaching($request->user()->id);
        return response()->json(['status' => 'ok']);
    }
}