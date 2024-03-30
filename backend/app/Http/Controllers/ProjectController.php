<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{

    public function index()
    {
        $user = Auth::user();

        // For HR, fetch all projects without eager loading team information
        if ($user->role === 'HR') {
            $projects = Project::with(['teams', 'teams.teamLeader', 'teams.employees'])->get();
        } else {
            // For other users, fetch projects associated with their teams and eager load team information
            $projects = Project::whereHas('teams.employees', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->with(['teams', 'teams.teamLeader', 'teams.employees'])->get();
        }

        return response()->json($projects);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'details' => 'nullable',
            'status' => 'required|in:running,completed,upcoming',
        ]);

        $project = Project::create($validatedData);
        return response()->json($project, 201);
    }

    public function update(Request $request, Project $project)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'details' => 'nullable',
            'status' => 'required|in:running,completed,upcoming',
        ]);

        $project->update($validatedData);

        return response()->json($project, 201);
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return response()->json(['message' => 'Project deleted successfully']);
    }
}
